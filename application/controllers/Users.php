<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends App_Controller
{
    /**
     * Display the security settings page.
     *
     * This method loads the security settings view with the necessary data.
     */
    public function index()
    {
        $data['view_path'] = 'pages/user-account/layout';
        $data['sub_view_path'] = 'pages/user-account/user-list';
        $data['page_title'] = 'Users - Zamil CRM';
        $data['page_heading'] = 'Users';
        $data['navlink'] = ['main-link' => 'account', 'sub-link' => 'users'];
        $data['scripts'] = ['assets/js/pages/user-account/user-list.js', 'assets/js/pages/user-account/new-user.js'];
        $this->load->view('layout', $data);
    }

    public function reset_password()
    {
        $data['view_path'] = 'pages/user-account/layout';
        $data['sub_view_path'] = 'pages/user-account/user-list';
        $data['page_title'] = 'Reset User Password - Zamil CRM';
        $data['page_heading'] = 'Reset User Password';
        $data['navlink'] = ['main-link' => 'account', 'sub-link' => 'reset-password'];
        $data['scripts'] = ['assets/js/pages/user-account/user-list.js', 'assets/js/pages/user-account/reset-password.js'];
        $this->load->view('layout', $data);
    }
}
