<?php

echo "Usuário autenticado: ".$this->session->userdata('nome');
echo br(2);

//echo "<div>";
//echo "<ul> ";
foreach ($menu_item as $key => $value) {
    //echo "<li>";    
    echo anchor($key, $value);
    //echo "</li>";
    echo " | ";
}
//echo "</ul>";
//echo "</div>";

echo anchor('Ctrl_login/logout','Sair');

echo br(2);
echo "<hr>";
