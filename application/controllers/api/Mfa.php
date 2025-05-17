<?php
defined('BASEPATH') or exit('No direct script access allowed');

use RobThree\Auth\TwoFactorAuth;

class Mfa extends CI_Controller
{
    protected $secret_key;
    public function __construct()
    {
        parent::__construct();
        $this->secret_key = APP_SECRET_KEY;
        $this->tfa = new TwoFactorAuth('Zamil CRM Authenticator');
    }

    // Validate time based otp
    public function otp()
    {
        // Check if the request method is POST
        if (strtolower($this->input->method()) !== 'post') {
            // Set the response status code to 405 Method Not Allowed
            $this->output
                ->set_status_header(405)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'type' => 'method',
                    'message' => 'Method Not Allowed. Please use POST.'
                ]));
            return;
        }

        // Validate User Data
        $this->form_validation->set_rules('OTP_CODE', 'OTP Code', 'required|trim|htmlspecialchars');
        // Check if form validation fails
        if ($this->form_validation->run() == FALSE) {
            // Prepare error messages in key-value format
            $errors = array(
                'OTP_CODE' => $this->form_validation->error('OTP_CODE')
            );
            // Remove null values (fields without errors) from the array
            $errors = array_filter($errors);
            $this->output
                ->set_status_header(400)  // Set appropriate status code (400 Bad Request for validation errors)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'type' => 'validation',
                    'errors' => $errors
                ]));
            return;
        } else {
            // Step 1: Sanitize User Data
            $input = $this->input->post();
            $cleaned_input = $this->security->xss_clean($input);

            $otp = $cleaned_input['OTP_CODE'];
            $user_id = $cleaned_input['USER_ID'];

            $user = $this->User_model->get_user_by_id($user_id);

            if ($user) {

                $multifactor_details = $this->User_model->get_2fa_details($user_id);
                // Check if there two step verification is set 
                if (empty($multifactor_details)) {
                    // User logged in 
                    $response = [
                        'status' => false,
                        'message' => 'Invalid Authentication Details, MFA Details not found.',
                    ];
                    $this->output
                        ->set_status_header(400)  // You can adjust this based on your needs
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response));
                    return;
                }
                $secret = $multifactor_details['TOTP_SECRET'];

                if (!$this->tfa->verifyCode($secret, $otp)) {
                    $response = [
                        'status' => false,
                        'message' => 'Invalid OTP, Please Try again.',
                    ];
                    $this->output
                        ->set_status_header(400)  // You can adjust this based on your needs
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response));
                    return;
                }


                // User is active and valid 
                $payload = json_encode(array(
                    'userid' => $user['ID'],
                    'usertype' => $user['USER_TYPE'],
                    'email' => $user['EMAIL'],
                    'username' => "$user[FIRST_NAME] $user[LAST_NAME]",
                    'timestamp' => time(),
                ));
                // Hash the payload with the secret key (HMAC)
                $token = hash_hmac('sha256', $payload, $this->secret_key);
                // Combine the payload and the hash as the final token
                $auth_token = base64_encode($payload) . '.' . $token;
                $expiry =  time() + (3 * 3600); // 3 hours in seconds
                // Set the cookie with the hashed token
                $cookie = array(
                    'name'   => 'auth_token',
                    'value'  => $auth_token,
                    'expire' => $expiry,
                    'secure' => FALSE, // Set to TRUE if using HTTPS
                    'httponly' => FALSE, // Prevent JS access
                    'domain' => $_SERVER['HTTP_HOST'],
                );
                // Set the cookie
                $this->input->set_cookie($cookie);

                // Add token value to the database
                $this->Auth_model->create_token($user['ID'], $auth_token, 'auth', $expiry);

                // User logged in 
                $response = [
                    'status' => true,
                    'message' => 'User Authenticated Successful',
                    'two_step_enabled' => false,
                    'url' => base_url()
                ];
            } else {
                // If user doesn't exist
                $response = [
                    'status' => false,
                    'message' => 'Invalid User Detail. Plese try again.'
                ];
            }

            // Return response
            $this->output
                ->set_status_header(200)  // You can adjust this based on your needs
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
    }

    public function logout()
    {
        // Get the token from the Authorization header
        $headers = $this->input->get_request_header('Authorization');

        if (!$headers) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'No authorization token found.'
                ]));
        }

        // Extract the token from the "Bearer <token>" format
        $token = str_replace('Bearer ', '', $headers);


        // Start a transaction
        $this->db->trans_start();

        try {
            // Delete the token from the xx_crm_authtokens table
            $this->db->delete('xx_crm_authtokens', ['token' => $token]);

            // Clear the cookies
            delete_cookie('auth_token'); // Adjust if you use a different cookie name

            // Commit the transaction
            $this->db->trans_complete();

            // Check if transaction was successful
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Failed to delete token from the database.');
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'message' => 'User logged out successfully.'
                ]));
        } catch (Exception $e) {
            // Rollback transaction in case of an error
            $this->db->trans_rollback();

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Error logging out: ' . $e->getMessage()
                ]));
        }
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
}
