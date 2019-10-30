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

    if (HasRole(1, 2, 3) || (HasRole(4) && $basic_info->tipo == 'Encontro')) {//mostra opções de fechar e cancelar caso possua roles 1,2,3 ou role 4 e tipo da solicitação seja Encontro (assistente fecha ou cancela somente solicitação de encontro)
        echo form_open("Ctrl_solicitacao/Fechar_ou_cancelar_solic/$basic_info->id");
        echo form_submit(array('name' => 'fechar_solic', 'class' => 'myButton'), 'Fechar Solicitação');
        echo form_close();
        echo form_open("Ctrl_solicitacao/Fechar_ou_cancelar_solic/$basic_info->id");
        echo form_submit(array('name' => 'fechar_solic', 'class' => 'myButton'), 'Cancelar Solicitação');
        echo form_close();
    }
} else {
    echo "<p style=\"color: blue;\">";
    echo $basic_info->status . "</strong></p>";
    echo "em " . mdate('%d/%m/%Y às %H:%i', mysql_to_unix($basic_info->closed_at)) . " por " . $basic_info->name . br(2);
}

switch ($basic_info->tipo) {//mostra os dados de acordo com o tpo de solicitação
    case 'Encontro':

        echo "Polo: " . $solic->polo . br();
        echo "Data do evento: " . mdate('%d/%m/%Y', mysql_to_unix($solic->data)) . br();
        echo "Hora de início: " . $solic->hora_inicio . br();
        echo "Data de término: " . $solic->hora_fim . br();
        echo "Professores a participar: " . $solic->professores . br();
        echo "Tutores a participar: " . $solic->tutores . br();
        echo "Quantidade de salas: " . $solic->quantidade_sala . br();
        echo "Capacidade de cada sala: " . $solic->capacidade_sala . br();
        echo "Quantidade de laboratórios: " . $solic->quantidade_lab . br();
        echo "Capacidade de cada laboratório: " . $solic->capacidade_lab . br();
        echo "Auditório: " . $solic->auditorio . br();
        echo "Equipamentos adicionais e quantidades: " . $solic->equip . br();
        echo "Observações: " . $solic->obs . br();

        break;

    case 'Compra':

        echo "Item a comprar: " . $solic->item_compra . br();
        echo "Especificações do item: " . $solic->especificacao_compra . br();
        echo "Unidade: " . $solic->unidade_compra . br();
        echo "Quantidade: " . $solic->quantidade_compra . br();
        echo "Valor do item (unidade, em R$): " . $solic->valor_compra . br();
        echo "Valor total (R$): " . $solic->valor_compra * $solic->quantidade_compra . br();
        echo "Motivação da compra: " . $solic->motivacao_compra . br();
        echo "Conexao da compra com o projeto: " . $solic->conexao_compra . br();

        break;

    case 'Servico':

        echo "Tipo de serviço: " . $solic->tipo_servico . br();
        echo "Motivação do serviço: " . $solic->motivacao_servico . br();
        echo "Conexão do serviço com o projeto: " . $solic->conexao_servico . br();
        echo "Prazo do serviço: " . mdate('%d/%m/%Y', mysql_to_unix($solic->prazo_servico)) . br();

        break;

    case 'Bolsa':

        echo "Mês / Ano de referência: " . mdate('%m/%Y', mysql_to_unix($solic->mes_ano)) . br();
        echo "Bolsa para: " . $solic->tutor_ou_docente . br();
        echo "A receber pagamento de bolsa:" . br(2);
        foreach ($bolsistas as $bolsista) {
            echo $bolsista->name . br();
        }

        break;

    case 'Contratacao'://bloco para solicitações de contratação

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

        echo br() . anchor('Ctrl_solicitacao/Classificacao_info/' . $basic_info->id, 'Classificacao', array('class' => 'myButton')) . br(2);

        echo "<hr>";
//        echo "<hr>";
//        echo "<h4>Classificação dos Candidatos</h4>";
//
//        
//        
//        if ($this->session->flashdata('classificacao_inserida')) {
//            echo "<div class=\"message_success\">";
//            echo $this->session->flashdata('classificacao_inserida');
//            echo "</div><br>";
//        }
//
//        switch ($solic->status) {//parte relativa a classificacao dos candidatos
//            case "Aguardando Classificacao"://este é o estágio após o  administrativo do netel enviar os curriculos dos candidatos via mensagem da solicitação e autorizar o coordenador a classificar
//                echo "Adicione os candidatos aprovados, em ordem de classificação, de cima para baixo.<br><br>";
//
//                if ($this->session->flashdata('sem_candidatos')) {
//                    echo "<div class=\"message_error\">";
//                    echo $this->session->flashdata('sem_candidatos');
//                    echo "</div><br>";
//                }
//                
        ?>

        <!--                <div class="classif_fields_wrap">
                            <a href="#" class="add_classif_button">Adicionar candidato classificado</a><br><br>
                        </div>-->

    <?php
////                if ($solic->tipo == "Autonomo") {
////                    echo form_hidden('solic_autonomo_pag', '1');
////                    echo "Parcela 1: Data: ";
////                    echo form_input(array('name' => 'parcela1data', 'type' => 'date', 'required' => 'required'), set_value('parcela1data'));
////                    echo "Valor (R$): ";
////                    echo form_input(array('name' => 'parcela1valor', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'required' => 'required'), set_value('parcela1valor')) . br();
////                    echo "Parcela 2: Data: ";
////                    echo form_input(array('name' => 'parcela2data', 'type' => 'date', 'required' => 'required'), set_value('parcela2data'));
////                    echo "Valor (R$): ";
////                    echo form_input(array('name' => 'parcela2valor', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'required' => 'required'), set_value('parcela2valor')) . br();
////                    echo "Parcela 3: Data: ";
////                    echo form_input(array('name' => 'parcela3data', 'type' => 'date', 'required' => 'required'), set_value('parcela3data'));
////                    echo "Valor (R$): ";
////                    echo form_input(array('name' => 'parcela3valor', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'required' => 'required'), set_value('parcela3valor')) . br();
////                }
//
//                echo form_open("Ctrl_solicitacao/Insert_classificacao/" . $basic_info->id);
//                echo form_submit(array('name' => 'inserir', 'class' => 'myButton'), 'Inserir Classificação');
//                echo form_close();
//
//                echo form_open("Ctrl_solicitacao/Nenhum_candidato_aprovado/$basic_info->id", array('onsubmit' => 'return confirm(\'Tem certeza que deseja informar que nenhum candidato foi aprovado?\')'));
//                echo form_submit(array('name' => 'fechar_solic', 'class' => 'myButton'), 'Nenhum Classificado');
//                echo form_close(); //este botão retorna o status para Aguardando Curriculos, quando nenhum candidato é aprovado pelo coordenador
//
//                break;
//
//            case "Aguardando Curriculos"://este é o estágio padrão, quando a solicitação é criada
//                if (HasRole(1, 2)) {//se for sysadmin ou admin netel, mostra o botão para liberar o coordenador de fazer a classificacao, deve ser clicado pelo admin netel após anexar os curriculos
//                    echo form_open("Ctrl_solicitacao/Liberar_para_classificacao/$basic_info->id", array('onsubmit' => 'return confirm(\'Tem certeza que deseja liberar o coordenador para indicação dos candidatos aprovados?\')'));
//                    echo form_submit(array('name' => 'fechar_solic', 'class' => 'myButton'), 'Liberar Classificação');
//                    echo form_close();
//                } else {//se nao form sysadmin ou admin netel apenas mostra a msg para o coordenador e assistente
//                    echo "<h4>Currículos ainda não disponíveis para classificação ou não liberado pelo NETEL.</h4>";
//                }
//                break;
//
//            case "Aguardando Netel"://este é o estágio após o coordenador inserir a classificação
//                if ($classificacao != null) {
//                    echo "<h4>Aguardando contratação pelo NETEL.</h4>";
//
//                    if (HasRole(1, 2)) {
//                        echo form_open('');
//                        echo "Clique no checkbox para marcar como contratado: " . br();
//                    }
//
//                    foreach ($classificacao as $candidato) {//mostra candidatos classificados em ordem
//                        if ($candidato->situacao == 'Contratado') {
//                            echo "<div class=\"contratado\">";
//                        } elseif ($candidato->situacao == 'Classificado') {
//                            echo "<div class=\"classificado\">";
//                            if (HasRole(1, 2)) {
//                                echo form_checkbox('contratado[]', $candidato->id);
//                            }
//                        }
//
//                        echo "Posição: $candidato->posicao: <strong>$candidato->nome</strong>; Exigências: $candidato->exigencias; Descrição: $candidato->descricao Situação: $candidato->situacao" . br();
//                        echo "</div>";
//                    }
//                    echo br();
//
//                    if (HasRole(1, 2)) {
//                        echo form_submit(array('name' => 'fechar_solic', 'class' => 'myButton'), 'Marcar Contratados');
//                        echo form_close();
//                    }
//                }
//                break;
//
//            default:
//                break;
//        }
//
//        echo "<hr>";
//
//        break;

    default:
        break;
}

echo br() . anchor('Ctrl_main', 'Voltar', array('class' => 'myButton')) . br(2);

if ($this->session->flashdata('invalid_email')) {
    echo "<div class = \"message_error\">";
    echo $this->session->flashdata('invalid_email');
    echo "</div><br>";
}

if ($this->session->flashdata('msg_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('msg_ok');
    echo "</div><br>";
}


if ($basic_info->status == "Aberto") {//só mostra opção de inserir msg se solicitação está aberta
    ?>

    <div>
        <button type="button" class="myButton" data-toggle="collapse" data-target="#novamsg">Mensagem / Anexo</button>
        <div id="novamsg" class="collapse">
            <?php
            echo form_open_multipart("Ctrl_solicitacao/New_message/" . $basic_info->id);

            echo br() . form_label('Mensagem: ') . br();
            echo form_textarea(array('name' => 'mensagem', 'required' => 'required'), set_value('mensagem'), 'autofocus');
            echo br(1);

            echo form_label('Notificar por e-mail (insira o e-mail completo; utilize vírgula (,) para múltiplos e-mails; não é necessário inserir o e-mail do solicitante): ') . br();
            echo form_textarea(array('name' => 'email_notif'), set_value('email_notif'));
            echo br(2);
            ?>

            <div class="input_fields_wrap">
                <a href="#" class="add_field_button">Adicionar Anexo</a><br><br>
            </div>
            <br><br>

            <?php
            echo form_submit(array('name' => 'inserir'), 'Inserir Mensagem');

            echo form_close();
            ?>
        </div>
    </div>
    <?php
}
?>

<!--<script>//script para adicionar classificados nas solicitações de contratacao
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
</script>-->

<script>//script para adicionar anexos às msgs
    $(document).ready(function () {
        var max_fields = 50;
        var wrapper = $(".input_fields_wrap");
        var add_button = $(".add_field_button");

        var x = 0;
        $(add_button).click(function (e) {
            e.preventDefault();
            if (x < max_fields) {
                x++;
                $(wrapper).append('<div class=\"file_upload_box\"><input type="file" name="files[]"/><a href="#" class="remove_field">Remover arquivo</a></div>');
            }
        });

        $(wrapper).on("click", ".remove_field", function (e) {
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })
    });
</script>

<?php
echo "<hr>";
echo "<h4>Mensagens:</h4>";

if (isset($listaMsg) && ($listaMsg != null)) {

    echo "<table class=\"tabela2\">";

    foreach ($listaMsg as $msg) {
        echo "<tr>";
        echo "<td>";
        echo "De: <strong>$msg->name</strong> em $msg->created_at<br><br>$msg->mensagem <br><br>";
        if (count($msg->files) > 0) {
            echo "Anexos (clique para download):<br><br>";
            foreach ($msg->files as $file) {
                $file->file_name = str_replace(' ', '_', $file->file_name); //remove tods espaços em branco para nao dar pau na hora de download
                echo anchor('uploads/' . $this->session->userdata['login'] . "/" .
                        $file->file_hash, $file->file_name, "download=$file->file_name") . "<br>";
            }
        }
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo '<h5>NENHUMA MENSAGEM CADASTRADA</h5>';
}
                    