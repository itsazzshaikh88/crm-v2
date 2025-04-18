<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends App_Controller
{
	public function index()
	{
		$data['view_path'] = 'pages/account/layout';
		$data['sub_view_path'] = 'pages/account/overview';
		$data['page_title'] = 'Profile Details - Zamil CRM';
		$data['page_heading'] = 'Profile Details';
		$data['navlink'] = ['main-link' => 'account', 'sub-link' => 'overview'];
		$data['scripts'] = ['assets/js/pages/accounts/overview.js'];
		$this->load->view('layout', $data);
	}
	public function settings()
	{
		$data['view_path'] = 'pages/account/layout';
		$data['sub_view_path'] = 'pages/account/settings';
		$data['page_title'] = 'Account and Settings - Zamil CRM';
		$data['page_heading'] = 'Account and Settings';
		$data['navlink'] = ['main-link' => 'account', 'sub-link' => 'settings'];
		$this->load->view('layout', $data);
	}
	public function security()
	{
		$data['view_path'] = 'pages/account/layout';
		$data['sub_view_path'] = 'pages/account/security';
		$data['page_title'] = 'Security - Zamil CRM';
		$data['page_heading'] = 'Security';
		$data['navlink'] = ['main-link' => 'account', 'sub-link' => 'security'];
		$data['scripts'] = ['assets/js/pages/accounts/password-management.js'];
		$this->load->view('layout', $data);
	}
	public function activities()
	{
		$data['view_path'] = 'pages/account/activities';
		$data['page_title'] = 'Account Activities - Zamil CRM';
		$data['page_heading'] = 'Account Activities';
		$data['navlink'] = ['main-link' => 'account', 'sub-link' => 'activities'];
		$data['scripts'] = ['assets/js/pages/accounts/activities.js'];
		$this->load->view('layout', $data);
	}
}
