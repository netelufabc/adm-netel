<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_assistente extends CI_Controller {

    public function __construct() {
        parent::__construct();
        IsLogged();
        AllowRoles(4);
        $this->load->model('Model_assistente');
    }

    public function Index() {
        
    }

    function List_projects() {

        $assistente_id = $this->session->userdata('id');
        $listaProjetos = $this->Model_assistente->Get_all_projects_from_assistente($assistente_id);

        $dados = array(
            'listaProjetos' => $listaProjetos,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_list_proj_assist',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

}
