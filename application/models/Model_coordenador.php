<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_coordenador extends CI_Model {

    function Get_all_projects_from_coordenador($coordenador_id) {
        $this->db->select('user_role.user_id, user_role.project_id, uab_project.title, uab_project.project_number, uab_project.description, uab_project.create_time, uab_project.id');
        $this->db->from('user_role');
        $this->db->join('uab_project', 'uab_project.id = user_role.project_id');
        $this->db->where(array('user_role.user_id' => $coordenador_id, 'user_role.role_id' => 3));
        return $this->db->get()->result();
    }

    /**
     * Query: select solicitacao_contratacao.solicitacao_id
     * from solicitacao
     * join solicitacao_contratacao on solicitacao_contratacao.solicitacao_id = solicitacao.id
     * where solicitacao_contratacao.tipo = 'Autonomo' and solicitacao.project_id = 26 
     * and solicitacao.tipo = 'Contratacao' and solicitacao.`status` = 'aberto';
     * @param int $project_id
     * @return ARRAY Objetos DB
     */
    function Get_autonomo_solic($project_id) {
        $this->db->select('solicitacao_contratacao.solicitacao_id')
                ->from('solicitacao')
                ->join('solicitacao_contratacao', 'solicitacao_contratacao.solicitacao_id = solicitacao.id')
                ->where('solicitacao_contratacao.tipo = \'Autonomo\'')
                ->where("solicitacao.project_id = $project_id")
                ->where('solicitacao.tipo = \'Contratacao\'')
                ->where('solicitacao.`status` = \'aberto\'');
        return $this->db->get()->result();
    }

    /**
     * Retorna id e nome de todos contratados que estão com  situação = contratado
     * Query: select classificacao_contratacao.id
     * from classificacao_contratacao
     * where classificacao_contratacao.id_solic = $solic_contratacao_id and classificacao_contratacao.situacao = 'Contratado';
     * @param int $solic_contratacao_id ID da solicitação de contratação
     * @return ARRAY Objetos DB
     */
    function Get_contratados($solic_contratacao_id) {
        $this->db->select('classificacao_contratacao.id, classificacao_contratacao.nome')
                ->from('classificacao_contratacao')
                ->where("classificacao_contratacao.id_solic = $solic_contratacao_id")
                ->where('classificacao_contratacao.situacao = \'Contratado\'');
        return $this->db->get()->result();
    }

    function Set_parcela_status($parcela_id, $status_pag) {
        $this->db->update('pagamento_autonomo', array('status_pag' => $status_pag), "id = $parcela_id");
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
//    function Get_tutor_project_reports($tutor_id, $project_id) {
//        $this->db->select('tutor_report.*, user.login, user.name as accepted_or_denied_by, uab_project.title, files.file_name, files.file_hash, files.file_name')
//                ->from('tutor_report')
//                ->join('user', 'tutor_report.accept_or_deny_by = user.id', 'left')
//                ->join('files', 'tutor_report.file_id = files.id', 'left')
//                ->join('uab_project', 'uab_project.id = tutor_report.project_id')
//                ->where("tutor_report.tutor_id = $tutor_id")
//                ->where("tutor_report.project_id = $project_id");                
//        return $this->db->get()->result();
//    }
}
