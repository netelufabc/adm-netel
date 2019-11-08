<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_coordenador extends CI_Controller {

    public function __construct() {
        parent::__construct();
        IsLogged();
        AllowRoles(2, 3);
        $this->load->model('Model_coordenador');
        $this->load->model('Model_solicitacao');
        $this->load->model('Model_administrativo');
        $this->load->model('Model_project');
    }

    public function Index() {
        
    }

    function List_projects() {

        $coordenador_id = $this->session->userdata('id');
        $listaProjetos = $this->Model_coordenador->Get_all_projects_from_coordenador($coordenador_id);

        $dados = array(
            'listaProjetos' => $listaProjetos,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_list_proj_coord',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    /**
     * Autorização de pagamento de autônomo - Menu Pagamento de Autônomos
     * Página da view "View_content_autonomo_pag"
     */
    function Pagamento_autonomo() {
        $coordenador_id = $this->session->userdata('id');

        if (HasRole(3)) {//se for coordenador, mostra só seus projetos
            $listaProjetos = $this->Model_coordenador->Get_all_projects_from_coordenador($coordenador_id);
        } elseif (HasRole(1, 2)) {//se for sysadmin ou administrativo, mostra todos projetos
            $listaProjetos = $this->Model_administrativo->Get_all_uab_projects();
        }

        foreach ($listaProjetos as $projeto) {//monta matriz de projetos->solicitacoes->contratados->parcelas
            $solics = $this->Model_coordenador->Get_autonomo_solic($projeto->id);
            $projeto->solics = $solics;
            foreach ($solics as $solic) {
                $candidatos = $this->Model_coordenador->Get_contratados($solic->solicitacao_id);
                $solic->candidatos = $candidatos;
                foreach ($candidatos as $candidato) {
                    $parcelas = $this->Model_solicitacao->Get_parcelas($candidato->id);
                    $candidato->parcelas = $parcelas;
                }
            }
        }

        $dados = array(
            'listaProjetos' => $listaProjetos,
            'view_menu' => 'View_menu.php',
            'view_content' => 'View_content_autonomo_pag',
            'menu_item' => criamenu($this->session->userdata('id'), $this->session->userdata('role')),
        );
        $this->load->view('View_main', $dados);
    }

    /**
     * Botão "autorizar pagamento" da view "view_content_autonomo".
     */
    function Autoriza_autonomo() {
        $parcela_id = $this->uri->segment(3);
        $today = date("Y-m-d");
        $dados_parcela = $this->Model_solicitacao->Get_parcela_data($parcela_id);
        if ($dados_parcela->data_pag <= $today) {//permitir autorização de pagamento smoente se a data atual é maior ou igual à data agendada   
            $this->Model_coordenador->Set_parcela_status($parcela_id, 'Autorizado');
            $this->session->set_flashdata('pag_alterado', "Pagamento autorizado!");
            redirect('Ctrl_coordenador/Pagamento_autonomo/');
        } else {
            $this->session->set_flashdata('data_menor_agendada', "O pagamento só pode ser autorizados a partir da data agendada (" . mdate('%d/%m/%Y', mysql_to_unix($dados_parcela->data_pag)) . ")");
            redirect('Ctrl_coordenador/Pagamento_autonomo/');
        }
    }

}
