<?php

echo "<h2>Login as</h2>";

echo form_open('Ctrl_sysadmin/Login_as');
echo form_dropdown('user_id', $users);
echo form_dropdown('role_id', $roles);
echo form_submit('loginas_submit', 'Go!');
echo form_close();

