<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase extends App_Controller
{
    public function new()
    {
        $this->validateUUID();
        $data['view_path'] = 'pages/purchase/new';
        $data['page_title'] = 'Create New Purchase Order - CRM Application';
        $data['page_heading'] = 'Create New Purchase Order';
        $data['navlink'] = 'purchase';
        $this->load->view('layout', $data);
    }
    public function list()
    {
        $data['view_path'] = 'pages/purchase/list';
        $data['page_title'] = 'PO List - CRM Application';
        $data['page_heading'] = 'Purchase Order List';
        $data['navlink'] = 'purchase';
        $this->load->view('layout', $data);
    }
}
