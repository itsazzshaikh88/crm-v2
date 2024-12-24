<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Delivery extends App_Controller
{
    public function asn()
    {
        $this->validateUUID();
        $data['view_path'] = 'pages/delivery/create-asn';
        $data['page_title'] = 'Create New ASN - CRM Application';
        $data['page_heading'] = 'Create New ASN';
        $data['navlink'] = 'delivery';
        $this->load->view('layout', $data);
    }
    public function report()
    {
        $data['view_path'] = 'pages/delivery/report';
        $data['page_title'] = 'Delivery Reports - CRM Application';
        $data['page_heading'] = 'Delivery Report';
        $data['navlink'] = 'delivery';
        $data['scripts'] = ['assets/js/pages/delivery/list.js'];
        $this->load->view('layout', $data);
    }
    public function asn_report()
    {
        $data['view_path'] = 'pages/delivery/asn_report';
        $data['page_title'] = 'ASN Reports - CRM Application';
        $data['page_heading'] = 'All ASN';
        $data['navlink'] = 'delivery';
        $this->load->view('layout', $data);
    }
}
