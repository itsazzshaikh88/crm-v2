<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends App_Controller
{
    public function new($uuid = null)
    {
        $this->validateUUID();
        $data['uuid'] = $uuid;

        if ($this->input->get('version') == '2')
            $data['view_path'] = 'pages/products/new-v2';
        else
            $data['view_path'] = 'pages/products/new';
        $data['page_title'] = 'Add New Product - CRM Application';
        $data['page_heading'] = 'Add New Product';
        $data['navlink'] = 'product';
        $data['css_files'] = ['assets/css/pages/products/new.css'];
        $data['toolbar'] = ['name' => 'new-product', 'action' => 'form'];
        $data['scripts'] = ['assets/js/pages/products/new.js'];
        $this->load->view('layout', $data);
    }
    public function list()
    {
        $data['version'] = $this->input->get('version');
        $data['view_path'] = 'pages/products/list';
        $data['page_title'] = 'Product Listing - CRM Application';
        $data['page_heading'] = 'All Products';
        $data['navlink'] = 'products';
        $data['css_files'] = [];
        $data['scripts'] = ['assets/js/pages/products/list.js', 'assets/js/pages/products/new-v3.js'];
        $data['toolbar'] = ['name' => 'new-product', 'action' => 'list', 'version' => $data['version']];
        $this->load->view('layout', $data);
    }

    public function view($uuid = null)
    {
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/products/view';
        $data['page_title'] = 'Product Details - CRM Application';
        $data['page_heading'] = 'Product Details';
        $data['navlink'] = 'products';
        $data['css_files'] = [];
        $data['scripts'] = ['assets/js/pages/products/view.js'];
        $data['toolbar'] = ['name' => 'new-product', 'action' => 'view'];
        $this->load->view('layout', $data);
    }
}
