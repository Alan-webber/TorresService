<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Base_Model extends CI_Model{
    
    public function acesso($acessos_permitidos = NULL, $acessos_permitidos_sem_login = NULL){
        $session = (object) $this->session->userdata();
        
        if ( !empty($session) )
        {
            if ( $session->logado )
            {
                if ( is_array($acessos_permitidos) )
                {
                    if ( in_array($session->acesso, $acessos_permitidos) )
                    {
                        return $session;
                    } 
                    else
                    {
                        $this->redirecionamento();
                    }
                } 
                else if ( is_object($acessos_permitidos) )
                {
                    if( !empty( $acessos_permitidos->{$session->acesso} ) )
                    {
                        if ( $acessos_permitidos->{$session->acesso} == TRUE )
                        {
                            return $session;
                        }
                        else
                        {
                            $this->redirecionamento();
                        }
                    }
                    else
                    {
                        redirect(base_url('login'));
                    }
                }
                else
                {
                    if ( (!is_null($acessos_permitidos) && $acessos_permitidos == $session->acesso) || is_null($acessos_permitidos) )
                    {
                        return $session;
                    }
                    else
                    {
                        $this->redirecionamento();
                    }
                }
            }
            else
            {
                redirect(base_url('login'));
            }
        }
        else
        {
            redirect(base_url('login'));
        }
    }

    public function permiteAcessoSomente($acesso_permitido){
        $sessao = $this->session->userdata();
        
        if(is_array($acesso_permitido))
        {
            if(in_array($sessao['acesso'], $acesso_permitido))
                return TRUE;
        } 
        else if(!is_array($acesso_permitido))
        {
            if($sessao['acesso'] == $acesso_permitido)
                return TRUE;
        }

        $this->redirecionamento();
    }

    public function redirecionamento(){
        if( $this->is_logado() )
        {
            redirect( base_url( ) );
        }
        else
        {
            redirect( base_url('login') );
        }
    }

    public function is_logado(){
        $session = (object) $this->session->userdata();

        if ( !empty($session->logado) )
        {
            if ( $session->logado )
            {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function numero2mes($numero = NULL){
        $numero = ( is_null($numero) ) ? date('n', time()) : $numero;

        $meses = ['','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

        return $meses[$numero];
    }

    public function getCategoriasMenu(){
        $this->load->model('Categorias_Servico_Model', 'categorias');

        return $this->categorias->get();
    }

    public function agrupaArray($array){
        $keys = array_keys($array);

        $returnArray = array();

        foreach ($array[$keys[0]] as $i => $value)
        {
            if(!empty($value))
            {
                foreach ($keys as $j => $in) {
                    $returnArray[$i][$in] = $array[$in][$i]; 
                }
            }
        }

        return $returnArray;
    }

    public function getFotoPerfil(){
        $this->load->model('Usuario_Model', 'usuario');

        return $this->usuario->getFoto();
    }

    public function getPerfil(){
        $this->load->model('Usuario_Model', 'usuario');

        return $this->usuario->getPerfil();
    }

    public function in_sequence($inicio, $fim)
    {
        $sequencia = array();

        for($i = $inicio; $i <= $fim; $i++)
        {
            $sequencia[] = $i;
        }

        return $sequencia;
    }

    public function getDiaSemana($numero)
    {
        $semana = ['', 'Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];

        if($numero > 0 && $numero <= 7)
            return $semana[$numero];
        else
            return null;
    }

    public function removeCaracteresEspeciasEAcentos($string)
    {
        $char_especiais = array(' ', '-', '^', '~', '!', '@', '#', '$', '%', '¨', '&', '*', '(', ')', '_', '+', '=', '§', 'ª', 'º', '|', "'", '"', ',', '?', ';', '<', '>', '[', ']', '{', '}', '°', '₢', '´', '`', '~', '^', '¬', '¨');

        $acentuacao = array('à', 'á', 'ã', 'ä', 'à', 'â', 'é', 'ë', 'è', 'ê', 'í', 'ì', 'ï', 'î', 'ó', 'ö', 'ò', 'ô', 'õ', 'ú', 'ü', 'ù', 'û', 'ç');

        return str_replace(array_merge($char_especiais, $acentuacao), "", $string);
    }

    public function isProfissional()
    {
        if($this->is_logado())
            if($this->session->userdata('acesso') == 'profissional')
                return TRUE;

        return FALSE;
    }

    public function isCliente()
    {
        if($this->is_logado())
            if($this->session->userdata('acesso') == 'cliente')
                return TRUE;

        return FALSE;
    }

    public function isAdmin()
    {
        if($this->is_logado())
            if($this->session->userdata('acesso') == 'admin')
                return TRUE;

        return FALSE;
    }

    public function orcamentos_nao_visualizados()
    {
        $this->load->model('Orcamento_Model', 'orcamento');

        if( !$this->isProfissional() )
            return FALSE;

        return $this->orcamento->get_count_nao_visualizados();
    }

    public function mensagens_nao_visualizadas()
    {
        $this->load->model('Orcamento_Model', 'orcamento');
        
        return count($this->orcamento->listar_mensagens_nao_visualizadas()); 
    }
    
}