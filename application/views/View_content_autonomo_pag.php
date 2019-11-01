<?php
echo "<h3>Autorização de pagamento de autônomos</h3>" . br();

if ($this->session->flashdata('pag_alterado')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('pag_alterado');
    echo "</div><br>";
}

if ($this->session->flashdata('data_menor_agendada')) {
    echo "<div class=\"message_error\">";
    echo $this->session->flashdata('data_menor_agendada');
    echo "</div><br>";
}

if (isset($listaProjetos) && count($listaProjetos) > 0) {
    foreach ($listaProjetos as $projeto) {
        ?>
        <a class="myButton" data-toggle="collapse" href="#collapseExample<?php echo $projeto->id ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $projeto->id ?>">
            <?php echo "Projeto Número: " . $projeto->project_number; ?>
        </a>
        <div class="collapse" id="collapseExample<?php echo $projeto->id ?>">
            <div class="card card-body">
                <?php
                echo "<div class=\"roundedDivBorder\">";
                if (count($projeto->solics) > 0) {
                    foreach ($projeto->solics as $solic) {
                        echo "<h4>Solicitação de contratação número: " . anchor('Ctrl_solicitacao/Solicitacao_info/' . $solic->solicitacao_id, $solic->solicitacao_id) . "</h4>";

                        if (count($solic->candidatos) > 0) {
                            foreach ($solic->candidatos as $candidato) {
                                echo "<h5>Candidato contratado:  <strong>$candidato->nome</strong></h5>";

                                if (count($candidato->parcelas) > 0) {
                                    ?>
                                    <table id="allSolic" class="tabela">
                                        <thead>
                                            <tr>                                        
                                                <th>PARCELA Nº</th>
                                                <th>DATA PAGAMENTO</th>
                                                <th>VALOR</th>
                                                <th>SITUAÇÃO</th>    
                                                <th>AÇÕES</th>   
                                            </tr>
                                        </thead>                           
                                        <?php
                                        foreach ($candidato->parcelas as $parcela) {

                                            echo "<tr>";
                                            echo "<td>";
                                            echo $parcela->parcela_num;
                                            echo "</td>";
                                            echo "<td>";
                                            echo mdate('%d/%m/%Y', mysql_to_unix($parcela->data_pag));
                                            echo "</td>";
                                            echo "<td>";
                                            echo "R$ " . number_format($parcela->valor_pag, 2);
                                            echo "</td>";
                                            echo "<td>";
                                            if ($parcela->status_pag == 'Pago') {
                                                $color = 'blue';
                                            } elseif ($parcela->status_pag == 'Aguardando autorização') {
                                                $color = 'orange';
                                            } elseif ($parcela->status_pag == 'Autorizado') {
                                                $color = 'green';
                                            } else {
                                                $color = 'black';
                                            }
                                            echo "<span style=\"color:$color\">" . $parcela->status_pag . "</span>";
                                            echo "</td>";
                                            echo "<td>";
                                            $jsConfirm_autoriza = 'return confirm(\'Tem certeza '
                                                    . 'que deseja autorizar o pagamento de '
                                                    . $candidato->nome . ', com data prevista de '
                                                    . mdate('%d/%m/%Y', mysql_to_unix($parcela->data_pag))
                                                    . ' no valor de R$ ' . number_format($parcela->valor_pag, 2) . '\')';
                                            $jsConfirm_revert = 'return confirm(\'Tem certeza que deseja alterar o status para Aguardando autorização?\')';
                                            if ($parcela->status_pag != 'Pago' && $parcela->status_pag != 'Autorizado') {
                                                echo anchor("Ctrl_coordenador/Autoriza_autonomo/" . $parcela->id, img(array('src' => "images/hired.png", 'title' => 'Autorizar pagamento', 'height' => '25', 'width' => '25')), array('onclick' => $jsConfirm_autoriza)) . " ";
                                                echo anchor("Ctrl_solicitacao/Classificacao_info/" . $solic->solicitacao_id, img(array('src' => "images/classified.png", 'title' => 'Editar', 'height' => '25', 'width' => '25'))) . " ";
                                            }
                                            if ($parcela->status_pag == 'Autorizado' && HasRole(1, 2)) {
                                                echo anchor("Ctrl_administrativo/Set_parcela_pago/" . $parcela->id, img(array('src' => "images/pago.png", 'title' => 'Marcar como Pago', 'height' => '25', 'width' => '25'))) . " ";
                                                echo anchor("Ctrl_administrativo/Set_parcela_aguardando_autoriza/" . $parcela->id, img(array('src' => "images/revert.png", 'title' => 'Reverter para aguardando autorização', 'height' => '25', 'width' => '25')), array('onclick' => $jsConfirm_revert)) . " ";
                                            }
                                            echo "</td>";
                                        }
                                        echo "</table>" . br();
                                    } else {
                                        echo "Nenhuma parcela de pagamento disponível para liberação." . br(2);
                                    }
                                }
                                echo "<hr class=\"hr1\">";
                            } else {
                                echo "Nenhum candidato contratado para esta solicitação." . br();
                                echo "<hr class=\"hr1\">";
                            }
                        }
                    } else {
                        echo"<h4>NENHUMA SOLICITAÇÃO DE CONTRATAÇÃO DE AUTÔNOMO REGISTRADA PARA ESTE PROJETO.</h4>";
                    }
                    ?>
            </div>
        </div>

        <?php
        echo "</div>" . br(2); //class=\"roundedDivBorder\"
    }
} else {
    echo"<h4>NENHUM PROJETO ASSOCIADO AO COORDENADOR.</h4>";
}