<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Model_login');
    }

    public function Index() {

        $dados_login = elements(array('login', 'password'), $this->input->post());

        $user_data = $this->Model_login->Get_user_and_role($dados_login['login']);
        
        $lowest_role = 100;
        foreach ($user_data as $user) {
            if($user->role < $lowest_role){
                $lowest_role = $user->role;
                $user_id = $user->id;
            }
        }
        $user_role = $lowest_role;

        if ($dados_login) {
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

                    //$uid = $info[0]["uid"][0];
                    //if ($uid == "gustavo.castilho" || $uid == "fabio.akira" || $uid == "thais.braga") {//fazer dinamico
                    $mail = $info[0]["mail"][0];
                    $displayname = $info[0]["displayname"][0];

                    $sessioninfo = array('id' => $user_id, 'login' => $dados_login['login'], 'nome' => $displayname, 'role' => $user_role, 'email' => $mail);
                    $this->session->set_userdata($sessioninfo);

                    ldap_close($ldapcon);
                    redirect('Ctrl_main');
//                    } else {
//                        $this->session->set_flashdata('invalid_credentials', "Não foi possível efetuar o Login: usuário não autorizado");
//                        ldap_close($ldapcon);
//                        redirect('Ctrl_main');
//                    }
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
    }

    public function logout() {

        $this->session->sess_destroy();
        redirect('Ctrl_main');
    }

}
