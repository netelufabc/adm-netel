<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_coordenador extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Model_coordenador');
    }

    public function Index() {
        
    }

    function List_projects() {

        $coordenador_id = $this->session->userdata('id');
        $listaProjetos = $this->Model_coordenador->Get_all_projects_from_coordenador($coordenador_id);

        $dados = array(
            'listaProjetos' => $listaProjetos,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_list_proj_coord',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

}
