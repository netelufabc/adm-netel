<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_login extends CI_Model {

    public function Get_user_and_role($login) {

        $this->db->select('user_role.role_id, user.id, user.login, user.email, user.name, roles.title, roles.description');
        $this->db->distinct();
        $this->db->from('user_role');
        $this->db->join('user', 'user.id = user_role.user_id', 'left');
        $this->db->join('roles' , 'user_role.role_id = roles.id');
        $this->db->where('user.login', $login);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            $this->session->set_flashdata('invalid_credentials', 'Login não cadastrado ou sem papel definido!');
            redirect('Ctrl_main');
        } else {
            return $query->result();
        }
    }

    public function Update_user_info_from_ldap($dados_user) {
        $this->db->update('user', $dados_user, "id = " . $dados_user['id']);
    }

}
