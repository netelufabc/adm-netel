
<?php

echo "<h3>Configurações do sistema:</h3>";

if ($this->session->flashdata('config_ok')) {
    echo "<div class=\"message_success\">";
    echo $this->session->flashdata('config_ok');
    echo "</div><br>";
}

?>

<table id="allSolic" class="tabela">
    <thead>
        <tr>
            <th>VARIÁVEL</th>
            <th>VALOR</th>
            <th>INFO</th>

        </tr>
    </thead>
    <?php

    foreach ($config as $var) {
        echo "<tr>";
        echo "<td>";
        echo $var->name;
        echo "</td>";

        echo form_open('Ctrl_administrativo/Change_config');

        echo "<td>";

        echo form_hidden('var_name', $var->name);
        echo form_input('var_value', $var->value, array('style' => 'margin:0;padding:0', 'required' => 'required'));
        echo form_submit('alterar', 'Alterar');

        echo form_close();

        echo "</td>";
        echo "<td>";
        echo $var->info;
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>" . br();

    #Rafael
    if (HasRole(1)) {

        if ($this->session->flashdata('new_role_ok')) {
            echo "<div class=\"message_success\">";
            echo $this->session->flashdata('new_role_ok');
            echo "</div><br>";
        }

        if ($this->session->flashdata('new_role_failed')) {
            echo "<div class=\"message_error\">";
            echo $this->session->flashdata('new_role_failed');
            echo "</div><br>";
        }

        echo br(1) . "Adicionar usuário como Administrador Netel:" . br(1);
        echo form_open("Ctrl_administrativo/Add_adm_netel_role");
        echo form_dropdown('netel_Adm', $lista_users);
        echo form_submit(array('name' => 'inserir_netel_Adm'), 'Adicionar');
        echo form_close();
    }
    #END_Rafael