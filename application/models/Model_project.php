<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_project extends CI_Model {

    public function Get_project_info($project_id) {
        return($this->db->get_where('uab_project', array('id' => $project_id))->row());
    }

    public function Get_project_coordenador($project_id) {       
        $this->db->select('uab_project.project_number, uab_project.id as project_id, uab_project.title,
                user.login as coordenador , user.name as coord_name, user.id as user_id ');
        $this->db->from('uab_project');
        $this->db->join('user_role', "user_role.project_id = $project_id and user_role.role_id = 3");
        $this->db->join('user', 'user.id = user_role.user_id');
        $this->db->where('uab_project.id', $project_id);
        $result = $this->db->get();
        return $result->row();
    }

    public function Get_project_assitentes($project_id) {
        $this->db->select('user.id, user.login, user.name, user.email, user_role.create_time, user_role.created_by');
        $this->db->from('user_role');
        $this->db->join('user', "user_role.user_id = user.id and user_role.role_id = 4");
        $this->db->where('user_role.project_id', $project_id);
        return($this->db->get()->result());
    }

    public function Get_project_tutores($project_id) {
        $this->db->select('user.id, user.login, user.name, user.email, user_role.create_time, user_role.created_by');
        $this->db->from('user_role');
        $this->db->join('user', "user_role.user_id = user.id and user_role.role_id = 5");
        $this->db->where('user_role.project_id', $project_id);
        return($this->db->get()->result());
    }
    
    public function Get_project_docentes($project_id) {
        $this->db->select('user.id, user.login, user.name, user.email, user_role.create_time, user_role.created_by');
        $this->db->from('user_role');
        $this->db->join('user', "user_role.user_id = user.id and user_role.role_id = 6");
        $this->db->where('user_role.project_id', $project_id);
        return($this->db->get()->result());
    }

    public function Insert_assistente($project_id, $user_id) {
        $query = $this->db->get_where('user_role', array('role_id' => '4', 'project_id' => $project_id, 'user_id' => $user_id));
        if ($query->num_rows() == 0 && $user_id != 0) {
            $this->db->insert('user_role', array('user_id' => $user_id, 'project_id' => $project_id, 'role_id' => '4'));
            $this->session->set_flashdata('add_assist_ok', 'Inserido Assistente!');
            redirect("Ctrl_administrativo/Edit_project/$project_id");
        } else {
            redirect("Ctrl_administrativo/Edit_project/$project_id");
        }
    }

    public function Insert_tutor($project_id, $user_id) {
        $query = $this->db->get_where('user_role', array('role_id' => '5', 'project_id' => $project_id, 'user_id' => $user_id));
        if ($query->num_rows() == 0 && $user_id != 0) {
            $this->db->insert('user_role', array('user_id' => $user_id, 'project_id' => $project_id, 'role_id' => '5'));
            $this->session->set_flashdata('add_tutor_ok', 'Inserido Tutor!');
            redirect("Ctrl_administrativo/Edit_project/$project_id");
        } else {
            redirect("Ctrl_administrativo/Edit_project/$project_id");
        }
    }
    
    public function Insert_docente($project_id, $user_id) {
        $query = $this->db->get_where('user_role', array('role_id' => '6', 'project_id' => $project_id, 'user_id' => $user_id));
        if ($query->num_rows() == 0 && $user_id != 0) {
            $this->db->insert('user_role', array('user_id' => $user_id, 'project_id' => $project_id, 'role_id' => '6'));
            $this->session->set_flashdata('add_docente_ok', 'Inserido Docente!');
            redirect("Ctrl_administrativo/Edit_project/$project_id");
        } else {
            redirect("Ctrl_administrativo/Edit_project/$project_id");
        }
    }
    
    /**
     * Pega todos relatórioa do tutor para o projeto
     * QUERY: select tutor_report.*, user.login, user.name as accepted_or_denied_by, 
     * uab_project.title, files.file_name, files.file_hash, files.file_name
     * from tutor_report
     * left join user on tutor_report.accept_or_deny_by = user.id
     * left join files on tutor_report.file_id = files.id
     * join uab_project on uab_project.id = tutor_report.project_id
     * where tutor_report.tutor_id = $tutor_id
     * and tutor_report.project_id = $project_id;
     * @param int $tutor_id
     * @param int $project_id
     * @param string $month_year yyyy-mm
     * @return DB_OBJECT Linha da tabela tutor_report relativa ao relatório do mês
     */
    function Get_tutor_project_reports($tutor_id, $project_id) {
        $this->db->select('tutor_report.*, user.login, user.name as accepted_or_denied_by, uab_project.title, files.file_name, files.file_hash, files.file_name')
                ->from('tutor_report')
                ->join('user', 'tutor_report.accept_or_deny_by = user.id', 'left')
                ->join('files', 'tutor_report.file_id = files.id', 'left')
                ->join('uab_project', 'uab_project.id = tutor_report.project_id')
                ->where("tutor_report.tutor_id = $tutor_id")
                ->where("tutor_report.project_id = $project_id");                
        return $this->db->get()->result();
    }

}
