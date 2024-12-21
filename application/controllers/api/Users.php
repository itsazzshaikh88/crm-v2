<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Users extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
    }
    function new()
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
            $this->form_validation->set_rules('FIRST_NAME', 'First Name', 'required');
            $this->form_validation->set_rules('LAST_NAME', 'Last Name', 'required');
            $this->form_validation->set_rules('EMAIL', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('PHONE_NUMBER', 'Contact Number', 'required');
            $this->form_validation->set_rules('USER_TYPE', 'User Type', 'required');
            $this->form_validation->set_rules('STATUS', 'Status', 'required');
            $this->form_validation->set_rules('NEW_PASSWORD', 'Password', 'required');
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

            // Check password and confirm password is matching
            if ($data['NEW_PASSWORD'] !== $data['CONFIRM_PASSWORD']) {
                $this->sendHTTPResponse(400, [
                    'status' => 400,
                    'error' => 'Password and Confirm Password not matched',
                    'message' => 'Password and Confirm Password not matched',
                ]);
                return;
            }

            // Check user is already created with given email
            $user = $this->User_model->get_user_by_email($data['EMAIL']);
            if (!empty($user)) {
                $this->sendHTTPResponse(409, [
                    'status' => 'error',
                    'code' => 409,
                    'error' => 'User with this email already exists.',
                    'message' => 'User with this email already exists.'
                ]);
                return;
            }


            // Save Data to the product table
            $createdUser = $this->User_model->add_user($data, $isAuthorized['userid']);

            if ($createdUser) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'New User Created Successfully',
                    'type' => 'insert',
                    'data' => $createdUser,
                ]);
            } else {
                throw new Exception('Failed to create new user.');
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

    function update($userID)
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
            $this->form_validation->set_rules('EMAIL', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('PHONE_NUMBER', 'Contact Number', 'required');
            $this->form_validation->set_rules('USER_TYPE', 'User Type', 'required');
            $this->form_validation->set_rules('STATUS', 'Status', 'required');

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
            $updatedUser = $this->User_model->update_user_details($userID, $data, $isAuthorized['userid']);

            if ($updatedUser) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'User Details Updated Successfully',
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

    function list()
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

        // Get the raw input data from the request
        $input = $this->input->raw_input_stream;

        // Decode the JSON data
        $data = json_decode($input, true); // Decode as associative array

        // Check if data is received
        if (!$data) {
            // Handle the error if no data is received
            $this->output
                ->set_status_header(400) // Set HTTP response status to 400 Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Invalid JSON input']))
                ->_display();
            exit;
        }

        // Access the parameters
        $limit = isset($data['limit']) ? $data['limit'] : null;
        $currentPage = isset($data['currentPage']) ? $data['currentPage'] : null;
        $filters = isset($data['filters']) ? $data['filters'] : [];

        $total_users = $this->User_model->get_users('total', $limit, $currentPage, $filters);
        $users = $this->User_model->get_users('list', $limit, $currentPage, $filters);

        $response = [
            'pagination' => [
                'total_records' => $total_users,
                'total_pages' => generatePages($total_users, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'users' => $users,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function detail()
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

        // Get the raw input data from the request
        $input = $this->input->raw_input_stream;

        // Decode the JSON data as an associative array
        $data = json_decode($input, true);

        // Validate input and check if `productUUID` is provided
        if (!$data || !isset($data['userID'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid JSON input or missing userID'
                ]));
        }

        // Retrieve product details using the provided clientUUID
        $userID = $data['userID'];
        $user = $this->User_model->get_user_by_id($userID);

        // Check if product data exists
        if (empty($user)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Client details not found'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Client details retrieved successfully',
                'data' => $user
            ]));
    }

    function delete($userID)
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

        // Check if the user is admin or not
        if (isset($isAuthorized['role']) && $isAuthorized['role'] !== 'admin') {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(403) // 403 Forbidden status code
                ->set_output(json_encode(['error' => 'You do not have permission to perform this action.']));
            return;
        }

        // Validate the product ID
        if (empty($userID) || !is_numeric($userID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid product ID.']));
            return;
        }

        // Attempt to delete the product
        $result = $this->User_model->delete_user_by_id($userID);
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => 'User deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the product.']));
        }
    }

    // User account and password management
    function reset_password($userID)
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
            $this->form_validation->set_rules('RESET_NEW_PASSWORD', 'New Password', 'required');
            $this->form_validation->set_rules('RESET_CONFIRM_PASSWORD', 'Confirm Password', 'required');

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

            // Check password and confirm password is matching
            if ($data['RESET_NEW_PASSWORD'] !== $data['RESET_CONFIRM_PASSWORD']) {
                $this->sendHTTPResponse(400, [
                    'status' => 400,
                    'error' => 'Password and Confirm Password not matched',
                    'message' => 'Password and Confirm Password not matched',
                ]);
                return;
            }

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
            $updated = $this->User_model->update_user_password($userID, $data, $isAuthorized['userid']);

            if ($updated) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'User Password Updated Successfully',
                    'type' => 'update',
                    'success' => true,
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

    // Get User profile from token
    public function user()
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


        // Validate input and check if `productUUID` is provided
        if (!$isAuthorized['userid']) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid User ID'
                ]));
        }

        $user = $this->User_model->get_user_by_id($isAuthorized['userid']);

        // Check if product data exists
        if (empty($user)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'User Not Found with the provided token'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'User details retrieved successfully',
                'user' => $user
            ]));
    }
}
