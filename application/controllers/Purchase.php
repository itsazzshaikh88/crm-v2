<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase extends App_Controller
{
    public function new($uuid = null)
    {
        $this->validateUUID();
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/purchase/new';
        $data['page_title'] = 'Create New Purchase Order - CRM Application';
        $data['page_heading'] = 'Create New Purchase Order';
        $data['navlink'] = 'purchase';
        $data['scripts'] = [
            'assets/js/pages/clients/modals/modal-list.js',
            'assets/js/pages/clients/modals/create-new-client.js',
            'assets/js/pages/purchase/new.js'
        ];
        $data['toolbar'] = ['name' => 'new-purchase', 'action' => 'form'];
        $this->load->view('layout', $data);
    }
    public function list()
    {
        $data['view_path'] = 'pages/purchase/list';
        $data['page_title'] = 'PO List - CRM Application';
        $data['page_heading'] = 'Purchase Order List';
        $data['navlink'] = 'purchase';
        $data['scripts'] = ['assets/js/pages/purchase/list.js'];
        $data['toolbar'] = ['name' => 'new-purchase', 'action' => 'list'];


        $this->load->view('layout', $data);
    }
    public function view($uuid = null)
    {
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/purchase/view';
        $data['page_title'] = 'purchase Details - CRM Application';
        $data['page_heading'] = 'purchase Details';
        $data['navlink'] = 'requests';
        $data['css_files'] = [];
        $data['scripts'] = ['assets/js/pages/purchase/view.js'];
        $data['toolbar'] = ['name' => 'new-purchase', 'action' => 'view'];
        $this->load->view('layout', $data);
    }
}
