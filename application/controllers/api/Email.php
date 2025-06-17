<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Email extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function send()
    {
        // Check if the authentication is valid
        $isAuthorized = $this->isAuthorized();
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401) // Set HTTP response status to 400 Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Unauthorized access. You do not have permission to perform this action.']))
                ->_display();
            exit;
        };

        try {
            // Check if the request method is POST
            if (strtolower($this->input->method()) !== 'post') {
                $this->sendHTTPResponse(405, [
                    'status' => 405,
                    'error' => 'Method Not Allowed',
                    'message' => 'The requested HTTP method is not allowed for this endpoint. Please check the API documentation for allowed methods.'
                ]);
                return;
            }

            // Read and decode JSON input
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            // Check for JSON parsing errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->sendHTTPResponse(400, [
                    'status' => 400,
                    'error' => 'Bad Request',
                    'message' => 'Invalid JSON format.'
                ]);
                return;
            }

            // Sanitize input data
            $this->load->helper('security');
            $recepient = $this->security->xss_clean($data['RECEIPIENT'] ?? '');
            $subject   = $this->security->xss_clean($data['SUBJECT'] ?? '');
            $message   = $this->security->xss_clean($data['MESSAGE'] ?? '');

            // Set validation data and rules
            $this->load->library('form_validation');
            $this->form_validation->set_data([
                'RECEIPIENT' => $recepient,
                'SUBJECT'    => $subject,
                'MESSAGE'    => $message
            ]);

            $this->form_validation->set_rules('RECEIPIENT', 'Recepient Email', 'required|valid_email');
            $this->form_validation->set_rules('SUBJECT', 'Subject', 'required');
            $this->form_validation->set_rules('MESSAGE', 'Message', 'required');

            // Run validation
            if ($this->form_validation->run() === FALSE) {
                $validation_errors = [];

                foreach (['RECEIPIENT', 'SUBJECT', 'MESSAGE'] as $field) {
                    $error = form_error($field);
                    if (!empty($error)) {
                        $validation_errors[$field] = strip_tags($error);
                    }
                }

                $this->sendHTTPResponse(422, [
                    'status' => 422,
                    'error' => 'Unprocessable Entity',
                    'message' => 'The submitted data failed validation.',
                    'validation_errors' => $validation_errors
                ]);
                return;
            }

            // Send Email code
            $payload = [
                'RECEPIENT' => $recepient,
                'SUBJECT' => $subject,
                'MESSAGE' => $message,
            ];
            $emailStatus = $this->email_service($payload);

            if (!$emailStatus['status']) {
                // Email sending failed, return error response
                $this->sendHTTPResponse(500, [
                    'status' => 500,
                    'error' => 'Internal Server Error',
                    'message' => 'Failed to send email - ' . $emailStatus['error'] ?? 'Unknown error'
                ]);
                return;
            }

            $created = true;
            if ($created) {
                $action_type = "SENT";
                // ***** ===== Add User Activity - STARTS ===== *****
                $userForActivity = [
                    'userid' => $isAuthorized['userid'] ?? '',
                    'role' => $isAuthorized['role'] ?? '',
                    'name' => $isAuthorized['name'] ?? ''
                ];
                $system = [
                    'IP_ADDRESS' => $this->get_local_ip(),
                    'USER_AGENT' => $this->get_user_agent(),
                    'BROWSER' => $this->get_browser_name(),
                ];

                $action = [
                    'ACTIVITY_TYPE' => "EMAIL {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} an Email from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
                ];

                $request = [
                    'REQUEST_URI' => $this->get_request_uri(),
                    // 'REQUEST_DATA' => $data,
                    'REQUEST_METHOD' => strtoupper($this->input->method()),
                    'RESPONSE_STATUS' => 'success'
                ];

                $this->App_model->add_activity_logs($action, $userForActivity, $system, $request);
                // ***** ===== Add User Activity - ENDS ===== *****

                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Email Sent Successfully',
                    'data' => $data,
                ]);
            } else {
                throw new Exception('Failed to create new request.');
            }
        } catch (Exception $e) {
            // Catch any unexpected errors and respond with a standardized error
            $this->sendHTTPResponse(500, [
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => 'An unexpected error occurred on the server.',
                'details' => $e->getMessage()
            ]);
        }
    }

    public function email_service($payload = [])
    {
        // Load PHPMailer library
        $this->load->library('phpmailer_lib');
        $mail = $this->phpmailer_lib->load();

        // Default response
        $response = [
            'status' => false,
            'message' => 'Email sending failed',
            'error' => null
        ];

        // Sanitize payload
        $recepient = isset($payload['RECEPIENT']) ? $payload['RECEPIENT'] : '';
        $subject   = isset($payload['SUBJECT']) ? $payload['SUBJECT'] : '';
        $message   = isset($payload['MESSAGE']) ? $payload['MESSAGE'] : '';

        // Basic validation
        if (empty($recepient) || empty($subject) || empty($message)) {
            $response['status'] = false;
            $response['message'] = 'Required fields missing.';
            $response['error'] = 'Required fields missing.';
            $response['validation_errors'] = [
                'RECEPIENT' => empty($recepient) ? 'The Recepient Email is required.' : null,
                'SUBJECT'   => empty($subject) ? 'The Subject field is required.' : null,
                'MESSAGE'   => empty($message) ? 'The Message field is required.' : null
            ];
            return $response;
        }
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'workflowmailer@zamilplastic.com';
        $mail->Password   = 'Wf@20252423#';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email headers
        $mail->setFrom('workflowmailer@zamilplastic.com', 'WorkFlow Mailer');
        $mail->addReplyTo('workflowmailer@zamilplastic.com', 'WorkFlow Mailer');
        $mail->addAddress($recepient);

        // Email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message); // Plain-text alternative for non-HTML email clients
        $mail->CharSet = 'UTF-8';

        // Send Email
        if ($mail->send()) {
            $response['status'] = true;
            $response['message'] = 'Email sent successfully';
        } else {
            $response['error'] = $mail->ErrorInfo;
        }

        return $response;
    }
}
