<?php
echo "<h2>Projeto número: " . $project_info->project_number . "</h2>";
echo "<h3>" . $project_info->title . "</h3>";
if (isset($coord) && ($coord != null)) {
    echo "<h4>Coordenador: " . $coord->coord_name . " (" . $coord->coordenador . ") " . "</h4>";
} else {
    echo "<h4>COORDENADOR NÃO DEFINIDO</h4>";
}
echo "Descrição: " . $project_info->description . br(2);

if ($relatorios_pendentes != null) {//mostra este bloco se houver relatorios de tutores pendentes para bolsa
    echo "<div class=\"validation_errors_list\">";
    echo "Existem relatórios de tutores pendentes para solicitação de bolsas:" . br(2);
    foreach ($relatorios_pendentes as $report) {
        echo "Tutor: <strong>" . $report->name . "</strong>";
        echo " - Mês: <strong>" . vdate($report->month_year, 'myext') . "</strong>" . br();
    }
    echo "</div><br>";
}

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
            $this->table->set_heading('LOGIN', 'NOME', 'EMAIL', 'CRIADO EM', 'INSERIDO POR', 'PAGAMENTO INÍCIO EM');
            foreach ($listaTutores as $row) {

                $this->table->add_row($row->login, $row->name, $row->email, $row->create_time, $row->created_by, vdate($row->tutor_pay_start, 'my'));
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
    ?>
    <table id="projSolic" class="tabela">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'projSolic')">NÚMERO</th>
                <th onclick="sortTable(1, 'projSolic')">TIPO</th>
                <th onclick="sortTable(2, 'projSolic')">STATUS</th>
                <th onclick="sortTable(3, 'projSolic')">CRIADO POR</th>
                <th onclick="sortTable(4, 'projSolic')">CRIADO EM</th>
            </tr>
        </thead>

        <?php
        foreach ($listaSolicitacoes as $row) {

            echo "<tr>";
            echo "<td>";
            echo anchor('Ctrl_solicitacao/Solicitacao_info/' . $row->id, $row->id);
            echo "</td>";
            echo "<td>";
            echo $row->tipo;
            echo "</td>";
            if ($row->status == "Aberto") {
                echo "<td style=\"color: red;\">";
            } else {
                echo "<td style=\"color: blue;\">";
            }
            echo $row->status;
            echo "</td>";
            echo "<td>";
            echo $row->criado_por;
            echo "</td>";
            echo "<td>";
            echo mdate('%d/%m/%Y - %H:%i', mysql_to_unix($row->created_at));
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo '<h5>NENHUMA SOLICITAÇÃO REGISTRADA</h5>';
    }

    echo "</table>";

    echo br(2) . anchor("Ctrl_project/New_solicitacao/$project_info->id", 'Nova Solicitação', array('class' => 'myButton'));

    