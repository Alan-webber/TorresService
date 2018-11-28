<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Usuario_Model extends CI_Model{
    private $tableName = 'usuario';
    private $_id;

    public function __construct()
    {
        parent::__construct();

        $this->_id = $this->session->userdata('id');
    }

    public function get()
    {
        return $this->db->get_where($this->tableName, "id_usuario = {$this->_id}")->row();
    }

    public function bloquear($id_usuario)
    {
        return $this->db->update($this->tableName, array('bloqueado' => true), "id_usuario = {$id_usuario}");
    }

    public function desbloquear($id_usuario)
    {
        return $this->db->update($this->tableName, array('bloqueado' => false), "id_usuario = {$id_usuario}");
    }

    public function getByUsuario($usuario)
    {
        return $this->db->get_where($this->tableName, "login_usuario = '$usuario'")->row();
    }

    public function getById($id_usuario)
    {
        return $this->db->get_where($this->tableName, "id_usuario = {$id_usuario}")->row();
    }

    public function register($user)
    {
        $this->db->insert($this->tableName, $user);

        return $this->db->insert_id();
    }

    public function login($user)
    {
        return $this->db->where('bloqueado', false)->get_where($this->tableName, $user)->row();
    }

    public function edit($user)
    {
        return $this->db->update($this->tableName, $user, "id_usuario = {$this->_id}");
    }

    public function getPerfil($withNull = false)
    {
        $perfil = 
            $this->db
                ->join('cidade', 'id_cidade', 'left')
                ->get_where("usuario_perfil", "id_usuario = {$this->_id}")->row();

        if(empty($perfil) && $withNull)
        {
            $perfil = (object) array(
                'pessoa_fisica_juridica' => TRUE,
                'cpf' => NULL,
                'rg' => NULL,
                'cnpj' => NULL,
                'inscricao_estadual' => NULL,
                'telefone1' => NULL,
                'telefone2' => NULL,
                'endereco' => NULL,
                'complemento' => NULL,
                'cep' => NULL,
                'id_cidade' => NULL,
                'numero' => NULL,
                'profissao' => NULL,
                'funcao' => NULL
            );
        }

        return $perfil;
    }

    public function savePerfil($perfil)
    {
        if($this->getPerfil())
            return $this->db->update("usuario_perfil", $perfil, "id_usuario = {$this->_id}");
        else
            return $this->db->insert("usuario_perfil", $perfil);
    }

    public function sendFoto($foto)
    {
        return $this->db->update('usuario_perfil', array('foto_perfil' => $foto), "id_usuario = {$this->_id}");
    }

    public function getFoto()
    {        
        $foto = $this->db->select('foto_perfil')->get_where('usuario_perfil', "id_usuario = {$this->_id}")->row();

        if(!empty($foto))
            return $foto->foto_perfil;
        else
            return null;
    }

    public function getCidades($id_cidade = null)
    {
        if(!is_null($id_cidade))
            $this->db->where('id_cidade', $id_cidade);

        return $this->db->get('cidade')->result();
    }

    public function getCidadeByName($cidade)
    {
        return $this->db->where('cidade', $cidade)->get('cidade')->row();
    }
}