<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends App_Controller
{
    public function forecast()
    {
        $data['view_path'] = 'pages/sales/forecast';
        $data['page_title'] = 'Sales Forecast - CRM Application';
        $data['page_heading'] = 'Zamil Sales Forecast';
        $data['navlink'] = 'sales';
        $data['css_files'] = [];
        $data['scripts'] = ['assets/js/pages/sales/forecast-list.js', 'assets/js/pages/sales/new-forecast.js', 'assets/js/pages/sales/suggest-forecast.js'];
        $data['toolbar'] = ['name' => 'sales-forecast', 'action' => 'list'];
        $this->load->view('layout', $data);
    }

    public function salesperson()
    {
        $data['view_path'] = 'pages/sales/salesperson';
        $data['page_title'] = 'Sales Persons - CRM Application';
        $data['page_heading'] = 'Zamil Sales Persons';
        $data['navlink'] = 'sales';
        $data['css_files'] = [];
        $data['scripts'] = ['assets/js/pages/sales/new-salesperson.js', 'assets/js/pages/sales/salesperson-list.js'];
        $data['toolbar'] = ['name' => 'sales-person', 'action' => 'list'];
        $this->load->view('layout', $data);
    }
}
