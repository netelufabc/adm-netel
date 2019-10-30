<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_tutor extends CI_Model {

    function Get_all_projects_from_tutor($tutor_id) {
        $this->db->select('user_role.user_id, user_role.project_id, uab_project.title, uab_project.project_number, uab_project.description, uab_project.create_time');
        $this->db->from('user_role');
        $this->db->join('uab_project', 'uab_project.id = user_role.project_id');
        $this->db->where(array('user_role.user_id' => $tutor_id, 'user_role.role_id' => 5));
        return $this->db->get()->result();
    }

//    function Get_reports($tutor_id, $project_id) {
//        $this->db->select('tutor_report.*, user.login, user.name, uab_project.title')
//                ->from('tutor_report')
//                ->join('user', 'tutor_report.tutor_id = user.id')
//                ->join('uab_project', 'uab_project.title = tutor_report.project_id')
//                ->where("tutor_report.tutor_id = $tutor_id")
//                ->where("tutor_report.project_id = $project_id");
//        return $this->db->get()->result();
//    }

    function Get_report_month($tutor_id, $project_id, $month_year) {
        $this->db->select('tutor_report.*, user.login, user.name as accepted_or_denied_by, uab_project.title')
                ->from('tutor_report')
                ->join('user', 'tutor_report.accept_or_deny_by = user.id')
                ->join('uab_project', 'uab_project.id = tutor_report.project_id')
                ->where("tutor_report.tutor_id = $tutor_id")
                ->where("tutor_report.project_id = $project_id")
                ->where("tutor_report.month_year =" . $this->db->escape($month_year . "-00"));
        return $this->db->get()->row();
    }

    function Get_tutor_role_date($tutor_id, $project_id) {
        $this->db->select('user.id as user_id, user_role.create_time as role_create_time');
        $this->db->from('user_role');
        $this->db->join('user', "user_role.user_id = user.id and user_role.role_id = 5");
        $this->db->where('user_role.project_id', $project_id);
        $this->db->where('user_role.user_id', $tutor_id);
        return($this->db->get()->row());
    }

}
