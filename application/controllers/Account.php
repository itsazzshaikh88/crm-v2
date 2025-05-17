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
	public function settings($navlink = null)
	{
		$data['view_path'] = 'pages/account/layout';
		$data['sub_view_path'] = 'pages/account/settings/layout';
		$data['navlink_view'] = $navlink;
		$data['page_title'] = $this->_get_setting_page_details($navlink, 'page_title');
		$data['page_heading'] = $this->_get_setting_page_details($navlink, 'page_heading');
		$data['navlink'] = ['main-link' => 'account', 'sub-link' => 'settings', 'navlink' => $navlink];
		if ($navlink != null)
			$data['scripts'] = ['assets/js/pages/accounts/settings/' . $navlink . ".js"];
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

	public function profile()
	{
		$data['view_path'] = 'pages/account/layout';
		$data['sub_view_path'] = 'pages/account/update-profile';
		$data['page_title'] = 'Update Profile - Zamil CRM';
		$data['page_heading'] = 'Update Profile';
		$data['navlink'] = ['main-link' => 'account', 'sub-link' => 'overview'];
		$data['scripts'] = ['assets/js/pages/accounts/update-profile.js'];
		$this->load->view('layout', $data);
	}


	// Internal functions to be called here
	function _get_setting_page_details($navlink, $key)
	{
		$navlinks = [
			'login-activities' => [
				'page_title' => "User Login Activities - Zamil CRM",
				'page_heading' => "User Login Activities",
			]
		];

		return $navlinks[$navlink][$key] ?? "Zamil CRM";
	}
}
