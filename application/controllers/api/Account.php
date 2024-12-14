<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Account extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function update_password()
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

            // Set validation rules
            $this->form_validation->set_rules('CURRENT_PASSWORD', 'Current Password', 'required');
            $this->form_validation->set_rules('NEW_PASSWORD', 'New Password', 'required');
            $this->form_validation->set_rules('CONFIRM_PASSWORD', 'Confirm Password', 'required');


            // Run validation
            if ($this->form_validation->run() == FALSE) {
                // Validation failed, prepare response with errors
                $errors = $this->form_validation->error_array();

                $this->sendHTTPResponse(422, [
                    'status' => 422,
                    'error' => 'Unprocessable Entity',
                    'message' => 'The submitted data failed validation.',
                    'validation_errors' => $errors
                ]);
                return;
            }

            // Retrieve POST data and sanitize it
            $data = $this->input->post();
            $data = array_map([$this->security, 'xss_clean'], $data);

            // Check if the account is not locked to update password
            $userid = $isAuthorized['userid'];
            $user = $this->User_model->get_user_by_id($userid);
            if (empty($user)) {
                $this->sendHTTPResponse(404, [
                    'status' => 404,
                    'error' => 'User details not found',
                    'message' => 'User details not found'
                ]);
                return;
            }
            $plain_password = $data['CURRENT_PASSWORD'];
            $new_password = $data['NEW_PASSWORD'];
            $confirm_password = $data['CONFIRM_PASSWORD'];
            $hashed_password = $user['PASSWORD'];
            // Check if the given password id the current password
            if (!password_verify($plain_password, $hashed_password)) {
                $this->sendHTTPResponse(401, [
                    'status' => 401,
                    'error' => 'Current Password is not matched with the provided password.',
                    'message' => 'Current Password is not matched with the provided password.'
                ]);
                return;
            }

            // Check new password is matching with the confirm password
            // Check if the given password id the current password
            if ($new_password !== $confirm_password) {
                $this->sendHTTPResponse(400, [
                    'status' => 400,
                    'error' => 'New Password is not matched with confirm password.',
                    'message' => 'New Password is not matched with confirm password.'
                ]);
                return;
            }


            // Update Pasword
            $passwordUpdated = $this->User_model->update_password($new_password, $userid);

            if ($passwordUpdated) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Password Updated Successfully',
                    'type' => 'update'
                ]);
            } else {
                throw new Exception('Failed to create new lead.');
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

    // Fetch client Profile
    function profile()
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

        $user = $this->User_model->get_user_by_id($isAuthorized['userid'] ?? 0);

        // Validate the product ID
        if (empty($user)) {
            $this->sendHTTPResponse(404, [
                'status' => 404,
                'error' => 'User profile details not found',
                'message' => 'User profile details not found'
            ]);
            return;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(['user' => $user]));
    }

    // Enable ad Disable Multi factor authentication
    function multifactor()
    {
        // Check if the authentication is valid
        $isAuthorized = $this->isAuthorized();
        if (!$isAuthorized['status']) {
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Unauthorized access. You do not have permission to perform this action.',
                'message' => 'Unauthorized access. You do not have permission to perform this action.'
            ]);
            exit;
        };

        $user = $this->User_model->get_user_by_id($isAuthorized['userid'] ?? 0);

        // Validate the product ID
        if (empty($user)) {
            $this->sendHTTPResponse(404, [
                'status' => 404,
                'error' => 'User profile details not found',
                'message' => 'User profile details not found'
            ]);
            return;
        }

        // Check action and update multi factor authentication
        $multifactor_details = $this->User_model->get_2fa_details($isAuthorized['userid']);
        
        if (empty($multifactor_details)) {
            // Insert TOTP Token details and Enable 2FA Account

        }else {
            // Enable and Disable Account

        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(['user' => $user]));
    }
}
