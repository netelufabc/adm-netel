<?php
echo "<h2>Nova solicitação: </h2>";

echo "<div>";
echo form_dropdown('solic_select', $new_solic_list);
echo "</div>";

echo br() . "<input value=\"Cancelar\" onclick=\"JavaScript:window.history.back();\" type=\"button\">" . br() . "<hr>" . br();

if ($this->session->flashdata('erro_solic')) {
    echo "<div class=\"message_error\">";
    echo $this->session->flashdata('erro_solic');
    echo "</div><br>";
}
?>

<div class="form_div" align="left"><!--div para formulario alinhar à esquerda-->

    <div class="red box" style="display:none"> 

        <h3>Contratação de Pessoal</h3>

        <?php
        echo form_label('Tipo de contratação:') . br();
        echo form_dropdown('tipo_contrata', array('' => 'Selecione', 'autonomo' => 'Autônomo', 'clt' => 'Celetista', 'bolsista' => 'Bolsista', 'estagiario' => 'Estagiário'));
        echo br(1);
        ?>

        <div class="autonomo caixa" style="display:none"> 

            <?php
            echo form_open('Ctrl_project/New_solic_autonomo');
            echo form_hidden('project_id', $project_id);
            echo br(1);

            echo form_label('Título da Vaga/Cargo Autônomo: ') . br();
            echo form_input(array('name' => 'titulo', 'required' => 'required'), set_value('titulo'));
            echo br(1);

            echo form_label('Quantidade de vagas: ') . br();
            echo form_input(array('name' => 'quantidade', 'type' => 'number', 'min' => '1', 'required' => 'required'), set_value('quantidade_vaga'));
            echo br();

            echo form_label('Descrição das atividades a serem desempenhadas:') . br();
            echo form_textarea(array('name' => 'descricao', 'required' => 'required'), set_value('descricao'));
            echo br(1);

            echo form_label('Requisitos Obrigatórios:') . br();
            echo form_textarea(array('name' => 'req_obrig', 'required' => 'required'), set_value('req_obrig'));
            echo br(1);

            echo form_label('Requisitos Desejáveis:') . br();
            echo form_textarea(array('name' => 'req_desej'), set_value('req_desej'));
            echo br(1);

            echo form_label('Remuneração Bruta (R$):') . br();
            echo form_input(array('name' => 'remuneracao_bruta', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'required' => 'required'), set_value('remuneracao_bruta'));
            echo br(1);

            echo form_label('Tempo estimado para duração do serviço (meses): ') . br();
            echo form_input(array('name' => 'tempo_estimado', 'type' => 'number', 'min' => '1', 'required' => 'required'), set_value('tempo_estimado'));
            echo br();

            echo form_label('Dias para divulgação da vaga: ') . br();
            echo form_input(array('name' => 'dias_divulgacao', 'type' => 'number', 'min' => '7', 'required' => 'required'), set_value('dias_divulgacao'));
            echo br();

            echo form_label('Tipo de seleção:') . br();
            echo form_checkbox('tipo_selecao[]', 'Curriculo', true) . "Análise Curricular" . br();
            echo form_checkbox('tipo_selecao[]', 'Provas') . "Provas" . br();
            echo form_checkbox('tipo_selecao[]', 'Entrevistas') . "Entrevistas" . br();
            echo br();

            echo form_submit(array('name' => 'new_solic_autonomo'), 'Criar Solicitação de Contratação Autônomo');
            echo form_close();
            ?>

        </div>

        <div class="clt caixa" style="display:none"> 

            <?php
            echo form_open('Ctrl_project/New_solic_celetista');
            echo form_hidden('project_id', $project_id);
            echo br(1);

            echo form_label('TÍtulo da Vaga/Cargo Celetista: ') . br();
            echo form_input(array('name' => 'titulo', 'required' => 'required'), set_value('titulo'));
            echo br(1);

            echo form_label('Quantidade de vagas: ') . br();
            echo form_input(array('name' => 'quantidade', 'type' => 'number', 'min' => '1', 'required' => 'required'), set_value('quantidade'));
            echo br();

            echo form_label('Descrição das atividades a serem desempenhadas:') . br();
            echo form_textarea(array('name' => 'descricao', 'required' => 'required'), set_value('descricao'));
            echo br(1);

            echo form_label('Requisitos Obrigatórios:') . br();
            echo form_textarea(array('name' => 'req_obrig', 'required' => 'required'), set_value('req_obrig'));
            echo br(1);

            echo form_label('Requisitos Desejáveis:') . br();
            echo form_textarea(array('name' => 'req_desej'), set_value('req_desej'));
            echo br(1);

            echo form_label('Remuneração Mensal (R$):') . br();
            echo form_input(array('name' => 'remuneracao_mensal', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'required' => 'required'), set_value('remuneracao_mensal'));
            echo br(1);

            echo form_label('Local de Trabalho:') . br();
            echo form_input(array('name' => 'local_trabalho', 'required' => 'required'), set_value('local_trabalho'));
            echo br(1);

            echo form_label('Horário de Trabalho:') . br();
            echo form_input(array('name' => 'horario_trabalho', 'required' => 'required'), set_value('horario_trabalho'));
            echo br(1);

            echo form_label('Dias para divulgação da vaga: ') . br();
            echo form_input(array('name' => 'dias_divulgacao', 'type' => 'number', 'min' => '7'), set_value('dias_divulgacao'));
            echo br();

            echo form_label('Tipo de seleção:') . br();
            echo form_checkbox('tipo_selecao[]', 'Curriculo', true) . "Análise Curricular" . br();
            echo form_checkbox('tipo_selecao[]', 'Provas') . "Provas" . br();
            echo form_checkbox('tipo_selecao[]', 'Entrevistas') . "Entrevistas" . br();
            echo br();

            echo form_submit(array('name' => 'new_solic_celetista'), 'Criar Solicitação de Contratação Celetista');
            echo form_close();
            ?>

        </div>

        <div class="bolsista caixa" style="display:none"> 

            <?php
            echo form_open('Ctrl_project/New_solic_bolsista');
            echo form_hidden('project_id', $project_id);
            echo br(1);

            echo form_label('TÍtulo da Vaga/Cargo Bolsista: ') . br();
            echo form_input(array('name' => 'titulo', 'required' => 'required'), set_value('titulo'));
            echo br(1);

            echo form_label('Quantidade de vagas: ') . br();
            echo form_input(array('name' => 'quantidade', 'type' => 'number', 'min' => '1', 'required' => 'required'), set_value('quantidade'));
            echo br();

            echo form_label('Descrição das atividades a serem desempenhadas:') . br();
            echo form_textarea(array('name' => 'descricao', 'required' => 'required'), set_value('descricao'));
            echo br(1);

            echo form_label('Requisitos Obrigatórios:') . br();
            echo form_textarea(array('name' => 'req_obrig', 'required' => 'required'), set_value('req_obrig'));
            echo br(1);

            echo form_label('Requisitos Desejáveis:') . br();
            echo form_textarea(array('name' => 'req_desej'), set_value('req_desej'));
            echo br(1);

            echo form_label('Remuneração Mensal (R$):') . br();
            echo form_input(array('name' => 'remuneracao_mensal', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'required' => 'required'), set_value('remuneracao_mensal'));
            echo br(1);

            echo form_label('Local de Trabalho:') . br();
            echo form_input(array('name' => 'local_trabalho', 'required' => 'required'), set_value('local_trabalho'));
            echo br(1);

            echo form_label('Horário de Trabalho:') . br();
            echo form_input(array('name' => 'horario_trabalho', 'required' => 'required'), set_value('horario_trabalho'));
            echo br(1);

            echo form_label('Dias para divulgação da vaga: ') . br();
            echo form_input(array('name' => 'dias_divulgacao', 'type' => 'number', 'min' => '7'), set_value('dias_divulgacao'));
            echo br();

            echo form_label('Tipo de seleção:') . br();
            echo form_checkbox('tipo_selecao[]', 'Curriculo', true) . "Análise Curricular" . br();
            echo form_checkbox('tipo_selecao[]', 'Provas') . "Provas" . br();
            echo form_checkbox('tipo_selecao[]', 'Entrevistas') . "Entrevistas" . br();
            echo br();

            echo form_submit(array('name' => 'new_solic_celetista'), 'Criar Solicitação de Contratação Celetista');
            echo form_close();
            ?>

        </div>

        <div class="estagiario caixa" style="display:none"> 

            <?php
            echo form_open('Ctrl_project/New_solic_estagiario');
            echo form_hidden('project_id', $project_id);
            echo br(1);

            echo form_label('TÍtulo da Vaga/Cargo Estagiário: ') . br();
            echo form_input(array('name' => 'titulo', 'required' => 'required'), set_value('titulo'));
            echo br(1);

            echo form_label('Quantidade de vagas: ') . br();
            echo form_input(array('name' => 'quantidade', 'type' => 'number', 'min' => '1', 'required' => 'required'), set_value('quantidade'));
            echo br();

            echo form_label('Descrição das atividades a serem desempenhadas:') . br();
            echo form_textarea(array('name' => 'descricao', 'required' => 'required'), set_value('descricao'));
            echo br(1);

            echo form_label('Requisitos Obrigatórios:') . br();
            echo form_textarea(array('name' => 'req_obrig', 'required' => 'required'), set_value('req_obrig'));
            echo br(1);

            echo form_label('Requisitos Desejáveis:') . br();
            echo form_textarea(array('name' => 'req_desej'), set_value('req_desej'));
            echo br(1);

            echo form_label('Local de Trabalho:') . br();
            echo form_input(array('name' => 'local_trabalho', 'required' => 'required'), set_value('local'));
            echo br(1);

            echo form_label('Horário de Trabalho:') . br();
            echo form_input(array('name' => 'horario_trabalho', 'required' => 'required'), set_value('horario'));
            echo br(1);

            echo form_label('Dias para divulgação da vaga: ') . br();
            echo form_input(array('name' => 'dias_divulgacao', 'type' => 'number', 'min' => '7'), set_value('dias_divulgacao'));
            echo br();

            echo form_label('Tipo de seleção:') . br();
            echo form_checkbox('tipo_selecao[]', 'Curriculo', true) . "Análise Curricular" . br();
            echo form_checkbox('tipo_selecao[]', 'Provas') . "Provas" . br();
            echo form_checkbox('tipo_selecao[]', 'Entrevistas') . "Entrevistas" . br();
            echo br();

            echo form_submit(array('name' => 'new_solic_estagiario'), 'Criar Solicitação de Contratação Estagiário');
            echo form_close();
            ?>

        </div>

    </div>

    <div class="green box"style="display:none">

        <h3>Pagamento de Bolsas</h3><br>

        <?php
//        if (substr($today, -2) >= '06' && substr($today, -2) <= 10) {//verifica se está no prazo
//            
//            if (count($month_array) > 0) {
//
//                foreach ($month_array as $month) {
//
//                    echo "Mês: " . vdate($month, 'my') . br();
//
//                    if (count($lista_tutores) > 0) {//verifica se existem tutores cadastrados no projeto
//                        foreach ($lista_tutores as $tutor) {
//                            echo "<h5>Tutor: $tutor->name</h5>";
//
//                            if (count($tutor->reports) > 0) {//verifica se o tutor tem algum relatório cadastrado
        ?>

<!--                                <table id="allSolic" class="tabela">
                                    <thead>
                                        <tr>                                        
                                            <th>MÊS/ANO</th>
                                            <th>SITUAÇÃO</th>
                                            <th>DATA DE ENVIO</th> 
                                            <th>RELATÓRIO</th>
                                            <th>AÇÕES</th>   
                                        </tr>
                                    </thead>  -->

        <?php
//                        foreach ($tutor->reports as $report) {
//                            echo "<tr>";
//                            echo "<td>";
//                            echo vdate($report->month_year, 'my');
//                            echo "</td>";
//                            echo "<td>";
//                            echo $report->status;
//                            echo "</td>";
//                            echo "<td>";
//                            echo vdate($report->upload_date);
//                            echo "</td>";
//                            echo "<td>";
//                            echo anchor('uploads/' . $this->session->userdata['login'] . "/" .
//                                    $report->file_hash, $report->file_name, "download=$report->file_name");
//                            echo "</td>";
//                            echo "<td>";
//                            echo "Aprovar Rejeitar";
//                            echo "</td>";
//                            echo "</tr>";
//                        }
//                        echo "</table>" . br();
//                    } else {
//                        echo "<h4>Nada pendente</h4>" . br();
//                    }
//                }
//            } else {
//                echo "<h3>Nenhum tutor cadastrado</h3>";
//            }
//        }
//    } else {
//        echo "array month_array vazio";
//    }
//} else {
//    echo "<h3>Fora do prazo</h3>";
//}
        if (substr(date('Y-m-d'), -2) >= '06' && substr(date('Y-m-d'), -2) <= 22) {
            echo form_open('Ctrl_project/New_solic_bolsa');
            echo form_hidden('project_id', $project_id);
            echo br(1);

            echo form_label('Mês / Ano: ') . br();
            echo form_input(array('name' => 'mes_ano', 'type' => 'month', 'required' => 'required'), set_value('mes_ano')) . br();
            echo br(1);

            echo form_label('Pagamento para tutores ou docentes?') . br();
            echo form_dropdown('tutor_ou_docente', array('' => 'Selecione', 'tutor' => 'Tutores', 'docente' => 'Docentes'));
            echo br(2);
        } else {
            echo "<h3>Fora do prazo (06 à 22 view_content_new_solicitacao)</h3>" . br();
        }
        ?>

        <div class="tutor caixa" style="display:none"> 

            <?php
//            echo "Selecione os tutores: (segure a tecla \"Ctrl\" para selecionar vários)" . br();
//            if (isset($lista_tutores) && ($lista_tutores != null)) {
//                echo form_multiselect('lista_tutores[]', $lista_tutores) . br();
//            } else {
//                echo "Nenhum tutor cadastrado" . br(2);
//            }
            ?>

        </div>
        <div class="docente caixa" style="display:none"> 

            //<?php
//            echo "Selecione os docentes: (segure a tecla \"Ctrl\" para selecionar vários)" . br();
//            if (isset($lista_docentes) && ($lista_docentes != null)) {
//                echo form_multiselect('lista_docentes[]', $lista_docentes) . br();
//            } else {
//                echo "Nenhum docente cadastrado" . br(2);
//            }
//            
            ?>

        </div>

        <?php
        echo form_submit(array('name' => 'inserir_solicitacao_bolsa'), 'Continuar...');
        echo form_close();
        ?>

    </div>

    <div class="blue box"style="display:none">

        <h3>Encontro Presencial</h3>        

        <?php
        echo form_open('Ctrl_project/New_solic_encontro');
        echo form_hidden('project_id', $project_id);
        echo br(1);

        echo form_label('Polo: ') . br();
        echo form_input(array('name' => 'polo', 'required' => 'required'), set_value('polo'));
        echo br(1);

        echo form_label('Data: ') . br();
        echo form_input(array('name' => 'data', 'type' => 'date', 'required' => 'required'), set_value('data'));
        echo br(1);

        echo form_label('Hora de Início: ') . br();
        echo form_input(array('name' => 'hora_inicio', 'type' => 'time', 'required' => 'required'), set_value('hora_inicio'));
        echo br();

        echo form_label('Hora de Término: ') . br();
        echo form_input(array('name' => 'hora_fim', 'type' => 'time', 'required' => 'required'), set_value('hora_fim'));
        echo br(1);

        echo form_label('Professores (nome e sobrenome): ') . br();
        echo form_textarea(array('name' => 'professores'), set_value('professores'));
        echo br(1);

        echo form_label('Tutores (nome e sobrenome): ') . br();
        echo form_textarea(array('name' => 'tutores'), set_value('tutores'));
        echo br(1);

        echo form_label('Quantidade de Salas: ') . br();
        echo form_input(array('name' => 'quantidade_sala', 'type' => 'number', 'min' => '0'), set_value('quantidade_sala'));
        echo br();

        echo form_label('Capacidade de cada sala: ') . br();
        echo form_input(array('name' => 'capacidade_sala', 'type' => 'number', 'min' => '0'), set_value('capacidade_sala'));
        echo br(1);

        echo form_label('Quantidade de Laboratórios: ') . br();
        echo form_input(array('name' => 'quantidade_lab', 'type' => 'number', 'min' => '0'), set_value('quantidade_lab')) . br();

        echo form_label('Capacidade de cada laboratório: ') . br();
        echo form_input(array('name' => 'capacidade_lab', 'type' => 'number', 'min' => '0'), set_value('capacidade_lab')) . br();

        echo form_label('Precisa de auditório? Se sim, qual a capacidade? ') . br();
        echo form_input(array('name' => 'auditorio'), set_value('capacidade_lab'));
        echo br(1);

        echo form_label('Equipamentos (Porta-banner, etc...) - Quantidade e descrição: ') . br();
        echo form_textarea(array('name' => 'equip'), set_value('equip'));
        echo br(1);

        echo form_label('Observações: ') . br();
        echo form_textarea(array('name' => 'obs'), set_value('obs'));
        echo br(2);

        echo form_submit(array('name' => 'new_solic_encontro'), 'Criar Solicitação de Encontro Presencial');
        echo form_close();
        ?>

    </div>

    <div class="maroon box" style="display:none">

        <h3>Serviços</h3>

        <?php
        echo form_open('Ctrl_project/New_solic_servico');
        echo form_hidden('project_id', $project_id);
        echo br(1);

        echo form_label('Tipo de Serviço: ') . br();
        echo form_textarea(array('name' => 'tipo_servico', 'required' => 'required'), set_value('tipo_servico'));
        echo br(1);

        echo form_label('Motivação da Contratação (Objetivos e justificativa): ') . br();
        echo form_textarea(array('name' => 'motivacao_servico', 'required' => 'required'), set_value('motivacao_servico'));
        echo br(1);

        echo form_label('Conexão entre a contratação eo planejamento existente: ') . br();
        echo form_textarea(array('name' => 'conexao_servico', 'required' => 'required'), set_value('conexao_servico'));
        echo br(1);

        echo form_label('Prazo para execução do serviço: ') . br();
        echo form_input(array('name' => 'prazo_servico', 'type' => 'date', 'required' => 'required'), set_value('prazo_servico'));
        echo br(2);

        echo form_submit(array('name' => 'inserir_solicitacao_serviço'), 'Criar Solicitação de Serviço');
        echo form_close();
        ?>

    </div>

    <div class="magenta box"style="display:none" >

        <h3>Compras</h3>

        <?php
        echo form_open('Ctrl_project/New_solic_compra');
        echo form_hidden('project_id', $project_id);
        echo br(1);

        echo form_label('Nome do Item: ') . br();
        echo form_input(array('name' => 'item_compra', 'required' => 'required'), set_value('item_compra'));
        echo br(1);

        echo form_label('Especificação (produto/cor/tamanho/etc): ') . br();
        echo form_textarea(array('name' => 'especificacao_compra', 'required' => 'required'), set_value('especificacao_compra'));
        echo br(1);

        echo form_label('Unidade: ') . br();
        echo form_input(array('name' => 'unidade_compra', 'required' => 'required'), set_value('unidade_compra'));
        echo br(1);

        echo form_label('Quantidade: ') . br();
        echo form_input(array('name' => 'quantidade_compra', 'type' => 'number', 'min' => '1', 'required' => 'required'), set_value('quantidade_compra'));
        echo br(1);

        echo form_label('Preço Médio Unitário (R$): ') . br();
        echo form_input(array('name' => 'valor_compra', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'required' => 'required'), set_value('valor_compra'));
        echo br(1);

        echo form_label('Motivação da compra (Objetivos e justificativa): ') . br();
        echo form_textarea(array('name' => 'motivacao_compra', 'required' => 'required'), set_value('motivacao_compra'));
        echo br(1);

        echo form_label('Conexão entre a contratação e o planejamento existente: ') . br();
        echo form_textarea(array('name' => 'conexao_compra', 'required' => 'required'), set_value('conexao_compra'));
        echo br(2);

        echo form_submit(array('name' => 'inserir_solicitacao_compra'), 'Criar Solicitação de Compra');
        echo form_close();
        ?>

    </div>

</div>
