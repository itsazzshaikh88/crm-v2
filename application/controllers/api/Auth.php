<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    protected $secret_key;
    public function __construct()
    {
        parent::__construct();
        $this->secret_key = APP_SECRET_KEY;
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

            $user = $this->Auth_model->verify_user($email, $password);  // Assuming this method fetches the user

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
                    'secure' => TRUE, // Set to TRUE if using HTTPS
                    'httponly' => FALSE, // Prevent JS access
                );
                // Set the cookie
                $this->input->set_cookie($cookie);

                // Add token value to the database
                $this->Auth_model->create_token($user['ID'], $auth_token, 'auth', $expiry);

                // User logged in 
                $response = [
                    'status' => true,
                    'message' => 'User Authenticated Successful',
                    'url' => base_url()
                ];
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
}
