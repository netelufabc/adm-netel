<?php
echo "<strong>Projeto número: " . $basic_info->project_number;
echo " - " . $basic_info->project_title . "</strong>" . br(2);

echo "Solicitação número: " . $basic_info->id . br();
echo "Criado por: " . $basic_info->criado_por . br();
echo "Aberto em: " . mdate('%d/%m/%Y - %H:%i:%s', mysql_to_unix($basic_info->created_at)) . br();
echo "Tipo: " . $basic_info->tipo . br();
echo "Status: " . $basic_info->status . br(2);

switch ($basic_info->tipo) {

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

    default:
        break;
}

echo br() . "<input value=\"Voltar\" onclick=\"JavaScript:window.history.back();\" type=\"button\" class=\"myButton\">" . br(2);

if ($this->session->flashdata('invalid_email')) {
    echo "<div class=\"message_error\">";
    echo $this->session->flashdata('invalid_email');
    echo "</div><br>";
}

if ($this->session->flashdata('msg_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('msg_ok');
    echo "</div><br>";
}
?>

<div>
    <button type="button" class="myButton" data-toggle="collapse" data-target="#novamsg">Nova Mensagem</button>
    <div id="novamsg" class="collapse">
        <?php
        echo form_open("Ctrl_solicitacao/New_message/" . $basic_info->id);

        echo br() . form_label('Mensagem: ') . br();
        echo form_textarea(array('name' => 'mensagem', 'required' => 'required'), set_value('mensagem'), 'autofocus');
        echo br(1);

        echo form_label('Notificar por e-mail (insira o e-mail completo; utilize vírgula (,) para múltiplos e-mails; não é necessário inserir o e-mail do solicitante): ') . br();
        echo form_textarea(array('name' => 'email_notif'), set_value('email_notif'));
        echo br(2);

        echo form_submit(array('name' => 'inserir'), 'Inserir Mensagem');

        echo form_close();
        ?>
    </div>
</div>

<?php
echo "<hr>";
echo "<h4>Mensagens:</h4>";

if (isset($listaMsg) && ($listaMsg != null)) {

    echo "<table class=\"tabela2\">";
    
    foreach ($listaMsg as $msg) {
        echo "<tr>";
        echo "<td>";
        echo "De: <strong>$msg->name</strong> em $msg->created_at<br><br>$msg->mensagem <br><br>";
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";

} else {
    echo '<h5>NENHUMA MENSAGEM CADASTRADA</h5>';
}
?>

