<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Survey extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Survey_model');
    }

    function new($survey_id = null)
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
            $this->form_validation->set_rules('SURVEY_NAME', 'Survey Name', 'required');
            $this->form_validation->set_rules('SURVEY_DESC', 'Survey Desc', 'required');
            $this->form_validation->set_rules('START_DATE', 'Start Date', 'required');
            $this->form_validation->set_rules('END_DATE', 'End Date', 'required');
            $this->form_validation->set_rules('CONDUCTED_BY', 'Counducted By', 'required');
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


            // Save Data to the product table
            $created = $this->Survey_model->add_survey($survey_id, $data, $isAuthorized['userid']);
            if ($created) {

                $action_type = $survey_id != null ? "UPDATED" : "CREATED";
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
                    'ACTIVITY_TYPE' => "SURVEY {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} Survey from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                    'message' => 'Survey created successfully.',
                    'data' => $data,
                    'type' => $survey_id != null ? 'update' : 'insert'
                ]);
            } else {
                throw new Exception('Failed to create new product.');
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

    function fill_new_survey()
    {
        // Check if the authentication is valid
        $isAuthorized = $this->isAuthorized();
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401) // Set HTTP response status to 401 Unauthorized
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Unauthorized access. You do not have permission to perform this action.']))
                ->_display();
            exit;
        }

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

            // Retrieve POST data
            $data = $this->input->post();

            // Sanitize data
            $data = array_map([$this->security, 'xss_clean'], $data);



            if (empty($data['SURVEY_ID'])) {
                $this->sendHTTPResponse(400, [
                    'status' => 400,
                    'error' => 'Bad Request',
                    'message' => 'Survey ID is required but was not provided.'
                ]);
                return;
            }

            // Save data to the survey table
            $created = $this->Survey_model->fill_new_survey($data, $isAuthorized['userid']);

            if ($created) {

                $action_type = "FILLED";
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
                    'ACTIVITY_TYPE' => "SURVEY {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} Survey from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                    'message' => 'Survey created successfully.',
                    'data' => $data
                ]);
            } else {
                throw new Exception('Failed to create a new survey.');
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


    function survey_list()
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

        // fetch model value
        // Access the parameters
        $limit = isset($data['limit']) ? $data['limit'] : null;
        $currentPage = isset($data['currentPage']) ? $data['currentPage'] : null;
        $search = isset($data['search']) ? $data['search'] : [];

        $total_surveys = $this->Survey_model->fetchSurvey();
        $surveys = $this->Survey_model->fetchSurvey();



        $response = [
            'pagination' => [
                'total_records' => $total_surveys,
                'total_pages' => generatePages($total_surveys, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'surveys' => $surveys,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
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

        // fetch model value
        // Access the parameters
        $limit = isset($data['limit']) ? $data['limit'] : null;
        $currentPage = isset($data['currentPage']) ? $data['currentPage'] : null;
        $filters = isset($data['filters']) ? $data['filters'] : [];
        $search = isset($data['search']) ? $data['search'] : null;

        $total_surveys = $this->Survey_model->get_survey('total', $limit, $currentPage, $filters, $search);
        $surveys = $this->Survey_model->get_survey('list', $limit, $currentPage, $filters, $search);



        $response = [
            'pagination' => [
                'total_records' => $total_surveys,
                'total_pages' => generatePages($total_surveys, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'surveys' => $surveys,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }



    function fill_surveylist()
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

        // fetch model value
        // Access the parameters
        $limit = isset($data['limit']) ? $data['limit'] : null;
        $currentPage = isset($data['currentPage']) ? $data['currentPage'] : null;
        $filters = isset($data['filters']) ? $data['filters'] : [];
        $search = isset($data['search']) ? $data['search'] : [];

        $total_surveys = $this->Survey_model->getfill_survey('total', $limit, $currentPage, $filters, $search);
        $surveys = $this->Survey_model->getfill_survey('list', $limit, $currentPage, $filters, $search);



        $response = [
            'pagination' => [
                'total_records' => $total_surveys,
                'total_pages' => generatePages($total_surveys, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'surveys' => $surveys,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
    function fetchSurveyFeedback($feedback_id = 0)
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

        // fetch model value
        // Access the parameters


        $surveys = $this->Survey_model->fetchSurveyFeedbackBYUUID($feedback_id);



        $response = [

            'surveys' => $surveys,
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

        // Validate input and check if `surveyUUID` is provided
        if (!$data || !isset($data['surveyUUID'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid JSON input or missing surveyUUID'
                ]));
        }

        // Retrieve product details using the provided surveyUUID
        $surveyUUID = $data['surveyUUID'];
        $surveyData = $this->Survey_model->get_survey_by_uuid($surveyUUID);

        // Check if product data exists
        if (empty($surveyData)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Survey not found'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Survey details retrieved successfully',
                'data' => $surveyData
            ]));
    }

    function delete($surveyId)
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
        if (empty($surveyId) || !is_numeric($surveyId)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid product ID.']));
            return;
        }

        // Attempt to delete the product
        $result = $this->Survey_model->delete_survey_by_id($surveyId);
        if ($result) {

            $action_type = "DELETED";
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
                'ACTIVITY_TYPE' => "SURVEY {$action_type}",
                'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} Survey from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                ->set_output(json_encode(['status' => true, 'message' => 'Survey deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the Survey.']));
        }
    }

    function surveyfill_delete($surveyId)
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
        if (empty($surveyId) || !is_numeric($surveyId)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid product ID.']));
            return;
        }

        // Attempt to delete the product
        $result = $this->Survey_model->delete_survey_fill_by_id($surveyId);
        if ($result) {

            $action_type = "DELETED";
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
                'ACTIVITY_TYPE' => "SURVEY {$action_type}",
                'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} Survey from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                ->set_output(json_encode(['status' => true, 'message' => 'Survey deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the Survey.']));
        }
    }

    public function export_csv()
    {
        $search = $this->input->get('search');

        $data = $this->Survey_model->get_survey('list', 9999999999999, 1, [], [], $search, 'export');

        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="survey_export_' . date('Ymd_His') . '.csv"');

        $output = fopen('php://output', 'w');

        if (!empty($data)) {
            // Output CSV headers
            fputcsv($output, array_keys($data[0]));

            // Output data rows
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        } else {
            fputcsv($output, ['No records found.']);
        }

        fclose($output);
        exit;
    }
}
