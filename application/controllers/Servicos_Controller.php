<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Servicos_Controller extends CI_Controller{

    private static $acessos_permitidos = ['profissional', 'cliente', 'admin'];

    private static $pasta_upload = "./uploads/fotos/servicos/";

    private $sessao;

    public function __construct()
    {
        parent::__construct();

        // Models
        $this->load->model('Servicos_Model', 'servicos');
        $this->load->model('Categorias_Servico_Model', 'categorias');
        $this->load->model('Usuario_Model', 'usuario');
        $this->load->model('Orcamento_Model', 'orcamento');

        $this->load->model('Base_Model', 'base');


        if(!in_array($this->uri->segment(2), ['exibir', 'detalhes']))
            $this->sessao = $this->base->acesso(self::$acessos_permitidos);
    }

    public function index()
    {
        $this->listar();
    }

    public function listar()
    {
        $this->base->permiteAcessoSomente('profissional');

        $data['servicos'] = $this->servicos->get();

        $this->load->view('include/header');   
        $this->load->view('include/navbar');   
        $this->load->view('servicos/listar', $data);   
        $this->load->view('include/footer');   
    }

    public function editar($id)
    {
        $this->base->permiteAcessoSomente('profissional');

        $this->form_validation->set_rules('titulo_servico', 'titulo_servico', 'required');
        $this->form_validation->set_rules('descricao_servico', 'descricao_servico', 'required');
        $this->form_validation->set_rules('categoria_servico', 'categoria_servico', 'required');
        
        if($this->form_validation->run())
        {
            $servico = array(
                "titulo_servico" => $this->input->post('titulo_servico'),
                "descricao_servico" => nl2br($this->input->post('descricao_servico')),
                "id_categoria_servico" => $this->input->post('categoria_servico'),
                "dias_semana" => join(';', $this->input->post('dias_semana')),
                "hora_inicial" => $this->input->post('hora_inicial'),
                "hora_final" => $this->input->post('hora_final')
            );

            $fotos_excluir = $this->input->post('fotos-excluir');

            if(!empty($fotos_excluir))
            {
                $fotos_excluir = explode(";", $fotos_excluir);
            }

            if(!empty($_FILES['fotos']))
            {
                $file = @mkdir(self::$pasta_upload, 0777, true);

                $fotos = $this->base->agrupaArray($_FILES['fotos']);

                $erros = 0;

                $fotosSend = array();

                foreach ($fotos as $key => $foto) {

                    // $uploadfile = $this->base->removeCaracteresEspeciasEAcentos(strtolower(self::$pasta_upload . time() . $key . $this->sessao->id . utf8_decode(basename($foto['name']))));

                    $uploadfile = strtolower(self::$pasta_upload . base64_encode(time() . $key . $this->sessao->id . utf8_encode(basename($foto['name']))) . image_type_to_extension(getimagesize($foto['tmp_name'])[2]));
                    
                    if (move_uploaded_file($foto['tmp_name'], $uploadfile)) {
                        $fotosSend[] = "$uploadfile";
                    } else {
                        $erros++;
                    }
                }
            }

            if($this->servicos->update($id, $servico, $fotosSend, $fotos_excluir)){
                $this->session->set_flashdata('success', 'Serviço editado com sucesso');

                redirect(base_url('servicos/listar'));
            } 
            else
            {
                $this->session->set_flashdata('error', 'Não foi possível alterar o serviço');

                redirect(base_url("servicos/editar/$id"));
            }

        }
        else
        {
            $data['servico'] = $this->servicos->getByIdServico($id);

            $data['fotos'] = $this->servicos->carregar_fotos_servico($data['servico']->id_servico);

            $data['categorias'] = $this->categorias->get();

            $data['servico']->dias_semana = explode(';', $data['servico']->dias_semana);
    
            $this->load->view('include/header');
            $this->load->view('include/navbar');
            $this->load->view('servicos/cadastrar', $data);
            $this->load->view('include/footer');
        }
    }

    public function cadastrar()
    {
        $this->base->permiteAcessoSomente('profissional');

        $this->form_validation->set_rules('titulo_servico', 'titulo_servico', 'required');
        $this->form_validation->set_rules('descricao_servico', 'descricao_servico', 'required');
        $this->form_validation->set_rules('categoria_servico', 'categoria_servico', 'required');

        if($this->form_validation->run())
        {
            $servico = array(
                "titulo_servico" => $this->input->post('titulo_servico'),
                "descricao_servico" => nl2br($this->input->post('descricao_servico')),
                "id_categoria_servico" => $this->input->post('categoria_servico'),
                "dias_semana" => join(';', $this->input->post('dias_semana')),
                "hora_inicial" => $this->input->post('hora_inicial'),
                "hora_final" => $this->input->post('hora_final')
            );

            $dadosServico = array(
                "titulo_servico" => $servico['titulo_servico'],
                "id_categoria_servico" => $servico['id_categoria_servico'],
                "id_usuario" => $this->sessao->id,
                "inativo" => false
            );

            if(!empty($this->servicos->getWhere($dadosServico))){
                $this->session->set_flashdata('error', 'Serviço já cadastrado');

                redirect(base_url('servicos/cadastrar'));
            }

            if(!empty($_FILES['fotos']))
            {
                $file = @mkdir(self::$pasta_upload, 0777, true);

                $fotos = $this->base->agrupaArray($_FILES['fotos']);

                $erros = 0;

                $fotosSend = array();

                foreach ($fotos as $key => $foto) {

                    $uploadfile = strtolower(self::$pasta_upload . base64_encode(time() . $key . $this->sessao->id . utf8_encode(basename($foto['name']))) . image_type_to_extension(getimagesize($foto['tmp_name'])[2]));

                    // $uploadfile = $this->base->removeCaracteresEspeciasEAcentos(strtolower(self::$pasta_upload . time() . $key . $this->sessao->id . utf8_decode(basename($foto['name']))));
                    
                    if (move_uploaded_file($foto['tmp_name'], $uploadfile)) {
                        $fotosSend[] = "$uploadfile";
                    } else {
                        $erros++;
                    }
                }

            }

            if($this->servicos->create($servico, $fotosSend)){
                $this->session->set_flashdata('success', 'Serviço cadastrado com sucesso');

                redirect(base_url('servicos/listar'));
            } 
            else
            {
                $this->session->set_flashdata('error', 'Não foi possível cadastrar o serviço');

                redirect(base_url('servicos/cadastrar'));
            }
        }
        else
        {   
            $data['categorias'] = $this->categorias->get();

            $this->load->view('include/header');
            $this->load->view('include/navbar');
            $this->load->view('servicos/cadastrar', $data);
            $this->load->view('include/footer');
        }
    }

    public function excluir($id)
    {
        $this->base->permiteAcessoSomente('profissional');

        if($this->servicos->delete($id))
            $this->session->set_flashdata('success', 'Serviço excluído');
        else
            $this->session->set_flashdata('error', 'Não é possível excluir esse serviço');

        redirect(base_url("servicos"));
    }

    public function exibir($id)
    {
        $data['categoria'] = $this->categorias->getById($id);

        $data['servicos'] = $this->servicos->listar_servicos_categoria($id);

        if(!empty($data['servicos']))
        {
            $data['servicos'] = $this->servicos->servicos_favoritos($data['servicos']);
    
            foreach ($data['servicos'] as $key => $servico) {
                $fotos = $this->servicos->carregar_fotos_servico($servico->id_servico);
    
                if(!empty($fotos))
                {
                    $servico->foto_principal = $fotos[0]['url'];
                }
                else
                {
                    $servico->foto_principal = null;
                }
            }
        }

        $this->load->view('include/header');   
        $this->load->view('include/navbar');   
        $this->load->view('servicos/exibir', $data);   
        $this->load->view('include/footer');
    }

    public function listar_favoritos()
    {
        $data['categoria'] = false;

        $data['favoritos'] = true;

        $data['servicos'] = $this->servicos->listar_servicos_favoritos();

        foreach ($data['servicos'] as $key => $servico) {
            $servico->favorito = true;

            $fotos = $this->servicos->carregar_fotos_servico($servico->id_servico);

            if(!empty($fotos))
            {
                $servico->foto_principal = $fotos[0]['url'];
            }
            else
            {
                $servico->foto_principal = null;
            }
        }

        $this->load->view('include/header');   
        $this->load->view('include/navbar');   
        $this->load->view('servicos/exibir', $data);   
        $this->load->view('include/footer');
    }

    public function detalhes_servico($id_servico){
        $data['servico'] = $this->servicos->detalhes_servico($id_servico);

        if(!$data['servico'])
            redirect(base_url('error404'));

        $data['servico']->favorito = $this->servicos->servico_favorito($id_servico);

        $data['cidades'] = json_encode($this->usuario->getCidades());

        $data['usuario'] = (object) array_merge((array)$this->usuario->get(), (array)$this->usuario->getPerfil(true));

        $data['pode_realizar_orcamento'] = $this->orcamento->pode_fazer_orcamento($id_servico);

        $data['nota'] = $this->servicos->media_nota($id_servico);

        if($this->base->is_logado())
            $view = $this->servicos->add_visualizacao($id_servico);

        $fotos = $this->servicos->carregar_fotos_servico($data['servico']->id_servico);

        if(!empty($fotos))
            $data['servico']->fotos = $fotos;
        else
            $data['servico']->foto_principal = null;

        $data['meta_tag'] = array(
            'locale'        => 'pt_BR',
            'url'           => base_url($this->uri->uri_string()),
            'title'         => $data['servico']->titulo_servico,
            'site_name'     => 'Torres Service',
            'description'   => $data['servico']->descricao_servico,
            'image'         => (!empty($data['servico']->fotos[0]) ? base_url($data['servico']->fotos[0]['url']) : ''),
            'image:type'    => 'image/jpeg',
            'image:width'   => '800',
            'image:height'  => '600',
            'type'          => 'website',
        );

        $this->load->view('include/header', $data);   
        $this->load->view('include/navbar');   
        $this->load->view('servicos/detalhar', $data);   
        $this->load->view('include/footer');
    }

    public function favoritar_servico($id_servico)
    {
        if(!$this->servicos->servico_favorito($id_servico))
        {
            if($this->servicos->favoritar_servico($id_servico))
                $this->session->set_flashdata('success', 'Serviço favoritado');
            else
                $this->session->set_flashdata('error', 'Não foi possível favoritar o serviço');
        }
        else
        {
            if($this->servicos->desfavoritar_servico($id_servico))
                $this->session->set_flashdata('success', 'Serviço desfavoritado');
            else
                $this->session->set_flashdata('error', 'Não foi possível desfavoritar o serviço');
        }

        redirect(base_url("servicos/detalhes/{$id_servico}"));
    }

    public function denunciar($id_servico){
        if(!empty($this->input->post('motivo_denuncia')))
        {
            $motivo = $this->input->post('motivo_denuncia');

            if(strlen(trim($motivo)) > 0)
            {
                $this->servicos->denunciar_servico(
                    array(
                        'id_servico' => $id_servico,
                        'id_usuario' => $this->sessao->id,
                        'motivo_denuncia' => nl2br(trim($motivo))
                    )
                );

                $this->session->set_flashdata('success', 'Serviço denunciado');
                redirect(base_url("servicos/detalhes/{$id_servico}"));
            }
        }

        redirect(base_url("servicos/detalhes/{$id_servico}"));        
    }

    public function imagem($id_servico_fotos)
    {
        $foto = $this->servicos->foto_servico($id_servico_fotos);

        header("content-type: {$foto->foto_type}");

        echo $foto->foto;
    }
}