<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_administrativo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        IsLogged();
        AllowRoles(2);
        $this->load->model('Model_administrativo');
        $this->load->model('Model_project');
        $this->load->model('Model_solicitacao');
        $this->load->model('Model_coordenador');
    }

    public function Index() {

        $listaProjetos = $this->Model_administrativo->Get_all_uab_projects();

        $dados = array(
            'listaProjetos' => $listaProjetos,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_list_projects',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    function List_all_solic(){
        $all_solic = $this->Model_solicitacao->Get_all_solicitacoes();
        
        $dados = array(
            'all_solic' => $all_solic,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_list_all_solicitacao',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }
    
    public function List_users() {

        $listaUsers = $this->Model_administrativo->Get_all_users();

        $dados = array(
            'listaUsers' => $listaUsers,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_list_users',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    public function New_project() {

        $this->form_validation->set_rules('project_number', 'NÚMERO DO PROJETO', 'required');

        if ($this->form_validation->run() == TRUE) {
            $dados_projeto = elements(array('project_number', 'title', 'description'), $this->input->post());
            $this->Model_administrativo->New_uab_project($dados_projeto);
        }

        $dados = array(
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_new_project',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    public function Edit_project() {

        $project_id = $this->uri->segment(3);
        $this->form_validation->set_rules('project_number', 'NÚMERO DO PROJETO', 'required');

        $dados_project = $this->Model_project->Get_project_info($project_id);
        $coordenador = $this->Model_project->Get_project_coordenador($project_id);

        if ($coordenador != null) {
            $coord = $coordenador->user_id;
        } else {
            $coord = 1;
        }

        if ($this->form_validation->run() == TRUE) {
            $dados_project_basic = elements(array('project_number', 'title', 'description'), $this->input->post());
            $coordenador_id = elements(['coordenador'], $this->input->post());
            $this->Model_administrativo->Update_project_basic($dados_project_basic, $project_id);
            if ($coordenador_id != null) {
                $this->Model_administrativo->Update_project_coordenador($coordenador_id['coordenador'], $project_id);
            }
        }

        $lista_users = $this->Model_administrativo->Get_all_users();
        $lista_assistentes = $this->Model_project->Get_project_assitentes($project_id);        
        $lista_tutores = $this->Model_project->Get_project_tutores($project_id);
        $lista_docentes = $this->Model_project->Get_project_docentes($project_id);

        $lista_users_array['0'] = " --Selecione-- ";
        foreach ($lista_users as $value) {
            $lista_users_array[$value->id] = $value->name;
        }
        asort($lista_users_array);

        $dados = array(
            'listaDocentes' => $lista_docentes,
            'listaAssistentes' => $lista_assistentes,
            'listaTutores' => $lista_tutores,
            'coord' => $coord,
            'dados_project' => $dados_project,
            'lista_users' => $lista_users_array,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_edit_project.php',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    public function Edit_project_assistente() {
        $project_id = $this->uri->segment(3);
        $user_id = elements(array('assistente'), $this->input->post());
        $this->Model_project->Insert_assistente($project_id, $user_id['assistente']);
    }

    public function Edit_project_tutor() {
        $project_id = $this->uri->segment(3);
        $user_id = elements(array('tutor'), $this->input->post());
        $this->Model_project->Insert_tutor($project_id, $user_id['tutor']);
    }
    
    public function Edit_project_docente() {
        $project_id = $this->uri->segment(3);
        $user_id = elements(array('docente'), $this->input->post());
        $this->Model_project->Insert_docente($project_id, $user_id['docente']);
    }

    public function New_user() {

        $this->form_validation->set_rules('login', 'LOGIN', 'required');

        if ($this->form_validation->run() == TRUE) {
            $dados_user = elements(array('login', 'name', 'email'), $this->input->post());
            $this->Model_administrativo->New_user($dados_user);
        }

        $dados = array(
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_new_user',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }
    
    /**
     * Botão "marcar como pago" da view "view_content_autonomo".
     */
    function Set_parcela_pago(){
        $parcela_id = $this->uri->segment(3);
        $this->Model_coordenador->Set_parcela_status($parcela_id, 'Pago');
        $this->session->set_flashdata('pag_alterado', "Pagamento efetuado!");
        redirect('Ctrl_coordenador/Pagamento_autonomo/');
    }

    function Set_parcela_aguardando_autoriza(){
        $parcela_id = $this->uri->segment(3);
        $this->Model_coordenador->Set_parcela_status($parcela_id, 'Aguardando autorização');
        $this->session->set_flashdata('pag_alterado', "Status alterado!");
        redirect('Ctrl_coordenador/Pagamento_autonomo/');
    }
    
}
