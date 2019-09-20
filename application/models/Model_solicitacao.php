<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_solicitacao extends CI_Model {

    function Get_all_solicitacoes() {
        $this->db->select('solicitacao.*, user.name, uab_project.project_number, uab_project.title');
        $this->db->from('solicitacao');
        $this->db->join('user','user.id = solicitacao.created_by');
        $this->db->join('uab_project','uab_project.id = solicitacao.project_id');
        return $this->db->get()->result();
    }

    function User_info($user_id) {
        return $this->db->get_where('user', array('id' => $user_id))->row();
    }
    
    /**
     * Retorna todas solicitações de um projeto.
     * @param int $project_id
     * @return ARRAY OBJETOS DB
     */
    function Get_solicitacoes_project($project_id) {
        $this->db->select('solicitacao.*, user.name as criado_por, uab_project.title as project_title, 
		 uab_project.project_number, uab_project.id as project_id');
        $this->db->from('solicitacao');
        $this->db->join('user', 'user.id = solicitacao.created_by');
        $this->db->join('uab_project', 'uab_project.id = solicitacao.project_id');
        $this->db->where('solicitacao.project_id', $project_id);
        return $this->db->get()->result();
    }

    function Get_solicitacao_basic_info($solicitacao_id) {
        $this->db->select('solicitacao.*, user.name as criado_por, uab_project.title as project_title, 
		 user.email, uab_project.project_number, uab_project.id as project_id');
        $this->db->from('solicitacao');
        $this->db->join('user', 'user.id = solicitacao.created_by');
        $this->db->join('uab_project', 'uab_project.id = solicitacao.project_id');
        $this->db->where("solicitacao.id = $solicitacao_id");
        return $this->db->get()->row();
    }

    function Get_solic_msgs($solicitacao_id) {
        $this->db->select('mensagens.*, user.name');
        $this->db->from('mensagens');
        $this->db->join('user', 'user.id = mensagens.created_by');
        $this->db->where("mensagens.solicitacao_id = $solicitacao_id");
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result();
    }

    function Get_solicitacao_encontro($solicitacao_id) {
        $this->db->select('solicitacao.id as main_solicitacao_id, solicitacao_encontro.*');
        $this->db->from('solicitacao');
        $this->db->join('solicitacao_encontro', 'solicitacao.id = solicitacao_encontro.solicitacao_id');
        $this->db->where("solicitacao.id = $solicitacao_id");
        return $this->db->get()->row();
    }

    function Get_solicitacao_compra($solicitacao_id) {
        $this->db->select('solicitacao.id as main_solicitacao_id, solicitacao_compra.*');
        $this->db->from('solicitacao');
        $this->db->join('solicitacao_compra', 'solicitacao.id = solicitacao_compra.solicitacao_id');
        $this->db->where("solicitacao.id = $solicitacao_id");
        return $this->db->get()->row();
    }

    function Get_solicitacao_servico($solicitacao_id) {
        $this->db->select('solicitacao.id as main_solicitacao_id, solicitacao_servico.*');
        $this->db->from('solicitacao');
        $this->db->join('solicitacao_servico', 'solicitacao.id = solicitacao_servico.solicitacao_id');
        $this->db->where("solicitacao.id = $solicitacao_id");
        return $this->db->get()->row();
    }

    function Get_solicitacao_bolsa($solicitacao_id) {
        $this->db->select('solicitacao.id as main_solicitacao_id, solicitacao_bolsa.*');
        $this->db->from('solicitacao');
        $this->db->join('solicitacao_bolsa', 'solicitacao.id = solicitacao_bolsa.solicitacao_id');
        $this->db->where("solicitacao.id = $solicitacao_id");
        return $this->db->get()->row();
    }

    function Get_bolsistas_from_solicitacao($solicitacao_id) {
        $this->db->select('user.id, user.login, user.name');
        $this->db->from('solicitacao_bolsista');
        $this->db->join('user', 'user.id = solicitacao_bolsista.bolsista_id');
        $this->db->where('solicitacao_bolsista.solicitacao_bolsa_id', $solicitacao_id);
        return $this->db->get()->result();
    }

    public function New_solic_encontro($dados_solic, $dados_solic_encontro) {
        $this->db->insert('solicitacao', $dados_solic);
        $dados_solic_encontro['solicitacao_id'] = $this->db->insert_id();
        $this->db->insert('solicitacao_encontro', $dados_solic_encontro);
        $this->session->set_flashdata('solic_criada_ok', 'Solicitação criada com sucesso!');
        redirect("Ctrl_project/Project_info/" . $dados_solic['project_id']);
    }

    public function New_solic_servico($dados_solic, $dados_solic_servico) {
        $this->db->insert('solicitacao', $dados_solic);
        $dados_solic_servico['solicitacao_id'] = $this->db->insert_id();
        $this->db->insert('solicitacao_servico', $dados_solic_servico);
        $this->session->set_flashdata('solic_criada_ok', 'Solicitação criada com sucesso!');
        redirect("Ctrl_project/Project_info/" . $dados_solic['project_id']);
    }

    public function New_solic_compra($dados_solic, $dados_solic_compra) {
        $this->db->insert('solicitacao', $dados_solic);
        $dados_solic_compra['solicitacao_id'] = $this->db->insert_id();
        $this->db->insert('solicitacao_compra', $dados_solic_compra);
        $this->session->set_flashdata('solic_criada_ok', 'Solicitação criada com sucesso!');
        redirect("Ctrl_project/Project_info/" . $dados_solic['project_id']);
    }

    public function New_solic_bolsa($dados_solic, $dados_solic_bolsa, $lista_bolsistas) {
        $this->db->insert('solicitacao', $dados_solic);
        $dados_solic_bolsa['solicitacao_id'] = $this->db->insert_id();
        $this->db->insert('solicitacao_bolsa', $dados_solic_bolsa);
        $id_solicitacao_bolsa = $this->db->insert_id();
        foreach ($lista_bolsistas as $bolsista) {
            $this->db->insert('solicitacao_bolsista', array('solicitacao_bolsa_id' => $id_solicitacao_bolsa, 'bolsista_id' => $bolsista));
        }
        $this->session->set_flashdata('solic_criada_ok', 'Solicitação criada com sucesso!');
        redirect("Ctrl_project/Project_info/" . $dados_solic['project_id']);
    }

    function New_message($dados_msg) {
        $this->db->insert('mensagens', $dados_msg);
        $this->session->set_flashdata('msg_ok', 'Mensagem registrada com sucesso!');
        redirect("Ctrl_solicitacao/Solicitacao_info/" . $dados_msg['solicitacao_id']);
    }
    
    public function Update_solic_status($solicitacao_id, $status) {
        $this->db->set('status', $status);
        $this->db->set('closed_by', $this->session->userdata('id'));
        $this->db->set('closed_at', date('Y-m-d H:i:s'));
        $this->db->where('id', $solicitacao_id);
        $this->db->update('solicitacao');
    }

}
