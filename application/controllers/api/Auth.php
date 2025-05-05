<?php
defined('BASEPATH') or exit('No direct script access allowed');

use RobThree\Auth\TwoFactorAuth;

class Auth extends CI_Controller
{
    protected $secret_key;
    public function __construct()
    {
        parent::__construct();
        $this->secret_key = APP_SECRET_KEY;
        $this->tfa = new TwoFactorAuth('Zamil CRM Authenticator');
    }

    public function login()
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
        $this->form_validation->set_rules('email', 'Email', 'required|trim|htmlspecialchars');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|htmlspecialchars');
        // Check if form validation fails
        if ($this->form_validation->run() == FALSE) {
            // Prepare error messages in key-value format
            $errors = array(
                'email' => $this->form_validation->error('email'),
                'password' => $this->form_validation->error('password')
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

            $email = $cleaned_input['email'];
            $password = $cleaned_input['password'];

            $user = $this->Auth_model->verify_user($email, $password);

            if ($user) {
                // Check if the user is locked or not// Check if the user is locked or inactive
                if (in_array($user['STATUS'], ['locked', 'suspended', 'inactive'])) {
                    $response = [
                        'status' => false,
                        'message' => 'Your account is ' . ucfirst($user['STATUS']) . '. Please contact Zamil Plastic IT Department.'
                    ];
                    // Return response
                    $this->output
                        ->set_status_header(200)  // You can adjust this based on your needs
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response));
                    return;
                }

                // Check if there two step verification is set 
                if (isset($user['IS_2FA_ENABLED']) && $user['IS_2FA_ENABLED'] == '1') {
                    // User logged in 
                    $response = [
                        'status' => true,
                        'message' => 'User Authenticated Successful',
                        'two_step_enabled' => true,
                        'user' => $user['ID']
                    ];
                } else {
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

                    $local_ip = ['localhost', '127.0.0.1'];
                    $domain_host = in_array($_SERVER['HTTP_HOST'], $local_ip) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_ADDR'];

                    $cookie = array(
                        'name'   => 'auth_token',
                        'value'  => $auth_token,
                        'expire' => $expiry,
                        'secure' => FALSE, // Set to TRUE if using HTTPS
                        'httponly' => FALSE, // Prevent JS access
                        'domain' => $domain_host,
                    );
                    // Set the cookie
                    $this->input->set_cookie($cookie);

                    // Add token value to the database
                    $this->Auth_model->create_token($user['ID'], $auth_token, 'auth', $expiry);


                    // ***** ===== Add User Activity - STARTS ===== *****
                    $userForActivity = [
                        'userid' => $user['ID'],
                        'role' => $user['USER_TYPE'],
                        'name' => "$user[FIRST_NAME] $user[LAST_NAME]"
                    ];
                    $system = [
                        'IP_ADDRESS' => $this->get_local_ip(),
                        'USER_AGENT' => $this->get_user_agent(),
                        'BROWSER' => $this->get_browser_name(),
                    ];
                    $action = [
                        'ACTIVITY_TYPE' => 'LOGGED IN',
                        'DESCRIPTION' => "User $userForActivity[name] (Role: $userForActivity[role]) logged in from IP $system[IP_ADDRESS] using $system[BROWSER] on " . date('D, d M Y - H:i:s')
                    ];

                    $request = [
                        'REQUEST_URI' => $this->get_request_uri(),
                        'REQUEST_METHOD' => strtoupper($this->input->method()),
                        'RESPONSE_STATUS' => 'success'
                    ];

                    $this->App_model->add_activity_logs($action, $userForActivity, $system, $request);

                    // ***** ===== Add User Activity - ENDS ===== *****

                    // User logged in 
                    $response = [
                        'status' => true,
                        'message' => 'User Authenticated Successful',
                        'two_step_enabled' => false,
                        'url' => base_url()
                    ];
                }
            } else {
                // If user doesn't exist
                // make account in active or suspened if login failed three times

                $response = [
                    'status' => false,
                    'message' => 'Invalid username or password'
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

                $local_ip = ['localhost', '127.0.0.1'];
                $domain_host = in_array($_SERVER['HTTP_HOST'], $local_ip) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_ADDR'];

                $cookie = array(
                    'name'   => 'auth_token',
                    'value'  => $auth_token,
                    'expire' => $expiry,
                    'secure' => FALSE, // Set to TRUE if using HTTPS
                    'httponly' => FALSE, // Prevent JS access
                    'domain' => $domain_host,
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
