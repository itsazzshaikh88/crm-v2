<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Quotes extends App_Controller
{
    public function new()
    {
        $this->validateUUID();
        $data['view_path'] = 'pages/quotes/new';
        $data['page_title'] = 'Create New Quote - CRM Application';
        $data['page_heading'] = 'Create New Quote';
        $data['navlink'] = 'quotes';
        $this->load->view('layout', $data);
    }
    public function list()
    {
        $data['view_path'] = 'pages/quotes/list';
        $data['page_title'] = 'Quaotations - CRM Application';
        $data['page_heading'] = 'Quotations';
        $data['navlink'] = 'quotes';
        $this->load->view('layout', $data);
    }
}
