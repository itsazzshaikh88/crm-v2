<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends App_Controller
{
    public function categories()
    {
        $data['view_path'] = 'pages/master/categories';
        $data['page_title'] = 'Manage Categories - Zamil CRM';
        $data['page_heading'] = 'Manage Categories';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'categories'];
        $data['scripts'] = ['assets/js/pages/masters/category-list.js', 'assets/js/pages/masters/new-category.js'];
        $this->load->view('layout', $data);
    }
}
