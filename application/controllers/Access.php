<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Access extends App_Controller
{
    public function roles()
    {
        $data['view_path'] = 'pages/user-account/layout';
        $data['sub_view_path'] = 'pages/access-management/roles';
        $data['page_title'] = 'Roles - Zamil CRM';
        $data['page_heading'] = 'Roles';
        $data['navlink'] = ['main-link' => 'account', 'sub-link' => 'roles'];
        $data['scripts'] = ['assets/js/pages/access-management/roles-list.js', 'assets/js/pages/access-management/new-role.js'];
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
