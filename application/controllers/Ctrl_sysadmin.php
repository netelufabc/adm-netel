<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_sysadmin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Model_sysadmin');
        $this->load->model('Model_administrativo');
        $this->load->model('Model_solicitacao');
    }

    public function Index() {
        
    }

    function Info() {

        $dados = array(
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_admin_info.php',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    function Login_as() {

        $this->form_validation->set_rules('user_id', 'USER ID', 'required');
        $this->form_validation->set_rules('role_id', 'ROLE ID', 'required');

        if ($this->form_validation->run() == TRUE) {
            $dados_session = elements(array('user_id', 'role_id'), $this->input->post());
            $user_data = $this->Model_solicitacao->User_info($dados_session['user_id']);
            $sessioninfo = array('id' => $user_data->id, 'login' => $user_data->login,
                'nome' => $user_data->name, 'email' => $user_data->email, 'role' => $dados_session['role_id']);
            $this->session->set_userdata($sessioninfo);
            redirect('Ctrl_main');
        }

        $users = $this->Model_administrativo->Get_all_users();
        foreach ($users as $user) {
            $user_array[$user->id] = $user->login;
        }

        $roles = $this->Model_sysadmin->Get_roles();
        foreach ($roles as $role) {
            $role_array[$role->id] = $role->title;
        }

        $dados = array(
            'users' => $user_array,
            'roles' => $role_array,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_login_as',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

}
