<?php

echo br(1) . $user_name . br(2);

if ($this->session->flashdata('new_user_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('new_user_ok');
    echo "</div><br>";
}

if (isset($user_info)) {

    $template = array(
        "table_open" => "<table class='tabela'>",
    );
    $this->table->set_template($template);
    $this->table->set_heading('PAPEL', 'PROJETO');
    foreach ($user_info as $row) {

        $this->table->add_row($row->title, anchor("Ctrl_administrativo/Edit_project/$row->id", $row->project_number));
    }
    echo $this->table->generate();
} else {
    echo '<h5>NENHUMA INFORMAÇÃO ENCONTRADA</h5>';
}

