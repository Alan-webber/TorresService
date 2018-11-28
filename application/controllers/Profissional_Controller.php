<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Profissional_Controller extends CI_Controller{

    public static $acessos_permitidos = 'profissional';

    private $sessao;

    public function __construct()
    {
        parent::__construct();

        // Models
        $this->load->model('Usuario_Model', 'usuario');
        $this->load->model('Base_Model', 'base');

        $this->sessao = $this->base->acesso(self::$acessos_permitidos);
    }

    public function index()
    {
        $this->load->view('include/header');
        $this->load->view('include/navbar');
        $this->load->view('include/footer');
    }

}