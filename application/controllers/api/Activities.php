<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Activities extends Api_controller
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

            $activity_type = strtolower($this->input->post('ACTION'));
            if (!isset($activity_type)) {
                $this->sendHTTPResponse(404, [
                    'status' => 404,
                    'error' => 'Data not found',
                    'message' => 'Activity type not found.'
                ]);
                return;
            }

            if (strtolower($activity_type) === 'call') {
                $this->form_validation->set_rules('CALL_DURATION', 'Call Duration', 'required');
                $this->form_validation->set_rules('CALL_PURPOSE', 'Call Purpose', 'required');
                $this->form_validation->set_rules('FOLLOW_UP_DATE', 'Follow up Date', 'required');
            } else if (strtolower($activity_type) === 'notes') {
                $this->form_validation->set_rules('NOTES', 'Note Details', 'required');
            } elseif (strtolower($activity_type) === 'meeting') {
                $this->form_validation->set_rules('LOCATION', 'Meeting Location', 'required');
                $this->form_validation->set_rules('AGENDA', 'Meeting Agenda', 'required');
                $this->form_validation->set_rules('ATTENDEES', 'Meeting Attendees', 'required');
                $this->form_validation->set_rules('NOTES', 'Meeting Outcome', 'required');
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

            $activity_UUID = $data["custom-activity-modal-" . $activity_type . "-ACTIVITY_UUID"] ?? '-';

            // Save Data to the product table
            $created = $this->Activity_model->add_activity($data, $isAuthorized['userid']);
            $newlyCreatedActivity = $this->Activity_model->get_activity_by_uuid($activity_UUID);

            if ($created) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => ucfirst($activity_type) . ' Activity Created Successfully',
                    'type' => 'insert',
                    'data' => $newlyCreatedActivity,
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

    function update($leadID)
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
            if (empty($leadID) || !is_numeric($leadID)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400) // 400 Bad Request status code
                    ->set_output(json_encode(['error' => 'Invalid Lead ID.']));
                return;
            }

            // Set validation rules
            $this->form_validation->set_rules('FIRST_NAME', 'First Name', 'required');
            $this->form_validation->set_rules('LAST_NAME', 'Last Name', 'required');
            $this->form_validation->set_rules('EMAIL', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('PHONE', 'Contact Number', 'required');
            $this->form_validation->set_rules('JOB_TITLE', 'Job Title', 'required');
            $this->form_validation->set_rules('STATUS', 'Status', 'required');
            $this->form_validation->set_rules('COMPANY_NAME', 'Company Name', 'required');
            $this->form_validation->set_rules('LEAD_SOURCE', 'Lead Source', 'required');
            $this->form_validation->set_rules('ASSIGNED_TO', 'Sales Person Name', 'required');


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
            $lead = $this->Lead_model->get_lead_by_id($leadID ?? 0);
            if (empty($lead)) {
                $this->sendHTTPResponse(409, [
                    'status' => 'error',
                    'code' => 404,
                    'error' => 'Not Found',
                    'message' => 'A Lead with provided Id does not found to update.'
                ]);
                return;
            }


            // Save Data to the lead table
            $updated = $this->Lead_model->update_lead($leadID, $data, $isAuthorized['userid']);
            $updatedLead = $this->Lead_model->get_lead_by_id($leadID);

            if ($updated) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Lead Updated Successfully',
                    'type' => 'update',
                    'data' => $updatedLead,
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

        $total_leads = $this->Lead_model->get_leads('total', $limit, $currentPage, $filters);
        $leads = $this->Lead_model->get_leads('list', $limit, $currentPage, $filters);

        $response = [
            'pagination' => [
                'total_records' => $total_leads,
                'total_pages' => generatePages($total_leads, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'leads' => $leads,
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
        if (!$data || !isset($data['leadID'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid JSON input or missing lead ID'
                ]));
        }

        // Retrieve product details using the provided leadID
        $leadID = $data['leadID'];
        $lead = $this->Lead_model->get_lead_and_activities_by_id($leadID);

        // Check if product data exists
        if (empty($lead)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Lead details not found'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Lead details retrieved successfully',
                'data' => $lead
            ]));
    }

    function delete($leadID)
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
        if (empty($leadID) || !is_numeric($leadID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid product ID.']));
            return;
        }

        // Attempt to delete the product
        $result = $this->User_model->delete_client_by_id($leadID);
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
