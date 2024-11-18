<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends App_Controller
{
	public function index()
	{
		$data['view_path'] = 'pages/home';
		$data['page_title'] = 'Home - Fixed Assets Application';
		$data['page_heading'] = 'Dashboard';
		$data['navlink'] = 'home';
		$data['scripts'] = ['assets/js/pages/dashboard/stats.js'];
		$this->load->view('layout', $data);
	}

	public function not_found()
	{
		$data['view_path'] = 'pages/not_found';
		$data['page_title'] = 'Page Not Found - Fixed Assets Application';
		$data['page_heading'] = 'Page Not Found';
		$data['navlink'] = 'home';
		$this->load->view('layout', $data);
	}
}
