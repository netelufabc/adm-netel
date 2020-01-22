
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
        echo form_input('var_value', $var->value, array('style'=>'margin:0;padding:0' , 'required' => 'required'));
        echo form_submit('alterar','Alterar');
        
        echo form_close();

        echo "</td>";
        echo "<td>";
        echo $var->info;
        echo "</td>";                
        echo "</tr>";
    }
    echo "</table>" . br();
    