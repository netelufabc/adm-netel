<?php

//echo br(2);
echo "<h2>SISTEMA ADMINISTRATIVO NETEL</h2>" . br();

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
echo form_input(array('name' => 'login', 'required' => 'required'), set_value('login'), 'autofocus');
QuestionTip("Digite seu login da UFABC (e-mail, SEM o @ufabc.edu.br ou @aluno.ufabc.edu.br)");
echo br(1);

echo form_label('Senha: ');
echo form_password(array('name' => 'password', 'required' => 'required'));
QuestionTip("Digite sua senha, a mesma do e-mail da UFABC");
echo br(1);

echo form_submit(array('name' => 'Ctrl_login'), 'Entrar');
echo form_close();

echo br();
echo anchor('https://acesso.ufabc.edu.br/passwordRecovery/index', 'Não sei / esqueci minha senha');
