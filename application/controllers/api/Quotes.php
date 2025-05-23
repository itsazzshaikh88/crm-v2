<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Quotes extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('quotes_model');
    }

    function new($quote_id = null)
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
            $this->form_validation->set_rules('ORG_ID', 'Org ID', 'required');
            $this->form_validation->set_rules('JOB_TITLE', 'Job Title', 'required');
            $this->form_validation->set_rules('COMPANY_ADDRESS', 'Company Address', 'required|min_length[3]');
            $this->form_validation->set_rules('SALES_PERSON', 'Sales Person', 'required');
            $this->form_validation->set_rules('EMPLOYEE_NAME', 'Employee Name', 'required');
            $this->form_validation->set_rules('MOBILE_NUMBER', 'Company Mobile Number', 'required');
            $this->form_validation->set_rules('EMAIL_ADDRESS', 'Company Email Address', 'required|valid_email');


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

            // Directory to upload files
            $uploadPath = './uploads/quotes/';
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx'];
            $uploadedFiles = null;

            // Check if files are attached
            if (!empty($_FILES['files']['name'][0])) {
                $uploadedFiles = upload_multiple_files($_FILES['files'], $uploadPath, $allowedTypes);
            }


            // Retrieve POST data and sanitize it
            $data = $this->input->post();
            $data['UPLOADED_FILES'] = $uploadedFiles ?? [];

            $data = array_map([$this->security, 'xss_clean'], $data);


            // Save Data to the Request table
            $created = $this->Quotes_model->add_quote($quote_id, $data, $isAuthorized['userid'], $isAuthorized['role']);
            if ($created) {

                $action_type = $quote_id != null ? 'UPDATED' : 'CREATED';
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
                    'ACTIVITY_TYPE' => "QUOTATION {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} a Quotation from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                    'message' => 'Quote Saved Successfully',
                    'data' => $data,
                    'type' => $quote_id != null ? 'update' : 'insert'
                ]);
            } else {
                throw new Exception('Failed to create new request.');
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

        $total_quotes = $this->Quotes_model->get_quotes('total', $limit, $currentPage, $filters, $isAuthorized, $search);
        $quotes = $this->Quotes_model->get_quotes('list', $limit, $currentPage, $filters, $isAuthorized, $search);

        $response = [
            'pagination' => [
                'total_records' => $total_quotes,
                'total_pages' => generatePages($total_quotes, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'quotes' => $quotes,
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

        // Validate input and check if `requestUUID` is provided
        if (!$data || !isset($data['searchkey']) || !isset($data['searchvalue'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid JSON input or missing quotes search value or search key'
                ]));
        }

        // Retrieve Request details using the provided quoteUUID
        $searchkey = $data['searchkey'];
        $searchvalue = $data['searchvalue'];
        $requestData = $this->Quotes_model->get_quote_by_searchkey($searchkey, $searchvalue);

        // Check if Request data exists
        if (empty($requestData['header'])) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Request not found'
                ]));
        }

        // Successful response with Request data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Request details retrieved successfully',
                'data' => $requestData
            ]));
    }

    function delete($requestId)
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

        // Validate the Request ID
        if (empty($requestId) || !is_numeric($requestId)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid Request ID.']));
            return;
        }

        // Attempt to delete the Request
        $result = $this->Quotes_model->delete_quote_by_id($requestId);
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => 'Request deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the Request.']));
        }
    }

    function ConvertNewQuote($requestId)
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

        // Validate the Request ID
        if (empty($requestId) || !is_numeric($requestId)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid Request ID.']));
            return;
        }

        // Check if lead is already converted to contact
        //   $quote = $this->Quotes_model->get_quote_by_quote_id($requestId ?? 0);
        //   if (!empty($quote)) {
        //       $this->sendHTTPResponse(409, [
        //           'status' => 'error',
        //           'code' => 409,
        //           'error' => 'The lead with the provided ID has already been converted to a contact.',
        //           'message' => 'The lead with the provided ID has already been converted to a contact.'
        //       ]);
        //       return;
        //   }

        // Attempt to delete the Request
        $result = $this->Quotes_model->convert_new_quote_by_id($requestId);
        if ($result) {
            $action_type = "CONVERTED";
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
                'ACTIVITY_TYPE' => "NEW QUOTATION CREATED FROM OLD QUOTE",
                'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} old Quotation to new Quotation from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                ->set_output(json_encode(['status' => true, 'message' => 'Convert to new Quote successfully.', 'quote' => $result]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to convert to new quotation']));
        }
    }
    public function get_request_numbers()
    {
        // Check if the authentication is valid
        $isAuthorized = $this->isAuthorized();
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Unauthorized access.']));
            return;
        }

        // Fetch request numbers from the database
        $requestNumbers = $this->db->select('UUID, REQUEST_NUMBER')
            ->from('xx_crm_req_header')
            ->get()
            ->result_array();

        // Check if data exists
        if (empty($requestNumbers)) {
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'No request numbers found.']));
            return;
        }

        // Return the data
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'data' => $requestNumbers
            ]));
    }

    function fetchClientRequests($ClientID)
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

        // Validate input and check if `requestUUID` is provided
        if (!$ClientID) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid Client ID'
                ]));
        }
        $data = $this->quotes_model->fetchClientRequests($ClientID);

        // Successful response with Request data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Request details retrieved successfully',
                'data' => $data

            ]));
    }

    function clientsQuotes($ClientID)
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

        // Validate input and check if `requestUUID` is provided
        if (!$ClientID) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid Client ID'
                ]));
        }
        // Successful response with Request data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Request details retrieved successfully',
                'data' => $this->quotes_model->fetchClientQuotes($ClientID)
            ]));
    }


    // Create new po based in the quotation
    function createFromRequest($requestID)
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
        // if (isset($isAuthorized['role']) && $isAuthorized['role'] !== 'admin') {
        //     $this->output
        //         ->set_content_type('application/json')
        //         ->set_status_header(403) // 403 Forbidden status code
        //         ->set_output(json_encode(['error' => 'You do not have permission to perform this action.']));
        //     return;
        // }

        // Validate the Request ID
        if (empty($requestID) || !is_numeric($requestID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid Request ID.']));
            return;
        }

        $request = $this->Request_model->get_request_by_search_term("ID", $requestID);
        if (empty($request ?? []) || empty($request['header'] ?? [])) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(404) //
                ->set_output(json_encode(['status' => false, 'message' => 'Request details not found to create new PO']));
            return;
        }

        // Attempt to delete the Request
        $result = $this->Quotes_model->create_new_quote_from_request($requestID, $isAuthorized['userid'] ?? 0, $isAuthorized['role'] ?? '', $request);
        if ($result) {

            $action_type = "CONVERTED";
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
                'ACTIVITY_TYPE' => "REQUEST CONVERTED TO QUOTE",
                'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} request to new Quotation from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                ->set_output(json_encode(['status' => true, 'message' => 'Quotations Created from Request with ID' . $requestID, 'quote' => $result]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to create quote from request']));
        }
    }

    public function export_csv()
    {
        $search = $this->input->get('search');
        $data = $this->Quotes_model->get_quotes('list', 9999999999999, 1, [], [], $search, 'export');
        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="quotation_export_' . date('Ymd_His') . '.csv"');

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
