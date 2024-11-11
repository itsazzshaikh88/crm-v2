<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Clients extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function new($client_id = null)
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
            $this->form_validation->set_rules('COMPANY_NAME', 'Company Name', 'required');
            $this->form_validation->set_rules('STATUS', 'Status', 'required');
            $this->form_validation->set_rules('SITE_NAME', 'Site Name', 'required');
            $this->form_validation->set_rules('ADDRESS_LINE_1', 'Client Address is Required', 'required');
            $this->form_validation->set_rules('BILLING_ADDRESS', 'Billing Address', 'required');
            $this->form_validation->set_rules('SHIPPING_ADDRESS', 'Shipping Address', 'required');
            $this->form_validation->set_rules('CITY', 'City Name', 'required');
            $this->form_validation->set_rules('STATE', 'State Name', 'required');
            $this->form_validation->set_rules('COUNTRY', 'Country Name', 'required');
            $this->form_validation->set_rules('ZIP_CODE', 'Zip Code', 'required');
            $this->form_validation->set_rules('PAYMENT_TERM', 'Payment Term', 'required');
            $this->form_validation->set_rules('CREDIT_LIMIT', 'Credit Limit', 'required');
            $this->form_validation->set_rules('CURRENCY', 'Currency Code', 'required');
            $this->form_validation->set_rules('ORDER_LIMIT', 'Order Limit', 'required');

            if ($client_id == null) {
                $this->form_validation->set_rules('PASSWORD', 'Account Password', 'required');
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

            // Retrieve POST data and sanitize it
            $data = $this->input->post();
            $data = array_map([$this->security, 'xss_clean'], $data);

            if ($client_id == null) {
                // Check if the user is already registered with the existing email address
                $client = $this->User_model->get_user_by_email($data['EMAIL']);
                if (!empty($client)) {
                    $this->sendHTTPResponse(409, [
                        'status' => 'error',
                        'code' => 409,
                        'error' => 'Conflict',
                        'message' => 'A client with this email already exists.'
                    ]);
                    return;
                }
            }

            // Save Data to the product table
            $created = $this->User_model->add_client($client_id, $data, $isAuthorized['userid']);
            if ($created) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Client Details Saved successfully.',
                    'data' => $data
                ]);
            } else {
                throw new Exception('Failed to create new client.');
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

        $total_clients = $this->User_model->get_clients('total', $limit, $currentPage, $filters);
        $clients = $this->User_model->get_clients('list', $limit, $currentPage, $filters);

        $response = [
            'pagination' => [
                'total_records' => $total_clients,
                'total_pages' => generatePages($total_clients, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'clients' => $clients,
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
        if (!$data || !isset($data['clientUUID'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid JSON input or missing clientUUID'
                ]));
        }

        // Retrieve product details using the provided clientUUID
        $clientUUID = $data['clientUUID'];
        $client = $this->User_model->get_client_by_uuid($clientUUID);

        // Check if product data exists
        if (empty($client)) {
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
                'data' => $client
            ]));
    }

    function delete($clientID)
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
        if (empty($clientID) || !is_numeric($clientID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid product ID.']));
            return;
        }

        // Attempt to delete the product
        $result = $this->User_model->delete_client_by_id($clientID);
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => 'Product deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the product.']));
        }
    }
}
