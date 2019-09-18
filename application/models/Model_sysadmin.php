<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_sysadmin extends CI_Model {

    function Get_roles() {
        return $this->db->get('roles')->result();
    }

    function User_info($user_id) {
        return $this->db->get_where('user', array('id' => $user_id))->row();
    }

}
