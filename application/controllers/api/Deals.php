<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Deals extends Api_controller
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
            $fields = ['DEAL_NAME',  'DEAL_STAGE', 'DEAL_TYPE', 'DEAL_VALUE', 'DEAL_PRIORITY', 'EXPECTED_CLOSE_DATE', 'ASSIGNED_TO', 'DEAL_SOURCE', 'DEAL_STATUS', 'CONTACT_NUMBER', 'ORG_ID'];
            foreach ($fields as $field):
                $this->form_validation->set_rules($field, ucwords(strtolower(str_replace("_", " ", $field))), 'required');
            endforeach;
            $this->form_validation->set_rules('EMAIL', 'Email', 'required|valid_email');

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
            }

            // Retrieve POST data and sanitize it
            $data = $this->input->post();
            $data = array_map([$this->security, 'xss_clean'], $data);

            // Save Data to the product table
            $created = $this->Deal_model->add_deal($data, $isAuthorized['userid']);
            $newlyCreatedDeal = $this->Deal_model->get_deal_by_uuid($data['UUID']);

            if ($created) {
                // send email to consultant
                $consultant_id = $data['ASSIGNED_TO_ID'] ?? 0;
                // Send an email after assigning the task
                $consultant_details = $this->User_model->get_user_by_id($consultant_id);
                if ($consultant_details) {
                    $emailViewConfig = [
                        'content_view' => 'deal-created',
                        'heading' => "New Deal Assigned: " . $newlyCreatedDeal['DEAL_NAME'],
                    ];
                    $dealDetailsForEmailContent = [
                        'user' => "$consultant_details[FIRST_NAME] $consultant_details[LAST_NAME]",
                        'deal_name' => "$newlyCreatedDeal[DEAL_NAME]",
                        'deal_stage' => $newlyCreatedDeal['DEAL_STAGE'],
                        'deal_value' => $newlyCreatedDeal['DEAL_VALUE'],
                        'deal_status' => $newlyCreatedDeal['DEAL_STATUS'],
                        'assigned_on' => date('d-M-Y'),
                        'link' => base_url("deals?u_source=email&mode=deal-assigned&deal-id=" . $newlyCreatedDeal['DEAL_ID'])
                    ];
                    $mailContent = $this->load->view('email-templates/layout', ['emailViewConfig' => $emailViewConfig, 'dealDetails' => $dealDetailsForEmailContent], true);

                    $consultant_email_address = $consultant_details['EMAIL'] ?? 'IT@zamilplastic.com';

                    $this->app_mailer([
                        'to'        => $consultant_email_address,
                        'subject'   => "New Deal Assigned: " . $newlyCreatedDeal['DEAL_NAME'],
                        'message'   => $mailContent,
                        'from'      => 'workflowmailer@zamilplastic.com',   // Optional
                        'from_name' => 'WorkFlow Mailer',                   // Optional
                    ]);
                }
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
                    'ACTIVITY_TYPE' => "DEAL {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} new Deal from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                    'message' => 'Deal Created Successfully',
                    'type' => 'insert',
                    'data' => $newlyCreatedDeal,
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

    function update($dealID)
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
            if (empty($dealID) || !is_numeric($dealID)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400) // 400 Bad Request status code
                    ->set_output(json_encode(['error' => 'Invalid Lead ID.']));
                return;
            }

            // Set validation rules
            $fields = ['DEAL_NAME',  'DEAL_STAGE', 'DEAL_TYPE', 'DEAL_VALUE', 'DEAL_PRIORITY', 'EXPECTED_CLOSE_DATE', 'ASSIGNED_TO', 'DEAL_SOURCE', 'DEAL_STATUS', 'CONTACT_NUMBER', 'ORG_ID'];
            foreach ($fields as $field):
                $this->form_validation->set_rules($field, ucwords(strtolower(str_replace("_", " ", $field))), 'required');
            endforeach;
            $this->form_validation->set_rules('EMAIL', 'Email', 'required|valid_email');


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

            // Check if deal details are present in table with existing ID
            $deal = $this->Deal_model->get_deal_by_id($dealID ?? 0);
            if (empty($deal)) {
                $this->sendHTTPResponse(404, [
                    'status' => 'error',
                    'code' => 404,
                    'error' => 'A deal with provided Id does not found to update.',
                    'message' => 'A deal with provided Id does not found to update.'
                ]);
                return;
            }


            // Save Data to the lead table
            $updated = $this->Deal_model->update_deal($dealID, $data, $isAuthorized['userid']);
            $updatedDeal = $this->Deal_model->get_deal_by_id($dealID);

            if ($updated) {

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
                    'ACTIVITY_TYPE' => "DEAL {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} Deal from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                    'message' => 'Deal Updated Successfully',
                    'type' => 'update',
                    'data' => $updatedDeal,
                ]);
            } else {
                throw new Exception('Error saving lead details');
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
        $search = isset($data['search']) ? $data['search'] : null;

        $total_deals = $this->Deal_model->get_deals('total', $limit, $currentPage, $filters, $search);
        $deals = $this->Deal_model->get_deals('list', $limit, $currentPage, $filters, $search);

        $response = [
            'pagination' => [
                'total_records' => $total_deals,
                'total_pages' => generatePages($total_deals, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'deals' => $deals,
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
        if (!$data || !isset($data['dealID'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid JSON input or missing lead ID'
                ]));
        }

        // Retrieve product details using the provided dealID
        $dealID = $data['dealID'];
        $deal = $this->Deal_model->get_deal_and_activities_by_id($dealID);

        // Check if product data exists
        if (empty($deal)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Deal details not found'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Deal details retrieved successfully',
                'data' => $deal
            ]));
    }

    function delete($dealID)
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
        if (empty($dealID) || !is_numeric($dealID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid product ID.']));
            return;
        }

        // Attempt to delete the product
        $result = $this->Deal_model->delete_deal_by_id($dealID);
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
                'ACTIVITY_TYPE' => "DEAL {$action_type}",
                'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} Deal from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                ->set_output(json_encode(['status' => true, 'message' => 'Deal deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the Deal.']));
        }
    }

    public function export_csv()
    {
        $search = $this->input->get('search');

        $data = $this->Deal_model->get_deals('list', 9999999999999, 1, [], [], $search, 'export');


        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="deals_export_' . date('Ymd_His') . '.csv"');

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
