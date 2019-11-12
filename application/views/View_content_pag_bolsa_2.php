<?php

echo "<h3>Parecer da Coordenação de Curso</h3><h4>Relatório Mensal de Atividades de Tutores</h4>Mês selecionado: " . vdate($dados_relatorio->month_year, 'myext') . br(2);

echo nl2br($text);

echo br(2);

echo "<hr>";

echo "<h4>Clique para baixar em PDF o Parecer da Coordenação de Curso para impressão.</h4>";

echo form_open_multipart('Ctrl_project/Create_solic_bolsa/');
echo "<div class=\"file_upload_box\">Coloque aqui o Parecer da Coordenação de Curso, assinado pelo coordenador:<br><input type=\"file\" required name=\"files\"/></div>" . br();
echo form_submit('criar', 'Criar solicitação de Bolsa', array('class' => 'myButton'));
echo form_close();
