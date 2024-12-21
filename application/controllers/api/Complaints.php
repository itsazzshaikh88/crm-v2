<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Complaints extends Api_controller
{
	public function __construct()
	{
		parent::__construct();
	}

	function new($complaint_id = null)
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
			$this->form_validation->set_rules('CUSTOMER_NAME', 'Customer Name', 'required');
			$this->form_validation->set_rules('COMPLAINT_RAISED_BY', 'Complaint Raised By', 'required');
			$this->form_validation->set_rules('MOBILE_NUMBER', 'Conatct Number', 'required');
			$this->form_validation->set_rules('EMAIL', 'Email Address', 'required');
			$this->form_validation->set_rules('COMPLAINT', 'Complaint Details', 'required|min_length[5]');

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
			$uploadPath = './uploads/requests/';
			$allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'sql'];
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
			$created = $this->Complaint_model->add_request($complaint_id, $data, $isAuthorized['userid'], $isAuthorized['role']);
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

	function resolve($resolve_id = null)
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



			// Directory to upload files
			$uploadPath = './uploads/requests/';
			$allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'sql'];
			$uploadedFiles = null;

			// Check if files are attached
			if (!empty($_FILES['files']['name'][0])) {
				$uploadedFiles = upload_multiple_files($_FILES['files'], $uploadPath, $allowedTypes);
			}


			// Retrieve POST data and sanitize it
			$data = $this->input->post();
			$data['UPLOADED_FILES'] = $uploadedFiles ?? [];

			$data = array_map([$this->security, 'xss_clean'], $data);


			if ($data['STATUS'] == 'Closed') {
				// Set validation rules
				$this->form_validation->set_rules('RECEIVED_BY', 'Received By', 'required');
				$this->form_validation->set_rules('STATUS', 'Status', 'required');
				$this->form_validation->set_rules('ESCALATION_NEEDED', 'Escalation Needed', 'required');
				$this->form_validation->set_rules('ACTIONS', 'Specific Action', 'required|min_length[5]');
				$this->form_validation->set_rules('ROOT_CAUSE', 'Root Cause', 'required|min_length[5]');
				$this->form_validation->set_rules('OUTCOME', 'Process Measure Outcome', 'required|min_length[5]');

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
			}
			// Save Data to the Request table
			$created = $this->Complaint_model->add_resolved_request($resolve_id, $data, $isAuthorized['userid'], $isAuthorized['role']);
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

	// Method to fetch user details
	public function getUserDetail()
	{
		// Get raw POST data from the input stream (for JSON data)
		$json_input = file_get_contents('php://input');

		// Decode JSON input to an associative array
		$data = json_decode($json_input, true);

		// Check if the data is valid
		if (json_last_error() !== JSON_ERROR_NONE) {
			$this->output->set_status_header(400);
			echo json_encode(['error' => 'Invalid JSON input']);
			return;
		}

		// Get user_id and email from the decoded data
		$user_id = isset($data['user_id']) ? $data['user_id'] : null;
		$email = isset($data['email']) ? $data['email'] : null;

		// Input validation
		if (empty($user_id) || empty($email)) {
			// Invalid input
			$this->output->set_status_header(400);
			echo json_encode(['error' => 'User ID and Email are required']);
			return;
		}

		// Fetch user details using the model
		$user = $this->User_model->getUserDetail($user_id, $email);

		if ($user) {
			// Return user details in JSON format
			$this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode($user));
		} else {
			// User not found
			$this->output->set_status_header(404);
			echo json_encode(['error' => 'User not found']);
		}
	}


	public function list()
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
		$search = isset($data['search']) ? $data['search'] : [];
		// Ensure filters are in the correct format (array)
		if (!is_array($filters)) {
			$filters = [];
		}

		$total_complaints = $this->Complaint_model->get_complaints('total', $limit, $currentPage, $filters, $search);
		$complaints = $this->Complaint_model->get_complaints('list', $limit, $currentPage, $filters, $search);

		$response = [
			'pagination' => [
				'total_records' => $total_complaints,
				'total_pages' => generatePages($total_complaints, $limit),
				'current_page' => $currentPage,
				'limit' => $limit
			],
			'complaints' => $complaints,
		];

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($response));
	}

	public function getComplaintDetails()
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
		$id = isset($data['Id']) ? $data['Id'] : 0;


		$complaints = $this->Complaint_model->getComplaintDetail($id);


		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($complaints));
	}

	public function getCardStats()
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
		$filters = isset($data['filters']) ? $data['filters'] : [];
		// Ensure filters are in the correct format (array)
		if (!is_array($filters)) {
			$filters = [];
		}

		$totalStats = $this->Complaint_model->getCardStats($filters);


		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($totalStats));
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
		if (!$data || !isset($data['complaintUUID'])) {
			return $this->output
				->set_status_header(400)
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => 'error',
					'code' => 400,
					'message' => 'Invalid JSON input or missing complaintUUID'
				]));
		}

		// Retrieve Request details using the provided complaintUUID
		$complaintUUID = $data['complaintUUID'];
		$complaintData = $this->Complaint_model->get_request_by_uuid($complaintUUID);

		// Check if Request data exists
		if (empty($complaintData['header'])) {
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
				'data' => $complaintData
			]));
	}

	public function resolveDetail()
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
		if (!$data || !isset($data['resolveUUID'])) {
			return $this->output
				->set_status_header(400)
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => 'error',
					'code' => 400,
					'message' => 'Invalid JSON input or missing complaintUUID'
				]));
		}

		// Retrieve Request details using the provided complaintUUID
		$resolveUUID = $data['resolveUUID'];
		$resolvedComplaintData = $this->Complaint_model->get_request_by_resolveUUId($resolveUUID);

		// Check if Request data exists
		if (empty($resolvedComplaintData)) {
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
				'data' => $resolvedComplaintData
			]));
	}

	function delete($complaintID)
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

		// Check if the user is not admin or client
		if (!isset($isAuthorized['role']) || ($isAuthorized['role'] != 'admin' && $isAuthorized['role'] != 'client')) {
			$this->output
				->set_content_type('application/json')
				->set_status_header(403) // 403 Forbidden status code
				->set_output(json_encode(['error' => 'You do not have permission to perform this action.']));
			return;
		}


		// Validate the Request ID
		if (empty($complaintID) || !is_numeric($complaintID)) {
			$this->output
				->set_content_type('application/json')
				->set_status_header(400) // 400 Bad Request status code
				->set_output(json_encode(['error' => 'Invalid Request ID.']));
			return;
		}

		// Attempt to delete the Request
		$result = $this->Complaint_model->delete_Request_by_id($complaintID);
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
}
