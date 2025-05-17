<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Application extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// Get the application_id and application_name from the query parameters
		$application_id = $this->input->get('application_id');
		$application_name = $this->input->get('application_name');

		// Validate the application_name to ensure it corresponds to a valid method
		if (method_exists($this, $application_name)) {
			try {
				// Call the dynamic method with the application_id
				$this->$application_name($application_id);
			} catch (Exception $e) {
				// Handle any exceptions that occur during method execution
				log_message('error', 'Error executing function: ' . $e->getMessage());
				show_error('An error occurred while processing your request.', 500);
			}
		} else {
			// If the method doesn't exist, show a 404 error
			show_404();
		}
	}


	function credit_application($application_id)
	{
		$data['application_id'] = $application_id;
		$data['view_path'] = 'pages/print/applications/credit_form';
		$this->load->view('pages/print/applications/credit_form', $data);
	}
}

