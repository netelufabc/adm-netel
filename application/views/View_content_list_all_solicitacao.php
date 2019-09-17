<?php

echo "<h3>Solicitações</h3>" . br();

if (isset($all_solic) && ($all_solic != null)) {
    ?>   

    <input type="text" id="coluna0" class="searchInput" onkeyup="filtraColuna(0, 'coluna0')" placeholder="Número...">
    <input type="text" id="coluna1" class="searchInput" onkeyup="filtraColuna(1, 'coluna1')" placeholder="Projeto Nº...">
    <input type="text" id="coluna2" class="searchInput" onkeyup="filtraColuna(2, 'coluna2')" placeholder="Nome Projeto...">
    <br>
    <input type="text" id="coluna3" class="searchInput" onkeyup="filtraColuna(3, 'coluna3')" placeholder="Tipo...">
    <input type="text" id="coluna4" class="searchInput" onkeyup="filtraColuna(4, 'coluna4')" placeholder="Status...">
    <input type="text" id="coluna5" class="searchInput" onkeyup="filtraColuna(5, 'coluna5')" placeholder="Criador...">
    <input type="text" id="coluna6" class="searchInput" onkeyup="filtraColuna(6, 'coluna6')" placeholder="Criado em......">

    <table id="allSolic" class="tabela">
        <thead>
            <tr>
                <th onclick="sortTable(0, 'allSolic')">NÚMERO</th>
                <th onclick="sortTable(1, 'allSolic')">PROJETO Nº</th>
                <th onclick="sortTable(2, 'allSolic')">NOME DO PORJETO</th>
                <th onclick="sortTable(3, 'allSolic')">TIPO</th>
                <th onclick="sortTable(4, 'allSolic')">STATUS</th>
                <th onclick="sortTable(5, 'allSolic')">CRIADO POR</th>
                <th onclick="sortTable(6, 'allSolic')">CRIADO EM</th>
            </tr>
        </thead>

        <?php

        foreach ($all_solic as $row) {

            echo "<tr>";
            echo "<td>";
            echo anchor('Ctrl_solicitacao/Solicitacao_info/' . $row->id, $row->id);
            echo "</td>";
            echo "<td>";
            echo $row->project_number;
            echo "</td>";
            echo "<td>";
            echo $row->title;
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
            echo $row->name;
            echo "</td>";
            echo "<td>";
            echo $row->created_at;
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo '<h5>NENHUMA SOLICITAÇÃO REGISTRADA</h5>';
    }

    echo "</table>";
    