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
            switch ($role) {
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
                default://caso o usuário esteja cadastrado mas não tem papel definido
                    echo "ROLE note set or out of bounds (session not set?).";
                    $this->session->set_flashdata('role_not_set', 'Papél de usuário não definido, entre em contato com o NETEL!');
                    $this->session->unset_userdata(array('id', 'nome', 'login', 'email'));
                    break;
            }

            if ($this->session->userdata('id') == null) {//verificação para quando o usuário nao tem papel definido
                $dados = array(
                    'view_content' => 'View_content_not_logged.php',
                );
                $this->load->view('View_main', $dados);
            } else {//se o papel estiver definido e ID ok, entra aqui

                $dados = array(
                    'view_menu' => 'View_menu.php',
                    'view_content' => 'View_content.php',
                    'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
                );
                $this->load->view('View_main', $dados);
            }
        }
    }

}
