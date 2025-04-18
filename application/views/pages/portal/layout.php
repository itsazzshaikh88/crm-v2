<?php
$this->load->view('pages/portal/partials/header');
if (isset($view_path) && file_exists(APPPATH . "views/" . $view_path . ".php"))
    $this->load->view($view_path);
$this->load->view('pages/portal/partials/footer');
