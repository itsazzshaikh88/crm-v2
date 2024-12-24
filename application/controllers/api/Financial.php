<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Financial extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
        
    }

    function credit_application($credit_id = null)
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
            $this->form_validation->set_rules('APPLICATION_DATE', 'Application Date', 'required');
            $this->form_validation->set_rules('CREDIT_VALUE', 'Credit Value', 'required|numeric');
            $this->form_validation->set_rules('CREDIT_IN_WORDS', 'Credit Limit in Words', 'required|min_length[5]');
            $this->form_validation->set_rules('WITHIN_DAYS', 'Within Days', 'required|numeric');
            $this->form_validation->set_rules('COMPANY_NAME', 'Company Name', 'required');
            $this->form_validation->set_rules('CONTACT_PERSON', 'Contact Person', 'required');
            $this->form_validation->set_rules('CONTACT_PERSON_TITLE', 'Contact Person Title', 'required');
            $this->form_validation->set_rules('CONTACT_EMAIL', 'Contact Email', 'required|valid_email');
            $this->form_validation->set_rules(
                'PHONE',
                'Phone Number',
                'required|regex_match[/^\+[1-9]{1}[0-9]{1,3}[0-9]{6,14}$/]',
                array(
                    'required' => 'The {field} field is required.',
                    'regex_match' => 'The {field} field must contain a valid phone number with country code (e.g., +1234567890).'
                )
            );
            $this->form_validation->set_rules('COMPANY_EMAIL', 'Company Email', 'required|valid_email');
            $this->form_validation->set_rules('CITY', 'City', 'required');
            $this->form_validation->set_rules('STATE', 'State/Province', 'required');
            $this->form_validation->set_rules('COUNTRY', 'Country', 'required');
            $this->form_validation->set_rules('ZIP_CODE', 'Zip Code', 'required|numeric');
            $this->form_validation->set_rules('ADDRESS_SPAN', 'Address Span', 'required');
            $this->form_validation->set_rules('BUSINESS_START_DATE', 'Business Start Date', 'required');
            $this->form_validation->set_rules('BUSINESS_TYPE', 'Business Type', 'required');
            $this->form_validation->set_rules('BANK_NAME', 'Bank Name', 'required');
            $this->form_validation->set_rules('BANK_LOCATION', 'Bank Location', 'required');
            $this->form_validation->set_rules('ACCOUNT_NUMBER', 'Account Number', 'required|numeric');
            $this->form_validation->set_rules('IBAN_NUMBER', 'IBAN Number', 'required');
            $this->form_validation->set_rules('SWIFT_CODE', 'SWIFT Code', 'required');
            $this->form_validation->set_rules('CRN_NUMBER', 'CRN Number', 'required');
            $this->form_validation->set_rules('DATE_OF_ISSUANCE', 'Date of Issuance', 'required');
            $this->form_validation->set_rules('DATE_OF_EXPIRY', 'Date of Expiry', 'required');
            $this->form_validation->set_rules('COMPANY_LOCATION', 'Company Location', 'required');
            $this->form_validation->set_rules('PAID_UP_CAPITAL', 'Paid Up Capital', 'required|numeric');
            $this->form_validation->set_rules('COMPANY_OWNER', 'Company Owner', 'required');
            $this->form_validation->set_rules('PERCENTAGE_OWNER', 'Percentage of Ownership', 'required|numeric');
            $this->form_validation->set_rules('TOP_MANAGER', 'Top Manager', 'required');
            $this->form_validation->set_rules('SIGN_NAME', 'Signatory Name', 'required');
            $this->form_validation->set_rules('SIGN_POSITION', 'Signatory Position', 'required');
            $this->form_validation->set_rules('SIGN_SPECIMEN', 'Sign Specimen', 'required');
            $this->form_validation->set_rules('BUS_ACTIVITIES', 'Business Activities', 'required');
            $this->form_validation->set_rules('GM_NAME', 'General Manager Name', 'required');
            $this->form_validation->set_rules('PUR_MGR_NAME', 'Purchasing Manager Name', 'required');
            $this->form_validation->set_rules('FIN_MGR_NAME', 'Finance Manager Name', 'required');
            $this->form_validation->set_rules('ZPIL_SIGN', 'ZPIL Signature', 'required');
            $this->form_validation->set_rules('ZPIL_SIGNATORY_NAME', 'ZPIL Signatory Name', 'required');
            $this->form_validation->set_rules('ZPIL_SIGN_POSN', 'ZPIL Signatory Position', 'required');
            $this->form_validation->set_rules('ZPIL_DATE', 'ZPIL Date', 'required');
            $this->form_validation->set_rules('CLIENT_SIGN', 'Client Signature', 'required');
            $this->form_validation->set_rules('CLIENT_STAMP', 'Client Stamp', 'required');
            $this->form_validation->set_rules('CLIENT_SIGN_NAME', 'Client Signatory Name', 'required');
            $this->form_validation->set_rules('CLIENT_SIGN_DATE', 'Client Sign Date', 'required');
            $this->form_validation->set_rules('CHAMBER_OF_COMMERCE', 'Chamber of Commerce', 'required');

            // Get the user type from session or logged-in user data
            $usertype = $isAuthorized['role'] ?? 'Guest';

            if ($usertype === 'admin') {

                $this->form_validation->set_rules('DIR_SALES_COMMENTS', 'Sales Director Comments', 'required');
                $this->form_validation->set_rules('SALES_MGR_COMMENTS', 'Sales Manager Comments', 'required');
                $this->form_validation->set_rules('GM_COMMENTS', 'General Manager Comments', 'required');
                $this->form_validation->set_rules('CREDIT_DIV_COMMENTS', 'Credit Division Comments', 'required');
                $this->form_validation->set_rules('FIN_MGR_COMMENTS', 'Finance Manager Comments', 'required');
                $this->form_validation->set_rules('MGMT_COMMENTS', 'Management Comments', 'required');
                $this->form_validation->set_rules('REC_CREDIT_LIMIT', 'Recommended Credit Limit', 'required|numeric');
                $this->form_validation->set_rules('REC_CREDIT_PERIOD', 'Recommended Credit Period', 'required|numeric');
                $this->form_validation->set_rules('APPROVED_FINANCE', 'Finance Approval', 'required');
                $this->form_validation->set_rules('APPROVED_MANAGEMENT', 'Management Approval', 'required');
                // $this->form_validation->set_rules('CRN_ATTACHMENT', 'CRN Attachment', 'required');
                // $this->form_validation->set_rules('BANK_CERTIFICATE', 'Bank Certificate', 'required');
                // $this->form_validation->set_rules('OWNER_ID', 'Owner ID', 'required');
            }


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


            // Process single file uploads
            $uploadPath = './uploads/credits/';
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx'];
            $uploadedFiles = [];

            // ATTACHMENT file
            if (!empty($_FILES['ATTACHMENT']['name'])) {
                $_FILES['file'] = $_FILES['ATTACHMENT']; // Assign file input for upload
                $uploadedFiles['CRN_ATTACHMENT'] = upload_single_file($_FILES['file'], $uploadPath, $allowedTypes);
            }

            // CERTIFICATE file
            if (!empty($_FILES['CERTIFICATE']['name'])) {
                $_FILES['file'] = $_FILES['CERTIFICATE']; // Assign file input for upload
                $uploadedFiles['BANK_CERTIFICATE'] = upload_single_file($_FILES['file'], $uploadPath, $allowedTypes);
            }

            // OWNER file
            if (!empty($_FILES['OWNER']['name'])) {
                $_FILES['file'] = $_FILES['OWNER']; // Assign file input for upload
                $uploadedFiles['OWNER_ID'] = upload_single_file($_FILES['file'], $uploadPath, $allowedTypes);
            }

            // Retrieve POST data and sanitize it
            $data = $this->input->post();

            $data = array_map([$this->security, 'xss_clean'], $data);

            // Add uploaded file data to $data
            $data['CRN_ATTACHMENT'] = $uploadedFiles['CRN_ATTACHMENT'] ?? null;
            $data['BANK_CERTIFICATE'] = $uploadedFiles['BANK_CERTIFICATE'] ?? null;
            $data['OWNER_ID'] = $uploadedFiles['OWNER_ID'] ?? null;



            // Save Data to the Request table
            $created = $this->Finance_model->add_credits($credit_id, $data, $isAuthorized['userid'], $isAuthorized['role']);
            if ($created) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Request Saved Successfully',
                    'data' => $data
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


    // =========================================

    function credit_application_list()
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

        // Add a filter for CUSTOMER_ID if the user is a client
        if ($isAuthorized['role'] === 'client') {
            $filters['ci.CUSTOMER_ID'] = $isAuthorized['userid'];
        }

        $total_credits = $this->Finance_model->get_credits('total', $limit, $currentPage, $filters);
        $credits = $this->Finance_model->get_credits('list', $limit, $currentPage, $filters);

        $response = [
            'pagination' => [
                'total_records' => $total_credits,
                'total_pages' => generatePages($total_credits, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'credits' => $credits,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    //   =========================================

    function delete($creditID)
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

        // Validate the Credit ID
        if (empty($creditID) || !is_numeric($creditID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Credit status code
                ->set_output(json_encode(['error' => 'Invalid Credit ID.']));
            return;
        }

        // Attempt to delete the Credit
        $result = $this->Finance_model->delete_Credit_by_id($creditID);
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => 'Credit deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the Credit.']));
        }
    }



    // =================================

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

        // Validate input and check if `creditUUID` is provided
        if (!$data || !isset($data['creditUUID'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid JSON input or missing creditUUID'
                ]));
        }

        // Retrieve credit details using the provided creditUUID
        $creditUUID = $data['creditUUID'];
        $creditData = $this->Finance_model->get_credit_application_by_uuid($creditUUID, $isAuthorized['role']);

        // Check if product data exists
        if (empty($creditData['credit'])) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Credit not found'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Credit details retrieved successfully',
                'data' => $creditData
            ]));
    }


    public function delete_file()
    {

        // Get the filename and field ID from the POST request
        $filename = isset($_POST['filename']) ? $_POST['filename'] : '';
        $fieldID = isset($_POST['fieldID']) ? $_POST['fieldID'] : '';
        $mainID = isset($_POST['mainID']) ? $_POST['mainID'] : '';

        // Validate inputs
        if (empty($filename) || empty($fieldID)) {
            echo json_encode(['success' => false, 'message' => 'Invalid input.']);
            exit;
        }

        // Define the directory where your files are stored
        $uploadDir = './uploads/credits/';

        // Construct the full path of the file to be deleted
        $filePath = $uploadDir . $filename;

        // Check if the file exists before attempting to delete
        if (file_exists($filePath)) {
            // Try deleting the file
            if (unlink($filePath)) {
                // File successfully deleted
                $isDeleted = $this->Finance_model->delete_file_data($mainID, $fieldID);

                if ($isDeleted) {
                    echo json_encode(['success' => true, 'message' => 'File and database entries deleted successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'File deleted, but database update failed.']);
                }
            } else {
                // Failed to delete the file
                echo json_encode(['success' => false, 'message' => 'Error deleting the file.']);
            }
        } else {
            // File not found
            echo json_encode(['success' => false, 'message' => 'File not found.']);
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

        // $total_financials = $this->Finance_model->get_financials('total', $limit, $currentPage, $filters);
        // $financials = $this->Finance_model->get_financials('list', $limit, $currentPage, $filters);


        $total_financials = '';
        $financials = [];

        $response = [
            'pagination' => [
                'total_records' => $total_financials,
                'total_pages' => generatePages($total_financials, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'financial' => $financials,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }


    function outstanding_list()
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

        // $total_outstanding = $this->Finance_model->get_outstanding('total', $limit, $currentPage, $filters);
        // $outstanding = $this->Finance_model->get_outstanding('list', $limit, $currentPage, $filters);


        $total_outstanding = '';
        $outstanding = [];

        $response = [
            'pagination' => [
                'total_records' => $total_outstanding,
                'total_pages' => generatePages($total_outstanding, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'outstanding' => $outstanding,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    function statements_list()
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

        // $total_statements = $this->Finance_model->get_statements('total', $limit, $currentPage, $filters);
        // $statements = $this->Finance_model->get_statements('list', $limit, $currentPage, $filters);


        $total_statements = '';
        $statements = [];

        $response = [
            'pagination' => [
                'total_records' => $total_statements,
                'total_pages' => generatePages($total_statements, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'statements' => $statements,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }


    function credit_report()
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

        // $total_statements = $this->Finance_model->get_credit('total', $limit, $currentPage, $filters);
        // $statements = $this->Finance_model->get_credit('list', $limit, $currentPage, $filters);


        $total_credit = '';
        $credit = [];

        $response = [
            'pagination' => [
                'total_records' => $total_credit,
                'total_pages' => generatePages($total_credit, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'credit' => $credit,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
}
