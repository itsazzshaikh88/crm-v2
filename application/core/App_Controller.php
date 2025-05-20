<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App_Controller extends CI_Controller
{
    protected $secret_key;
    protected $userDetails;
    protected $userFullDetails;
    public function __construct()
    {
        parent::__construct();
        $this->secret_key = APP_SECRET_KEY;
        $this->isAuthenticated();
        $this->userDetails = $this->getUserDetails();
        $this->userFullDetails = $this->User_model->get_logged_in_user($this->userDetails);;
        // Share user details with all views
        $this->load->vars(['loggedInUser' => $this->userDetails, 'loggedInUserFullDetails' => $this->userFullDetails]);
    }

    /**
     * Retrieves user details from the auth_token cookie.
     *
     * @return array|null Returns an associative array of user details if valid, or null if invalid or not set.
     */
    protected function getUserDetails(): ?array
    {
        $cookieName = 'auth_token';
        // Check if the cookie is set
        if (!isset($_COOKIE[$cookieName])) {
            return null; // Return null if the cookie is not set
        }

        // Split the token into payload and hash
        [$encodedPayload, $hash] = explode('.', $_COOKIE[$cookieName]);

        // Decode the payload
        $payloadJson = base64_decode($encodedPayload);
        if ($payloadJson === false) {
            return null; // Return null if decoding fails
        }

        $payload = json_decode($payloadJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null; // Return null if JSON decoding fails
        }

        // Verify the hash
        $expectedHash = hash_hmac('sha256', $payloadJson, $this->secret_key);
        // Check if the hash matches
        if (!hash_equals($expectedHash, $hash)) {
            return null; // Return null if the hash does not match
        }



        // Token is valid, return user details
        return $payload;
    }

    protected function isAuthenticated()
    {
        // Retrieve the auth token from the cookie
        $auth_token = $this->input->cookie('auth_token');
        if (!$auth_token) {
            // If no token is found, redirect to login
            redirect(base_url() . 'login');
        } else {

            $tokenData = $this->Auth_model->validate_token($auth_token);
            if (empty($tokenData) || time() > $tokenData['EXPIRY']) {
                // If token is not valid then delete cookie and redirect
                delete_cookie('auth_token');
                redirect(base_url() . 'login');
            }

            // Split the token into payload and hash
            list($encoded_payload, $token_hash) = explode('.', $auth_token);
            // Decode the payload
            $payload = json_decode(base64_decode($encoded_payload), true);
            // Recreate the hash from the payload and secret key
            $recreated_hash = hash_hmac('sha256', base64_decode($encoded_payload), $this->secret_key);
            // Validate the hash
            if ($recreated_hash === $token_hash) {
                // Hash is valid, so extract the user data
                $user_id = $payload['userid'];
                if (!$this->User_model->validate_user($user_id)) {
                    // If user is not valid, delete cookie and redirect
                    delete_cookie('auth_token');
                    redirect(base_url() . 'login');
                }
            } else {
                // If the hash doesn't match, delete cookie and redirect
                delete_cookie('auth_token');
                redirect(base_url() . 'login');
            }
        }
    }



    // append UUID to the url's 
    // Function to check and append UUID to URL if missing
    protected function validateUUID()
    {
        // Get current URL without index.php
        $currentUrl = $this->uri->uri_string();
        $uriSegments = $this->uri->segment_array();

        // Check if the last segment is a valid UUID (UUID v4 format)
        $lastSegment = end($uriSegments);
        if (!preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $lastSegment)) {
            // UUID not present in the URL, generate a new UUID
            $uuid = uuid_v4();

            // Append the UUID to the URL
            $newUrl = $currentUrl . '/' . $uuid;
            // Redirect to the new URL with the UUID
            redirect(base_url() . $newUrl);
        }
        // If the UUID is already present, do nothing
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

    public function send_email($params = [])
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
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.office365.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'workflowmailer@zamilplastic.com';
            $mail->Password   = 'Abc$1234';
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
