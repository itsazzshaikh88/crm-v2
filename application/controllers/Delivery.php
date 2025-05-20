<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Delivery extends App_Controller
{
    public function report()
    {
        $data['view_path'] = 'pages/delivery/report';
        $data['page_title'] = 'Delivery Reports - CRM Application';
        $data['page_heading'] = 'Delivery Report';
        $data['navlink'] = 'delivery';
        $data['scripts'] = ['assets/js/pages/delivery/list.js'];
        $this->load->view('layout', $data);
    }

    public function receipt()
    {
        $data['scripts'] = ['assets/js/pages/delivery/list.js'];
        $this->load->view('pages/delivery/receipt', $data);
    }
}
