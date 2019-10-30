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

        IsProjectOwnerOrAssist($this->session->userdata['id'], $coordenador, $lista_assistentes);//verificar se usuario é assistente ou coordenador do projeto

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
        IsProjectOwnerOrAssist($this->session->userdata['id'], $coordenador, $lista_assistentes);//verificar se usuario é assistente ou coordenador do projeto

        $lista_tutores0 = $this->Model_project->Get_project_tutores($project_id);
        $lista_docentes0 = $this->Model_project->Get_project_docentes($project_id);
        $lista_tutores = null; //usado para solicitacao de bolsa
        $lista_docentes = null; //usado para solicitacao de bolsa
        $role = $this->session->userdata['role'];

        if ($role == 3 || $role == 1 || $role == 2) { //mostra opções de nova solicitação para roles 1,2 e 3
            $new_solic_list = array('' => 'Selecione...',
                'red' => 'Contratação de Pessoal',
                'green' => 'Pagamento de Bolsa',
                'blue' => 'Encontro Presencial',
                'maroon' => 'Contratação de Serviços',
                'magenta' => 'Compras');
        } else {//mostra somente opção de nova solicitação de encontro para assistentes
            $new_solic_list = array('' => 'Selecione...', 'blue' => 'Encontro Presencial');
        }

        foreach ($lista_tutores0 as $value) {
            $lista_tutores[$value->id] = $value->name;
        }

        foreach ($lista_docentes0 as $value) {
            $lista_docentes[$value->id] = $value->name;
        }

        $dados = array(
            'new_solic_list' => $new_solic_list, //lista para menu dropdown
            'lista_tutores' => $lista_tutores,
            'lista_docentes' => $lista_docentes,
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
            $dados_solic_bolsa = elements(array('mes_ano', 'tutor_ou_docente'), $this->input->post());
            $dados_solic = array('project_id' => $this->input->post('project_id'),
                'created_by' => $this->session->userdata['id'], 'tipo' => 'Bolsa',
                'status' => 'Aberto');
            $lista_tutores = $this->input->post('lista_tutores');
            $lista_docentes = $this->input->post('lista_docentes');
            $dados_solic_bolsa['mes_ano'] = $dados_solic_bolsa['mes_ano'] . "-01";
            if ($dados_solic_bolsa['tutor_ou_docente'] == 'tutor') {
                if ($lista_tutores != null) {
                    $this->Model_solicitacao->New_solic_bolsa($dados_solic, $dados_solic_bolsa, $lista_tutores);
                } else {
                    $this->session->set_flashdata('erro_solic', 'Selecione ao menos um tutor!');
                    redirect('Ctrl_project/New_solicitacao/' . $this->input->post('project_id'));
                }
            }
            if ($dados_solic_bolsa['tutor_ou_docente'] == 'docente') {
                if ($lista_docentes != null) {
                    $this->Model_solicitacao->New_solic_bolsa($dados_solic, $dados_solic_bolsa, $lista_docentes);
                } else {
                    $this->session->set_flashdata('erro_solic', 'Selecione ao menos um docente!');
                    redirect('Ctrl_project/New_solicitacao/' . $this->input->post('project_id'));
                }
            }
            $this->session->set_flashdata('erro_solic', 'Selecione se quem vai receber a bolsa é tutor ou docente!');
            redirect('Ctrl_project/New_solicitacao/' . $this->input->post('project_id'));
        } else {
            redirect('Ctrl_project/Project_info/' . $this->input->post('project_id'));
        }
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
