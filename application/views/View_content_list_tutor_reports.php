<?php

echo "<h3>Relatórios de Tutoria</h3>" . br();

echo "<strong>Projeto número: " . $project_info->project_number;
echo " - " . $project_info->title . "</strong>" . br(2);

echo anchor('/files/tutor_relatorio_generico.pdf', 'Clique aqui para baixar o relatório mensal de atividades', array('download' => 'tutor_relatorio_generico.pdf'));

if ($this->session->flashdata('report_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('report_ok');
    echo "</div><br>";
}

if ($this->session->flashdata('report_null')) {
    echo "<div class=\"message_error\">";
    echo $this->session->flashdata('report_null');
    echo "</div><br>";
}

echo "<hr>";

/*
 * NOTA:
 * ADICIONADO 86400 (UM DIA) AO TIMESTAMP PARA A FUNCAO DATE E MDATE
 * (OU MYSQL_TO_UNIX) CONSIDERAR O MÊS CORRETO (ESTÁ JOGANDO PARA DIA 31, OU ULTIMO DIA, DO MÊS ANTERIOR).
 * NÃO SEI POR QUE ESTAVA SAINDO ERRADO, GO HORSE PARA RESOLVER.
 */

if ($today >= 1 && $today <= 27) {//prazo para exibição aos tutores enviar arquivos
    if ($meses_nao_enviados != null) {
        echo "<h4>Meses pendentes de envio de relatório:</h4>";
        echo form_open_multipart('Ctrl_tutor/Upload_reports/' . $project_info->id);
        foreach ($meses_nao_enviados as $mes => $value) {
            echo "<div class=\"div_border\">";
            echo "<strong>" . mdate('%F de %Y', mysql_to_unix($mes) + 86400) . "</strong>" . br();
            echo "Anexar relatório: " . form_upload(array('name' => 'file' . $mes));
            echo "</div>";
        }
        echo form_submit('Enviar', 'Enviar');
        echo form_close();
    } else {
        echo "<h4>Nenhum mês pendente de envio de relatório.</h4>";
    }

    echo "<hr>";

    if ($meses_pendentes != null) {
        echo "<h4>Meses com pagamento pendente (aguardando decisão do coordenador):</h4>";
        foreach ($meses_pendentes as $mes) {
            echo "<div class=\"div_border\">";
            echo "<strong>" . mdate('%F de %Y', mysql_to_unix($mes->month_year) + 86400) . "</strong>" . br();
            echo "Relatório enviado: " . anchor('uploads/' . $this->session->userdata['login'] . "/" .
                    $mes->file_hash, $mes->file_name, "download=$mes->file_name") . "<br>";
            echo "</div>";
        }
    } else {
        echo "<h4>Nenhum mês com pagamento pendente no momento.</h4>";
    }

    echo "<hr>";

    if ($meses_reenvio != null) {
        echo "<h4>Meses para reenvio de relatório:</h4>";
        foreach ($meses_reenvio as $mes) {
            echo "<div class=\"div_border\">";
            echo "<strong>" . mdate('%F de %Y', mysql_to_unix($mes->month_year) + 86400) . "</strong>" . br() . "Rejeitado em " . vdate($mes->accept_or_deny_at)
            . " por " . $mes->accepted_or_denied_by . ". Motivo: " . $mes->deny_reason . br();
            echo "Relatório enviado: $mes->file_name" . br();
            echo "Anexar relatório: " . form_upload(array('name' => 'file' . $mes->month_year));
            echo "</div>";
        }
    } else {
        echo "<h4>Nenhum mês com pagamento rejeitado.</h4>";
    }

    echo "<hr>";

    if ($meses_rejeitados_permanente != null) {
        echo "<h4>Meses com pagamento rejeitado permanentemente:</h4>";
        foreach ($meses_rejeitados_permanente as $mes) {
            echo "<div class=\"div_border\">";
            echo "<strong>" . mdate('%F de %Y', mysql_to_unix($mes->month_year) + 86400) . "</strong>" . br() . "Rejeitado em " . vdate($mes->accept_or_deny_at)
            . " por " . $mes->accepted_or_denied_by . ". Motivo: " . $mes->deny_reason . br();
            echo "Relatório enviado: $mes->file_name";
            echo "</div>";
        }
    } else {
        echo "<h4>Nenhum mês com pagamento rejeitado permanentemente.</h4>";
    }

    echo "<hr>";

    if ($meses_aprovados != null) {
        echo "<h4>Meses com pagamento aprovado:</h4>";
        foreach ($meses_aprovados as $mes) {
            echo "<div class=\"div_border\">";
            echo "<strong>" . mdate('%F de %Y', mysql_to_unix($mes->month_year) + 86400) . "</strong>" . br() . "Aprovado em " . vdate($mes->accept_or_deny_at)
            . " por " . $mes->accepted_or_denied_by . br();
            echo "Relatório enviado: $mes->file_name";
            echo "</div>";
        }
    } else {
        echo "<h4>Nenhum mês com pagamento aprovado até o momento.</h4>";
    }
} else {
    echo "<h4>O período para envio do relatório mensal de atividades é entre os dias 1 e 5 de cada mês.</h4>";
}

echo "<hr>";

echo br() . anchor('Ctrl_main', 'Cancelar e Voltar', Array('class' => 'myButton'));
