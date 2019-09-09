<?php
echo "<h2>Nova solicitação: </h2>";
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        $("select").change(function () {
            var color = $(this).val();
            if (color == "red") {
                $(".box").not(".red").hide();
                $(".red").show();
            } else if (color == "green") {
                $(".box").not(".green").hide();
                $(".green").show();
            } else if (color == "blue") {
                $(".box").not(".blue").hide();
                $(".blue").show();
            } else if (color == "maroon") {
                $(".box").not(".maroon").hide();
                $(".maroon").show();
            } else if (color == "magenta") {
                $(".box").not(".magenta").hide();
                $(".magenta").show();
            } else {
                $(".box").hide();
            }
        });
    });
</script>



<div>
    <select>
        <option>Selecione</option>
        <option value="red">Contratação de Pessoal</option>
        <option value="green">Pagamento de Bolsa</option>
        <option value="blue">Encontro Presencial</option>
        <option value="maroon">Contratação de Serviços</option>
        <option value="magenta">Compras</option>
    </select>
</div>
<?php echo br(1) . "<input value=\"Cancelar\" onclick=\"JavaScript:window.history.back();\" type=\"button\">"; ?>
<br><hr><br>

<div class="form_div" align="left"><!--div para formulario alinhar à esquerda-->

    <div class="red box" style="display:none"> 

        <h3>Contratação de Pessoal</h3>

        nada aqui </div>

    <div class="green box"style="display:none">

        <h3>Pagamento de Bolsas</h3>

        <?php
        echo form_open('');
        echo form_hidden('project_id', $project_id);
        echo br(1);

        echo form_label('Mês / Ano: ') . br();
        echo form_dropdown('mes', array('' => 'Mês', '1' => 'Janeiro', '2' => 'Fevereiro', '3' => 'Março'));
        echo form_dropdown('ano', array('' => 'Ano', '2018' => '2018', '2019' => '2019', '2020' => '2020'));
        echo br(1);

        echo form_label('Tutor ou Docente? ') . br();
        echo form_input(array('name' => 'title', 'required' => 'required'), set_value('title'));
        echo br(2);

        echo form_submit(array('name' => 'inserir_solicitacao_bolsa'), 'Criar Solicitação de Pagamento de Bolsa');
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
        echo form_open('');
        echo form_hidden('project_id', $project_id);
        echo br(1);

        echo form_label('Tipo de Serviço: ') . br();
        echo form_input(array('name' => 'tipo_servico', 'required' => 'required'), set_value('tipo_servico'));
        echo br(1);

        echo form_label('Motivação da Contratação (Objetivos e justificativa): ') . br();
        echo form_textarea(array('name' => 'motivacao', 'required' => 'required'), set_value('motivacao'));
        echo br(1);

        echo form_label('Conexão entre a contratação eo planejamento existente: ') . br();
        echo form_textarea(array('name' => 'conexao', 'required' => 'required'), set_value('conexao'));
        echo br(1);

        echo form_label('Prazo para execução do serviço: ') . br();
        echo form_input(array('name' => 'prazo', 'type' => 'date', 'required' => 'required'), set_value('prazo'));
        echo br(2);

        echo form_submit(array('name' => 'inserir_solicitacao_serviço'), 'Criar Solicitação de Serviço');
        echo form_close();
        ?>

    </div>

    <div class="magenta box"style="display:none" >

        <h3>Compras</h3>

        <?php
        echo form_open('');
        echo form_hidden('project_id', $project_id);
        echo br(1);

        echo form_label('Nome do Item: ') . br();
        echo form_input(array('name' => 'item', 'required' => 'required'), set_value('item'));
        echo br(1);

        echo form_label('Especificação (produto/cor/tamanho/etc): ') . br();
        echo form_textarea(array('name' => 'especificacao', 'required' => 'required'), set_value('especificacao'));
        echo br(1);

        echo form_label('Unidade: ') . br();
        echo form_input(array('name' => 'unidade', 'required' => 'required'), set_value('unidade'));
        echo br(1);

        echo form_label('Quantidade: ') . br();
        echo form_input(array('name' => 'quantidade', 'type' => 'number', 'min' => '1', 'required' => 'required'), set_value('quantidade'));
        echo br(1);

        echo form_label('Preço Médio Unitário (R$): ') . br();
        echo form_input(array('name' => 'preco', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'required' => 'required'), set_value('quantidade'));
        echo br(1);

        echo form_label('Motivação da compra (Objetivos e justificativa): ') . br();
        echo form_textarea(array('name' => 'motivacao', 'required' => 'required'), set_value('motivacao'));
        echo br(1);

        echo form_label('Conexão entre a contratação e o planejamento existente: ') . br();
        echo form_textarea(array('name' => 'conexao', 'required' => 'required'), set_value('conexao'));
        echo br(2);

        echo form_submit(array('name' => 'inserir_solicitacao_compra'), 'Criar Solicitação de Compra');
        echo form_close();
        ?>

    </div>

</div>

</div>