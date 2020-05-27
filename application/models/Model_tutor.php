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

    /**
     * Verifica se existe relatório de tutor para o mês indicado
     * QUERY: select tutor_report.*, user.login, user.name as accepted_or_denied_by, 
     * uab_project.title, files.file_name, files.file_hash, files.file_name
     * from tutor_report
     * left join user on tutor_report.accept_or_deny_by = user.id
     * left join files on tutor_report.file_id = files.id
     * join uab_project on uab_project.id = tutor_report.project_id
     * where tutor_report.tutor_id = $tutor_id
     * and tutor_report.project_id = $project_id
     * and tutor_report.month_year = $month_year
     * @param int $tutor_id
     * @param int $project_id
     * @param string $month_year yyyy-mm
     * @return DB_OBJECT Linha da tabela tutor_report relativa ao relatório do mês
     */
    function Get_report_month($tutor_id, $project_id, $month_year) {
        $this->db->select('tutor_report.*, user.login, user.name as accepted_or_denied_by, uab_project.title, files.file_name, files.file_hash')
                ->from('tutor_report')
                ->join('user', 'tutor_report.accept_or_deny_by = user.id', 'left')
                ->join('files', 'tutor_report.file_id = files.id', 'left')
                ->join('uab_project', 'uab_project.id = tutor_report.project_id')
                ->where("tutor_report.tutor_id = $tutor_id")
                ->where("tutor_report.project_id = $project_id")
                ->where("tutor_report.month_year =" . $this->db->escape($month_year . "-00"));
        return $this->db->get()->row();
    }

    function Get_tutor_role_date($tutor_id, $project_id) {
        $this->db->select('user.id as user_id, user_role.tutor_pay_start as role_create_time');
        $this->db->from('user_role');
        $this->db->join('user', "user_role.user_id = user.id and user_role.role_id = 5");
        $this->db->where('user_role.project_id', $project_id);
        $this->db->where('user_role.user_id', $tutor_id);
        return($this->db->get()->row());
    }

    /**
     * Insere os dados do arquivo na tabela files e retorna o ID inserido
     * @param ASSOCIATIVE_ARRAY $file_info Array associativo com os dados da tabela files
     * @return int ID da linha inserida ou null se falhar
     */
    function Set_report_file_info($file_info) {
        if ($this->db->insert('files', $file_info)) {
            return $this->db->insert_id();
        } else {
            return null;
        }
    }

    /**
     * Insere os dados do report na tabela tutor_report e retorna o ID inserido
     * @param ASSOCIATIVE_ARRAY $dados_report Array associativo com os dados da tabela tutor_report
     */
    function Set_report($dados_report) {
        $this->db->insert('tutor_report', $dados_report);
    }

}
