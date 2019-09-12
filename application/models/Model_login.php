<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_login extends CI_Model {

    public function Get_user_and_role($login) {

        $this->db->select('user.id,user.login,user_role.role_id as role, user_role.project_id');
        $this->db->from('user');
        $this->db->join('user_role', 'user.id = user_role.user_id', 'left');
        $this->db->where('user.login', $login);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            $this->session->set_flashdata('invalid_credentials', 'Login não cadastrado no sistema!');
            redirect('Ctrl_main');
        } else {
            return $query->result();
        }
    }

    public function Update_user_info_from_ldap($dados_user) {
        $this->db->update('user', $dados_user, "id = " . $dados_user['id']);
    }

}
