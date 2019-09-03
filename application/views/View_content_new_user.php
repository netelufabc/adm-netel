<?php

echo form_open("Ctrl_administrativo/New_user");

if (validation_errors() != NULL) {
    echo "<div class=\"validation_errors\">";
    echo validation_errors('<p>', '</p>');
    echo "</div><br>";
}

if ($this->session->flashdata('new_user_failed')) {
    echo "<div class=\"message_error\">";
    echo $this->session->flashdata('new_user_failed');
    echo "</div><br>";
}

echo form_label('Login do usuário (sem @ufabc): ');
echo form_input(array('name' => 'login', 'required'=> 'required'), set_value('login'), 'autofocus');
echo br(1);

echo form_label('Nome: ');
echo form_input(array('name' => 'name', 'required'=> 'required'), set_value('name'));
echo br(1);

echo form_label('E-mail: ');
echo form_input(array('name' => 'email', 'required'=> 'required'), set_value('email'));
echo br(1);

echo "<br><br>";

echo form_submit(array('name' => 'inserir'), 'Inserir Usuário');
echo "&nbsp&nbsp&nbsp&nbsp";
echo "<input value=\"Cancelar\" onclick=\"JavaScript:window.history.back();\" type=\"button\">";

echo form_close();
