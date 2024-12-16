<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends App_Controller
{
	public function settings()
	{
		$data['view_path'] = 'pages/account/layout';
		$data['sub_view_path'] = 'pages/account/settings';
		$data['page_title'] = 'Account and Settings - Fixed Assets Application';
		$data['page_heading'] = 'Account and Settings';
		$data['navlink'] = ['main-link' => 'account', 'sub-link' => 'settings'];
		$this->load->view('layout', $data);
	}
	public function security()
	{
		$data['view_path'] = 'pages/account/layout';
		$data['sub_view_path'] = 'pages/account/security';
		$data['page_title'] = 'Security - Fixed Assets Application';
		$data['page_heading'] = 'Security';
		$data['navlink'] = ['main-link' => 'account', 'sub-link' => 'security'];
		$data['scripts'] = ['assets/js/pages/accounts/password-management.js'];
		$this->load->view('layout', $data);
	}
}
