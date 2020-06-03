<?php

echo "<h3>Parecer da Coordenação de Curso</h3><h4>Relatório Mensal de Atividades de Tutores</h4>Mês selecionado: " . vdate($dados_relatorio->month_year, 'myext') . br(2);

echo nl2br($text);

echo br(2);

echo "<hr>";

//echo "<h4>Clique para baixar em PDF o Parecer da Coordenação de Curso para impressão.</h4>";
//echo anchor('Ctrl_gerarPdf/pdf_parecer_coord', "<h4>Clique para baixar em PDF o Parecer da Coordenação de Curso para impressão.</h4>");
echo form_open('Ctrl_gerarPdf/pdf_parecer_coord/', array('target' => '_blank'));
echo form_hidden('nome_coordenador', $dados_relatorio->coordenador);
echo form_hidden('mes_ano', $dados_relatorio->month_year);
echo form_hidden('nome_projeto', $dados_relatorio->nome_projeto);
echo form_hidden('numero_projeto', $dados_relatorio->numero_projeto);
foreach ($aprovados as $tutor) {
    echo form_hidden('tutor[]', $tutor);
}
echo form_submit('baixar', 'Clique para baixar em PDF o Parecer da Coordenação de Curso para impressão.', array('class' => ''));
echo form_close();

echo form_open_multipart('Ctrl_project/Create_solic_bolsa/');

echo form_hidden('project_id', $dados_relatorio->project_id);
echo form_hidden('mes_ano', $dados_relatorio->month_year);
echo form_hidden('tutor_ou_docente', $dados_relatorio->tutor_ou_docente);

foreach ($reports as $report) {//pega ids dos bolsistas aprovados para pagamento somente
    echo form_hidden('report_id[]', $report->id);
    echo form_hidden('situacao[]', $report->situacao);
    echo form_hidden('motivo[]', $report->motivo);
    if ($report->situacao == 'aprovado') {
        echo form_hidden('tutor_id[]', $report->tutor_id);
    }
}

echo "<div class=\"file_upload_box\">Coloque aqui o Parecer da Coordenação de Curso, assinado pelo coordenador:<br><input type=\"file\" required name=\"files\"/></div>" . br();
echo form_submit('criar', 'Criar solicitação de Bolsa', array('class' => 'myButton'));
echo form_close();
