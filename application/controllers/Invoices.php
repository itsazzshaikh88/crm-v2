<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoices extends App_Controller
{
    public function list()
    {
        $data['view_path'] = 'pages/invoices/list';
        $data['page_title'] = 'Invoices - CRM Application';
        $data['page_heading'] = 'Invoices';
        $data['navlink'] = 'invoices';
        $this->load->view('layout', $data);
    }
}
