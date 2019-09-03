<?php

echo form_open("Ctrl_administrativo/New_project");

if (validation_errors() != NULL) {
    echo "<div class=\"validation_errors\">";
    echo validation_errors('<p>', '</p>');
    echo "</div><br>";
}

if ($this->session->flashdata('new_project_failed')) {
    echo "<div class=\"message_error\">";
    echo $this->session->flashdata('new_project_failed');
    echo "</div><br>";
}

echo form_label('Número do Projeto: ');
echo form_input(array('name' => 'project_number', 'required'=> 'required'), set_value('project_number'), 'autofocus');
echo br(1);

echo form_label('Título do Projeto: ');
echo form_input(array('name' => 'title', 'required'=> 'required'), set_value('title'));
echo br(1);

echo form_label('Descrição: ');
echo form_textarea(array('name' => 'description'), set_value('description'));
echo br(1);

//echo form_label('Coordenador: ');
//echo form_dropdown('user_id');

echo "<br><br>";

echo form_submit(array('name' => 'inserir'), 'Inserir Projeto');
echo "&nbsp&nbsp&nbsp&nbsp";
echo "<input value=\"Cancelar\" onclick=\"JavaScript:window.history.back();\" type=\"button\">";

echo form_close();
