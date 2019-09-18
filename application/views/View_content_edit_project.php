<?php

echo form_open("Ctrl_administrativo/Edit_project/$dados_project->id");

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
echo form_input(array('name' => 'project_number', 'required' => 'required'), set_value('project_number', $dados_project->project_number), 'autofocus');
echo br(1);

echo form_label('Título do Projeto: ');
echo form_input(array('name' => 'title', 'required' => 'required'), set_value('title', $dados_project->title));
echo br(1);

echo form_label('Descrição: ');
echo form_textarea(array('name' => 'description'), set_value('description', $dados_project->description));
echo br(1);

echo form_label('Coordenador: ');
echo form_dropdown('coordenador', $lista_users, $coord);

echo "<br><br>";

echo form_submit(array('name' => 'inserir'), 'Alterar Projeto');
echo "&nbsp&nbsp&nbsp&nbsp";
echo "<input value=\"Cancelar\" onclick=\"JavaScript:window.history.back();\" type=\"button\">";
echo form_close();

echo "<hr>";

echo "Assistentes:" .br(1);

if ($this->session->flashdata('add_assist_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('add_assist_ok');
    echo "</div><br>";
}

if (isset($listaAssistentes) && ($listaAssistentes != null)) {

    $template = array(
        "table_open" => "<table class='tabela'>",
    );
    $this->table->set_template($template);
    $this->table->set_heading('LOGIN', 'NOME', 'EMAIL', 'CRIADO EM', 'CRIADO POR');
    foreach ($listaAssistentes as $row) {

        $this->table->add_row($row->login, $row->name, $row->email, $row->create_time, $row->created_by);
    }
    echo $this->table->generate();
} else {
    echo '<h5>NENHUM ASSISTENTE REGISTRADO</h5>';
}

echo br(1). "Adicionar Assistente:" .br(1);
echo form_open("Ctrl_administrativo/Edit_project_assistente/$dados_project->id");
echo form_dropdown('assistente', $lista_users);
echo form_submit(array('name' => 'inserir_assistente'), 'Adicionar Assistente');
echo form_close();

echo "<hr>";

echo "Docentes:" .br(1);

if ($this->session->flashdata('add_docente_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('add_docente_ok');
    echo "</div><br>";
}

if (isset($listaDocentes) && ($listaDocentes != null)) {

    $template = array(
        "table_open" => "<table class='tabela'>",
    );
    $this->table->set_template($template);
    $this->table->set_heading('LOGIN', 'NOME', 'EMAIL', 'CRIADO EM', 'CRIADO POR');
    foreach ($listaDocentes as $row) {

        $this->table->add_row($row->login, $row->name, $row->email, $row->create_time, $row->created_by);
    }
    echo $this->table->generate();
} else {
    echo '<h5>NENHUM DOCENTE REGISTRADO</h5>';
}

echo br(1). "Adicionar Docente:" .br(1);
echo form_open("Ctrl_administrativo/Edit_project_docente/$dados_project->id");
echo form_dropdown('docente', $lista_users);
echo form_submit(array('name' => 'inserir_assistente'), 'Adicionar Docente');
echo form_close();

echo "<hr>";

echo "Tutores:" .br(1);

if ($this->session->flashdata('add_tutor_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('add_tutor_ok');
    echo "</div><br>";
}

if (isset($listaTutores) && ($listaTutores != null)) {

    $template = array(
        "table_open" => "<table class='tabela'>",
    );
    $this->table->set_template($template);
    $this->table->set_heading('LOGIN', 'NOME', 'EMAIL', 'CRIADO EM', 'CRIADO POR');
    foreach ($listaTutores as $row) {

        $this->table->add_row($row->login, $row->name, $row->email, $row->create_time, $row->created_by);
    }
    echo $this->table->generate();
} else {
    echo '<h5>NENHUM TUTOR REGISTRADO</h5>';
}

echo br(1). "Adicionar Tutor:" .br(1);
echo form_open("Ctrl_administrativo/Edit_project_tutor/$dados_project->id");
echo form_dropdown('tutor', $lista_users);
echo form_submit(array('name' => 'inserir_tutor'), 'Adicionar Tutor');
echo form_close();
