<?php

echo "<h3>Projetos UAB</h3>" . br();

if (isset($listaProjetos)) {

    $template = array(
        "table_open" => "<table class='tabela'>",
    );
    $this->table->set_template($template);
    $this->table->set_heading('NÚMERO UAB', 'TÍTULO', 'DESCRIÇÃO', 'CRIADO EM'); //, 'EDITAR'); //, 'EXCLUIR CURSO');
    foreach ($listaProjetos as $row) {
        $this->table->add_row(anchor("Ctrl_project/Project_info/$row->project_id", $row->project_number), $row->title, $row->description, $row->create_time); //, anchor("Ctrl_administrativo/Edit_project/$row->id"));//, img(array('src' => "images/edit_icon.jpg", 'height' => '25', 'width' => '25'))));
    }
    echo $this->table->generate();
} else {
    echo '<h5>NENHUM PROJETO REGISTRADO</h5>';
}