<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_administrativo extends CI_Model {

    public function Get_all_uab_projects() {
        return($this->db->get('uab_project')->result());
    }

    public function Get_all_users() {
        return($this->db->get('user')->result());
    }

    public function New_uab_project($new_uab_project_info) {

        $this->db->db_debug = FALSE;
        $result = $this->db->insert('netel_adm.uab_project', $new_uab_project_info);
        $this->db->db_debug = TRUE;
        if ($result) {
            $this->session->set_flashdata('new_project_ok', 'Projeto UAB inserido!');
            redirect('Ctrl_administrativo');
        } else {
            $this->session->set_flashdata('new_project_failed', 'Falha ao inserir, erro de banco de dados (número duplicado?).');
            redirect('Ctrl_administrativo/New_project');
        }
    }

    public function New_user($user_data) {

        $user_data['created_by'] = $this->session->userdata('login');

        $this->db->db_debug = FALSE;
        $result = $this->db->insert('netel_adm.user', $user_data);
        $this->db->db_debug = TRUE;
        if ($result) {
            $this->session->set_flashdata('new_user_ok', 'Usuário inserido!');
            redirect('Ctrl_administrativo');
        } else {
            $this->session->set_flashdata('new_user_failed', 'Falha ao inserir, erro de banco de dados (login, email duplicado?).');
            redirect('Ctrl_administrativo/New_user');
        }
    }

    public function Update_project_coordenador($user_id, $project_id) {
        $query = $this->db->get_where('user_role', array('role_id' => '3', 'project_id' => $project_id));
        if ($query->num_rows() > 0) {
            $this->db->where('role_id', '3');
            $this->db->where('project_id', $project_id);
            $this->db->update('user_role', array('user_id' => $user_id, 'project_id' => $project_id, 'role_id' => '3'));
            $this->session->set_flashdata('project_update_ok', 'Projeto atualizado!');
            redirect('Ctrl_administrativo');
        } else {
            $this->db->insert('user_role', array('user_id' => $user_id, 'project_id' => $project_id, 'role_id' => '3'));
            $this->session->set_flashdata('project_update_ok', 'Projeto atualizado!');
            redirect('Ctrl_administrativo');
        }
    }

    public function Update_project_basic($dados, $project_id) {
        $this->db->where('id', $project_id);
        $this->db->update('uab_project', $dados);
    }



}
