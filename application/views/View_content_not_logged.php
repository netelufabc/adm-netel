<?php

echo br(2);
echo "<h2>NADA AQUI. NÃO LOGADO.</h2>";

if ($this->session->flashdata('invalid_credentials')) {
    echo "<div class=\"message_error\">";
    echo $this->session->flashdata('invalid_credentials');
    echo "</div><br>";
}

if ($this->session->flashdata('role_not_set')) {
    echo "<div class=\"validation_errors\">";
    echo $this->session->flashdata('role_not_set');
    echo "</div><br>";
}

echo form_open('Ctrl_login/Get_user');

echo form_label('Login: ');
echo form_input(array('name' => 'login', 'required'=> 'required'), set_value('login'), 'autofocus');
echo br(1);

echo form_label('Senha: ');
echo form_password(array('name' => 'password', 'required'=> 'required'));
echo br(1);

echo form_submit(array('name' => 'Ctrl_login'), 'Entrar');
echo form_close();

echo br(2);