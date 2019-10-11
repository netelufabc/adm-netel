<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_solicitacao extends CI_Controller {

    public function __construct() {
        parent::__construct();
        IsLogged();
        AllowRoles(2, 3, 4);
        $this->load->model('Model_solicitacao');
        $this->load->model('Model_project');
    }

    public function Index() {
        
    }

    function Solicitacao_info() {

        $bolsistas = null;
        $solicitacao_id = $this->uri->segment(3);
        $basic_info = $this->Model_solicitacao->Get_solicitacao_basic_info($solicitacao_id);

        //verificar se usuario é assistente ou coordenador do projeto
        $project_id = $basic_info->project_id;
        $coordenador = $this->Model_project->Get_project_coordenador($project_id);
        $lista_assistentes = $this->Model_project->Get_project_assitentes($project_id);
        IsProjectOwnerOrAssist($this->session->userdata['id'], $coordenador, $lista_assistentes);
        //FIM verificar se usuario é assistente ou coordenador do projeto

        if ($basic_info->closed_by != null) {
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
            case 'Contratacao':
                $solic = $this->Model_solicitacao->Get_solicitacao_contratacao($solicitacao_id);
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
        $notif_emails_string = $this->input->post('email_notif'); //pega emails cópia do formulario
        $solic_basic_info = $this->Model_solicitacao->Get_solicitacao_basic_info($solicitacao_id); //info da solicitacao
        $notif_emails_array0 = explode(',', $notif_emails_string);
        array_push($notif_emails_array0, $solic_basic_info->email); //insere email do requisitante no array de e-mails cópia
        $notif_emails_array = array_filter($notif_emails_array0); //array com email do requisitante e cópias
        foreach ($notif_emails_array as $email) {//verifica se os email são válidos
            $email = preg_replace('/\s/', '', $email);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {//se for email invalido, redireciona
                $this->session->set_flashdata('invalid_email', "E-MAIL INVALIDO: $email - MENSAGEM NÃO REGISTRADA!");
                redirect("Ctrl_solicitacao/Solicitacao_info/" . $solicitacao_id);
            }
        }
        $dados_msg = elements('mensagem', $this->input->post()); //msg para gravar no banco
        $msg_body = nl2br("Solicitação número: $solic_basic_info->id\n"//corpo da msg
                . "Tipo: $solic_basic_info->tipo \n"
                . "Status <strong>$solic_basic_info->status</strong>\n"
                . "Projeto Número: $solic_basic_info->project_number\n"
                . "Projeto Título: $solic_basic_info->project_title\n\n"
                . "Mensagem:\n" . "<strong>" . $this->input->post('mensagem') . "</strong>");
        $subject = "Projeto " . $solic_basic_info->project_number . " - Nova mensagem de " . $solic_basic_info->criado_por; //assunto da msg
        enviar_mail($subject, $msg_body, $notif_emails_array);
        $dados_msg['solicitacao_id'] = $solicitacao_id; //info para inserir no db
        $dados_msg['created_by'] = $this->session->userdata('id'); //info para inserir no db
        $this->Model_solicitacao->New_message($dados_msg); //grava msg no banco
    }

    public function upload_files($dados) {

        $countfiles = count($_FILES['files']['name']);

        if ($countfiles > 0) {
            //verifica e cria diretorio de upload do user
            if (!is_dir('uploads/' . $this->session->userdata['login'])) {
                mkdir('uploads/' . $this->session->userdata['login']);
            }

            //deleta o campo status porque nÃ£o tem na tabela de upload
            //unset($dados['status']);

            for ($i = 0; $i < $countfiles; $i++) {
                //falta definir melhor como serÃ¡ padronizado o nome do arquivo
                $filename = $this->session->userdata['login'] . '-' . date('dmyHis\-') . utf8_decode($_FILES['files']['name'][$i]);
                $dados['filename'] = $filename;
                if (move_uploaded_file($_FILES['files']['tmp_name'][$i], 'uploads/' . $this->session->userdata['login'] . '/' . $filename)) {
                   echo "ok";// $this->Model_project->Insert_upload_infos($dados);
                }
            }
        }
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
