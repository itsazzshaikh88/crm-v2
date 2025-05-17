<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/clients/list';
        $data['page_title'] = 'Clients - Zamil CRM';
        $data['page_heading'] = 'Our Clients';
        $data['navlink'] = 'admin-suite';
        $data['toolbar'] = ['name' => 'clients', 'action' => 'list'];
        $data['scripts'] = ['assets/js/pages/clients/list.js'];
        $this->load->view('layout', $data);
    }

    public function new()
    {
        $this->validateUUID();
        $data['view_path'] = 'pages/clients/new';
        $data['page_title'] = 'Add New Clients - CRM Application';
        $data['page_heading'] = 'Add New Clients';
        $data['navlink'] = 'admin';
        $data['toolbar'] = ['name' => 'clients', 'action' => 'form'];
        $data['scripts'] = ['assets/js/pages/clients/new.js'];
        $this->load->view('layout', $data);
    }

    public function view($uuid = null)
    {
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/clients/view';
        $data['page_title'] = 'Client Details - CRM Application';
        $data['page_heading'] = 'Client Details';
        $data['navlink'] = 'admin';
        $data['css_files'] = [];
        $data['toolbar'] = ['name' => 'clients', 'action' => 'view'];
        $data['scripts'] = ['assets/js/pages/clients/view.js'];
        $this->load->view('layout', $data);
    }
}
