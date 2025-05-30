<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Purchase extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function new($po_id = null)
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
            // $this->form_validation->set_rules('quotation_number', 'Quotation Number', 'required');
            // $this->form_validation->set_rules('REQUEST_ID', 'Request Number', 'required');
            $this->form_validation->set_rules('ORG_ID', 'Org ID', 'required');
            $this->form_validation->set_rules('COMPANY_NAME', 'Company Name', 'required');
            $this->form_validation->set_rules('COMPANY_ADDRESS', 'Company Address', 'required');
            $this->form_validation->set_rules('EMAIL_ADDRESS', 'Email Address', 'required|valid_email');
            $this->form_validation->set_rules('CONTACT_NUMBER', 'Contact Number', 'required|numeric');


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
            $uploadPath = './uploads/purchase/';
            $allowedTypes = ['jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'gif', 'GIF', 'pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'xls', 'XLS', 'xlsx', 'XLSX', 'ppt', 'PPT', 'pptx', 'PPTX', 'txt', 'TXT', 'odt', 'ODT', 'rtf', 'RTF', 'csv', 'CSV'];

            $uploadedFiles = null;

            // Check if files are attached
            if (!empty($_FILES['files']['name'][0])) {
                $uploadedFiles = upload_multiple_files($_FILES['files'], $uploadPath, $allowedTypes);
            }


            // Retrieve POST data and sanitize it
            $data = $this->input->post();

            $data['UPLOADED_FILES'] = $uploadedFiles ?? [];
            $data = array_map([$this->security, 'xss_clean'], $data);


            // Save Data to the product table
            $created = $this->Purchase_model->purchase_det($po_id, $data, $isAuthorized['userid']);
            if ($created) {
                $action_type = $po_id != null ? 'UPDATED' : 'CREATED';
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
                    'ACTIVITY_TYPE' => "PO {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} a Purchase Order from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                    'message' => 'Purchase created successfully.',
                    'data' => $data,
                    'type' => $po_id != '' ? 'update' : 'insert'
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

        $total_po = $this->Purchase_model->get_req('total', $limit, $currentPage, $filters, $isAuthorized, $search);
        $po_list = $this->Purchase_model->get_req('list', $limit, $currentPage, $filters, $isAuthorized, $search);

        $response = [
            'pagination' => [
                'total_records' => $total_po,
                'total_pages' => generatePages($total_po, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'po' => $po_list,
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

        // Validate input and check if `poUUID` is provided
        if (!$data || !isset($data['searchkey']) || !isset($data['searchvalue'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid Search key and Search value'
                ]));
        }

        // Retrieve Request details using the provided poUUID
        $searchkey = $data['searchkey'];
        $searchvalue = $data['searchvalue'];
        $requestData = $this->Purchase_model->get_request_by_searchkey($searchkey, $searchvalue);

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
    function fetchClientRequests($ClientID)
    {
        echo json_encode($this->Purchase_model->fetchClientRequests($ClientID));
    }
    function delete($poUUID)
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
        if (empty($poUUID) || !is_numeric($poUUID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid Request ID.']));
            return;
        }

        // Attempt to delete the Request
        $result = $this->Purchase_model->delete_Request_by_id($poUUID);
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

    // Create new po based in the quotation
    function createFromQuote($quoteID)
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
        if (empty($quoteID) || !is_numeric($quoteID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid Quotation ID.']));
            return;
        }

        $quote = $this->Quotes_model->get_quote_by_searchkey("QUOTE_ID", $quoteID);
        if (empty($quote ?? []) || empty($quote['header'] ?? [])) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(404) //
                ->set_output(json_encode(['status' => false, 'message' => 'Quotation details not found to create new PO']));
            return;
        }

        // Attempt to delete the Request
        $result = $this->Purchase_model->create_new_po_from_quote($quoteID, $isAuthorized['userid'] ?? 0, $isAuthorized['role'] ?? '');
        if ($result) {
            $action_type = 'CONVERTED';
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
                'ACTIVITY_TYPE' => "QUOTATION CONVERTED TO PO",
                'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} a Quote to PO from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
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
                ->set_output(json_encode(['status' => true, 'message' => 'PO Created from Quotation with ID' . $quoteID, 'po' => $result]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to create po from quotation']));
        }
    }

    // Function to get data of the open orders
    function open_orders()
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

        $total_open_pos = $this->Purchase_model->get_po_for_tracking('total', $limit, $currentPage, $filters, $isAuthorized['userid'], $isAuthorized['role']);
        $open_pos = $this->Purchase_model->get_po_for_tracking('list', $limit, $currentPage, $filters, $isAuthorized['userid'], $isAuthorized['role']);

        $response = [
            'pagination' => [
                'total_records' => $total_open_pos,
                'total_pages' => generatePages($total_open_pos, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'open_pos' => $open_pos,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function po_track_detail()
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

        $po_num = $this->input->get('po', TRUE); // TRUE enables XSS filtering
        $product = $this->input->get('product', TRUE);

        // Validate required input values
        if (empty($po_num) || empty($product)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Both PO number and product code are required'
                ]));
        }

        $trackerData = $this->Purchase_model->get_po_tracker_details($po_num, $product);

        // Check if Request data exists
        if (empty($trackerData)) {
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
                'message' => 'Tracker details retrieved successfully',
                'data' => $trackerData
            ]));
    }

    public function export_csv()
    {
        $search = $this->input->get('search');

        $data = $this->Purchase_model->get_req('list', 9999999999999, 1, [], [], $search, 'export');



        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="purchase_export_' . date('Ymd_His') . '.csv"');

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
