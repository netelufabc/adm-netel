<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model_sysadmin extends CI_Model {

    function Get_roles() {
        return $this->db->get('roles')->result();
    }

    /**
     * Retorna tabela com as configurações da aplicação
     * @return objectDB array com Objetos do BD
     */
    function Get_config() {
        return $this->db->get('config')->result();
    }

    function Get_var_info($var_name) {
        return $this->db->get_where('config', array('name' => $var_name))->row();
    }

    /**
     * Atualiza tabela de configurações
     * @param String $var_name
     * @param String $var_value
     */
    function Set_config($var_name, $var_value) {
        $this->db->set('value', $var_value);
        $this->db->where('name', $var_name);
        if ($this->db->update('config')) {
            return true;
        } else {
            return false;
        }
    }

}
