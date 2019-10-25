<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ctrl_coordenador extends CI_Controller {

    public function __construct() {
        parent::__construct();
        IsLogged();
        AllowRoles(3);
        $this->load->model('Model_coordenador');
        $this->load->model('Model_solicitacao');
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
        $listaProjetos = $this->Model_coordenador->Get_all_projects_from_coordenador($coordenador_id);

        foreach ($listaProjetos as $projeto) {
            //echo "Projeto: " . $projeto->title . br();
            $solics = $this->Model_coordenador->Get_autonomo_solic($projeto->id);
            $projeto->solics = $solics;
            foreach ($solics as $solic) {
                //echo "--Solicitação: $solic->solicitacao_id" . br();
                $candidatos = $this->Model_coordenador->Get_contratados($solic->solicitacao_id);
                $solic->candidatos = $candidatos;
                foreach ($candidatos as $candidato) {
                    //echo "----Candidato Contratado: $candidato->id $candidato->nome" . br();
                    $parcelas = $this->Model_solicitacao->Get_parcelas($candidato->id);
                    $candidato->parcelas = $parcelas;
                    foreach ($parcelas as $parcela) {
                        //echo "--------Parcela Info: $parcela->id_classificado $parcela->parcela_num $parcela->valor_pag $parcela->data_pag $parcela->status_pag" . br();
                    }
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
    
    function Autoriza_autonomo(){
        $parcela_id = $this->uri->segment(3);             
        $this->Model_coordenador->Set_parcela_status($parcela_id, 'Autorizado');
        $this->session->set_flashdata('pag_alterado', "Pagamento autorizado!");
        redirect('Ctrl_coordenador/Pagamento_autonomo/');
    }

}
