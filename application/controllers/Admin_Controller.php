<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Admin_Controller extends CI_Controller{

    private static $acessos_permitidos = 'admin';
    
    private static $Json_icons_categorias = 'assets/css/icons.json';

    private $sessao;
    

    public function __construct()
    {
        parent::__construct();

        // Models
        $this->load->model('Usuario_Model', 'usuario');
        $this->load->model('Base_Model', 'base');
        $this->load->model('Servicos_Model', 'servicos');

        $this->sessao = $this->base->acesso(self::$acessos_permitidos);
    }

    public function listar_categorias()
    {
        $categorias = 
            $this->db
                ->query(
                    "SELECT 
                        categoria_servico.*, 
                        count(servico.id_servico) as numero_anuncios
                    FROM
                        categoria_servico
                    LEFT JOIN servico USING (id_categoria_servico)
                    GROUP BY id_categoria_servico
                    ORDER BY nome_categoria_servico"
                )
            ->result();

        $this->load->view('include/header');
        $this->load->view('include/navbar');
        $this->load->view('admin/categorias/listar', array('categorias' => $categorias));
        $this->load->view('include/footer');
    }

    public function inserir_categoria()
    {
        $icones = file_get_contents(base_url(self::$Json_icons_categorias));

        $icones = json_decode($icones, true);

        $icones = $icones['categories'];

        $this->load->view('include/header');
        $this->load->view('include/navbar');
        $this->load->view('admin/categorias/inserir', array('icones' => $icones));
        $this->load->view('include/footer');
    }

    public function cadastrar_categoria()
    {
        $insert = array(
            'nome_categoria_servico' => $this->input->post('nome_categoria_servico'),
            'icon_name' => $this->input->post('icon_name')
        );

        if( $this->db->insert('categoria_servico', $insert) )
        {
            $this->session->set_flashdata('success', "Categoria {$insert['nome_categoria_servico']} cadastrada com sucesso!");
            redirect(base_url('admin/listar_categorias'));
        }
        else
        {
            $this->session->set_flashdata('error', "Não foi possível cadastrar a categoria {$insert['nome_categoria_servico']}!");
            redirect(base_url('admin/listar_categorias'));
        }
    }

    public function excluir_categoria($id_categoria_servico)
    {
        $categoria = 
            $this->db
                ->query(
                    "SELECT 
                        categoria_servico.*, 
                        count(servico.id_servico) as numero_anuncios
                    FROM
                        categoria_servico
                    LEFT JOIN servico USING (id_categoria_servico)
                    WHERE id_categoria_servico = {$id_categoria_servico}
                    GROUP BY id_categoria_servico"
                )
            ->row();

        if( $categoria->numero_anuncios > 0 )
        {
            $this->session->set_flashdata('error', "Não é possível excluir categoria com anuncios!");
            redirect(base_url('admin/listar_categorias'));
        }

        if( $this->db->delete('categoria_servico', "id_categoria_servico = {$id_categoria_servico}") )
        {
            $this->session->set_flashdata('success', "Categoria excluida com sucesso!");
            redirect(base_url('admin/listar_categorias'));
        }
        else
        {
            $this->session->set_flashdata('error', "Não foi possível excluir a categoria!");
            redirect(base_url('admin/listar_categorias'));
        }
    }

    public function listar_usuarios()
    {
        $usuarios = $this->db->where_not_in('acesso_usuario', array('admin'))->order_by('id_usuario')->get('usuario')->result();

        $this->load->view('include/header');
        $this->load->view('include/navbar');
        $this->load->view('admin/usuarios/listar', array('usuarios' => $usuarios));
        $this->load->view('include/footer');
    }

    public function denuncias()
    {
        $data['servicos'] = $this->servicos->servicos_com_denuncia();
        
        $this->load->view('include/header');   
        $this->load->view('include/navbar');   
        $this->load->view('admin/servicos/listar_denunciados', $data);   
        $this->load->view('include/footer');  
    }

    public function denuncias_servico($id_servico)
    {
        $data['id_servico'] = $id_servico;

        $data['denuncias'] = $this->servicos->denuncias_servico($id_servico);

        $data['servico_inativo'] = $this->servicos->isServicoInativo($id_servico);

        $this->load->view('include/header');   
        $this->load->view('include/navbar');   
        $this->load->view('admin/servicos/denuncias_servico', $data);   
        $this->load->view('include/footer');
    }

    public function inativar_servico($id_servico)
    {
        if($this->servicos->inativar($id_servico))
        {
            $this->session->set_flashdata('success', 'Serviço inativado');
        }
        else
        {
            $this->session->set_flashdata('error', 'Não é possível inativar esse serviço');
        }

        redirect(base_url("servicos/detalhes/$id_servico"));
        
    }

    public function ativar_servico($id_servico)
    {
        if($this->servicos->ativar($id_servico))
        {
            $this->session->set_flashdata('success', 'Serviço ativado novamente');
        }
        else
        {
            $this->session->set_flashdata('error', 'Não é possível ativar esse serviço');
        }

        redirect(base_url("servicos/detalhes/$id_servico"));
        
    }

    public function bloquear_usuario($id_usuario)
    {
        if($this->usuario->bloquear($id_usuario))
        {
            $this->session->set_flashdata('success', "Usuário bloqueado");
        }
        else
        {
            $this->session->set_flashdata('error', "Não é possível bloquear o usuário");
        }

        redirect(base_url("admin/listar_usuarios"));
    }

    public function desbloquear_usuario($id_usuario)
    {
        if($this->usuario->desbloquear($id_usuario))
        {
            $this->session->set_flashdata('success', "Usuário desbloqueado");
        }
        else
        {
            $this->session->set_flashdata('error', "Não é possível desbloquear o usuário");
        }

        redirect(base_url("admin/listar_usuarios"));
    }

}