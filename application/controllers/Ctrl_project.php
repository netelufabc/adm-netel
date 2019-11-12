<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_project extends CI_Controller {

    public function __construct() {
        parent::__construct();
        IsLogged();
        AllowRoles(2, 3, 4);
        $this->load->model('Model_project');
        $this->load->model('Model_solicitacao');
    }

    public function Index() {
        
    }

    function Project_info() {

        $project_id = $this->uri->segment(3);
        $coordenador = $this->Model_project->Get_project_coordenador($project_id);
        $lista_assistentes = $this->Model_project->Get_project_assitentes($project_id);

        IsProjectOwnerOrAssist($this->session->userdata['id'], $coordenador, $lista_assistentes); //verificar se usuario é assistente ou coordenador do projeto

        $project_info = $this->Model_project->Get_project_info($project_id);
        $lista_tutores = $this->Model_project->Get_project_tutores($project_id);
        $lista_docentes = $this->Model_project->Get_project_docentes($project_id);
        $lista_solicitacoes = $this->Model_solicitacao->Get_solicitacoes_project($project_id);

        $dados = array(
            'listaSolicitacoes' => $lista_solicitacoes,
            'coord' => $coordenador,
            'listaTutores' => $lista_tutores,
            'listaDocentes' => $lista_docentes,
            'listaAssistentes' => $lista_assistentes,
            'project_info' => $project_info,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_project',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    function New_solicitacao() {

        $project_id = $this->uri->segment(3);

        $coordenador = $this->Model_project->Get_project_coordenador($project_id);
        $lista_assistentes = $this->Model_project->Get_project_assitentes($project_id);
        IsProjectOwnerOrAssist($this->session->userdata['id'], $coordenador, $lista_assistentes); //verificar se usuario é assistente ou coordenador do projeto

        if (HasRole(1, 2, 3)) { //mostra opções de nova solicitação para roles 1,2 e 3
            $new_solic_list = array('' => 'Selecione...',
                'red' => 'Contratação de Pessoal',
                'green' => 'Pagamento de Bolsa',
                'blue' => 'Encontro Presencial',
                'maroon' => 'Contratação de Serviços',
                'magenta' => 'Compras');
        } else {//mostra somente opção de nova solicitação de encontro para assistentes
            $new_solic_list = array('' => 'Selecione...', 'blue' => 'Encontro Presencial');
        }

        $dados = array(
            'new_solic_list' => $new_solic_list, //lista para menu dropdown
            'project_id' => $project_id,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_new_solicitacao',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    function New_solic_encontro() {

        $this->form_validation->set_rules('polo', 'POLO', 'required');
        $this->form_validation->set_rules('data', 'DATA', 'required');
        $this->form_validation->set_rules('hora_inicio', 'HORA INÍCIO', 'required');
        $this->form_validation->set_rules('hora_fim', 'HORA TERMINO', 'required');


        if ($this->form_validation->run() == TRUE) {
            $dados_solic_encontro = elements(array('polo', 'data', 'hora_inicio', 'hora_fim',
                'professores', 'tutores', 'quantidade_sala', 'capacidade_sala', 'quantidade_lab',
                'capacidade_lab', 'auditorio', 'equip', 'obs'), $this->input->post());
            $dados_solic = array('project_id' => $this->input->post('project_id'),
                'created_by' => $this->session->userdata['id'], 'tipo' => 'Encontro',
                'status' => 'Aberto');

            $this->Model_solicitacao->New_solic_encontro($dados_solic, $dados_solic_encontro);
        } else {
            redirect('Ctrl_project/Project_info/' . $this->input->post('project_id'));
        }
    }

    function New_solic_bolsa() {

        $this->form_validation->set_rules('mes_ano', 'MÊS/ANO', 'required');

        if ($this->form_validation->run() == TRUE) {
            $project_id = $this->input->post('project_id');
            $dados_solic_bolsa = elements(array('mes_ano', 'tutor_ou_docente'), $this->input->post());

            $dados_solic_bolsa['mes_ano'] = $dados_solic_bolsa['mes_ano'] . "-00";

            if ($dados_solic_bolsa['tutor_ou_docente'] == '') {
                $this->session->set_flashdata('erro_solic', 'Selecione se quem vai receber a bolsa é tutor ou docente!');
                redirect('Ctrl_project/New_solicitacao/' . $this->input->post('project_id'));
            }

            $lista_tutores = $this->Model_project->Get_project_tutores($project_id);
//          $lista_docentes = $this->Model_project->Get_project_docentes($project_id);
            foreach ($lista_tutores as $tutor) {
                $tutor->reports = $this->Model_project->Get_tutor_project_reports($tutor->id, $project_id);
                foreach ($tutor->reports as $key => $report) {
                    if (($report->month_year != $dados_solic_bolsa['mes_ano']) || ($report->status != 'pendente')) {
                        unset($tutor->reports{$key});
                    }
                }
            }

            $dados = array(
                'month_year' => $dados_solic_bolsa['mes_ano'],
                'lista_tutores' => $lista_tutores,
                'project_id' => $project_id,
                'view_menu' => 'View_menu.php',
                'view_content' => 'View_content_pag_bolsa_1.php',
                'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
            );
            $this->load->view('View_main', $dados);
        }
    }

    /**
     * botão prosseguir da página View_content_pag_bolsa_1.php
     */
    function Confirma_tutores() {
        $project_id = $this->uri->segment(3);

        if ($this->input->post('report_id') != null) {//verifica se existe ao menos um tutor com relatório cadastrado
            $report_id = $this->input->post('report_id');
        } else {
            redirect('Ctrl_project/New_solicitacao/' . $project_id);
        }

        //verificar se ao menos um dos relatórios foi selecionado como aprovado ou rejeitado
        $radio_set = false;
        foreach ($report_id as $id) {
            if ($this->input->post('aprovar' . $id)) {
                $radio_set = true;
                break;
            }
        }

        if (!$radio_set) {
            $this->session->set_flashdata('erro_solic', 'Selecione ao menos um relatório aprovado ou rejeitado!');
            redirect('Ctrl_project/New_solicitacao/' . $project_id);
        }
        //fim verificar se ao menos um dos relatórios foi selecionado como aprovado ou rejeitado
        //monta array de relatórios
        $reports = array();
        foreach ($report_id as $id) {
            $report = new stdClass();
            if ($this->input->post('aprovar' . $id)) {
                $report->id = $id;
                $report->tutor = $this->Model_project->Get_tutor_by_report($id)->name;
                $report->situacao = $this->input->post('aprovar' . $id);
                if ($this->input->post('aprovar' . $id) == 'reprovado') {//verifica se o relatório foi reprovado
                    if ($this->input->post('motivo' . $id) == null) {//verifica se foi escolhido um motivo caso relatório foi rejeitado
                        $this->session->set_flashdata('erro_solic', 'Você deve escolher um motivo para um relatório rejeitado!');
                        redirect('Ctrl_project/New_solicitacao/' . $project_id);
                    } else {
                        $report->motivo = $report->motivo = $this->input->post('motivo' . $id);
                    }
                } else {
                    $report->motivo = null;
                }
                array_push($reports, $report);
            }
        }
        //fim monta array de relatórios

        $aprovados = array();
        $reprovados = array();
        foreach ($reports as $report) {
            if ($report->situacao == 'aprovado') {
                array_push($aprovados, $report->tutor);
            } else {
                array_push($reprovados, $report->tutor . " - Motivo: " . $report->motivo);
            }
        }

        $dados_relatorio = new stdClass();
        $dados_relatorio->project_id = $this->Model_project->Get_project_info($project_id)->id;
        $dados_relatorio->numero_projeto = $this->Model_project->Get_project_info($project_id)->project_number;
        $dados_relatorio->nome_projeto = $this->Model_project->Get_project_info($project_id)->title;
        $dados_relatorio->coordenador = $this->Model_project->Get_project_coordenador($project_id)->coord_name;
        $dados_relatorio->month_year = substr($this->input->post('month_year'), 0, -2) . "01";
        $dados_relatorio->today = date('Y-m-d');

        $text_temp = "Na condição de Coordenador do projeto número <strong>$dados_relatorio->numero_projeto - $dados_relatorio->nome_projeto</strong>, oferecido em parceria com a UFABC/CAPES, "
                . "atesto e ratifico o trabalho realizado pelos tutores relacionados abaixo durante o mês de <strong>" . vdate($dados_relatorio->month_year, 'myext') . "</strong>.\nOs detalhes do trabalho "
                . "realizado por cada tutor estão apresentados no relatório mensal individual de tutoria, que foi encaminhado pelos seguintes a esta coordenação:";

        $aprovado_string = '';
        if (count($aprovados) > 0) {
            $aprovado_string = "\n\n<strong>Pagamentos de bolsas aprovados:</strong>\n";
            foreach ($aprovados as $aprovado) {
                $aprovado_string = $aprovado_string . "\n" . $aprovado;
            }
        }

        $reprovado_string = '';
        if (count($reprovados) > 0) {
            $reprovado_string = "\n\n<strong>Pagamentos de bolsas reprovados:</strong>\n";
            foreach ($reprovados as $reprovado) {
                $reprovado_string = $reprovado_string . "\n" . $reprovado;
            }
        }

        $footer = "\n\n\n<strong>Santo André, " . vdate($dados_relatorio->today, 'dmy') . "</strong>" .
                "\n\n\n_________________________________________\n<strong>" . $dados_relatorio->coordenador . "\nCoordenador de Projeto UAB/UFABC</strong>";

        $text = $text_temp . $aprovado_string . $reprovado_string . $footer; //string final para o PDF

        $dados = array(
            'text' => $text,
            'dados_relatorio' => $dados_relatorio,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_pag_bolsa_2.php',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    function New_solic_servico() {

        $this->form_validation->set_rules('tipo_servico', 'TOP DE SERVIÇO', 'required');
        $this->form_validation->set_rules('motivacao_servico', 'MOTIVAÇÃO DO SERVIÇO', 'required');
        $this->form_validation->set_rules('conexao_servico', 'CONEXÃO DO SERVIÇO', 'required');
        $this->form_validation->set_rules('prazo_servico', 'PRAZO DO SERVIÇO', 'required');

        if ($this->form_validation->run() == TRUE) {
            $dados_solic_servico = elements(array('tipo_servico', 'motivacao_servico',
                'conexao_servico', 'prazo_servico'), $this->input->post());
            $dados_solic = array('project_id' => $this->input->post('project_id'),
                'created_by' => $this->session->userdata['id'], 'tipo' => 'Servico',
                'status' => 'Aberto');
            $this->Model_solicitacao->New_solic_servico($dados_solic, $dados_solic_servico);
        } else {
            redirect('Ctrl_project/Project_info/' . $this->input->post('project_id'));
        }
    }

    function New_solic_compra() {

        $this->form_validation->set_rules('item_compra', 'ITEM', 'required');
        $this->form_validation->set_rules('especificacao_compra', 'ESPECIFICAÇÃO', 'required');
        $this->form_validation->set_rules('unidade_compra', 'UNIDADE', 'required');
        $this->form_validation->set_rules('quantidade_compra', 'QUANTIDADE', 'required');

        if ($this->form_validation->run() == TRUE) {
            $dados_solic_servico = elements(array('item_compra', 'especificacao_compra',
                'unidade_compra', 'quantidade_compra', 'valor_compra', 'motivacao_compra',
                'conexao_compra'), $this->input->post());
            $dados_solic = array('project_id' => $this->input->post('project_id'),
                'created_by' => $this->session->userdata['id'], 'tipo' => 'Compra',
                'status' => 'Aberto');
            $this->Model_solicitacao->New_solic_compra($dados_solic, $dados_solic_servico);
        } else {
            redirect('Ctrl_project/Project_info/' . $this->input->post('project_id'));
        }
    }

    function New_solic_autonomo() {

        $this->form_validation->set_rules('titulo', 'TITULO', 'required');
        $this->form_validation->set_rules('quantidade', 'QUANTIDADE DE VAGAS', 'required');
        $this->form_validation->set_rules('descricao', 'DESCRIÇÃO', 'required');
        $this->form_validation->set_rules('req_obrig', 'REQUISITOS OBRIGATÓRIOS', 'required');
        $this->form_validation->set_rules('tempo_estimado', 'TEMPO ESTIMADO', 'required');
        $this->form_validation->set_rules('remuneracao_bruta', 'REMUNERAÇÃO BRUTA', 'required');
        $this->form_validation->set_rules('dias_divulgacao', 'DIAS PARA DIVULGAÇÃO', 'required');

        if ($this->form_validation->run() == TRUE) {
            $dados_solic_autonomo = elements(array('titulo', 'quantidade',
                'descricao', 'req_obrig', 'req_desej', 'remuneracao_bruta',
                'tempo_estimado', 'dias_divulgacao', 'tipo_selecao'), $this->input->post());
            $dados_solic_autonomo['tipo'] = 'Autonomo';
            $dados_solic_autonomo['tipo_selecao'] = $this->Tipo_selecao($dados_solic_autonomo['tipo_selecao']);
            $dados_solic = array('project_id' => $this->input->post('project_id'),
                'created_by' => $this->session->userdata['id'], 'tipo' => 'Contratacao',
                'status' => 'Aberto');
            $this->Model_solicitacao->New_solic_contratacao($dados_solic, $dados_solic_autonomo);
        } else {
            redirect('Ctrl_project/Project_info/' . $this->input->post('project_id'));
        }
    }

    function New_solic_celetista() {

        $this->form_validation->set_rules('titulo', 'TITULO', 'required');
        $this->form_validation->set_rules('quantidade', 'QUANTIDADE DE VAGAS', 'required');
        $this->form_validation->set_rules('descricao', 'DESCRIÇÃO', 'required');
        $this->form_validation->set_rules('req_obrig', 'REQUISITOS OBRIGATÓRIOS', 'required');
        $this->form_validation->set_rules('local_trabalho', 'LOCAL DE TRABALHO', 'required');
        $this->form_validation->set_rules('horario_trabalho', 'HORÁRIO DE TRABALHO', 'required');
        $this->form_validation->set_rules('remuneracao_mensal', 'REMUNERAÇÃO MENSAL', 'required');
        $this->form_validation->set_rules('dias_divulgacao', 'DIAS PARA DIVULGAÇÃO', 'required');

        if ($this->form_validation->run() == TRUE) {
            $dados_solic_celetista = elements(array('titulo', 'quantidade',
                'descricao', 'req_obrig', 'req_desej', 'remuneracao_mensal',
                'local_trabalho', 'horario_trabalho', 'dias_divulgacao', 'tipo_selecao'), $this->input->post());
            $dados_solic_celetista['tipo'] = 'Celetista';
            $dados_solic_celetista['tipo_selecao'] = $this->Tipo_selecao($dados_solic_celetista['tipo_selecao']);
            $dados_solic = array('project_id' => $this->input->post('project_id'),
                'created_by' => $this->session->userdata['id'], 'tipo' => 'Contratacao',
                'status' => 'Aberto');
            $this->Model_solicitacao->New_solic_contratacao($dados_solic, $dados_solic_celetista);
        } else {
            redirect('Ctrl_project/Project_info/' . $this->input->post('project_id'));
        }
    }

    function New_solic_bolsista() {

        $this->form_validation->set_rules('titulo', 'TITULO', 'required');
        $this->form_validation->set_rules('quantidade', 'QUANTIDADE DE VAGAS', 'required');
        $this->form_validation->set_rules('descricao', 'DESCRIÇÃO', 'required');
        $this->form_validation->set_rules('req_obrig', 'REQUISITOS OBRIGATÓRIOS', 'required');
        $this->form_validation->set_rules('local_trabalho', 'LOCAL DE TRABALHO', 'required');
        $this->form_validation->set_rules('horario_trabalho', 'HORÁRIO DE TRABALHO', 'required');
        $this->form_validation->set_rules('remuneracao_mensal', 'REMUNERAÇÃO MENSAL', 'required');
        $this->form_validation->set_rules('dias_divulgacao', 'DIAS PARA DIVULGAÇÃO', 'required');

        if ($this->form_validation->run() == TRUE) {
            $dados_solic_celetista = elements(array('titulo', 'quantidade',
                'descricao', 'req_obrig', 'req_desej', 'remuneracao_mensal',
                'local_trabalho', 'horario_trabalho', 'dias_divulgacao', 'tipo_selecao'), $this->input->post());
            $dados_solic_celetista['tipo'] = 'Bolsista';
            $dados_solic_celetista['tipo_selecao'] = $this->Tipo_selecao($dados_solic_celetista['tipo_selecao']);
            $dados_solic = array('project_id' => $this->input->post('project_id'),
                'created_by' => $this->session->userdata['id'], 'tipo' => 'Contratacao',
                'status' => 'Aberto');
            $this->Model_solicitacao->New_solic_contratacao($dados_solic, $dados_solic_celetista);
        } else {
            redirect('Ctrl_project/Project_info/' . $this->input->post('project_id'));
        }
    }

    function New_solic_estagiario() {

        $this->form_validation->set_rules('titulo', 'TITULO', 'required');
        $this->form_validation->set_rules('quantidade', 'QUANTIDADE DE VAGAS', 'required');
        $this->form_validation->set_rules('descricao', 'DESCRIÇÃO', 'required');
        $this->form_validation->set_rules('req_obrig', 'REQUISITOS OBRIGATÓRIOS', 'required');
        $this->form_validation->set_rules('local_trabalho', 'LOCAL DE TRABALHO', 'required');
        $this->form_validation->set_rules('horario_trabalho', 'HORÁRIO DE TRABALHO', 'required');
        $this->form_validation->set_rules('dias_divulgacao', 'DIAS PARA DIVULGAÇÃO', 'required');

        if ($this->form_validation->run() == TRUE) {
            $dados_solic_celetista = elements(array('titulo', 'quantidade',
                'descricao', 'req_obrig', 'req_desej', 'remuneracao_mensal',
                'local_trabalho', 'horario_trabalho', 'dias_divulgacao', 'tipo_selecao'), $this->input->post());
            $dados_solic_celetista['tipo'] = 'Estagiario';
            $dados_solic_celetista['tipo_selecao'] = $this->Tipo_selecao($dados_solic_celetista['tipo_selecao']);
            $dados_solic = array('project_id' => $this->input->post('project_id'),
                'created_by' => $this->session->userdata['id'], 'tipo' => 'Contratacao',
                'status' => 'Aberto');
            $this->Model_solicitacao->New_solic_contratacao($dados_solic, $dados_solic_celetista);
        } else {
            redirect('Ctrl_project/Project_info/' . $this->input->post('project_id'));
        }
    }

    /**
     * Verifica os checkboxes clicados e insere seus valores numa string,
     * retornando a string formada ou todos valores caso nenhum checkbox seja marcado.
     * @param array $checkboxes Array dos checkboxes marcados.
     * @return string String com os valores marcados ou todos se nenhum marcado.
     */
    function Tipo_selecao($checkboxes) {
        $tipo_selecao = null;
        if (count($checkboxes) > 0) {
            foreach ($checkboxes as $ts) {
                $tipo_selecao = $tipo_selecao . $ts . ",";
            }
            $tipo_selecao = substr($tipo_selecao, 0, -1);
            return $tipo_selecao;
        } else {
            return 'Curriculo,Provas,Entrevistas';
        }
    }

}
