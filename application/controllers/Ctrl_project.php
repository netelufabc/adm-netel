<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_project extends CI_Controller {

    public function __construct() {
        parent::__construct();
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
        $lista_solicitacoes = $this->Model_solicitacao->Get_solicitacoes_project($project_id);

        $dados = array(
            'listaSolicitacoes' => $lista_solicitacoes,
            'coord' => $coordenador,
            'listaTutores' => $lista_tutores,
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

        $dados = array(
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

    public function New_solic_servico() {

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

    public function New_solic_compra() {

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
