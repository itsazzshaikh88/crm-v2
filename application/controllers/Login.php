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
        $this->load->view('pages/login', $data);
    }
}
