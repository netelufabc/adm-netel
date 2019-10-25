<?php
echo "<strong>Projeto número: " . $basic_info->project_number; //basic_info é info da tablea solicitacoes, $solic_info é da tabela específica de cada tipo de solicitação
echo " - " . $basic_info->project_title . "</strong>" . br(2);

echo "Solicitação número: " . $basic_info->id . br();
echo "Criado por: " . $basic_info->criado_por . br();
echo "Aberto em: " . mdate('%d/%m/%Y - %H:%i:%s', mysql_to_unix($basic_info->created_at)) . br();
echo "Tipo: " . $basic_info->tipo . br();
echo "<strong>Status: ";

if ($basic_info->status == "Aberto") {
    echo "<p style=\"color: red;\">";
    echo $basic_info->status . "</strong></p>";
} else {
    echo "<p style=\"color: blue;\">";
    echo $basic_info->status . "</strong></p>";
    echo "em " . mdate('%d/%m/%Y às %H:%i', mysql_to_unix($basic_info->closed_at)) . " por " . $basic_info->name . br(2);
}

echo "<strong>Tipo de vaga: " . $solic->tipo . "</strong>" . br();
echo "Título da vaga: " . $solic->titulo . br();
echo "Quantidade de vagas: " . $solic->quantidade . br();
echo "Tempo estimado para duração do serviço (meses): " . $solic->tempo_estimado . br();
echo "Descrição das atividades: " . $solic->descricao . br();
echo "Requisitos Obrigatórios: " . $solic->req_obrig . br();
echo "Requisitos Desejáveis: " . $solic->req_desej . br();
echo "Dias para divulgação: " . $solic->dias_divulgacao . br();
echo "Tipo de seleção: " . $solic->tipo_selecao . br();
echo "Remuneração Bruta (R$): " . $solic->remuneracao_bruta . br();
echo "Remuneração Mensal (R$): " . $solic->remuneracao_mensal . br();
echo "Local de trabalho: " . $solic->local_trabalho . br();
echo "Horário de trabalho: " . $solic->horario_trabalho . br();
echo "Situação: <strong>" . $solic->status . "</strong>" . br();

echo "<hr>";
echo "<h4>Classificação dos Candidatos</h4>";

if ($this->session->flashdata('classificacao_inserida')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('classificacao_inserida');
    echo "</div><br>";
}

switch ($solic->status) {//parte relativa a classificacao dos candidatos
    case "Aguardando Classificacao"://este é o estágio após o  administrativo do netel enviar os curriculos dos candidatos via mensagem da solicitação e autorizar o coordenador a classificar
        echo "Adicione os candidatos aprovados, em ordem de classificação, de cima para baixo.<br><br>";

        if ($this->session->flashdata('sem_candidatos')) {
            echo "<div class=\"message_error\">";
            echo $this->session->flashdata('sem_candidatos');
            echo "</div><br>";
        }

        echo form_open("Ctrl_solicitacao/Insert_classificacao/" . $basic_info->id);
        ?>
        <div class="classif_fields_wrap">
            <a href="#" class="add_classif_button">Adicionar candidato classificado</a><br><br>
        </div>
        <?php
        echo form_submit(array('name' => 'inserir', 'class' => 'myButton'), 'Inserir Classificação');
        echo form_close();

        echo form_open("Ctrl_solicitacao/Nenhum_candidato_aprovado/$basic_info->id", array('onsubmit' => 'return confirm(\'Tem certeza que deseja informar que nenhum candidato foi aprovado?\')'));
        echo form_submit(array('name' => 'fechar_solic', 'class' => 'myButton'), 'Nenhum Classificado');
        echo form_close(); //este botão retorna o status para Aguardando Curriculos, quando nenhum candidato é aprovado pelo coordenador

        break;

    case "Aguardando Curriculos"://este é o estágio padrão, quando a solicitação é criada
        if (HasRole(1, 2)) {//se for sysadmin ou admin netel, mostra o botão para liberar o coordenador de fazer a classificacao, deve ser clicado pelo admin netel após anexar os curriculos
            echo form_open("Ctrl_solicitacao/Liberar_para_classificacao/$basic_info->id", array('onsubmit' => 'return confirm(\'Tem certeza que deseja liberar o coordenador para indicação dos candidatos aprovados?\')'));
            echo form_submit(array('name' => 'fechar_solic', 'class' => 'myButton'), 'Liberar Classificação');
            echo form_close();
        } else {//se nao form sysadmin ou admin netel apenas mostra a msg para o coordenador e assistente
            echo "<h4>Currículos ainda não disponíveis para classificação ou não liberado pelo NETEL.</h4>";
        }
        break;

    case "Aguardando Netel"://este é o estágio após o coordenador inserir a classificação
        if ($classificacao != null) {

//Bloco Administrativo inicio ########################################################
            if (HasRole(1, 2)) {//campo administrativo para marcar contratados
                echo "<div class=\"roundedDivBorder\">";
                echo "<br><strong>Administrativo</strong>" . br(2);

                if ($this->session->flashdata('parcela_paga')) {
                    echo "<div class=\"message_error\">";
                    echo $this->session->flashdata('parcela_paga');
                    echo "</div><br>";
                }

                if ($this->session->flashdata('classif_alterada')) {
                    echo "<div class=\"message_success\">";
                    echo $this->session->flashdata('classif_alterada');
                    echo "</div><br>";
                }
                ?>
                <table id="allSolic" class="tabela">
                    <thead>
                        <tr>
                            <th>POSIÇÃO</th>
                            <th>NOME</th>
                            <th>EXIGÊNCIAS</th>
                            <th>DESCRIÇÃO</th>
                            <th>SITUAÇÃO</th>    
                            <th>AÇÕES</th>   
                        </tr>
                    </thead>
                    <?php
                    foreach ($classificacao as $candidato) {
                        echo "<tr>";
                        echo "<td>";
                        echo $candidato->posicao;
                        echo "</td>";
                        echo "<td>";
                        echo $candidato->nome;
                        echo "</td>";
                        echo "<td>";
                        echo $candidato->exigencias;
                        echo "</td>";
                        echo "<td>";
                        echo $candidato->descricao;
                        echo "</td>";
                        if ($candidato->situacao == "Classificado") {
                            echo "<td style=\"color: orange;\">";
                        } else if ($candidato->situacao == "Contratado") {
                            echo "<td style=\"color: green;\">";
                        } else if ($candidato->situacao == "Desligado") {
                            echo "<td style=\"color: red;\">";
                        }
                        echo $candidato->situacao;
                        echo "</td>";
                        echo "<td>";
                        $jsConfirm = 'return confirm(\'Esta ação vai apagar as '
                                . 'datas e valores inseridos pelo coordenador '
                                . '(não vai alterar nada caso alguma parcela já'
                                . ' foi paga). Tem certeza?\')';
                        echo anchor("Ctrl_solicitacao/Set_contratado/$candidato->id/$basic_info->id", img(array('src' => "images/hired.png", 'title' => 'Marcar como contratado (possibilita o coordenador a inserir datas e valores de pagamento)', 'height' => '25', 'width' => '25'))) . " ";
                        echo anchor("Ctrl_solicitacao/Set_classificado/$candidato->id/$basic_info->id", img(array('src' => "images/classified.png", 'title' => 'Marcar como classificado (remove a possibilidade do coordenador inserir datas e valores de pagamento)', 'height' => '25', 'width' => '25')), array('onclick' => $jsConfirm)) . " ";
                        echo anchor("Ctrl_solicitacao/Set_fired/$candidato->id", img(array('src' => "images/fired.png", 'title' => 'Marcar como desligado (remove os pagamento pendentes (se houver)e marca como desligado)', 'height' => '25', 'width' => '25')));
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>" . br();
                    echo "</div>";
                }
//Bloco administrativo fim ############################################################

                if ($this->session->flashdata('sem_parcelas')) {
                    echo "<div class=\"message_error\">";
                    echo $this->session->flashdata('sem_parcelas');
                    echo "</div><br>";
                }

                if ($this->session->flashdata('pag_atualizado')) {
                    echo "<div class=\"message_success\">";
                    echo $this->session->flashdata('pag_atualizado');
                    echo "</div><br>";
                }

                foreach ($classificacao as $candidato) {//mostra candidatos classificados em ordem
                    if ($candidato->situacao == 'Contratado') {
                        echo "<div class=\"contratado\">";
                    } elseif ($candidato->situacao == 'Classificado') {
                        echo "<div class=\"classificado\">";
                    }

                    echo "Posição $candidato->posicao: <strong>$candidato->nome</strong>; Exigências: $candidato->exigencias; Descrição: $candidato->descricao Situação: $candidato->situacao" . br(1);
                    if ($candidato->situacao == 'Contratado') {

                        echo form_open('Ctrl_solicitacao/Set_pagamento/' . $basic_info->id . "/" . $candidato->id);
                        for ($i = 0; $i <= 2; $i++) {// menor ou igual a 2 pois o máximo de parcelas é 3 (0,1,2)
                            isset($candidato->parcelas{$i}->id) ? $id = $candidato->parcelas{$i}->id : $id = null;
                            isset($candidato->parcelas{$i}->data_pag) ? $data = $candidato->parcelas{$i}->data_pag : $data = null;
                            isset($candidato->parcelas{$i}->valor_pag) ? $valor = $candidato->parcelas{$i}->valor_pag : $valor = null;
                            isset($candidato->parcelas{$i}->status_pag) ? $status = $candidato->parcelas{$i}->status_pag : $status = 'Não definido';

                            if ($status == 'Pago' || $status == 'Autorizado') {
                                $readonly = 'readonly';
                            } else {
                                $readonly = '';
                            }

                            echo form_hidden("parcela[]", $id);
                            echo form_hidden("status[]", $status);
                            echo form_hidden("remuneracao_bruta", $solic->remuneracao_bruta);
                            echo br() . "Parcela " . ($i + 1) . ": Data: ";
                            ?>
                            <input type="date" name="parceladata[]" value=<?php echo $data ?> class="inputparcelas" <?php echo $readonly ?>>
                            <?php
                            echo "Valor (R$): ";
                            ?>
                            <input type="number" name="parcelavalor[]" value=<?php echo $valor ?> class="inputparcelas" min="0" step="0.01" <?php echo $readonly ?>>                        
                            <?php
                            echo "Situação: ";
                            if ($status == 'Pago') {
                                $color = 'blue';
                            } elseif ($status == 'Aguardando autorização') {
                                $color = 'orange';
                            } elseif ($status == 'Autorizado') {
                                $color = 'green';
                            } else {
                                $color = 'black';
                            }
                            echo "<span style=\"color:$color\">" . $status . "</span>";
                        }
                        echo br(2) . form_submit(array('name' => 'fechar_solic', 'class' => 'myButton'), 'Definir parcelas');
                        echo form_close();
                    }

                    echo "</div>";
                }
                echo br();
            }
            break;

        default:
            break;
    }

    echo "<hr>";

    echo br() . anchor('Ctrl_main', 'Voltar', array('class' => 'myButton')) . br(2);
    ?>

    <script>//script para adicionar classificados nas solicitações de contratacao
        $(document).ready(function () {
            var max_fields = 50;
            var wrapper = $(".classif_fields_wrap");
            var add_button = $(".add_classif_button");

            var x = 0;
            $(add_button).click(function (e) {
                e.preventDefault();
                if (x < max_fields) {
                    x++;
                    $(wrapper).append('<div class="file_upload_box"><br>Nome do candidato:  \n\
                        <input type="text" name="nome[]" value="" required="required" autofocus="">\n\
                        <br>Exigências do cargo/vaga apresentadas pelo candidato:\n\
                        <br><textarea name="exigencias[]" cols="40" rows="10" required="required">\n\
                        </textarea><br>Descrição da motivação da classificação: <br><textarea name="descricao[]"\n\
                        cols="40" rows="10" required="required"></textarea><br><a href="#" \n\
                        class="remove_field">Remover candidato</a></div>');
                }
            });

            $(wrapper).on("click", ".remove_field", function (e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            })
        });
    </script>

