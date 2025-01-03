<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('phpmailer_lib');
    }

    protected function isAuthorized()
    {
        // Get the Authorization header
        $headers = $this->input->request_headers();

        // Check if the Authorization header exists and is not empty
        if (!isset($headers['Authorization']) || empty(trim($headers['Authorization']))) {
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Authorization Header Missing',
                'message' => 'The Authorization header is missing from the request or does not contain a token. Please include the header to access the API.'
            ]);
            return ['status' => false];
        }

        // Extract the token
        $authHeader = trim($headers['Authorization']); // Trim to avoid extra spaces
        // Check for valid token format
        if (strpos($authHeader, ' ') === false) {
            // If there is no space in the header, it's not valid
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Invalid Token Format',
                'message' => 'The Authorization header must be in the format "Bearer <token>".'
            ]);
            return ['status' => false];
        }

        // Assuming the format is "Bearer <token>"
        list($type, $token) = explode(" ", $authHeader, 2);



        // Check if the token type is Bearer
        if (strcasecmp($type, 'Bearer') !== 0) {
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Invalid Token Type',
                'message' => "The token type must be 'Bearer'. Please provide a valid authorization token."
            ]);
            return ['status' => false];
        }

        // Query the database to check for the token and get USER_TYPE from xx_crm_users
        $this->db->select('t.TOKEN, t.EXPIRY, t.USER_ID, u.USER_TYPE');
        $this->db->from('xx_crm_authtokens t');
        $this->db->join('xx_crm_users u', 't.USER_ID = u.ID');
        $this->db->where('t.TOKEN', $token);
        $this->db->where('t.TOKEN_TYPE', 'auth');
        $query = $this->db->get();

        // Check if the token exists and is valid
        if ($query->num_rows() === 0) {
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Token Not Found',
                'message' => "The provided token is invalid or does not exist. Please check the token and try again."
            ]);
            return ['status' => false];
        }

        $row = $query->row();
        // Check if the token is expired
        if (time() >= $row->EXPIRY) {
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Token Expired',
                'message' => "The token has expired. Please obtain a new token to continue accessing the API."
            ]);
            return ['status' => false];
        }

        return ['status' => true, 'userid' => $row->USER_ID, 'role' => $row->USER_TYPE];
    }


    function sendHTTPResponse($statusCode, $response)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($statusCode)
            ->set_output(json_encode($response));
        return;
    }

    function send_email($recipient = [], $emailContent = [])
    {
        // Get Email Configuration to send an email
        $emailConfig = $this->Setup_model->getEmailConfig();
        if (empty($emailConfig)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Email Configuration is not configured.'
                ]));
        }
        if (empty($emailContent)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Email Content not provided.'
                ]));
        }


        //php mailer object
        $mail = $this->phpmailer_lib->load();
        //smtp configuration
        $mail->isSMTP(); // whether it is SMTP or not
        $mail->Host = $emailConfig['SMTP_SERVER'] ?? '';  // hostname
        $mail->SMTPAuth = true;  // authentication parameter 
        $mail->Username = $emailConfig['FROM_EMAIL'] ?? '';    //username of hostname
        $mail->Password = base64_decode($emailConfig['PASSWORD'] ?? '');    //password

        $mailProtocol = '';
        $mailPort = '';
        if (isset($emailConfig['USE_TLS']) && $emailConfig['USE_TLS']  == 1) {
            $mailProtocol = 'tls';
            $mailPort = 587;
        }

        $mail->SMTPSecure = $mailProtocol;    //connection type
        $mail->Port = $mailPort;    //port number

        $mail->setFrom($emailConfig['FROM_EMAIL'] ?? '', 'WorkFlow Mailer');    //sent from
        $mail->addReplyTo($emailConfig['FROM_EMAIL'] ?? '', 'WorkFlow Mailer');    //reply to
        //add recipient 
        $mail->addAddress($recipient['email'] ?? '');    // recepient address

        //subject
        $mail->Subject = $emailContent['subject'];    // subject of mail

        $mail->isHTML(true);

        $mail->Body = $emailContent['body'] ?? '';    //message body

        if (!$mail->Send()) {       //if error
            return ['status' => false, 'error' => $mail->ErrorInfo, "message" => "Failed to send email to provided recipient"];
        }
        return ['status' => true, 'message' => "Email Sent Successfully.", 'error' => ''];
    }
}
