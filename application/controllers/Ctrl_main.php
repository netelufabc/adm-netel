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

            $role = $this->session->userdata('role');
            switch ($role) {//role deve ser a menor role cadastrada, fazer função
                case 1:
                    redirect('Ctrl_sysadm');
                    break;
                case 2:
                    redirect('Ctrl_administrativo');
                    break;
                case 3:
                    redirect('Ctrl_coordenador');                    
                    break;
                case 4:
                    redirect('Ctrl_assistente');   
                    break;
                case 5:
                    redirect('Ctrl_tutor');   
                    break;
                default:
                    echo "ROLE note set or out of bounds (session not set?).";
                    $this->session->sess_destroy();
                    redirect('Ctrl_main');
                    break;
            }

            $dados = array(
                'view_menu' => 'View_menu.php',
                'view_content' => 'View_content.php',
                'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
            );
            $this->load->view('View_main', $dados);
        }
    }

}
