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
        // $data['scripts'] = [
        //     'assets/js/pages/clients/modals/modal-list.js',
        //     'assets/js/pages/clients/modals/create-new-client.js',
        //     'assets/js/pages/quotes/new.js'
        // ];

        $data['toolbar'] = ['name' => 'new-quote', 'action' => 'form'];
        $this->load->view('layout', $data);
    }
    public function list()
    {
        $data['view_path'] = 'pages/quotes/list';
        $data['page_title'] = 'Quaotations - CRM Application';
        $data['page_heading'] = 'All Quotations';
        $data['navlink'] = 'quotes';
        $data['toolbar'] = ['name' => 'new-quote', 'action' => 'list'];
        $data['scripts'] = [
            'assets/js/pages/quotes/new-v1.js',
            'assets/js/pages/clients/modals/modal-list.js',
            'assets/js/pages/quotes/list.js',
            'assets/js/pages/clients/modals/create-new-client.js',
        ];

        $this->load->view('layout', $data);
    }
    public function view($uuid = null)
    {
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/quotes/view';
        $data['page_title'] = 'Quote Details - CRM Application';
        $data['page_heading'] = 'Quote Details';
        $data['navlink'] = 'quotes';
        $data['css_files'] = [];
        $data['scripts'] = ['assets/js/pages/quotes/view.js'];
        $data['toolbar'] = ['name' => 'new-quote', 'action' => 'view'];
        $this->load->view('layout', $data);
    }
}
