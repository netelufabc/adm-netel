<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_solicitacao extends CI_Model {

    public function Get_all_solicitacoes() {
        
    }

    public function Get_solicitacoes_project($project_id) {
        $this->db->select('solicitacao.*, solicitacao_encontro.id as encontro_id, solicitacao_compra.id as  compra_id,
                solicitacao_servico.id as evento_id, user.name as criado_por');
        $this->db->from('solicitacao');
        $this->db->join('solicitacao_encontro', 'solicitacao.id = solicitacao_encontro.solicitacao_id', 'left');
        $this->db->join('solicitacao_servico', 'solicitacao.id = solicitacao_servico.solicitacao_id', 'left');
        $this->db->join('solicitacao_compra', 'solicitacao.id = solicitacao_compra.solicitacao_id', 'left');
        $this->db->join('user', 'user.id = solicitacao.created_by');
        $this->db->where('solicitacao.project_id', $project_id);
        return $this->db->get()->result();
    }

    public function New_solic_encontro($dados_solic, $dados_solic_encontro) {
        $this->db->insert('solicitacao', $dados_solic);
        $dados_solic_encontro['solicitacao_id'] = $this->db->insert_id();
        $this->db->insert('solicitacao_encontro', $dados_solic_encontro);
        $this->session->set_flashdata('solic_criada_ok', 'Solicitação criada com sucesso!');
        redirect("Ctrl_project/Project_info/" . $dados_solic['project_id']);
    }

}
