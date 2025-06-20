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

    public function resources()
    {
        $data['view_path'] = 'pages/user-account/layout';
        $data['sub_view_path'] = 'pages/access-management/resources';
        $data['page_title'] = 'Resource Management - Zamil CRM';
        $data['page_heading'] = 'Resource Management';
        $data['navlink'] = ['main-link' => 'account', 'sub-link' => 'resources'];
        $data['scripts'] = ['assets/js/pages/access-management/resource-list.js', 'assets/js/pages/access-management/new-resource.js'];
        $this->load->view('layout', $data);
    }

    public function permissions()
    {
        $data['view_path'] = 'pages/user-account/layout';
        $data['sub_view_path'] = 'pages/access-management/permissions';
        $data['page_title'] = 'User Resource Permissions - Zamil CRM';
        $data['page_heading'] = 'User Resource Permissions';
        $data['navlink'] = ['main-link' => 'account', 'sub-link' => 'permission'];
        $data['scripts'] = ['assets/js/pages/access-management/permissions-list.js', 'assets/js/pages/access-management/new-permission.js'];
        $this->load->view('layout', $data);
    }
}
