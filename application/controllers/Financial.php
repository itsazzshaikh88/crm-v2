<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Financial extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['view_path'] = 'pages/financial/financial-report';
        $data['page_title'] = 'Financial - CRM Application';
        $data['page_heading'] = 'Financial';
        $data['navlink'] = 'financial';
        $data['scripts'] = ['assets/js/pages/financial/financial-list.js'];
        $this->load->view('layout', $data);
    }
    public function outstandings()
    {
        $data['view_path'] = 'pages/financial/customer-outstandings';
        $data['page_title'] = 'Customer Outstandings - CRM Application';
        $data['page_heading'] = 'Customer Outstandings';
        $data['navlink'] = 'financial';
        $data['scripts'] = ['assets/js/pages/financial/customer-outstandings-list.js'];
        $this->load->view('layout', $data);
    }
    public function statements()
    {
        $data['view_path'] = 'pages/financial/customer-statements';
        $data['page_title'] = 'Customer Statements - CRM Application';
        $data['page_heading'] = 'Customer Statements';
        $data['navlink'] = 'financial';
        $data['scripts'] = ['assets/js/pages/financial/customer-statements-list.js'];
        $this->load->view('layout', $data);
    }
    public function credit_report()
    {
        $data['view_path'] = 'pages/financial/credit-report';
        $data['page_title'] = 'Credit Report - CRM Application';
        $data['page_heading'] = 'Credit Report';
        $data['navlink'] = 'financial';
        $data['scripts'] = ['assets/js/pages/financial/credit_report.js'];
        $this->load->view('layout', $data);
    }
    public function balance_cofirmation()
    {
        $data['view_path'] = 'pages/financial/balance-confirmation';
        $data['page_title'] = 'Balance Confirmation - CRM Application';
        $data['page_heading'] = 'Balance Confirmation';
        $data['navlink'] = 'financial';
        $this->load->view('layout', $data);
    }
    public function credit_application($uuid = null)
    {

        $this->validateUUID();
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/financial/credit_application/new';
        $data['page_title'] = 'Customer Credit - CRM Application';
        $data['page_heading'] = 'Application For the Credit Facility';
        $data['css_files'] = ['assets/css/pages/financial/credit_application.css'];
        $data['scripts'] = [
            'assets/js/pages/financial/credit_application.js',
            'assets/js/pages/print/application.js'
        ];
        $data['toolbar'] = ['name' => 'list-credit', 'action' => 'form'];
        $data['navlink'] = 'financial';
        $this->load->view('layout', $data);
    }



    public function list()
    {
        $data['view_path'] = 'pages/financial/credit_application/list';
        $data['page_title'] = 'Credit - List';
        $data['page_heading'] = 'All Credit';
        $data['navlink'] = 'financial';
        $data['toolbar'] = ['name' => 'list-credit', 'action' => 'list'];
        $data['scripts'] = [
            'assets/js/pages/financial/list.js',
            'assets/js/pages/print/application.js'
        ];
        $this->load->view('layout', $data);
    }


    public function view($uuid = null)
    {
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/financial/credit_application/view';
        $data['page_title'] = 'Credit Details - CRM Application';
        $data['page_heading'] = 'Credit Details';
        $data['navlink'] = 'financial';
        $data['css_files'] = [];
        $data['scripts'] = ['assets/js/pages/financial/view.js'];
        $data['toolbar'] = ['name' => 'list-credit', 'action' => 'view'];
        $this->load->view('layout', $data);
    }
}
