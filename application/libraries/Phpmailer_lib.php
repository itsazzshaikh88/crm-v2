<?php
defined('BASEPATH') OR exit('no direct script access allowed');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Phpmailer_lib {
	public function __construct()
	{
		log_message('Debug', 'PHPMailer class is loaded');
	}


	public function load(){
		require_once APPPATH.'third_party/PHPMailer/Exception.php';
		require_once APPPATH.'third_party/PHPMailer/PHPMailer.php';
		require_once APPPATH . 'third_party/PHPMailer/SMTP.php';

		$mail = new PHPMailer;
		return $mail;
	}
}
