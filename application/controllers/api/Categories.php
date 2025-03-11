<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Categories extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function all()
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

        $categories = $this->Category_model->get_all_categories();
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($categories));
    }

    // CRUD API
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
            $this->form_validation->set_rules('CATEGORY_CODE', 'Category Code', 'required');
            $this->form_validation->set_rules('CATEGORY_NAME', 'Category Name', 'required');


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

            // Check if the category is already registered with the existing email address
            $categoryByCode = $this->Category_model->get_category_by_key("CATEGORY_CODE", $data['CATEGORY_CODE']);
            if (!empty($categoryByCode)) {
                $this->sendHTTPResponse(409, [
                    'status' => 'error',
                    'code' => 409,
                    'error' => 'Conflict',
                    'message' => 'A Category code is already defined'
                ]);
                return;
            }

            // Check if the category is already registered with the existing email address
            $categorybyName = $this->Category_model->get_category_by_key("CATEGORY_NAME", $data['CATEGORY_NAME']);
            if (!empty($categorybyName)) {
                $this->sendHTTPResponse(409, [
                    'status' => 'error',
                    'code' => 409,
                    'error' => 'Conflict',
                    'message' => 'A Category name is already defined'
                ]);
                return;
            }


            // Save Data to the product table
            $createdCategory = $this->Category_model->add_category($data, $isAuthorized['userid']);
            if ($createdCategory) {
                $action_type = 'CREATED';
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
                    'ACTIVITY_TYPE' => "CATEGORY {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} new category from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                    'message' => "Category {$createdCategory['CATEGORY_NAME']} Created Successfully",
                    'type' => 'insert',
                    'data' => $createdCategory,
                ]);
            } else {
                throw new Exception('Failed to create new category.');
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

    function update($categoryID)
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
            if (empty($categoryID) || !is_numeric($categoryID)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400) // 400 Bad Request status code
                    ->set_output(json_encode(['error' => 'Invalid Category ID.']));
                return;
            }

            // Set validation rules
            $this->form_validation->set_rules('CATEGORY_CODE', 'Category Code', 'required');
            $this->form_validation->set_rules('CATEGORY_NAME', 'Category Name', 'required');

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

            // Check if category details are present in table with existing ID
            $category = $this->Category_model->get_category_by_key("ID", $categoryID ?? 0);
            if (empty($category)) {
                $this->sendHTTPResponse(404, [
                    'status' => 'error',
                    'code' => 404,
                    'error' => 'Not Found',
                    'message' => 'A Category with provided Id does not found to update.'
                ]);
                return;
            }

            // Check if the category is already registered with the existing email address
            $categoryByCode = $this->Category_model->check_category_exists("CATEGORY_CODE", $data['CATEGORY_CODE'], $categoryID);
            if (!empty($categoryByCode)) {
                $this->sendHTTPResponse(409, [
                    'status' => 'error',
                    'code' => 409,
                    'error' => 'Conflict',
                    'message' => 'A Category code is already defined'
                ]);
                return;
            }

            // Check if the category is already registered with the existing email address
            $categorybyName = $this->Category_model->check_category_exists("CATEGORY_NAME", $data['CATEGORY_NAME'], $categoryID);
            if (!empty($categorybyName)) {
                $this->sendHTTPResponse(409, [
                    'status' => 'error',
                    'code' => 409,
                    'error' => 'Conflict',
                    'message' => 'A Category name is already defined'
                ]);
                return;
            }


            // Save Data to the category table
            $updatedCategory = $this->Category_model->update_category($categoryID, $data, $isAuthorized['userid']);

            if ($updatedCategory) {
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
                    'ACTIVITY_TYPE' => "CATEGORY {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} Category from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                    'message' => "Category {$updatedCategory["CATEGORY_NAME"]} Updated Successfully",
                    'type' => 'update',
                    'data' => $updatedCategory,
                ]);
            } else {
                throw new Exception('Error saving category details');
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

        $total_categories = $this->Category_model->get_categories('total', $limit, $currentPage, $filters);
        $categories = $this->Category_model->get_categories('list', $limit, $currentPage, $filters);

        $response = [
            'pagination' => [
                'total_records' => $total_categories,
                'total_pages' => generatePages($total_categories, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'categories' => $categories,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function detail($categoryID = null)
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

        if (empty($categoryID) || !is_numeric($categoryID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid category ID.']));
            return;
        }

        $category = $this->Category_model->get_category_by_key("ID", $categoryID);

        // Check if product data exists
        if (empty($category)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Category details not found'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Category details retrieved successfully',
                'data' => $category
            ]));
    }

    function delete($categoryID)
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
        if (empty($categoryID) || !is_numeric($categoryID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid category ID.']));
            return;
        }

        // Attempt to delete the product
        $result = $this->Category_model->delete_category_by_id($categoryID);
        if ($result) {

            $action_type = 'DELETED';
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
                'ACTIVITY_TYPE' => "CATEGORY {$action_type}",
                'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} Category from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
            ];

            $request = [
                'REQUEST_URI' => $this->get_request_uri(),
                // 'REQUEST_DATA' => $data,
                'REQUEST_METHOD' => strtoupper($this->input->method()),
                'RESPONSE_STATUS' => 'success'
            ];

            $this->App_model->add_activity_logs($action, $userForActivity, $system, $request);
            // ***** ===== Add User Activity - ENDS ===== *****

            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => 'Category deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the category.']));
        }
    }
}
