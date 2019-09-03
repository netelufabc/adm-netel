<?php

echo br(1) . "Projetos UAB" . br(2);

if ($this->session->flashdata('new_project_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('new_project_ok');
    echo "</div><br>";
}

if ($this->session->flashdata('new_user_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('new_user_ok');
    echo "</div><br>";
}

if ($this->session->flashdata('project_update_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('project_update_ok');
    echo "</div><br>";
}

if (isset($listaProjetos)) {

    $template = array(
        "table_open" => "<table class='tabela'>",
    );
    $this->table->set_template($template);
    $this->table->set_heading('NÚMERO UAB', 'TÍTULO', 'DESCRIÇÃO', 'EDITAR'); //, 'EXCLUIR CURSO');
    foreach ($listaProjetos as $row) {
        $this->table->add_row(anchor("Ctrl_project/Project_info/$row->id", $row->project_number), $row->title, $row->description, anchor("Ctrl_administrativo/Edit_project/$row->id", img(array('src' => "images/edit_icon.jpg", 'height' => '25', 'width' => '25'))));
    }
    echo $this->table->generate();
} else {
    echo '<h5>NENHUM PROJETO REGISTRADO</h5>';
}