<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Categorias_Servico_Model extends CI_Model{
    private $tableName = 'categoria_servico';
    private $_id;

    public function __construct()
    {
        parent::__construct();

        $this->_id = $this->session->userdata('id');
    }

    public function get()
    {
        return $this->db->order_by('nome_categoria_servico')->get($this->tableName)->result();
    }

    public function getById($id)
    {
        return $this->db->get_where($this->tableName, "id_categoria_servico = $id")->row();
    }

}