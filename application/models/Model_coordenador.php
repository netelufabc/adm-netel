<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_coordenador extends CI_Model {

    function Get_all_projects_from_coordenador($coordenador_id) {
        $this->db->select('user_role.user_id, user_role.project_id, uab_project.title, uab_project.project_number, uab_project.description, uab_project.create_time');
        $this->db->from('user_role');
        $this->db->join('uab_project', 'uab_project.id = user_role.project_id');
        $this->db->where(array('user_role.user_id' => $coordenador_id, 'user_role.role_id' => 3));
        return $this->db->get()->result();
    }

}
