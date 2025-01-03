<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Zmailer extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function send()
    {
        // Get Input From Users
        $input = $this->input->raw_input_stream;
        $data = json_decode($input, true);
        $recipient = $data['recipient'] ?? [];

        if (empty($recipient)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Recipient details not provided.'
                ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
        $emailViewConfig = [
            'content_view' => 'account-created',
            'heading' => "Your Zamil CRM Account is created.",
        ];
        $mailContent['body'] = $this->load->view('email-templates/layout', ['emailViewConfig' => $emailViewConfig], true);
        $mailContent['subject'] = "Test Email - Zmailer Setup";
        $response = $this->send_email($recipient, $mailContent);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
}
