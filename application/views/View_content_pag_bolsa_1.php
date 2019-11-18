<?php
echo "<h3>Pagamento de Bolsas</h3>Selecione os bolsistas do mês selecionado (" . mdate('%F de %Y', mysql_to_unix($month_year) + 86400) . ")" . br(2);

if (count($lista_tutores) > 0) {//verifica se existem tutores cadastrados no projeto
    echo form_open('Ctrl_project/Confirma_tutores/' . $project_id);

    echo form_hidden('project_id', $project_id);
    echo form_hidden('month_year', $month_year);
    echo form_hidden('tutor_ou_docente', $tutor_ou_docente);

    foreach ($lista_tutores as $tutor) {
        echo "<h4>Tutor: $tutor->name</h4>";

        if (count($tutor->reports) > 0) {//verifica se o tutor tem algum relatório cadastrado
            ?>

            <table id="allSolic" class="tabela">
                <thead>
                    <tr>                                        
                        <th>MÊS/ANO</th>
                        <th>SITUAÇÃO</th>
                        <th>DATA DE ENVIO</th> 
                        <th>RELATÓRIO</th>
                        <th>AÇÕES</th>   
                    </tr>
                </thead>  

                <?php
                foreach ($tutor->reports as $report) {

                    echo form_hidden('report_id[]', $report->id);
                    echo "<tr>";
                    echo "<td>";
                    echo mdate('%F de %Y', mysql_to_unix($report->month_year) + 86400);
                    echo "</td>";
                    echo "<td>";
                    echo $report->status;
                    echo "</td>";
                    echo "<td>";
                    echo vdate($report->upload_date);
                    echo "</td>";
                    echo "<td>";
                    echo anchor('uploads/' . $this->session->userdata['login'] . "/" .
                            $report->file_hash, $report->file_name, "download=$report->file_name");
                    echo "</td>";
                    echo "<td>";
                    echo "Aprovar " . form_radio(array('name' => "aprovar$report->id", 'value' => 'aprovado', 'onclick' => "show1($report->id)")) .
                    "Reprovar " . form_radio(array('name' => "aprovar$report->id", 'value' => 'reprovado', 'onclick' => "show2($report->id)")) .
                    "<div id=\"div$report->id\" class=\"none\">" . form_dropdown("motivo$report->id", array('' => 'Escolha o motivo...', 'errado' => 'Relatório incorreto', 'permanente' => 'Negado Permanente')) . "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>" . br();
            } else {
                echo "<h5>Nada pendente</h5>" . br();
            }
        }
        echo form_submit('continuar', 'Prosseguir...', array('class' => 'myButton'));
        echo form_close();
    } else {
        echo "<h3>Nenhum tutor cadastrado</h3>";
    }
    ?>

    <script>
        function show1(x) {
            document.getElementById('div' + x).style.display = 'none';
        }
        function show2(x) {
            document.getElementById('div' + x).style.display = 'block';
        }
    </script>