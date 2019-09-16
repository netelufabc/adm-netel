<?php

function criamenu($user_id, $user_role) {

    if ($user_id != NULL) {
        switch ($user_role) {
            case 10:
                $menu = array(
                    'processos/meus_processos' => 'Meus Processos',
                );
                break;
            case 1:
                $menu = array(
                    'Ctrl_administrativo/New_project' => 'Cadastrar Novo Projeto',       
                    'processos/meus_processos' => 'Meus Processos',
                    'projetos' => 'Projetos',
                );
                break;
            case 2:
                $menu = array(
                    'Ctrl_administrativo/List_all_solic' => 'Listar Solicitações',
                    'Ctrl_administrativo' => 'Listar Projetos UAB',
                    'Ctrl_administrativo/List_users' => 'Listar Usuários',
                    'Ctrl_administrativo/New_user' => 'Cadastrar Usuários',
                    'Ctrl_administrativo/New_project' => 'Cadastrar Novo Projeto',                    
                );
                break;
            default:

                break;
        }
        return($menu);
    } else {
        return NULL;
    }
}
