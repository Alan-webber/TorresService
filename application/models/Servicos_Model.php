<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Servicos_Model extends CI_Model{
    private $tableName = 'servico';
    private $_id;
    private $pasta_upload = "./uploads/fotos/servicos/";

    public function __construct()
    {
        parent::__construct();

        $this->_id = $this->session->userdata('id');
    }

    public function get($verInativos = FALSE)
    {
        $servicos = $this->db->order_by('data_servico', 'desc')->where("id_usuario", $this->_id);

        if(!$verInativos)
            $servicos = $this->db->where('inativo IS FALSE');

        $servicos = $this->db->get($this->tableName)->result();

        return $servicos;
    }

    public function getByIdServico($id_servico)
    {
        $servico = $this->db->where('id_servico', $id_servico);

        if(!$this->base->isAdmin())
            $servico = $this->db->where('inativo is FALSE');

        return $this->db->get($this->tableName)->row();
    }

    public function listar_servicos_categoria($id_categoria)
    {
        $servicos = $this->db->where('id_categoria_servico', $id_categoria);

        if(!$this->base->isAdmin())
            $servicos = $this->db->where('inativo IS FALSE');

        $servicos = $this->db->order_by('data_servico', 'desc')->get('vw_listar_servicos_categoria')->result();
        
        return $servicos;
    }

    public function getView()
    {
        // $servicos = $this->db->query("SELECT * FROM vw_listar_servicos_categoria ORDER BY data_servico DESC;")->result();

        if(!$this->base->isAdmin())
            $servicos = $this->db->where('inativo IS FALSE');

        $servicos = $this->db->order_by('data_servico', 'desc')->get('vw_listar_servicos_categoria')->result();
        
        return $servicos;
    }

    public function mais_visitados($quantidade = 10)
    {
        // $servicos = $this->db->query("SELECT * FROM vw_listar_servicos_categoria WHERE numero_visualizacoes > 0 ORDER BY numero_visualizacoes DESC LIMIT {$quantidade};")->result();

        $servicos = $this->db->where('numero_visualizacoes > 0');

        if(!$this->base->isAdmin())
            $servicos = $this->db->where('inativo IS FALSE');

        $servicos = $this->db->order_by('numero_visualizacoes', 'desc')->limit($quantidade)->get('vw_listar_servicos_categoria')->result();

        return $servicos;
    }

    public function detalhes_servico($id_servico)
    {
        // $servico = $this->db->query("SELECT * FROM vw_detalhes_servico WHERE id_servico = {$id_servico};")->row();

        $servico = $this->db->where('id_servico', $id_servico);

        if(!$this->base->isAdmin())
            $servico = $this->db->where('inativo IS FALSE');

        $servico = $this->db->get('vw_detalhes_servico')->row();

        if(empty($servico))
            return FALSE;

        $servico->disponibilidade = null;

        $dias_semana_array = explode(';', $servico->dias_semana);

        if($servico->dias_semana == join(';', $this->base->in_sequence($dias_semana_array[0], $dias_semana_array[count($dias_semana_array)-1])))
        {
            $servico->disponibilidade = "De {$this->base->getDiaSemana($dias_semana_array[0])} à {$this->base->getDiaSemana($dias_semana_array[count($dias_semana_array)-1])}";
        } 
        else
        {
            foreach($dias_semana_array as $key => $dia_semana)
            {
                if($key > 0)
                    $servico->disponibilidade .= ($key == count($dias_semana_array)-1) ? " e " : ", ";

                $servico->disponibilidade .= $this->base->getDiaSemana($dia_semana);
            }
        }

        $servico->disponibilidade .= " |  Horário: {$servico->hora_inicial} às {$servico->hora_final}";

        return $servico;
    }

    public function add_visualizacao($id_servico)
    {
        $view = ($this->getByIdServico($id_servico)->id_usuario != $this->_id ? TRUE : FALSE);

        if($view)
        {
            $insert = false;

            $servico = $this->get_ultimos_visitados_usuario($id_servico, TRUE);

            if( empty($servico) )
                $insert = true;

            if( !$insert )
            {
                $servico = $servico[0];

                $interval = date_diff( new DateTime(), new DateTime( $servico->data_hora ) );

                $hour_diff = intval(($interval->y * 365.25 + $interval->m * 30 + $interval->d) * 24 + $interval->h + $interval->i/60);

                if( $hour_diff >= 2 )
                    $insert = true;
            }

            if( $insert )
            {
                $this->db->insert('servico_visualizacoes',
                    array(
                        'id_servico' => $id_servico,
                        'id_usuario' => $this->_id
                    )
                );
            }
        }

        return NULL;
    }

    public function get_ultimos_visitados_usuario($id_servico = NULL, $last = NULL)
    {
        $query = $this->db
                      ->from("vw_listar_servicos_categoria")
                      ->join("servico_visualizacoes", "id_servico", "inner")
                      ->where("servico_visualizacoes.id_usuario", $this->_id);

        if(!$this->base->isAdmin())
            $query = $this->db->where('inativo IS FALSE');
                      
        if(!is_null($id_servico) && is_numeric($id_servico))
            $query = $this->db->where("id_servico", $id_servico);

        if(!is_null($last) && is_bool($last) && $last == TRUE)
            $query = $this->db->order_by("id_servico_visualizacoes", "DESC")->limit(1);

        $query = $this->db->get()->result();

        return $query;
    }

    public function getWhere($where)
    {
        return $this->db->get_where($this->tableName, $where)->result();
    }

    public function create($servico, $fotos)
    {
        $servico['id_usuario'] = $this->_id;

        $this->db->insert($this->tableName, $servico);

        $insertId = $this->db->insert_id();

        foreach ($fotos as $key => $foto) {
            $insert = array();
            $insert['id_servico'] = $insertId;
            $insert['url'] = $foto;

            $this->db->insert('servico_fotos', $insert);
        }

        return $insertId;
    }

    public function carregar_fotos_servico($id_servico){
        return $this->db->get_where('servico_fotos', "id_servico = $id_servico")->result_array();
    }

    public function carrega_foto($id_foto, $id_servico){
        return $this->db->get_where('servico_fotos', "id_servico = $id_servico AND id_servico_fotos = $id_foto")->result_array();
    }

    public function excluir_foto($id_foto, $id_servico)
    {
        $foto = $this->carrega_foto($id_foto, $id_servico);

        $this->db->delete('servico_fotos', "id_servico_fotos = $id_foto");
    }

    public function foto_servico($id_servico_fotos){
        $SQL = "SELECT foto_bytea::text as foto, id_servico_fotos, foto_type FROM servico_fotos WHERE id_servico_fotos = {$id_servico_fotos}";

        return $this->db->query($SQL)->row();
    }

    public function update($id, $servico, $fotos = null, $fotos_a_excluir = null)
    {
        $this->db->update($this->tableName, $servico, "id_servico = {$id}");

        if(!is_null($fotos))
        {
            foreach ($fotos as $key => $foto) {
                $insert = array();
                $insert['id_servico'] = $id;
                $insert['url'] = $foto;
    
                $this->db->insert('servico_fotos', $insert);
            }
        }

        if(!is_null($fotos_a_excluir))
        {
            foreach ($fotos_a_excluir as $i => $foto) {
                if(!empty($this->carrega_foto($foto, $id)))
                {
                    $this->excluir_foto($foto, $id);
                }
            }
        }

        return TRUE;
    }

    public function delete($id_servico)
    {
        return $this->update($id_servico, array('inativo' => true));
    }

    public function favoritar_servico($id_servico)
    {
        if(!$this->servico_favorito($id_servico)){
            return $this->db->insert(
                'servico_favorito',
                array(
                    'id_servico' => $id_servico,
                    'id_usuario' => $this->_id,
                )
            );
        }

        return FALSE;
    }

    public function listar_servicos_favoritos()
    {
        $query = $this->db->join('servico_favorito', "id_servico", "inner")->where('id_usuario', $this->_id);

        if(!$this->base->isAdmin())
            $query = $this->db->where('inativo IS FALSE');

        $query = $this->db->get('vw_listar_servicos_categoria')->result();

        return $query;
    }

    public function desfavoritar_servico($id_servico)
    {
        if($this->servico_favorito($id_servico))
        {
            return $this->db->delete('servico_favorito', "id_servico = {$id_servico} AND id_usuario = {$this->_id}");
        }
        return FALSE;
    }

    public function servico_favorito($id_servico){
        $favorito = $this->db->get_where('servico_favorito', "id_servico = {$id_servico} AND id_usuario = {$this->_id}")->row();

        if(!empty($favorito))
            return TRUE;
        else
            return FALSE;
    }

    public function servicos_favoritos($servicos)
    {
        foreach ($servicos as $key => $servico) {
            $servico->favorito = $this->servico_favorito($servico->id_servico);            
        }
        
        return $servicos;
    }

    public function denunciar_servico($denuncia)
    {
        $this->db->insert('servico_denunciado', $denuncia);
    }

    public function avaliar($id_servico, $nota)
    {
        if(count($this->buscar_nota($id_servico)) == 0)
        {
            return 
                $this
                    ->db
                    ->insert(
                        'servico_nota', 
                        array(
                            'nota' => intval($nota),
                            'id_servico' => intval($id_servico),
                            'id_usuario' => intval($this->_id)
                        )
                    );
        }

        return FALSE;
    }

    public function buscar_nota($id_servico)
    {
        return
            $this
                ->db
                ->where('id_servico', $id_servico)
                ->where('id_usuario', $this->_id)
                ->get('servico_nota')
                ->row();
    }

    public function media_nota($id_servico)
    {
        return
            $this
                ->db
                ->query("SELECT AVG(nota)::decimal(15,2) as media_nota, COUNT(id_servico_nota) as qtd FROM servico_nota WHERE id_servico = {$id_servico}")
                ->row();
    }

    public function procurar_servico($search)
    {
        $query = 
            $this
                ->db
                // ->where("unaccent(titulo_servico) ILIKE '%'||unaccent('{$search}')||'%' OR unaccent(descricao_servico) ILIKE '%'||unaccent('{$search}')||'%'")
                ->where("(titulo_servico ILIKE '%{$search}%' OR descricao_servico ILIKE '%{$search}%')");
                // ->or_like("titulo_servico", $search)
                // ->or_like("descricao_servico", $search);
                
        if(!$this->base->isAdmin())
            $query = $this->db->where('inativo IS FALSE');
                
        $query = $this->db
                ->get('vw_listar_servicos_categoria')
                ->result();

        return $query;
    }

    public function denuncias_servico($id_servico)
    {
        return
            $this
                ->db
                ->select("id_usuario, id_servico_denunciado, id_servico, motivo_denuncia, nome_usuario, data_hora_denuncia")
                ->from("servico_denunciado")
                ->join("usuario", "id_usuario", "inner")
                ->where("id_servico", $id_servico)
                ->get()
                ->result();
    }

    public function servicos_com_denuncia()
    {
        $id_servicos_denunciados = 
            $this
                ->db
                ->select('id_servico, count(id_servico) as qtd')
                ->group_by('id_servico')
                ->order_by('qtd', 'desc')
                ->get('servico_denunciado')
                ->result('array');

        $servicos =
            $this
                ->db
                ->select('
                    categoria_servico.id_categoria_servico,
                    servico.id_servico,
                    servico.titulo_servico,
                    servico.descricao_servico,
                    servico.data_servico,
                    servico.numero_visualizacoes,
                    servico.inativo,
                    usuario.nome_usuario,
                    usuario_perfil.pessoa_fisica_juridica,
                    usuario_perfil.cpf,
                    usuario_perfil.cnpj,
                    usuario_perfil.inscricao_estadual,
                    usuario.data_cadastro')
                ->from('categoria_servico')
                ->join('servico', 'id_categoria_servico', 'inner')
                ->join('usuario', 'id_usuario', 'inner')
                ->join('usuario_perfil', 'id_usuario', 'inner')
                ->where_in('id_servico', array_column($id_servicos_denunciados, 'id_servico'))
                ->get()
                ->result();

        foreach ($servicos as $key => $servico) {
            foreach ($id_servicos_denunciados as $value) {
                if( $servico->id_servico == $value['id_servico'] )
                {
                    $servico->qtd_denuncias = $value['qtd'];
                    continue;
                }
            }
        }

        usort($servicos, function($a, $b){
            return $b->qtd_denuncias - $a->qtd_denuncias;
        });

        return $servicos;
    }

    public function isServicoInativo($id_servico)
    {
        return $this->db->select('inativo')->where('id_servico', $id_servico)->get($this->tableName)->row()->inativo;
    }

    public function ativar($id_servico)
    {
        if($this->base->isAdmin())
        {
            return $this->db->update($this->tableName, array('inativo' => false), "id_servico = {$id_servico}");
        }
        
        return FALSE;
    }

    public function inativar($id_servico)
    {
        if($this->base->isAdmin())
        {
            return $this->db->update($this->tableName, array('inativo' => true), "id_servico = {$id_servico}");
        }
        
        return FALSE;
    }
}