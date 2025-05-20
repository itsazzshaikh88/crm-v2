<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tasks extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/tasks/list';
        $data['page_title'] = 'Zamil Task Manager - CRM Application';
        $data['page_heading'] = 'Zamil Task Manager';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'tasks'];
        $data['css_files'] = [];
        $data['scripts'] = ['assets/js/pages/salespersons/modals/list.js', 'assets/js/pages/tasks/common.js', 'assets/js/pages/tasks/list.js', 'assets/js/pages/tasks/new.js'];
        $data['toolbar'] = ['name' => 'task-manager', 'action' => 'list'];
        $this->load->view('layout', $data);
    }

    public function details($taskId)
    {
        $data['task_id'] = $taskId;
        $data['view_path'] = 'pages/tasks/details'; // Create this view file
        $data['page_title'] = 'Task Details - Zamil Task Manager';
        $data['page_heading'] = 'Task Details';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'tasks'];
        $data['css_files'] = []; // Add CSS if needed
        $data['scripts'] = [
            'assets/js/pages/tasks/details.js'
        ];
        $data['toolbar'] = ['name' => 'task-manager', 'action' => 'details'];

        $this->load->view('layout', $data);
    }
}
