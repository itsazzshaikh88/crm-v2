<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Survey extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/survey/home';
        $data['page_title'] = 'Customer Survey - CRM Application';
        $data['page_heading'] = 'Customer Survey';
        $data['navlink'] = 'survey';
        $this->load->view('layout', $data);
    }
    public function new()
    {
        $this->validateUUID();
        $data['view_path'] = 'pages/survey/new';
        $data['page_title'] = 'Create New Survey - CRM Application';
        $data['page_heading'] = 'Create New Survey';
        $data['navlink'] = 'survey';
        $this->load->view('layout', $data);
    }
    public function list()
    {
        $data['view_path'] = 'pages/survey/list';
        $data['page_title'] = 'Survey List - CRM Application';
        $data['page_heading'] = 'Zamil Customer Survey List';
        $data['navlink'] = 'survey';
        $this->load->view('layout', $data);
    }
    public function response()
    {
        $data['view_path'] = 'pages/survey/response';
        $data['page_title'] = 'Survey Feedbacks - CRM Application';
        $data['page_heading'] = 'Customer Feedback - Survey';
        $data['navlink'] = 'survey';
        $this->load->view('layout', $data);
    }
}
