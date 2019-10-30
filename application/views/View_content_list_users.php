<?php

echo br(1) . "Usuários" . br(2);

if ($this->session->flashdata('new_user_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('new_user_ok');
    echo "</div><br>";
}

if (isset($listaUsers)) {

    $template = array(
        "table_open" => "<table class='tabela'>",
    );
    $this->table->set_template($template);
    $this->table->set_heading('LOGIN', 'NOME', 'EMAIL', 'CRIADO EM', 'CRIADO POR', 'PAPÉIS');
    foreach ($listaUsers as $row) {

        $this->table->add_row($row->login, $row->name, $row->email, $row->create_time, $row->created_by);
    }
    echo $this->table->generate();
} else {
    echo '<h5>NENHUM USUÁRIO REGISTRADO</h5>';
}