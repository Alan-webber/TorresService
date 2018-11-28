<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Perfil_Controller extends CI_Controller{

    public static $acessos_permitidos;

    private static $pasta_upload = "./uploads/fotos/perfil/";

    private $sessao;

    public function __construct()
    {
        parent::__construct();

        // Models
        $this->load->model('Usuario_Model', 'usuario');
        $this->load->model('Base_Model', 'base');

        $this->sessao = $this->base->acesso(self::$acessos_permitidos);
    }

    public function editar_perfil()
    {
        $data['usuario'] = $this->usuario->get();
        $data['usuario_perfil'] = $this->usuario->getPerfil(true);

        if(!is_null($data['usuario_perfil']->id_cidade))
            $data['usuario_perfil']->cidade = $this->usuario->getCidades($data['usuario_perfil']->id_cidade)[0]->cidade;
        else
            $data['usuario_perfil']->cidade = null;

        $data['cidades'] = json_encode($this->usuario->getCidades());

        $this->load->view('include/header');
        $this->load->view('include/navbar');
        $this->load->view('perfil/editar', $data);
        $this->load->view('include/footer');
    }

    public function enviar_foto()
    {
        if(!empty($_FILES['foto-perfil'])){
            mkdir(self::$pasta_upload, 0777, true);

            $foto = $_FILES['foto-perfil'];

            $uploadfile = strtolower(self::$pasta_upload . base64_encode("perfil_" . $this->sessao->id) . image_type_to_extension(getimagesize($foto['tmp_name'])[2]));

            if (move_uploaded_file($foto['tmp_name'], $uploadfile)) {
                $fotoSend = "$uploadfile";
            } else {
                $erros++;
            }

            $this->usuario->sendFoto($fotoSend);
        }

        if($this->session->flashdata('last-uri'))
            redirect(base_url($this->session->flashdata('last-uri')));
        else
            redirect(base_url('perfil/editar'));
    }

    public function editar_formulario()
    {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');

        $senhaPOST = $this->input->post('senha');
        $confirmaSenhaPOST = $this->input->post('confirmar_senha');

        if ( $this->form_validation->run() && ($senhaPOST === $confirmaSenhaPOST || empty($senhaPOST)) )
        {
            if( empty($senhaPOST) && empty($confirmaSenhaPOST) )
            {
                $user = array(
                      'nome_usuario' => $this->input->post('nome'),
                     'email_usuario' => $this->input->post('email')
                );
            }
            else
            {
                $user = array(
                      'nome_usuario' => $this->input->post('nome'),
                     'email_usuario' => $this->input->post('email'),
                     'senha_usuario' => MD5($this->input->post('senha'))
                );
            }

            $this->usuario->edit($user);

            $pessoaFisicaJuridica = (($this->input->post('pessoaFisicaJurica') == 'pessoaFisica') ? TRUE : FALSE);

            $perfil = array(
                'id_usuario' => $this->sessao->id,
                'pessoa_fisica_juridica' => $pessoaFisicaJuridica,
                'cpf' => (($pessoaFisicaJuridica) ? $this->input->post('cpf') : NULL),
                'rg' => (($pessoaFisicaJuridica) ? $this->input->post('rg') : NULL),
                'cnpj' => (($pessoaFisicaJuridica == FALSE) ? $this->input->post('cnpj') : NULL),
                'inscricao_estadual' => (($pessoaFisicaJuridica == FALSE) ? $this->input->post('inscricaoEstadual') : NULL),
                'telefone1' => $this->input->post('telefone1'),
                'telefone2' => $this->input->post('telefone2'),
                'endereco' => $this->input->post('endereco'),
                'complemento' => $this->input->post('complemento'),
                'cep' => $this->input->post('cep'),
                'id_cidade' => $this->usuario->getCidadeByName($this->input->post('cidade'))->id_cidade,
                'numero' => $this->input->post('numero'),
                'profissao' => $this->input->post('profissao'),
                'funcao' => $this->input->post('funcao')
            );

            $this->usuario->savePerfil($perfil);

            $this->session->set_userdata(
                array(
                    'id'    => $this->sessao->id,
                    'nome'  => $user['nome_usuario'],
                    'email' => $user['email_usuario'],
                    'acesso'=> $this->sessao->acesso,
                    'logado'=> $this->sessao->logado
                )
            );

            $this->base->redirecionamento();
        }
        else
        {
            redirect(base_url('perfil/editar'));
        }
    }

}