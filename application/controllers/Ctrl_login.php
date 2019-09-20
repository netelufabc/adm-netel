<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Model_login');
    }

    function Get_user() {
        $dados_login = elements(array('login', 'password'), $this->input->post());
        $user_data = $this->Model_login->Get_user_and_role($dados_login['login']);
        $user_id = $user_data{0}->id;

        if ($this->Ldap_login($dados_login, $user_id)) {
            if (count($user_data) == 1) {
                $role = $user_data{0}->role_id;
                $sessioninfo = array('id' => $user_data{0}->id, 'login' => $user_data{0}->login,
                    'nome' => $user_data{0}->name, 'email' => $user_data{0}->email, 'role' => $role);
                $this->session->set_userdata($sessioninfo);
                redirect('Ctrl_main');
            } else {
                $dados = array(
                    'user_data' => $user_data,
                    'view_content' => 'View_content_select_role.php',
                );
                $this->load->view('View_main', $dados);
            }
        }
    }

    function Set_role() {
        $post_data = elements(array('user_id', 'name', 'email', 'login', 'role'), $this->input->post());
        $sessioninfo = array('id' => $post_data['user_id'], 'login' => $post_data['login'],
            'nome' => $post_data['name'], 'email' => $post_data['email'], 'role' => $post_data['role']);
        $this->session->set_userdata($sessioninfo);
        redirect('Ctrl_main');
    }

    function Ldap_login($dados_login, $user_id) {
        $ldap_server = "openldap.ufabc.int.br";
        $ldap_porta = "389";
        $dominio = ",ou=users,dc=ufabc,dc=edu,dc=br"; //$dominio = "dc=ufabc,dc=edu,dc=br"; //Dominio local ou global
        $user = "uid=" . $dados_login['login'] . $dominio; //formato: uid=fabio.akira,ou=users,dc=ufabc,dc=edu,dc=br

        $ldapcon = ldap_connect($ldap_server, $ldap_porta) or die("Could not connect.");

        if ($ldapcon) {

            if (@ldap_bind($ldapcon, $user, $dados_login['password'])) {

                $attributes = array('mail', 'displayname', 'uid');
                $filter = "(uid=$dados_login[login])";

                $result = ldap_search($ldapcon, $user, $filter, $attributes);
                $info = ldap_get_entries($ldapcon, $result);

                $mail = $info[0]["mail"][0];
                $displayname = $info[0]["displayname"][0];

                $dados_user = array('id' => $user_id, 'login' => $dados_login['login'], 'email' => $mail, 'name' => $displayname);
                $this->Model_login->Update_user_info_from_ldap($dados_user);

                ldap_close($ldapcon);
                return true;
            } else {
                $err_message = ldap_error($ldapcon);
                $this->session->set_flashdata('invalid_credentials', "Não foi possível efetuar o Login: $err_message");
                ldap_close($ldapcon);
                redirect('Ctrl_main');
            }
        } else {
            echo "Conexão com LDAP falhou.";
        }
    }

    public function logout() {

        $this->session->sess_destroy();
        redirect('Ctrl_main');
    }

}
