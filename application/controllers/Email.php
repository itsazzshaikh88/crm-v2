<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('phpmailer_lib');
    }

    function send()
    {
        $emailViewConfig = [
            'content_view' => 'account-created',
            'heading' => "Your Zamil CRM Account is created.",
        ];
        $mailContent = $this->load->view('email-templates/layout', ['emailViewConfig' => $emailViewConfig], true);

        $result = $this->send_email([
            'to'        => 'shaikh.azim@olivesofts.com',
            'subject'   => 'Test PHPMailer Email',
            'message'   => $mailContent,
            'from'      => 'workflowmailer@zamilplastic.com',   // Optional
            'from_name' => 'WorkFlow Mailer',                   // Optional
        ]);

        if ($result['success']) {
            echo "Email sent successfully to: " . $result['details']['to'];
        } else {
            echo "Email failed: " . $result['error'];
        }
        echo "</br>";

        beautify_array($result);
    }

    function view()
    {
        $emailViewConfig = [
            'content_view' => 'task-comment',
            'heading' => "Task Manager - New Comment added",
        ];
        $this->load->view('email-templates/layout', ['emailViewConfig' => $emailViewConfig]);
    }
}
