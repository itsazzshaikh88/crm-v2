<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/projects/list';
        $data['page_title'] = 'Projects - Zamil CRM';
        $data['page_heading'] = 'Projects';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'projects'];
        $data['scripts'] = ['assets/js/pages/projects/list.js'];
        $data['toolbar'] = ['name' => 'projects', 'action' => 'list'];
        $this->load->view('layout', $data);
    }

    public function new()
    {
        $data['view_path'] = 'pages/projects/new';
        $data['page_title'] = 'Create or Add New Project - CRM Application';
        $data['page_heading'] = 'Create or Add New Project';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'projects'];
        $data['scripts'] = [
            'assets/js/pages/projects/new-project.js'
        ];
        $data['toolbar'] = ['name' => 'new-project', 'action' => 'form'];
        $this->load->view('layout', $data);
    }
}
