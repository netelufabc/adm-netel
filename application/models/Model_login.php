<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_login extends CI_Model {

    public function Get_user_and_role($login) {

        $query = $this->db->query("select user.id,user.login,
            user_role.role_id as role, user_role.project_id from user
            left join user_role on user.id = user_role.user_id
            where user.login = ?", array($login));
        if ($query->num_rows() == 0) {
            $this->session->set_flashdata('invalid_credentials', 'Login não cadastrado no sistema!');
            redirect('Ctrl_main');
        } else {
            return $query->result();
        }
    }
    
}
