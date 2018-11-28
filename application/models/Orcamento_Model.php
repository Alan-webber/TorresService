<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Orcamento_Model extends CI_Model{
    private $tableName = 'orcamento';
    private $view = 'vw_orcamento';
    private $_id;

    const TEMPO_ENTRE_ORCAMENTOS_REALIZADOS_EM_DIAS = 7;

    public function __construct()
    {
        parent::__construct();

        $this->_id = $this->session->userdata('id');
    }

    public function create($orcamento)
    {
        if( !empty($orcamento) )
        {
            $orcamento['id_usuario'] = (int)$this->_id;

            if( $this->db->insert($this->tableName, $orcamento) )
                return TRUE;
        }

        return FALSE;
    }

    public function get_by_profissional()
    {
        return
            $this->db
                 ->get_where($this->view, "id_profissional = {$this->_id}")
                 ->result();
    }

    public function get_by_cliente()
    {
        return
            $this->db
                 ->get_where($this->view, "id_usuario_realizou_orcamento = {$this->_id}")
                 ->result();
    }

    public function get_count_nao_visualizados()
    {
        return 
            $this->db
                 ->select('COUNT(*) AS count')
                 ->where("id_profissional", $this->_id)
                 ->where("orcamento_visualizado", "false")
                 ->get($this->view)
                 ->row()
                 ->count;
    }

    public function pode_fazer_orcamento($id_servico)
    {
        $orcamento = 
            $this->db
                ->select('id_orcamento, data_realizado')
                ->where('id_usuario', $this->_id)
                ->where('id_servico', $id_servico)
                ->where('orcamento.finalizado IS FALSE')
                ->order_by('data_realizado', 'desc')
                ->limit(1)
                 ->get($this->tableName)
                ->row();

        if(empty($orcamento))   
            return TRUE;

        $interval = date_diff( new DateTime(), new DateTime( $orcamento->data_realizado ) );

        if( $interval->days >= self::TEMPO_ENTRE_ORCAMENTOS_REALIZADOS_EM_DIAS )
            return TRUE;

        return FALSE;
    }

    public function get($id){
        $orcamento = 
            $this->db
                 ->select('*')
                 ->from($this->view)
                 ->where('id_orcamento', $id)
                 ->get()
                 ->row();
        
        return $orcamento;
    }

    public function adicionar_mensagem($mensagem_item)
    {
        if($this->base->isProfissional())
        {
            $sql_verifica = 
                "SELECT 
                    count(usuario.id_usuario) as qtd
                FROM
                    orcamento
                    JOIN servico USING(id_servico)
                    JOIN usuario ON servico.id_usuario = usuario.id_usuario
                WHERE
                    orcamento.id_orcamento = {$mensagem_item['id_orcamento']}
                AND	usuario.id_usuario = {$this->_id};";
        }
        else if($this->base->isCliente())
        {
            $sql_verifica = 
                "SELECT
                    count(id_orcamento) as qtd
                FROM 
                    {$this->view}
                WHERE
                    id_orcamento = {$mensagem_item['id_orcamento']}
                AND id_usuario_realizou_orcamento = {$this->_id}";
        }

        $mensagem_item['mensagem'] = base64_encode($mensagem_item['mensagem']);

        if($this->db->query($sql_verifica)->row()->qtd >= 1)
            return $this->db->insert('mensagem_item', $mensagem_item);
        else
            return false;
    }

    public function mensagens($id_orcamento)
    {
        $mensagens =
            $this->db
                 ->where("id_orcamento", $id_orcamento)
                 ->order_by('data_envio')
                 ->get('mensagem_item')
                 ->result();

        foreach ($mensagens as $key => &$value) {
            $value->mensagem = base64_decode($value->mensagem);
        }

        return $mensagens;
    }

    public function set_mensagens_visualizada($id_orcamento)
    {
        if( $this->base->isProfissional() )
        {
            $this->db
                    ->set("mensagem_visualizada", true)
                    ->where("id_orcamento", $id_orcamento)
                    ->where("mensagem_enviada_pelo_profissional", false)
                    ->update('mensagem_item');
        }
        else
        {
            $this->db
                    ->set("mensagem_visualizada", true)
                    ->where("id_orcamento", $id_orcamento)
                    ->where("mensagem_enviada_pelo_profissional", true)
                    ->update('mensagem_item');
        }

    }

    public function finalizar_orcamento($id_orcamento)
    {
        if($this->base->isProfissional())
        {
            $sql_verifica = 
                "SELECT 
                    count(usuario.id_usuario) as qtd
                FROM
                    orcamento
                    JOIN servico USING(id_servico)
                    JOIN usuario ON servico.id_usuario = usuario.id_usuario
                WHERE
                    orcamento.id_orcamento = {$id_orcamento}
                AND orcamento.finalizado = false
                AND	usuario.id_usuario = {$this->_id};";
    
            if($this->db->query($sql_verifica)->row()->qtd >= 1)
                return $this->db->set('finalizado', true)->where('id_orcamento', $id_orcamento)->update('orcamento');                
        }

        return false;
    }

    public function set_orcamento_visualizado($id_orcamento)
    {
        if($this->base->isProfissional())
        {
            $this->db->set('visualizado', true)->where('id_orcamento', $id_orcamento)->update('orcamento');
        }

    }

    public function listar_mensagens_nao_visualizadas()
    {
        if(!in_array($this->session->userdata('acesso'), ['cliente', 'profissional']))
            redirect(base_url());
            
        if($this->base->isCliente())
        {
            $sql = 
                "SELECT 
                    id_orcamento,
                    orcamento.id_usuario as id_usuario,
                    count(id_mensagem_item) as quantidade,
                    titulo_servico,
                    id_servico,
                    nome_usuario
                FROM 
                    mensagem_item
                
                    INNER JOIN orcamento USING(id_orcamento)
                    INNER JOIN servico USING(id_servico)

                    INNER JOIN usuario ON usuario.id_usuario = servico.id_usuario
                
                WHERE orcamento.id_usuario = {$this->_id} AND mensagem_visualizada IS FALSE
 
                GROUP BY orcamento.id_usuario, id_orcamento, id_servico, titulo_servico, nome_usuario
            ";
        }
        else if($this->base->isProfissional())
        {
            $sql = 
                "SELECT 
                    id_orcamento,
                    servico.id_usuario as id_usuario,
                    count(id_mensagem_item) as quantidade,
                    titulo_servico,
                    id_servico,
                    nome_usuario
                FROM 
                    mensagem_item
                
                    INNER JOIN orcamento USING(id_orcamento)
                    INNER JOIN servico USING(id_servico)

                    INNER JOIN usuario ON usuario.id_usuario = orcamento.id_usuario
                
                WHERE servico.id_usuario = {$this->_id} AND mensagem_visualizada IS FALSE
 
                GROUP BY servico.id_usuario, id_orcamento, id_servico, titulo_servico, nome_usuario
            ";
        }

        return $this->db->query($sql)->result();
    }
}