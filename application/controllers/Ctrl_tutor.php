<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_tutor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        IsLogged();
        AllowRoles(5, 6);
        $this->load->model('Model_tutor');
        $this->load->model('Model_project');
    }

    public function Index() {
        
    }

    function List_projects() {

        $tutor_id = $this->session->userdata('id');
        $listaProjetos = $this->Model_tutor->Get_all_projects_from_tutor($tutor_id);

        $dados = array(
            'listaProjetos' => $listaProjetos,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_list_proj_tutor',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    function Project_reports() {
        $project_id = $this->uri->segment(3);
        $tutor_id = $this->session->userdata('id');
        $project_info = $this->Model_project->Get_project_info($project_id);
        //$project_create_time = date('Y-m-01', strtotime($project_info->create_time));
        $today = date('d', strtotime('today'));
        $last_month = date('Y-m', strtotime('last month'));
        //$this_month = date('Y-m-01', strtotime('this month'));
        //$tutor_reports = $this->Model_tutor->Get_reports($tutor_id, $project_id);
        $tutor_added_at_raw = $this->Model_tutor->Get_tutor_role_date($tutor_id, $project_id)->role_create_time;
        $tutor_added_at = date('Y-m', strtotime($tutor_added_at_raw));

        $meses_nao_enviados = null;
        $meses_rejeitados_permanente = null;
        $meses_reenvio = null;
        $meses_aprovados = null;
        $meses_pendentes = null;

        while ($tutor_added_at <= $last_month) {//data de cadastro do tutor ou docente no projeto
            $existe_report = $this->Model_tutor->Get_report_month($tutor_id, $project_id, $last_month);
            if ($existe_report == null) {
                $meses_nao_enviados[$last_month] = null;
            } else {
                if ($existe_report->status == 'negado' && $existe_report->deny_reason == 'errado') {
                    $meses_reenvio[$last_month] = $existe_report;
                }
                if ($existe_report->status == 'negado' && $existe_report->deny_reason == 'permanente') {
                    $meses_rejeitados_permanente[$last_month] = $existe_report;
                }
                if ($existe_report->status == 'aprovado') {
                    $meses_aprovados[$last_month] = $existe_report;
                }
                if ($existe_report->status == 'pendente') {
                    $meses_pendentes[$last_month] = $existe_report;
                }
            }
            $last_month = date('Y-m', strtotime('last month', strtotime($last_month)));
        }

        $dados = array(
            'meses_pendentes' => $meses_pendentes,
            'meses_aprovados' => $meses_aprovados,
            'meses_nao_enviados' => $meses_nao_enviados,
            'meses_rejeitados_permanente' => $meses_rejeitados_permanente,
            'meses_reenvio' => $meses_reenvio,
            'project_info' => $project_info,
            'today' => $today,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_list_tutor_reports',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

}
