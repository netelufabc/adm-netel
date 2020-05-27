<?php
echo "<strong>Projeto número: " . $basic_info->project_number; //basic_info é info da tablea solicitacoes, $solic_info é da tabela específica de cada tipo de solicitação
echo " - " . $basic_info->project_title . "</strong>" . br(2);

echo "Solicitação número: " . $basic_info->id . br();
echo "Criado por: " . $basic_info->criado_por . br();
echo "Aberto em: " . vdate($basic_info->created_at, 'full') . br();
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
    echo "em " . vdate($basic_info->closed_at) . " por " . $basic_info->name . br(2);
}

switch ($basic_info->tipo) {//mostra os dados de acordo com o tpo de solicitação
    case 'Encontro':

        echo "Polo: " . $solic->polo . br();
        echo "Data do evento: " . vdate($solic->data, 'dmy') . br();
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
        echo "Prazo do serviço: " . vdate($solic->prazo_servico, 'dmy') . br();

        break;

    case 'Bolsa':

        echo "Mês / Ano de referência: " . vdate($solic->mes_ano, 'my') . br();
        echo "Bolsa para: " . $solic->tutor_ou_docente . br();
        echo "Relatório: ";
//        echo anchor('uploads/' . $this->session->userdata['login'] . "/" .
//                $relatorio_bolsa->file_hash, $relatorio_bolsa->file_name, "download=$relatorio_bolsa->file_name") . br(2);
        if ($relatorio_bolsa == null) {
            echo "Arquivo de relatório não encontrado" . br();
        } else {
            FileDownload($relatorio_bolsa->file_name, $relatorio_bolsa->file_hash);
            //echo anchor('uploads/' .
            //        $relatorio_bolsa->file_hash, $relatorio_bolsa->file_name, "download=$relatorio_bolsa->file_name") . br(2);
        }
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
        echo "Remuneração Bruta (R$): " . number_format($solic->remuneracao_bruta, 2) . br();
        echo "Remuneração Mensal (R$): " . number_format($solic->remuneracao_mensal, 2) . br();
        echo "Local de trabalho: " . $solic->local_trabalho . br();
        echo "Horário de trabalho: " . $solic->horario_trabalho . br();
        echo "Situação: <strong>" . $solic->status . "</strong>" . br();

        echo br() . anchor('Ctrl_solicitacao/Classificacao_info/' . $basic_info->id, 'Classificacao', array('class' => 'myButton')) . br(2);

        echo "<hr>";

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
        echo "De: <strong>$msg->name</strong> em " . vdate($msg->created_at, 'full') . "<br><br>" . $msg->mensagem . br(2);
        if (count($msg->files) > 0) {
            echo "Anexos (clique para download):<br><br>";
            foreach ($msg->files as $file) {
                //$file->file_name = str_replace(' ', '_', $file->file_name); //remove tods espaços em branco para nao dar pau na hora de download
//                echo anchor('uploads/' . $this->session->userdata['login'] . "/" .
//                        $file->file_hash, $file->file_name, "download=$file->file_name") . "<br>";
                FileDownload($file->file_name, $file->file_hash);
            }
        }
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo '<h5>NENHUMA MENSAGEM CADASTRADA</h5>';
}
                    