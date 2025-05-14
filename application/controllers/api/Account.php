<?php
defined('BASEPATH') or exit('No direct script access allowed');

use RobThree\Auth\TwoFactorAuth;

require_once(APPPATH . 'core/Api_controller.php');
class Account extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->tfa = new TwoFactorAuth('Zamil CRM Authenticator');
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

            $userNameOfUser = $user['FIRST_NAME'] ?? '' . ' ' . $user['LAST_NAME'] ?? '';

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

                $action_type = 'UPDATED';
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
                    'ACTIVITY_TYPE' => "PASSWORD {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} a PASSWORD from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
        $mfa_details = $this->User_model->get_2fa_details($isAuthorized['userid'] ?? 0);
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
            ->set_output(json_encode(['user' => $user, 'mfa' => $mfa_details]));
    }

    // Enable ad Disable Multi factor authentication
    function multifactor($action = null)
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

        // check user exist or not
        if (empty($user)) {
            $this->sendHTTPResponse(404, [
                'status' => 404,
                'error' => 'User profile details not found',
                'message' => 'User profile details not found'
            ]);
            return;
        }

        // check action is provided
        if (!$action) {
            $this->sendHTTPResponse(404, [
                'status' => 404,
                'error' => 'Two Factor Authentication Action is not defined.',
                'message' => 'Two Factor Authentication Action is not defined.'
            ]);
            return;
        }

        // Check action and update multi factor authentication
        $multifactor_details = $this->User_model->get_2fa_details($isAuthorized['userid']);

        // Enable and Disable it in the User table
        $action_updated = $this->User_model->set_2fa_status($isAuthorized['userid'], $action);
        if ($action_updated) {
            if (empty($multifactor_details) && $action == 'enable') {
                // Generate Secret key and QR Code data
                $secret = $this->tfa->createSecret();
                $clientID = $user['EMAIL'] ?? 'ZMLCRM-' . time() . 'loc';
                // $clientID = $user['USER_ID'] ?? 'ZMLCRM-' . time();
                $qrCodeUrl = $this->tfa->getQRCodeImageAsDataUri($clientID, $secret);

                $mfa_data = [
                    'USER_ID' => $isAuthorized['userid'],
                    'TOTP_SECRET' => $secret,
                    'BACKUP_CODES' => $this->generateTOTPBackupCodes(),
                    'IS_ACTIVE' => TRUE,
                    'QR_DATA' => $qrCodeUrl
                ];
                $status = $this->User_model->add_2fa_details($mfa_data);
                if ($status) {

                    $action_type = strtoupper($action) . "D";
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

                    $activityAction = [
                        'ACTIVITY_TYPE' => "TWO STEP VERIFICATION {$action_type}",
                        'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} Two Step Verification from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
                    ];

                    $request = [
                        'REQUEST_URI' => $this->get_request_uri(),
                        // 'REQUEST_DATA' => $data,
                        'REQUEST_METHOD' => strtoupper($this->input->method()),
                        'RESPONSE_STATUS' => 'success'
                    ];

                    $this->App_model->add_activity_logs($activityAction, $userForActivity, $system, $request);
                    // ***** ===== Add User Activity - ENDS ===== *****
                    $this->sendHTTPResponse(201, [
                        'status' => 201,
                        'message' => 'Two Factor Autentication ' . ucfirst($action) . "d successfully.",
                        'action' => 'success',
                        'mfa' => $this->User_model->get_2fa_details($isAuthorized['userid'])
                    ]);
                    return;
                } else {
                    $this->sendHTTPResponse(400, [
                        'status' => 400,
                        'status' => 404,
                        'error' => 'Failed to save two step authentication details.',
                        'message' => 'Failed to save two step authentication details.'
                    ]);
                    return;
                }
            } else {

                $action_type = strtoupper($action) . "D";
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

                $activityAction = [
                    'ACTIVITY_TYPE' => "TWO STEP VERIFICATION {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} Two Step Verification from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
                ];

                $request = [
                    'REQUEST_URI' => $this->get_request_uri(),
                    // 'REQUEST_DATA' => $data,
                    'REQUEST_METHOD' => strtoupper($this->input->method()),
                    'RESPONSE_STATUS' => 'success'
                ];

                $this->App_model->add_activity_logs($activityAction, $userForActivity, $system, $request);
                // ***** ===== Add User Activity - ENDS ===== *****

                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Two Factor Autentication ' . ucfirst($action) . "d successfully.",
                    'action' => 'success',
                    'mfa' => $this->User_model->get_2fa_details($isAuthorized['userid'])
                ]);
                return;
            }
        } else {
            $this->sendHTTPResponse(400, [
                'status' => 400,
                'status' => 404,
                'error' => 'Failed to update authentication status',
                'message' => 'Failed to update authentication status'
            ]);
            return;
        }
        return;
    }

    function generateTOTPBackupCodes($numCodes = 10)
    {
        $codes = [];
        for ($i = 0; $i < $numCodes; $i++) {
            $codes[] = ['code' => strtoupper(bin2hex(random_bytes(4))), 'is_used' => 'no'];  // Generates a random 8-character code
        }
        $json_codes = json_encode($codes);
        return $json_codes ?? null;
    }


    // Verify TOTP from client side, to check qr code is working fine
    public function verify_totp()
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

        $this->form_validation->set_rules('TOTP_CODE', 'OTP Code', 'required');

        // Run validation
        if ($this->form_validation->run() == FALSE) {
            // Validation failed, prepare response with errors
            $errors = $this->form_validation->error_array();

            $this->sendHTTPResponse(422, [
                'status' => 422,
                'error' => 'The submitted data failed validation.',
                'message' => 'The submitted data failed validation.',
                'validation_errors' => $errors
            ]);
            return;
        } else {
            // Step 1: Sanitize User Data
            $input = $this->input->post();
            $cleaned_input = $this->security->xss_clean($input);

            $otp = $cleaned_input['TOTP_CODE'];
            $user_id = $isAuthorized['userid'] ?? 0;

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
                        'message' => 'Invalid OTP, OTP verification unsuccessful.',
                    ];
                    $this->output
                        ->set_status_header(400)  // You can adjust this based on your needs
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response));
                    return;
                }

                // User logged in 
                $response = [
                    'status' => true,
                    'message' => 'OTP Verification successful.',
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

    // Update user profile based on cookie set
    function update_profile($userID)
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

            // Validate the product ID
            if (empty($userID) || !is_numeric($userID)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400) // 400 Bad Request status code
                    ->set_output(json_encode(['error' => 'Invalid User ID.']));
                return;
            }

            // Set validation rules
            $this->form_validation->set_rules('FIRST_NAME', 'First Name', 'required');
            $this->form_validation->set_rules('LAST_NAME', 'Last Name', 'required');
            $this->form_validation->set_rules('PHONE_NUMBER', 'Contact Number', 'required');

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

            // Check if user details are present in table with existing ID
            $user = $this->User_model->get_user_by_id($userID ?? 0);
            if (empty($user)) {
                $this->sendHTTPResponse(404, [
                    'status' => 'error',
                    'code' => 404,
                    'error' => 'A user with provided Id does not found to update.',
                    'message' => 'A user with provided Id does not found to update.'
                ]);
                return;
            }


            // Save Data to the user table
            $updatedUser = $this->User_model->update_user_profile_details($userID, $data, $isAuthorized['userid']);

            if ($updatedUser) {

                $action_type = "UPDATED";
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
                    'ACTIVITY_TYPE' => "USER {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} user profile from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                    'message' => 'User Profile Updated Successfully',
                    'type' => 'update',
                    'data' => $updatedUser,
                ]);
            } else {
                throw new Exception('Error saving user details');
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
}
