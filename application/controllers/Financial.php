<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Financial extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/financial/financial-report';
        $data['page_title'] = 'Financials - CRM Application';
        $data['page_heading'] = 'Financials';
        $data['navlink'] = 'financial';
        $this->load->view('layout', $data);
    }
    public function outstandings()
    {
        $data['view_path'] = 'pages/financial/customer-outstandings';
        $data['page_title'] = 'Customer Outstandings - CRM Application';
        $data['page_heading'] = 'Customer Outstandings';
        $data['navlink'] = 'financial';
        $this->load->view('layout', $data);
    }
    public function statements()
    {
        $data['view_path'] = 'pages/financial/customer-statements';
        $data['page_title'] = 'Customer Statements - CRM Application';
        $data['page_heading'] = 'Customer Statements';
        $data['navlink'] = 'financial';
        $this->load->view('layout', $data);
    }
    public function credit_report()
    {
        $data['view_path'] = 'pages/financial/credit-report';
        $data['page_title'] = 'Credit Report - CRM Application';
        $data['page_heading'] = 'Credit Report';
        $data['navlink'] = 'financial';
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
    public function credit_application()
    {
        $data['view_path'] = 'pages/financial/credit-application';
        $data['page_title'] = 'Customer Credit Application - CRM Application';
        $data['page_heading'] = 'Customer Credit Application';
        $data['navlink'] = 'financial';
        $this->load->view('layout', $data);
    }
}
