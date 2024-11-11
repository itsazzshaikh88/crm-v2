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
        // $data['css_files'] = ['assets/css/pages/requests/new.css'];
        $data['scripts'] = ['assets/js/pages/clients/modals/modal-list.js', 'assets/js/pages/requests/new.js'];
        $data['toolbar'] = 'new-request';
        $this->load->view('layout', $data);
    }
    public function list()
    {
        $data['view_path'] = 'pages/requests/list';
        $data['page_title'] = 'Requests - CRM Application';
        $data['page_heading'] = 'All Requests';
        $data['navlink'] = 'requests';
        $this->load->view('layout', $data);
    }
}
