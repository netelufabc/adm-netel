<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_solicitacao extends CI_Controller {

    public function __construct() {
        parent::__construct();
        IsLogged();
        $this->load->model('Model_solicitacao');
    }

    public function Index() {
        
    }

    function Solicitacao_info() {

        $bolsistas = null;
        $solicitacao_id = $this->uri->segment(3);
        $basic_info = $this->Model_solicitacao->Get_solicitacao_basic_info($solicitacao_id);
        if($basic_info->closed_by != null){
            $closed_by_name = $this->Model_solicitacao->User_info($basic_info->closed_by)->name;
            $basic_info->name = $closed_by_name;
        }
        $mensagens = $this->Model_solicitacao->Get_solic_msgs($solicitacao_id);
        switch ($basic_info->tipo) {
            case 'Encontro':
                $solic = $this->Model_solicitacao->Get_solicitacao_encontro($solicitacao_id);
                break;
            case 'Bolsa':
                $solic = $this->Model_solicitacao->Get_solicitacao_bolsa($solicitacao_id);
                $bolsistas = $this->Model_solicitacao->Get_bolsistas_from_solicitacao($solic->id);
                break;
            case 'Compra':
                $solic = $this->Model_solicitacao->Get_solicitacao_compra($solicitacao_id);
                break;
            case 'Servico':
                $solic = $this->Model_solicitacao->Get_solicitacao_servico($solicitacao_id);
                break;
            default:
                break;
        }

        $dados = array(
            'listaMsg' => $mensagens,
            'basic_info' => $basic_info,
            'solic' => $solic,
            'bolsistas' => $bolsistas,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_solicitacao',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    function New_message() {
        $solicitacao_id = $this->uri->segment(3);
        $this->form_validation->set_rules('mensagem', 'MENSAGEM', 'required');

        if ($this->form_validation->run() == TRUE) {
            $this->Registra_mensagem($solicitacao_id);
        } else {
            redirect('Ctrl_solicitacao/Solicitacao_info/' . $solicitacao_id);
        }
    }

    function Registra_mensagem($solicitacao_id) {
        $notif_emails_string = $this->input->post('email_notif');
        $solic_basic_info = $this->Model_solicitacao->Get_solicitacao_basic_info($solicitacao_id);
        $notif_emails_array0 = explode(',', $notif_emails_string);
        array_push($notif_emails_array0, $solic_basic_info->email);
        $notif_emails_array = array_filter($notif_emails_array0);
        foreach ($notif_emails_array as $email) {
            $email = preg_replace('/\s/', '', $email);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->session->set_flashdata('invalid_email', "E-MAIL INVALIDO: $email - MENSAGEM NÃO REGISTRADA!");
                redirect("Ctrl_solicitacao/Solicitacao_info/" . $solicitacao_id);
            }
        }
        //$subject = "Projeto " . $solic_basic_info->project_number . " - Nova mensagem de " . $solic_basic_info->criado_por;
        $dados_msg = elements('mensagem', $this->input->post());
        $dados_msg['solicitacao_id'] = $solicitacao_id;
        $dados_msg['created_by'] = $this->session->userdata('id');
        $this->Model_solicitacao->New_message($dados_msg);
    }

    function Fechar_ou_cancelar_solic() {

        $solicitacao_id = $this->uri->segment(3);

        $this->form_validation->set_rules('mensagem', 'MENSAGEM', 'required');
        if ($this->form_validation->run() == TRUE && (null !== ($this->input->post('fechar')) || null !== ($this->input->post('cancelar')))) {
            if ($this->input->post('fechar') != null) {
                $status = 'Fechado';
            } else {
                $status = 'Cancelado';
            }
            $this->Model_solicitacao->Update_solic_status($solicitacao_id, $status);
            $this->Registra_mensagem($solicitacao_id);
        }

        $basic_info = $this->Model_solicitacao->Get_solicitacao_basic_info($solicitacao_id);
        $fechar_ou_cancelar = $this->input->post('fechar_solic');

        if ($fechar_ou_cancelar == 'Fechar Solicitação') {
            $fechar_ou_cancelar = 'fechar';
        } else {
            $fechar_ou_cancelar = 'cancelar';
        }

        $dados = array(
            'fechar_ou_cancelar' => $fechar_ou_cancelar,
            'basic_info' => $basic_info,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_confirma_fechar',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

}
