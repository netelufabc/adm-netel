<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_sysadmin extends CI_Model {

    function Get_roles() {
        return $this->db->get('roles')->result();
    }

}
