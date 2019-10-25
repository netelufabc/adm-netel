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

    /**
     * Página inicial view_content_solicitacao
     */
    function Solicitacao_info() {

        $bolsistas = null;
        $solicitacao_id = $this->uri->segment(3);
        $basic_info = $this->Model_solicitacao->Get_solicitacao_basic_info($solicitacao_id);

        //verificar se usuario é assistente ou coordenador do projeto, para evitar fraudes
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
        foreach ($mensagens as $msg) {//bloco para adicionar anexos à mensagem, se existirem
            $msg->files = array();
            $files = $this->Model_solicitacao->Get_msg_files($msg->id);
            if (count($files) > 0) {
                foreach ($files as $anexo) {
                    array_push($msg->files, $anexo);
                }
            }
        }

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

    /**
     * Página inicial view_content_classificacao
     */
    function Classificacao_info() {

        $solicitacao_id = $this->uri->segment(3);
        $basic_info = $this->Model_solicitacao->Get_solicitacao_basic_info($solicitacao_id);

        //verificar se usuario é assistente ou coordenador do projeto, para evitar fraudes
        $project_id = $basic_info->project_id;
        $coordenador = $this->Model_project->Get_project_coordenador($project_id);
        $lista_assistentes = $this->Model_project->Get_project_assitentes($project_id);
        IsProjectOwnerOrAssist($this->session->userdata['id'], $coordenador, $lista_assistentes);
        //FIM verificar se usuario é assistente ou coordenador do projeto

        if ($basic_info->closed_by != null) {
            $closed_by_name = $this->Model_solicitacao->User_info($basic_info->closed_by)->name;
            $basic_info->name = $closed_by_name;
        }

        $classificacao = null; //para solicitacaoes de contratacao somente
        $solic = $this->Model_solicitacao->Get_solicitacao_contratacao($solicitacao_id);
        if ($solic->status == "Aguardando Netel") {//se status for aguardando netel, pega a lista d classificacao
            $classificacao = $this->Model_solicitacao->Get_classificacao($solicitacao_id);
        }

        if ($classificacao != null) {
            foreach ($classificacao as $classificado) {
                if ($classificado->situacao == 'Contratado') {
                    $classificado->parcelas = $this->Model_solicitacao->Get_parcelas($classificado->id);
                }
            }
        }

        $dados = array(
            'classificacao' => $classificacao,
            'basic_info' => $basic_info,
            'solic' => $solic,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_classificacao',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    /**
     * Função que insere, remove e atualiza do banco de dados as parcelas de autônomo.
     * Botão "Definir Parcelas" em view_content_classificacao.
     * Mexa por sua conta e risco.
     */
    function Set_pagamento() {
        $solicitacao_id = $this->uri->segment(3);
        $classificado_id = $this->uri->segment(4);

        $valor_parcelas = $this->input->post('parcelavalor');
        $data_parcelas = $this->input->post('parceladata');
        $id_parcelas = $this->input->post('parcela');
        $status_parcelas = $this->input->post('status');

        $today = date('Y-m-d'); //verificar se as datas são maiores que data do dia
        foreach ($data_parcelas as $key => $value) {
            if ($data_parcelas{$key} == '') {//caso exista a parcela e usuario mande alterar sem colocar a data, seta valor = 0 para dar erro
                $valor_parcelas{$key} = '';
            }
            if (($data_parcelas{$key} <= $today) && ($status_parcelas{$key} != 'Pago') && ($data_parcelas{$key} != '')) {
                $this->session->set_flashdata('sem_parcelas', "Datas devem ser maior que a data de hoje!");
                redirect('Ctrl_solicitacao/Classificacao_info/' . $solicitacao_id);
            }
        }//fim verificar se as datas são maiores que data do dia        

        $remuneracao_bruta = $this->input->post('remuneracao_bruta'); //verificar se soma das parcelas é igual valor total do pagamento
        $soma_parecelas = 0;
        foreach ($valor_parcelas as $key => $value) {
            $soma_parecelas += $valor_parcelas{$key};
        }
        if ($soma_parecelas != $remuneracao_bruta) {
            $this->session->set_flashdata('sem_parcelas', "A soma dos valores das parcelas ($soma_parecelas) deve ser igual a remuneração total bruta ($remuneracao_bruta).");
            redirect('Ctrl_solicitacao/Classificacao_info/' . $solicitacao_id);
        }//fim verificar se soma das parcelas é igual valor total do pagamento

        $parcelas = array(); //monta array com objetos 'parcela'
        foreach ($id_parcelas as $key => $value) {
            $parcela_temp = new stdClass();
            $parcela_temp->id = SetValueNotEmpty($id_parcelas{$key});
            $parcela_temp->data_pag = SetValueNotEmpty($data_parcelas{$key});
            $parcela_temp->valor_pag = SetValueNotEmpty($valor_parcelas{$key});
            $parcela_temp->status_pag = SetValueNotEmpty($status_parcelas{$key});
            $parcela_temp->acao = null;
            array_push($parcelas, $parcela_temp);
        }//fim monta array

        foreach ($parcelas as $key => $parcela) {//estabelece acoes para cada parcela, remove caso dados inserido invalidos ou null
            if ($parcela->id != null) {//se ID existe no banco
                if ($parcela->data_pag == null || $parcela->valor_pag == null) {
                    $parcela->acao = 'remover'; //remove do banco
                } else {
                    if ($parcela->status_pag != 'Pago' && $parcela->status_pag != 'Autorizado') {//se já foi pago ou autorizado, nao mexe, senão altera status
                        $parcela->status_pag = 'Aguardando autorização';
                    }
                    $parcela->acao = 'atualizar'; //atualiza no banco                        
                }
            } else {//se ID não existe no banco (nova inclusao de parcela)
                if ($parcela->data_pag == null || $parcela->valor_pag == null) {//se um dos campos for null, nao faz nada
                    unset($parcelas{$key});
                } else {//se todos campos preenchidos, insere
                    $parcela->status_pag = 'Aguardando autorização';
                    $parcela->acao = 'inserir';
                }
            }
        }//fim estabelece acoes para cada parcela

        if (count($parcelas) > 0) {// remover do banco
            foreach ($parcelas as $key => $parcela) {//remove onde nao foi preenchido os campos
                if ($parcela->acao == 'remover') {
                    $this->Model_solicitacao->Delete_parcela($parcela->id); //remove do banco
                    unset($parcelas{$key});
                }
            }
        }// fim remover do banco



        if (count($parcelas) > 0) {
            usort($parcelas, function($a, $b) { //sort array por data
                return strcmp($a->data_pag, $b->data_pag);
            });
        } else {//retorna erro se não foi inserido ao menos uma data e valor.            
            $this->session->set_flashdata('sem_parcelas', "Defina ao menos uma para pagamento (data e valor)");
            redirect('Ctrl_solicitacao/Classificacao_info/' . $solicitacao_id);
        }

        $parcela_num = 1; //ajustar coluna parcela_num da tabela
        foreach ($parcelas as $parcela) {
            $parcela->parcela_num = $parcela_num;
            $parcela_num++;
        }//fim ajustar coluna parcela_num da tabela

        foreach ($parcelas as $key => $parcela) {// inserir e atualizar o banco
            $parcela->id_classificado = $classificado_id;
            if ($parcela->acao == 'atualizar') {
                unset($parcela->acao);
                $this->Model_solicitacao->Update_parcela($parcela); //atualiza no banco
            } elseif ($parcela->acao == 'inserir') {
                unset($parcela->acao);
                $this->Model_solicitacao->Insert_parcela($parcela); //insere no banco
            }
        }//fim inserir e atualizar do banco

        $this->session->set_flashdata('pag_atualizado', "Parcelas de pagamento atualizadas."); //se der tudo certo, retorna
        redirect('Ctrl_solicitacao/Classificacao_info/' . $solicitacao_id);
    }

    /**
     * Função do botão "Marcar como contratado" na tabela de classificados em 
     * view_content_classificacao, para o administrativo somente.
     */
    function Set_contratado() {
        $classificado_id = $this->uri->segment(3);
        $solicitacao_id = $this->uri->segment(4);
        $this->Model_solicitacao->Set_contratado_status($classificado_id, 'Contratado');
        $this->session->set_flashdata('classif_alterada', "Classificacao alterada para contratado");
        redirect('Ctrl_solicitacao/Classificacao_info/' . $solicitacao_id);
    }

    /**
     * Função do botão "Marcar como classificado" na tabela de classificados em 
     * view_content_classificacao, para o administrativo somente.
     */
    function Set_classificado() {
        $classificado_id = $this->uri->segment(3);
        $solicitacao_id = $this->uri->segment(4);

        $parcelas = $this->Model_solicitacao->Get_parcelas($classificado_id); //verifica se alguma parcela já foi paga. se sim, redireciona com erro
        foreach ($parcelas as $parcela) {
            if ($parcela->status_pag == 'Pago') {
                $this->session->set_flashdata('parcela_paga', "Não é possível alterar o status, alguma parcela já foi paga!");
                redirect('Ctrl_solicitacao/Classificacao_info/' . $solicitacao_id);
            }
        }

        foreach ($parcelas as $parcela) {//apaga todas parcelas e set como classificado, caso nenhuma parcela foi paga
            $this->Model_solicitacao->Delete_parcela($parcela->id);
        }
        $this->Model_solicitacao->Set_contratado_status($classificado_id, 'Classificado');
        $this->session->set_flashdata('classif_alterada', "Classificacao alterada para classificado");
        redirect('Ctrl_solicitacao/Classificacao_info/' . $solicitacao_id);
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
        $dados_msg['solicitacao_id'] = $solicitacao_id; //info para inserir no db
        $dados_msg['created_by'] = $this->session->userdata('id'); //info para inserir no db        
        $msg_id = $this->Model_solicitacao->New_message($dados_msg); //grava msg no banco

        if (count($_FILES) > 0) {//se nao há arquivos anexos, pula esta parte
            $this->upload_files($msg_id);
            $msg_body = $msg_body . "<br><br>Esta mensagem contém anexos, para visualizá-los, acesse o sistema do NETEL: netel.ufabc.edu.br<br><br>";
        }

        enviar_mail($subject, $msg_body, $notif_emails_array); //envia os emails

        $this->session->set_flashdata('msg_ok', 'Mensagem registrada com sucesso!');
        redirect("Ctrl_solicitacao/Solicitacao_info/" . $dados_msg['solicitacao_id']);
    }

    function upload_files($msg_id = null) {//$dados) {
        $countfiles = count($_FILES['files']['name']);

        //verifica e cria diretorio de upload do user
        if (!is_dir('uploads/' . $this->session->userdata['login'])) {
            mkdir('uploads/' . $this->session->userdata['login']);
        }

        for ($i = 0; $i < $countfiles; $i++) {
            $file = array();
            $file['msg_id'] = $msg_id;
            $file['file_hash'] = generateRandomString();
            $file['file_name'] = utf8_decode($_FILES['files']['name'][$i]);
            move_uploaded_file($_FILES['files']['tmp_name'][$i], 'uploads/' . $this->session->userdata['login'] . '/' . $file['file_hash']);
            $this->Model_solicitacao->Insert_file_info($file);
        }
    }

    /**
     * Página view_content_confirma_fechar, botões fechar ou cancelar da página de solicitações
     */
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

    /**
     * Botão "LIberar Classificacao" do formulario em view_content_classificacao
     */
    function Liberar_para_classificacao() {
        $solic_id = $this->uri->segment(3);
        $this->Model_solicitacao->Update_contratacao_status('Aguardando Classificacao', $solic_id);
    }

    /**
     * Botão "Inserir Classificacao" do formulario em view_content_classificacao
     */
    function Insert_classificacao() {

        $solic_id = $this->uri->segment(3);

        if (count($this->input->post('nome')) == 0) {
            $this->session->set_flashdata('sem_candidatos', "Nenhum candidato inserido, favor inserir ao menos um classificado, ou clicar em Nenhum Classificado");
            redirect("Ctrl_solicitacao/Classificacao_info/" . $solic_id);
        }

        $nome = $this->input->post('nome');
        $exigencias = $this->input->post('exigencias');
        $descricao = $this->input->post('descricao');

        foreach ($nome as $key => $value) {
            $classificado = array('id_solic' => $solic_id, 'posicao' => ($key + 1), 'nome' => $value, 'exigencias' => $exigencias[$key], 'descricao' => $descricao[$key]);
            $this->Model_solicitacao->Insert_classificado($classificado);
        }
        $this->Model_solicitacao->Update_contratacao_status('Aguardando Netel', $solic_id);
    }

    /**
     * Botão "Nenhum Aprovado" do formulário de classificacao (view_content_classificacao)
     */
    function Nenhum_candidato_aprovado() {
        $solic_id = $this->uri->segment(3);
        $this->Model_solicitacao->Update_contratacao_status('Aguardando Curriculos', $solic_id);
    }

}
