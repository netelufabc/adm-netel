<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        IsLogged();
        $this->load->model('Model_sysadmin');
    }

    private $configs; //objeto de configurações da aplicação, tabela config do BD

    function GetConfig() {
        $config = new stdClass();
        $vars = $this->Model_sysadmin->Get_config();
        foreach ($vars as $var) {
            $var_name = $var->name;
            $config->$var_name = $var->value;
        }
        $this->configs = $config;
        return $this->configs;
    }

    function GetConfigInfo($var_name) {

        return $this->Model_sysadmin->Get_var_info($var_name);
    }

    function SetConfig($var_name, $var_value) {
        if ($this->Model_sysadmin->Set_config($var_name, $var_value)) {
            return true;
        } else {
            return false;
        }
    }

}
