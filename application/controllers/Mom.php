<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mom extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/mom/list';
        $data['page_title'] = 'Minutes of Meetings - Fixed Assets Application';
        $data['page_heading'] = 'Minutes of Meetings';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'mom'];
        $data['scripts'] = ['assets/js/pages/mom/list.js', 'assets/js/pages/mom/new.js'];
        $data['toolbar'] = ['name' => 'mom', 'action' => 'list'];
        $this->load->view('layout', $data);
    }
}
