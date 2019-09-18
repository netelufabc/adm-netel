<?php

echo br();
echo "<h3>Múltiplos papéis cadastrados, selecione um:</h3>" . br();

foreach ($user_data as $value) {
    echo "<div style=\"background-color: lightgrey;\">";
    echo form_open('Ctrl_login/Set_role');
    echo form_hidden('user_id', $value->id);
    echo form_hidden('login', $value->login);
    echo form_hidden('name', $value->name);
    echo form_hidden('email', $value->email);
    echo form_hidden('role', $value->role_id);
    echo "$value->title - $value->description - ";
    echo form_submit(array('name' => 'Ctrl_login'), 'Go!');
    echo form_close();
    echo "</div>";
    echo br();
}

