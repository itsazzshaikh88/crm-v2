<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Complaints extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/complaints/home';
        $data['page_title'] = 'Customer Complaints - CRM Application';
        $data['page_heading'] = 'Customer Complaints';
        $data['navlink'] = 'feedback';
        $this->load->view('layout', $data);
    }
    public function list()
    {
        $data['view_path'] = 'pages/requests/list';
        $data['page_title'] = 'Requests - CRM Application';
        $data['page_heading'] = 'All Requests';
        $data['navlink'] = 'quotes';
        $this->load->view('layout', $data);
    }
}
