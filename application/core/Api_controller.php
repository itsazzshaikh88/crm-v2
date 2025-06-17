<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('phpmailer_lib');
        set_time_limit(0);
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
        $this->db->select("t.TOKEN, t.EXPIRY, t.USER_ID, u.USER_TYPE, CONCAT(u.FIRST_NAME , ' ', u.LAST_NAME) AS UNAME, r.ROLE_NAME");
        $this->db->from('xx_crm_authtokens t');
        $this->db->join('xx_crm_users u', 't.USER_ID = u.ID');
        $this->db->join('xx_crm_access_roles r', 'r.ID = u.USER_TYPE', "left");
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

        return ['status' => true, 'userid' => $row->USER_ID, 'role' => strtolower($row->ROLE_NAME), 'name' => $row->UNAME];
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

    function get_browser_name()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if (strpos($user_agent, 'Edge') !== false || strpos($user_agent, 'Edg') !== false) {
            return 'Microsoft Edge';
        } elseif (strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR') !== false) {
            return 'Opera';
        } elseif (strpos($user_agent, 'Chrome') !== false) {
            return 'Google Chrome';
        } elseif (strpos($user_agent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        } elseif (strpos($user_agent, 'Safari') !== false && strpos($user_agent, 'Chrome') === false) {
            return 'Apple Safari';
        } elseif (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false) {
            return 'Internet Explorer';
        } else {
            return 'Unknown Browser';
        }
    }

    function get_user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    public function get_local_ip()
    {
        return gethostbyname(gethostname());
    }

    public function get_request_uri()
    {
        return isset($_SERVER['REQUEST_URI']) ? base_url($_SERVER['REQUEST_URI']) : '';
    }

    public function app_mailer($params = [])
    {
        // Load PHPMailer
        $mail = $this->phpmailer_lib->load();

        // Basic validations
        if (empty($params['to']) || !filter_var($params['to'], FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'error'   => 'Invalid recipient email address.'
            ];
        }

        if (empty($params['subject'])) {
            return [
                'success' => false,
                'error'   => 'Email subject is required.'
            ];
        }

        if (empty($params['message'])) {
            return [
                'success' => false,
                'error'   => 'Email message is required.'
            ];
        }

        try {
            $mail->CharSet = 'UTF-8';
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.office365.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'workflowmailer@zamilplastic.com';
            $mail->Password   = 'Wf@20252423#';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Email headers
            $from_email = $params['from'] ?? 'workflowmailer@zamilplastic.com';
            $from_name  = $params['from_name'] ?? 'WorkFlow Mailer';

            $reply_to_email = $params['reply_to'] ?? $from_email;
            $reply_to_name  = $params['reply_to_name'] ?? $from_name;

            $mail->setFrom($from_email, $from_name);
            $mail->addReplyTo($reply_to_email, $reply_to_name);
            $mail->addAddress($params['to']);

            // Subject and Body
            $mail->Subject = $params['subject'];
            $mail->isHTML(true);
            $mail->Body    = $params['message'];
            $mail->AltBody = strip_tags($params['message']); // Plain text fallback

            // Send Email
            if (!$mail->send()) {
                return [
                    'success' => false,
                    'error'   => $mail->ErrorInfo
                ];
            }

            return [
                'success' => true,
                'details' => [
                    'to'      => $params['to'],
                    'subject' => $params['subject'],
                    'from'    => $from_email,
                    'time'    => date('Y-m-d H:i:s')
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error'   => 'Mailer Exception: ' . $e->getMessage()
            ];
        }
    }
}
