<?php
defined('BASEPATH') OR exit('Não é possível diretamente');

class Orcamento_Controller extends CI_Controller{

    public static $acessos_permitidos = ['profissional', 'cliente'];

    private $sessao; 

    public function __construct(){
        parent::__construct();

        // Models
        $this->load->model('Usuario_Model', 'usuario');
        $this->load->model('Base_Model', 'base');
        $this->load->model('Orcamento_Model', 'orcamento');
        $this->load->model('Servicos_Model', 'servico');

        $this->sessao = $this->base->acesso(self::$acessos_permitidos);
    }

    public function novo_orcamento($id_servico){
        $this->form_validation->set_rules('necessidade', 'Necessidade', 'required');
        $this->form_validation->set_rules('prazo', 'Prazo', 'required');
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('telefone', 'Telefone', 'required');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required');
        $this->form_validation->set_rules('endereco', 'Endereco', 'required');
        $this->form_validation->set_rules('cep', 'Cep', 'required');

        if ($this->form_validation->run()) {
            
            $orcamento = 
                array(
                    'necessidade' => nl2br($this->input->post('necessidade')),
                    'prazo' => $this->input->post('prazo'),
                    'nome' => $this->input->post('nome'),
                    'email' => $this->input->post('email'),
                    'telefone' => $this->input->post('telefone'),
                    'id_cidade' => (int)$this->usuario->getCidadeByName($this->input->post('cidade'))->id_cidade,
                    'endereco' => $this->input->post('endereco'),
                    'cep' => $this->input->post('cep'),
                    'id_servico' => (int)$id_servico
                );

            $insert = $this->orcamento->create($orcamento);

            if( $insert )
                $this->session->set_flashdata('success', 'Orçamento realizado com sucesso!');
            else
                $this->session->set_flashdata('error', 'Não foi possível realizar o orçamento, tente novamente!');
                
        } else {
            $this->session->set_flashdata('error', 'Todos os campos são requeridos!');
        }

        redirect(base_url("servicos/detalhes/{$id_servico}"));
        
    }

    public function listar(){
        if( in_array($this->sessao->acesso, ['profissional']) )
            $this->listar_profissional();

        if( in_array($this->sessao->acesso, ['cliente']) )
            $this->listar_cliente();     
    }

    public function listar_profissional(){
        $data['orcamentos'] = $this->orcamento->get_by_profissional();

        $this->load->view('include/header');
        $this->load->view('include/navbar');
        $this->load->view('orcamento/listar', $data);
        $this->load->view('include/footer');
    }

    public function visualizar($id_orcamento){
        $data['orcamento'] = $this->orcamento->get($id_orcamento);

        $data['orcamento']->nota = $this->servico->buscar_nota($data['orcamento']->id_servico);

        $data['existe_mensagem_nao_visualizada'] = FALSE;

        $data['mensagens'] = $this->orcamento->mensagens($id_orcamento);

        foreach ($data['mensagens'] as $key => $mensagem) {
            if( !$mensagem->mensagem_visualizada && 
                    (($this->sessao->acesso == 'profissional' && $mensagem->mensagem_enviada_pelo_profissional == false) ||
                    ($this->sessao->acesso == 'cliente' && $mensagem->mensagem_enviada_pelo_profissional == true))
            )
            {
                $data['existe_mensagem_nao_visualizada'] = TRUE;
            }
        }

        $this->orcamento->set_mensagens_visualizada($id_orcamento);
        $this->orcamento->set_orcamento_visualizado($id_orcamento);

        $this->load->view('include/header');
        $this->load->view('include/navbar');
        $this->load->view('orcamento/detalhar', $data);
        $this->load->view('include/footer');
    }

    public function listar_cliente(){
        $data['orcamentos'] = $this->orcamento->get_by_cliente();

        $this->load->view('include/header');
        $this->load->view('include/navbar');
        $this->load->view('orcamento/listar', $data);
        $this->load->view('include/footer');
    }

    public function enviar_mensagem($id_orcamento){
        $mensagem_item = array(
            "mensagem_enviada_pelo_profissional" => ($this->sessao->acesso == 'profissional') ? true : false,
            "mensagem" => $this->input->post('form-chat'),
            "id_orcamento" => intval($id_orcamento)
        );

        $this->session->set_flashdata('mensagem_enviada', 'true');

        if($this->orcamento->adicionar_mensagem($mensagem_item))
            $this->session->set_flashdata('success', "Mensagem enviada.");
        else
            $this->session->set_flashdata('error', "Você não pode enviar a mensagem");
        
        redirect(base_url("orcamento/visualizar/$id_orcamento"));
    }

    public function finalizar($id_orcamento){
        if($this->orcamento->finalizar_orcamento($id_orcamento))
            $this->session->set_flashdata('success', "Orçamento finalizado.");
        else
            $this->session->set_flashdata('error', "Não é possível finalizar este orçamento");
        
        redirect(base_url("orcamento/visualizar/$id_orcamento"));
    }

    public function avaliar($id_orcamento){
        $id_servico = $this->input->post('id_servico');
        $nota = $this->input->post('nota');

        if($this->servico->avaliar($id_servico, $nota))
            $this->session->set_flashdata('success', "Serviço avalidado.");
        else
            $this->session->set_flashdata('error', "Não é possível avaliar esse serviço.");
        
        redirect(base_url("orcamento/visualizar/$id_orcamento"));
    }

    public function listar_mensagens_nao_visualizadas()
    {
        $data['mensagens'] = $this->orcamento->listar_mensagens_nao_visualizadas();

        $this->load->view('include/header');
        $this->load->view('include/navbar');
        $this->load->view('orcamento/mensagens_nao_visualizadas', $data);
        $this->load->view('include/footer');
    }
}