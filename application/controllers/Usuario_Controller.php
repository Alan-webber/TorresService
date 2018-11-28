<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Usuario_Controller extends CI_Controller{

    private static $acessos_permitidos_sem_login = ['usuario/sair', 'login/sair', 'sair', 'termo', 'buscar'];

    public function __construct()
    {
        parent::__construct();

        // Models
        $this->load->model('Usuario_Model', 'usuario');
        $this->load->model('Base_Model', 'base');

        // if(!in_array($this->uri->uri_string(), self::$acessos_permitidos_sem_login) && $this->base->is_logado())
            // $this->base->redirecionamento();
    }

    public function screenLogin()
    {
        $this->load->view('include/header');
        $this->load->view('login/login');
        $this->load->view('include/footer');
    }

    public function screenRegister()
    {
        $this->load->view('include/header');
        $this->load->view('login/register');
        $this->load->view('include/footer');
    }

    public function register()
    {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('usuario', 'Usuário', 'required');
        $this->form_validation->set_rules('acesso', 'Acesso do usuário', 'required');
        $this->form_validation->set_rules('senha', 'Senha', 'required');
        $this->form_validation->set_rules('confirmar_senha', 'Confirmar senha', 'required');
        $this->form_validation->set_rules('concordo_termo', 'Termo', 'required');

        $senhaPOST = $this->input->post('senha');
        $confirmaSenhaPOST = $this->input->post('confirmar_senha');

        $this->session->set_flashdata('cadastro', $this->input->post());

        if ( $this->form_validation->run() )
        {
            if(!($senhaPOST === $confirmaSenhaPOST))
            {
                $this->session->set_flashdata('error', 'As senhas não são iguais');

                redirect(base_url('login/registrar'));
            }

            if(!empty($this->usuario->getByUsuario($this->input->post('usuario'))))
            {
                $this->session->set_flashdata('error', 'Usuário já cadastrado com esse login');

                redirect(base_url('login/registrar'));
            }

            $user = array(
                  'nome_usuario' => $this->input->post('nome'),
                 'email_usuario' => $this->input->post('email'),
                 'login_usuario' => $this->input->post('usuario'),
                 'senha_usuario' => MD5($this->input->post('senha')),
                'acesso_usuario' => $this->input->post('acesso')
            );

            $id_user = $this->usuario->register($user);

            // redirect(base_url("aviso_confirmacao_de_conta/" . base64_encode($id_user)));
            redirect(base_url('login'));
        }
        else
        {
            $this->session->set_flashdata('error', 'Preencha corretamente os campos necessários');

            redirect(base_url('login/registrar'));
        }
    }

    public function login()
    {
        $this->form_validation->set_rules('usuario', 'Usuário', 'required');
        $this->form_validation->set_rules('senha', 'Senha', 'required');

        if( $this->form_validation->run() )
        {
            $post = array(
                'login_usuario' => $this->input->post('usuario'),
                'senha_usuario' => MD5($this->input->post('senha'))
            );

            $usuario = $this->usuario->login($post);

            /* if( $usuario->verificado == FALSE )
            {
                redirect(base_url('aviso_confirmacao_conta/' . base64_encode($usuario->id_usuario)));
            } */

            if ( !empty($usuario) )
            {
                $this->session->set_userdata(
                    array(
                        'id'    => $usuario->id_usuario,
                        'nome'  => $usuario->nome_usuario,
                        'email' => $usuario->email_usuario,
                        'acesso'=> $usuario->acesso_usuario,
                        'logado'=> TRUE
                    )
                );

                $this->base->redirecionamento();
            }
            else
            {
                $this->session->set_flashdata('error', 'Usuário e/ou senha inválida');

                redirect(base_url('login'));
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Preencha corretamente os campos');

            redirect(base_url('login'));
        }
    }

    public function aviso_confirmacao_conta($id_user)
    {
        // $this->load->model('Send_Email_Model', 'sEmail');

        try {
            $usuario = $this->usuario->getById(base64_decode($id_user));

            if($usuario->verificado == FALSE)
            {
                $data = (object)array(
                    'nome_usuario' => $nome_usuario = explode(' ', $usuario->nome_usuario)[0],
                    'email_usuario' => substr($usuario->email_usuario, 0, 3) . '.....' . substr($usuario->email_usuario, strlen($usuario->email_usuario) - 8, 8)
                );

                $mensagem_email = "
                    <p>Olá, <span class=\"blue\"><?= $usuario->nome_usuario ?></span>.</p>

                    <p>Clique em \"confirmar conta\" para começar a usar o Torres Service</p>

                    <br>

                    <p><a href=\"". base_url("confirmar_conta/{$this->set_key_confirmar_conta($usuario->id_usuario)}") ."\" class=\"btn\">Confirmar conta</a></p>
                ";

                $this->sEmail->send($usuario->email_usuario, "Confirmação de conta - Torres Service", $mensagem_email);

                $this->load->view('login/aviso_confirmacao_conta', array('usuario' => $data));
            }
            else
            {
                redirect(base_url('login'));
            }
        } catch (\Throwable $th) {
            throw $th;
        } catch (Exception $ex){
            throw $ex;
        }

        
    }

    public function sair()
    {
        $this->session->sess_destroy();

        redirect(base_url());
    }

    public function termo()
    {
        $this->load->view('include/header');
        $this->load->view('include/termo');
        $this->load->view('include/footer');
    }

    public function buscar()
    {
        $this->load->model("servicos_Model", 'servicos');

        $busca = $this->input->get('search');

        $data['busca'] = !empty($busca) ? $busca : "todos os serviços";

        $data['servicos'] = $this->servicos->procurar_servico($busca);

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

    public function error404()
    {
        $this->load->view('include/header');
        $this->load->view('include/404');
        $this->load->view('include/footer');
    }

    private function set_key_confirmar_conta($key, $criptografar = TRUE)
    {
        $this->load->library('encryption');

        if($criptografar)
            $crypt = $this->encryption->encrypt($key);
        else   
            $crypt = $this->encryption->decrypt($key);

        return $crypt;
    }
}