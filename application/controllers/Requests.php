<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Requests extends App_Controller
{
    public function new($uuid = null)
    {
        $this->validateUUID();
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/requests/new';
        $data['page_title'] = 'Create New Request - CRM Application';
        $data['page_heading'] = 'Create New Request';
        $data['navlink'] = 'requests';
        $data['css_files'] = ['assets/css/pages/requests/new.css'];
        $data['scripts'] = [
            'assets/js/pages/clients/modals/modal-list.js',
            'assets/js/pages/clients/modals/create-new-client.js',
            'assets/js/pages/requests/new.js'
        ];
        $data['toolbar'] = ['name' => 'new-request', 'action' => 'form'];
        $this->load->view('layout', $data);
    }
    public function list()
    {
        $data['view_path'] = 'pages/requests/list';
        $data['page_title'] = 'Requests - CRM Application';
        $data['page_heading'] = 'All Requests';
        $data['navlink'] = 'requests';
        $data['toolbar'] = ['name' => 'new-request', 'action' => 'list'];
        $data['scripts'] = [
            'assets/js/pages/requests/list.js',
            'assets/js/pages/requests/new-v1.js',
            'assets/js/pages/clients/modals/modal-list.js',
            'assets/js/pages/clients/modals/create-new-client.js',
        ];
        $this->load->view('layout', $data);
    }

    public function view($uuid = null)
    {
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/requests/view';
        $data['page_title'] = 'Request Details - CRM Application';
        $data['page_heading'] = 'Request Details';
        $data['navlink'] = 'requests';
        $data['css_files'] = [];
        $data['scripts'] = ['assets/js/pages/requests/view.js'];
        $data['toolbar'] = ['name' => 'new-request', 'action' => 'view'];
        $this->load->view('layout', $data);
    }
}
