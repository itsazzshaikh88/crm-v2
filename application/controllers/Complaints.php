<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Complaints extends App_Controller

{

	public function __construct()
	{
		parent::__construct();
		$this->secret_key = APP_SECRET_KEY;
		$this->isAuthenticated();
		$this->userDetails = $this->getUserDetails();
		// Share user details with all views
		$this->load->vars(['loggedInUser' => $this->userDetails]);
	}
	public function index()
	{
		$data['view_path'] = 'pages/complaints/home';
		$data['page_title'] = 'Customer Complaints - CRM Application';
		$data['page_heading'] = 'Customer Complaints';
		$data['scripts'] = ['assets/js/pages/complaints/home.js'];
		$data['navlink'] = 'feedback';
		$usertype = $loggedInUser['userrole'] ?? 'Guest';
		$data['toolbar'] = ['name' => 'new-complaints', 'action' => 'form'];
		$this->load->view('layout', $data);
	}
	public function list($type = 'Total')
	{
		$data['view_path'] = 'pages/complaints/list';
		$data['page_title'] = 'Requests - CRM Application';
		// $data['scripts'] = [base_url('assets/js/pages/complaint/details.js'), base_url('assets/js/pages/complaint/listing.js')];
		$data['page_heading'] = ucfirst(($type)) . ' Complaints';
		$data['type'] = ucfirst(($type));
		$data['scripts'] = ['assets/js/pages/complaints/list.js'];
		$data['navlink'] = 'feedback';
		$data['toolbar'] = ['name' => 'new-complaints', 'action' => 'form'];
		$this->load->view('layout', $data);
	}

	public function new($uuid = null)
	{
		$this->validateUUID();
		$data['uuid'] = $uuid;
		$data['view_path'] = 'pages/complaints/new';
		$data['page_title'] = 'Raise New Complaint - CRM Application';
		$data['page_heading'] = 'Raise New Complaint';
		$data['navlink'] = 'feedback';
		$data['css_files'] = ['assets/css/pages/complaint/new.css'];
		$data['scripts'] = [
			'assets/js/pages/clients/modals/modal-list.js',
			'assets/js/pages/clients/modals/create-new-client.js',
			'assets/js/pages/complaints/new.js'
		];
		// $data['toolbar'] = ['name' => 'new-request', 'action' => 'form'];
		$data['toolbar'] = ['name' => 'new-complaints', 'action' => 'form'];

		$this->load->view('layout', $data);
	}

	public function resolve($id = null, $uuid = null)
	{
		$this->validateUUID();
		$data['uuid'] = $uuid;
		$data['view_path'] = 'pages/complaints/resolve';
		$data['page_title'] = 'Resolve Complaint - CRM Application';
		$data['page_heading'] = 'Resolve Complaint';
		$data['navlink'] = 'feedback';
		$data['css_files'] = ['assets/css/pages/complaint/new.css'];
		$data['complaintUUID'] = $this->Complaint_model->getComplaintUUID($id);
		$data['scripts'] = [
			'assets/js/pages/clients/modals/modal-list.js',
			'assets/js/pages/clients/modals/create-new-client.js',
			'assets/js/pages/complaints/resolve.js'
		];
		// $data['toolbar'] = ['name' => 'new-request', 'action' => 'form'];
		$data['toolbar'] = ['name' => 'new-complaints', 'action' => 'form', 'uuid' => $data['complaintUUID']['UUID']];
		$data['toolbar'] = ['name' => 'new-complaints', 'action' => 'view', 'uuid' => $data['complaintUUID']['UUID'], 'id' => $id];

		$this->load->view('layout', $data);
	}

	public function view($uuid = null)
	{
		$data['uuid'] = $uuid;
		$data['view_path'] = 'pages/complaints/view';
		$data['page_title'] = 'Raised Complaint - CRM Application';
		$data['page_heading'] = 'Raised Complaint';
		$data['navlink'] = 'feedback';
		$data['css_files'] = [];
		$data['scripts'] = ['assets/js/pages/complaints/view.js'];
		$data['complaintID'] = $this->Complaint_model->getComplaintId($uuid);
		$data['toolbar'] = ['name' => 'new-complaints', 'action' => 'form', 'id' => $data['complaintID']['COMPLAINT_ID'], 'uuid' => $uuid];
		$this->load->view('layout', $data);
	}
}
