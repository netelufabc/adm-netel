<?php

echo "Usuário autenticado: " . $this->session->userdata('nome') . " (" . $this->session->userdata('login') . ") - " . $this->session->userdata('role');
echo br(2);

foreach ($menu_item as $key => $value) {

    echo anchor($key, $value);
    echo " | ";
}

echo anchor('Ctrl_login/logout', 'Sair');

echo br(2);
echo "<hr>";
