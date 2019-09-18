<?php
echo "<h2>Projeto número: " . $project_info->project_number . "</h2>";
echo "<h3>" . $project_info->title . "</h3>";
echo "<h4>Coordenador: " . $coord->coord_name . " (" . $coord->coordenador . ") " . "</h4>";
echo "Descrição: " . $project_info->description . br(1);

echo "<hr>";
?>
<div>
    <button type="button" class="myButton" data-toggle="collapse" data-target="#assist">Assitentes</button>
    <div id="assist" class="collapse">
        <?php
        if (isset($listaAssistentes) && ($listaAssistentes != null)) {

            $template = array(
                "table_open" => "<table class='tabela'>",
            );
            $this->table->set_template($template);
            $this->table->set_heading('LOGIN', 'NOME', 'EMAIL', 'CRIADO EM', 'INSERIDO POR');
            foreach ($listaAssistentes as $row) {

                $this->table->add_row($row->login, $row->name, $row->email, $row->create_time, $row->created_by);
            }
            echo $this->table->generate();
        } else {
            echo '<h5>NENHUM ASSISTENTE REGISTRADO</h5>';
        }
        ?>
    </div>
</div>
<br>
<div>
    <button type="button" class="myButton" data-toggle="collapse" data-target="#docente">Docentes</button>
    <div id="docente" class="collapse">
        <?php
        if (isset($listaDocentes) && ($listaDocentes != null)) {

            $template = array(
                "table_open" => "<table class='tabela'>",
            );
            $this->table->set_template($template);
            $this->table->set_heading('LOGIN', 'NOME', 'EMAIL', 'CRIADO EM', 'INSERIDO POR');
            foreach ($listaDocentes as $row) {

                $this->table->add_row($row->login, $row->name, $row->email, $row->create_time, $row->created_by);
            }
            echo $this->table->generate();
        } else {
            echo '<h5>NENHUM DOCENTE REGISTRADO</h5>';
        }
        ?>
    </div>
</div>
<br>
<div>
    <button type="button" class="myButton" data-toggle="collapse" data-target="#tutor">Tutores</button>
    <div id="tutor" class="collapse">
        <?php
        if (isset($listaTutores) && ($listaTutores != null)) {

            $template = array(
                "table_open" => "<table class='tabela'>",
            );
            $this->table->set_template($template);
            $this->table->set_heading('LOGIN', 'NOME', 'EMAIL', 'CRIADO EM', 'INSERIDO POR');
            foreach ($listaTutores as $row) {

                $this->table->add_row($row->login, $row->name, $row->email, $row->create_time, $row->created_by);
            }
            echo $this->table->generate();
        } else {
            echo '<h5>NENHUM TUTOR REGISTRADO</h5>';
        }
        ?>
    </div>
</div>

<?php
echo "<hr>";
echo "<h4>Solicitações:</h4>";

if ($this->session->flashdata('solic_criada_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('solic_criada_ok');
    echo "</div><br>";
}

if (isset($listaSolicitacoes) && ($listaSolicitacoes != null)) {

    $template = array(
        "table_open" => "<table class='tabela'>",
    );
    $this->table->set_template($template);
    $this->table->set_heading('NÚMERO', 'TIPO', 'STATUS', 'CRIADO POR', 'CRIADO EM');

    foreach ($listaSolicitacoes as $row) {

        $this->table->add_row(anchor('Ctrl_solicitacao/Solicitacao_info/' . $row->id, $row->id), $row->tipo, $row->status, $row->criado_por, mdate('%d/%m/%Y - %H:%i', mysql_to_unix($row->created_at)));
    }
    echo $this->table->generate();
} else {
    echo '<h5>NENHUMA SOLICITAÇÃO REGISTRADA</h5>';
}

echo br(2) . anchor("Ctrl_project/New_solicitacao/$project_info->id", 'Nova Solicitação', array('class' => 'myButton'));

