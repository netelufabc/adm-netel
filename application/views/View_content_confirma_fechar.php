<?php

echo "<strong>Projeto número: " . $basic_info->project_number;
echo " - " . $basic_info->project_title . "</strong>" . br(2);

echo "Solicitação número: " . $basic_info->id . br();
echo "Criado por: " . $basic_info->criado_por . br();
echo "Aberto em: " . mdate('%d/%m/%Y - %H:%i:%s', mysql_to_unix($basic_info->created_at)) . br();
echo "Tipo: " . $basic_info->tipo . br();

echo "<h3>Confirma ";
if ($fechar_ou_cancelar == 'fechar') {
    echo "FECHAR esta solicitação (marcar como resolvida)?";
    $button_msg = "FECHAR SOLICITAÇÃO";
    $button_name = "fechar";
} else {
    echo "CANCELAR esta solicitação?";
    $button_msg = "CANCELAR SOLICITAÇÃO";
    $button_name = "cancelar";
}
echo "</h3>";

echo form_open("Ctrl_solicitacao/Fechar_ou_cancelar_solic/" . $basic_info->id);

echo br() . form_label('Mensagem: ') . br();
echo form_textarea(array('name' => 'mensagem', 'required' => 'required'), set_value('mensagem'), 'autofocus');
echo br(1);

echo form_label('Notificar por e-mail (insira o e-mail completo; utilize vírgula (,) para múltiplos e-mails; não é necessário inserir o e-mail do solicitante): ') . br();
echo form_textarea(array('name' => 'email_notif'), set_value('email_notif'));
echo br(2);

echo form_submit(array('name' => $button_name), $button_msg);

echo form_close();
