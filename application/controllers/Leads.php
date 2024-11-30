<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Leads extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/leads/list';
        $data['page_title'] = 'Leads - Fixed Assets Application';
        $data['page_heading'] = 'Leads';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'leads'];
        $data['scripts'] = ['assets/js/pages/leads/list.js', 'assets/js/pages/leads/new-lead.js', 'assets/js/pages/activities/add-activities.js'];
        $data['toolbar'] = ['name' => 'lead', 'action' => 'list'];
        $this->load->view('layout', $data);
    }
}
