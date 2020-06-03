<?php

if (!isset($view_header)) {
    $this->load->view('View_header.php');    
} else {
    $this->load->view($view_header);
}

if (!isset($view_menu)) {
    //echo br(1)."Menu not set";
    $this->load->view('View_menu_not_logged.php');
} else {
    $this->load->view($view_menu);
}

if (!isset($view_content)) {
    echo br(1)."Content not set";
    $this->load->view('View_content_not_logged.php');
} else {
    $this->load->view($view_content);
}

if (!isset($view_footer)) {
    $this->load->view('View_footer.php');
} else {
    $this->load->view($view_footer);
}


