<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['account'] = $this->input->get('account') ?? 'admin-central';
        $data['view_path'] = 'pages/login';
        $data['page_title'] = 'Login - Zamil CRM';
        $data['navlink'] = 'login';
        $data['scripts'] = ['assets/js/pages/login.js'];
        $this->load->view('front/layout', $data);
    }

    public function auth()
    {
        $data['account'] = $this->input->get('account') ?? 'admin-central';
        $data['view_path'] = 'pages/portal/login';
        $data['page_title'] = 'Login - Zamil CRM';
        $data['navlink'] = 'login';
        $data['scripts'] = ['assets/js/pages/login.js'];
        $this->load->view('pages/portal/layout', $data);
    }
}
