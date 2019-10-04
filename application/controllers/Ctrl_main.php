<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_main extends CI_Controller {

    public function Index() {

        if ($this->session->userdata('id') == NULL) {

            $dados = array(
                'view_content' => 'View_content_not_logged.php',
            );
            $this->load->view('View_main', $dados);
        }

        if ($this->session->userdata('id') != NULL) {

            switch ($this->session->userdata('role')) {
                case 1:
                    redirect('Ctrl_sysadmin/Info');
                    break;
                case 2:
                    redirect('Ctrl_administrativo');
                    break;
                case 3:
                    redirect('Ctrl_coordenador/List_projects');
                    break;
                case 4:
                    redirect('Ctrl_assistente/List_projects');
                    break;
                case 5:
                    redirect('Ctrl_tutor/List_projects');
                    break;
                case 6:
                    redirect('Ctrl_tutor/List_projects');
                    break;
                default:
                    break;
            }
        }
    }

}
