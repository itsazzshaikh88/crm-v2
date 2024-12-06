<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Contacts extends Api_controller
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
            $fields = ['FIRST_NAME', 'LAST_NAME', 'PHONE', 'COMPANY_NAME', 'JOB_TITLE', 'DEPARTMENT', 'CONTACT_SOURCE', 'STATUS', 'ASSIGNED_TO', 'NOTES', 'PREFERRED_CONTACT_METHOD', 'ADDRESS'];
            foreach ($fields as $field):
                $this->form_validation->set_rules($field, ucwords(str_replace("_", "", $field)), 'required');
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

            // Check if the lead is already registered with the existing email address
            $contact = $this->Contact_model->get_contact_by_email($data['EMAIL']);
            if (!empty($contact)) {
                $this->sendHTTPResponse(409, [
                    'status' => 'error',
                    'code' => 409,
                    'error' => 'Conflict',
                    'message' => 'A Contact with this email already exists.'
                ]);
                return;
            }


            // Save Data to the product table
            $created = $this->Contact_model->add_contact($data, $isAuthorized['userid']);
            $newlyCreatedContact = $this->Contact_model->get_contact_by_uuid($data['UUID']);

            if ($created) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Contact Created Successfully',
                    'type' => 'insert',
                    'data' => $newlyCreatedContact,
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

    function update($contactID)
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
            if (empty($contactID) || !is_numeric($contactID)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400) // 400 Bad Request status code
                    ->set_output(json_encode(['error' => 'Invalid Contact ID.']));
                return;
            }

            // Set validation rules
            $fields = ['FIRST_NAME', 'LAST_NAME', 'PHONE', 'COMPANY_NAME', 'JOB_TITLE', 'DEPARTMENT', 'CONTACT_SOURCE', 'STATUS', 'ASSIGNED_TO', 'NOTES', 'PREFERRED_CONTACT_METHOD', 'ADDRESS'];
            foreach ($fields as $field):
                $this->form_validation->set_rules($field, ucwords(str_replace("_", "", $field)), 'required');
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

            // Check if lead details are present in table with existing ID
            $contact = $this->Contact_model->get_contact_by_id($contactID ?? 0);
            if (empty($contact)) {
                $this->sendHTTPResponse(409, [
                    'status' => 'error',
                    'code' => 404,
                    'error' => 'Not Found',
                    'message' => 'A Lead with provided Id does not found to update.'
                ]);
                return;
            }


            // Save Data to the lead table
            $updated = $this->Contact_model->update_contact($contactID, $data, $isAuthorized['userid']);
            $updatedContact = $this->Contact_model->get_contact_by_id($contactID);

            if ($updated) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Contact Updated Successfully',
                    'type' => 'update',
                    'data' => $updatedContact,
                ]);
            } else {
                throw new Exception('Error saving contact details');
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

        $total_contacts = $this->Contact_model->get_contacts('total', $limit, $currentPage, $filters);
        $contacts = $this->Contact_model->get_contacts('list', $limit, $currentPage, $filters);

        $response = [
            'pagination' => [
                'total_records' => $total_contacts,
                'total_pages' => generatePages($total_contacts, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'contacts' => $contacts,
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
        if (!$data || !isset($data['contactUUID'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid JSON input or missing contact ID'
                ]));
        }

        // Retrieve product details using the provided contactUUID
        $contactUUID = $data['contactUUID'];
        $contact = $this->Contact_model->get_contact_by_uuid($contactUUID);

        // Check if product data exists
        if (empty($contact)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Contact details not found'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Contact details retrieved successfully',
                'data' => $contact
            ]));
    }

    function delete($contactID)
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

        // Validate the Contact ID
        if (empty($contactID) || !is_numeric($contactID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid Contact ID.']));
            return;
        }

        // Attempt to delete the Contact
        $result = $this->Contact_model->delete_contact_by_id($contactID);
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => 'Contact deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the Contact.']));
        }
    }
}
