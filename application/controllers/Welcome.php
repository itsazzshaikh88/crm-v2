<?php
defined('BASEPATH') or exit('No direct script access allowed');

use RobThree\Auth\TwoFactorAuth; // Import the class using the full namespace

class Welcome extends App_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->tfa = new TwoFactorAuth('Olive Authenticator'); // Replace with your app name
	}
	public function index()
	{
		$data['view_path'] = 'pages/home';
		$data['page_title'] = 'Home - Zamil CRM';
		$data['page_heading'] = 'Dashboard';
		$data['navlink'] = HOME_ACTIVE_LINK;
		$data['scripts'] = ['assets/js/pages/dashboard/stats.js', 'assets/js/pages/dashboard/tracker.js'];
		$this->load->view('layout', $data);
	}

	public function not_found()
	{
		$data['view_path'] = 'pages/not_found';
		$data['page_title'] = 'Page Not Found - Zamil CRM';
		$data['page_heading'] = 'Page Not Found';
		$data['navlink'] = HOME_ACTIVE_LINK;
		$this->load->view('layout', $data);
	}
	public function generate()
	{

		$this->session->unset_userdata('secret');

		$secret = $this->tfa->createSecret();
		$this->session->set_userdata('secret', $secret);
		$qrCodeUrl = $this->tfa->getQRCodeImageAsDataUri('Olive Authenticator', $secret);
		$data['secret'] = $secret;
		$data['qrCodeUrl'] = $qrCodeUrl;

		$this->load->view('totp_setup', $data);
	}

	public function validate()
	{
		$secret = $this->session->userdata('secret');
		$otp = $this->input->post('otp');

		if ($this->tfa->verifyCode($secret, $otp)) {
			echo "OTP is valid!";
		} else {
			echo "Invalid OTP!";
		}
	}

	function generateTOTPBackupCodes($numCodes = 10)
	{
		$codes = [];
		for ($i = 0; $i < $numCodes; $i++) {
			$codes[] = ['code' => strtoupper(bin2hex(random_bytes(4))), 'is_used' => 'no'];  // Generates a random 8-character code
		}
		return json_encode($codes);
	}
}
