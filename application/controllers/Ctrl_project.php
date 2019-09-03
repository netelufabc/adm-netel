<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_project extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Model_project');
    }

    public function Index() {
        
    }

    public function Project_info() {

        $project_id = $this->uri->segment(3);
        $project_info = $this->Model_project->Get_project_info($project_id);

        $dados = array(
            'project_info' => $project_info,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_project',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

}
