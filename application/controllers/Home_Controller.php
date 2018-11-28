<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Home_Controller extends CI_Controller{
    
    private static $_login;

    public function __construct()
    {
        parent::__construct();

        if($this->session->userdata('logado'))
            self::$_login = true;
        else
            self::$_login = false;


        $this->load->model('Base_Model', 'base');
        $this->load->model('Servicos_Model', 'servicos');
        $this->load->model('Categorias_Servico_Model', 'categorias');
    }

    public function index()
    {
        $data['servicos'] = $this->servicos->getView();

        $data['mais_visitados'] = $this->servicos->mais_visitados();

        $data['titulo'] = "Serviços";

        foreach ($data['servicos'] as $key => $servico) {
            $servico->favorito = false;

            $fotos = $this->servicos->carregar_fotos_servico($servico->id_servico);
    
            if( !empty($fotos) )
                $servico->foto_principal = $fotos[0]['url'];
            else
                $servico->foto_principal = null;
        }

        if(self::$_login)
            $data['servicos'] = $this->servicos->servicos_favoritos($data['servicos']);

        $this->load->view('include/header');

        $this->load->view('include/navbar');
        
        $this->load->view('servicos/exibir', $data);

        $this->load->view('include/footer');
    }

    public function categorias(){
        $data['categorias'] = $this->categorias->get();

        $this->load->view('include/header');

        $this->load->view('include/navbar');
        
        $this->load->view('categorias/listar', $data);

        $this->load->view('include/footer');
    }

}