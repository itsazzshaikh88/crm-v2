<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activities extends App_Controller
{
    public function logs()
    {
        $data['view_path'] = 'pages/account/activities';
        $data['page_title'] = 'Account Activities - Zamil CRM';
        $data['page_heading'] = 'Account Activities';
        $data['navlink'] = ['main-link' => '', 'sub-link' => ''];
        $data['scripts'] = ['assets/js/pages/accounts/activities.js'];
        $this->load->view('layout', $data);
    }
}
