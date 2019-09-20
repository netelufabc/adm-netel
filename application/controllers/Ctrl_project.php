<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_project extends CI_Controller {

    public function __construct() {
        parent::__construct();
        IsLogged();
        $this->load->model('Model_project');
        $this->load->model('Model_solicitacao');
    }

    public function Index() {
        
    }

    public function Project_info() {

        $project_id = $this->uri->segment(3);
        $project_info = $this->Model_project->Get_project_info($project_id);
        $coordenador = $this->Model_project->Get_project_coordenador($project_id);
        $lista_assistentes = $this->Model_project->Get_project_assitentes($project_id);
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

    public function New_solicitacao() {
        $project_id = $this->uri->segment(3);

        $lista_tutores0 = $this->Model_project->Get_project_tutores($project_id);
        $lista_docentes0 = $this->Model_project->Get_project_docentes($project_id);        
        $lista_tutores = null;
        $lista_docentes = null;        
        
        foreach ($lista_tutores0 as $value) {
            $lista_tutores[$value->id] = $value->name;
        }

        foreach ($lista_docentes0 as $value) {
            $lista_docentes[$value->id] = $value->name;
        }

        $dados = array(
            'lista_tutores' => $lista_tutores,
            'lista_docentes' => $lista_docentes,
            'project_id' => $project_id,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_new_solicitacao',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    public function New_solic_encontro() {

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
        }

        $dados = array(
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_project.php',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    public function New_solic_bolsa() {

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
        }

        $dados = array(
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_project.php',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    public function New_solic_servico() {

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
        }

        $dados = array(
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_project.php',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    public function New_solic_compra() {

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
        }

        $dados = array(
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_project.php',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    public function New_solic_pessoal() {

        $this->form_validation->set_rules('polo', 'POLO', 'required');

        if ($this->form_validation->run() == TRUE) {
            $dados_solic = elements(array('project_id', 'polo', 'data', 'hora_inicio', 'hora_termino',
                'professores', 'tutores', 'quantidade_sala', 'capacidade_sala', 'quantidade_lab',
                'capacidade_lab', 'auditorio', 'equip', 'obs'), $this->input->post());
            $this->Model_project->New_solic_encontro($dados_solic);
        }

        $dados = array(
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_project.php',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

}
