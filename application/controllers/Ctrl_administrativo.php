<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_administrativo extends MY_Controller {

    public function __construct() {
        parent::__construct();
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

    function Config() {

        $config_temp = get_object_vars($this->GetConfig());
        $config = array();
        foreach ($config_temp as $name => $value) {
            $var = new stdClass();
            $var->name = $name;
            $var->value = $value;
            $var->info = $this->GetConfigInfo($var->name)->info;
            array_push($config, $var);
        }

        $lista_users = $this->Model_administrativo->Get_all_users();

        $lista_users_array['0'] = " --Selecione-- ";
        foreach ($lista_users as $value) {
            $lista_users_array[$value->id] = $value->name;
        }
        asort($lista_users_array);

        $dados = array(
            'config' => $config,
            'lista_users' => $lista_users_array,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_config',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    function Change_config() {
        $this->form_validation->set_rules('var_value', 'VALOR DA VARIÁVEL', 'required|trim');

        if ($this->form_validation->run() == TRUE) {
            $dados_var = elements(array('var_name', 'var_value'), $this->input->post());
            if ($this->SetConfig($dados_var['var_name'], $dados_var['var_value'])) {
                $this->session->set_flashdata('config_ok', 'Configuração alterada!');
            }
            redirect('Ctrl_administrativo/Config');
        } else {
            redirect('Ctrl_administrativo/Config');
        }
    }

    function List_all_solic() {
        $all_solic = $this->Model_solicitacao->Get_all_solicitacoes();

        $dados = array(
            'all_solic' => $all_solic,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_list_all_solicitacao',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

//    function Configs() {
//
//        $dados = array(
//            'configs' => $configs,
//            'view_menu' => 'View_menu.php',
//            'view_content' => 'View_content_configs',
//            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
//        );
//        $this->load->view('View_main', $dados);
//    }

    public function List_users() {

        //$listaUsers = $this->Model_administrativo->Get_all_users();
        #Rafael
        $listaUsers = $this->Model_administrativo->Get_all_user_roles();
        #END_Rafael

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
    function Set_parcela_pago() {
        $parcela_id = $this->uri->segment(3);
        $this->Model_coordenador->Set_parcela_status($parcela_id, 'Pago');
        $this->session->set_flashdata('pag_alterado', "Pagamento efetuado!");
        redirect('Ctrl_coordenador/Pagamento_autonomo/');
    }

    /**
     * Botão "marcar como aguardando autorizacao" da view "view_content_autonomo".
     */
    function Set_parcela_aguardando_autoriza() {
        $parcela_id = $this->uri->segment(3);
        $this->Model_coordenador->Set_parcela_status($parcela_id, 'Aguardando autorização');
        $this->session->set_flashdata('pag_alterado', "Status alterado!");
        redirect('Ctrl_coordenador/Pagamento_autonomo/');
    }

#Rafael

    public function Add_adm_netel_role() {
        if (!$this->Model_administrativo->User_has_role($this->input->post('netel_Adm'), 2)) {
            $this->Model_administrativo->Add_new_role($this->input->post('netel_Adm'), 2);
        } else {
            $this->session->set_flashdata('new_role_failed', 'Usuário já é Administrador Netel.');
        }

        redirect('Ctrl_administrativo/Config');
    }

    public function User_detail() {
        $user_id = $this->uri->segment(3);
        $user_name = $this->uri->segment(4);
        $user_name = str_replace("%20", " ", $user_name);

        $user_info = $this->Model_administrativo->Get_user_project_roles($user_id);

        foreach ($user_info as $info) {
            if ($info->role_id <= 2) {
                $info->project_number = ' ';
                $info->id = ' ';
            }
        }

        $dados = array(
            'user_name' => $user_name,
            'user_info' => $user_info,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_user_details',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    #END_Rafael
}
