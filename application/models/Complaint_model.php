<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';

class Complaint_model extends App_model
{
	protected $product_table; // Holds the name of the user table
	protected $inventory_table; // Holds the name of the token table
	protected $variant_table; // Holds the name of the token table
	protected $category_table; // Holds the name of the token table
	protected $req_header_table; // Holds the name of the token table
	protected $req_lines_table; // Holds the name of the token table
	protected $comp_header_table; // Holds the name of the token table
	protected $comp_lines_table; // Holds the name of the token table
	protected $comp_resolution_table; // Holds the name of the token table

	public function __construct()
	{
		parent::__construct();
		$this->product_table = 'xx_crm_products'; // Initialize user table
		$this->inventory_table = 'xx_crm_product_inventory'; // Initialize token table
		$this->variant_table = 'xx_crm_product_variants'; // Initialize token table
		$this->category_table = 'xx_crm_product_categories'; // Initialize token table
		$this->req_header_table = 'xx_crm_req_header'; // Initialize token table
		$this->req_lines_table = 'xx_crm_req_lines'; // Initialize token table
		$this->comp_header_table = 'XX_CRM_COMPL_HEADER  '; // Initialize token table
		$this->comp_lines_table = 'XX_CRM_COMPL_LINES '; // Initialize token table
		$this->comp_resolution_table = 'XX_CRM_COMPL_RESOLUTION '; // Initialize token table
	}
	// Function to add or update product
	public function add_request($complaint_id, $data, $userid, $role)
	{

		$header_data = [
			'COMPLAINT_DATE' => $data['COMPLAINT_DATE'],
			'CLIENT_ID' => $data['CLIENT_ID'],
			'CUSTOMER_NAME' => $data['CUSTOMER_NAME'],
			'EMAIL' => $data['EMAIL'],
			'COMPLAINT_RAISED_BY' => $data['COMPLAINT_RAISED_BY'],
			'MOBILE_NUMBER' => $data['MOBILE_NUMBER'],
			'COMPLAINT' => $data['COMPLAINT'],
			'IS_RESOLVED' => false,
			'STATUS' => 'Active',
			'CREATED_AT' => date('Y-m-d'),
			'VERSION' => '1'
		];


		if (!in_array($complaint_id, [' ', '', 0, null])) {
			// check if the data is present 
			$this->db->where('COMPLAINT_ID', $complaint_id);
			$request = $this->db->get($this->comp_header_table)->row_array();
			// Append newly upoaded images
			if (isset($data['UPLOADED_FILES']) && !empty($data['UPLOADED_FILES'])) {
				$filesFromDB = $request['ATTACHMENTS'];
				if (!in_array($filesFromDB, ["", ' ', null, "\"\"", "\" \"", 'null', "''", "' '"])) {
					$decodedFiles = json_decode($filesFromDB, true);
					$filesToStore = array_merge($data['UPLOADED_FILES'], $decodedFiles);
					$header_data['ATTACHMENTS'] = json_encode($filesToStore);
				} else {
					$header_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);
				}
			}

			// Update existing product
			$this->db->where('COMPLAINT_ID', $complaint_id);
			$this->db->update($this->comp_header_table, $header_data);

			$this->addComplaintLines($complaint_id, $data);
			return true;
		} else {

			$header_data['CREATED_BY'] = $userid;
			$header_data['UUID'] = $data['UUID'];

			if (isset($data['UPLOADED_FILES']))
				$header_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);
			// Insert new product
			$inserted = $this->db->insert($this->comp_header_table, $header_data);
			if ($inserted) {
				// $inserted_id = $this->db->insert_id();
				$inserted_id = $this->get_column_value($this->comp_header_table, 'COMPLAINT_ID', ['UUID' => $header_data['UUID']]);

				// Create request_number in the required format
				$complaint_no = "COMP-" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
				// Update the request_number field for the newly inserted product
				$this->db->where('COMPLAINT_ID', $inserted_id);
				$this->db->update($this->comp_header_table, ['COMPLAINT_NUMBER' => $complaint_no]);
				// Add Lines
				$this->addComplaintLines($inserted_id, $data);
				return true;
			} else
				return false;
		}
	}

	function addComplaintLines($req_id, $data)
	{
		$total_lines = 0;
		if (isset($data['PO_NUMBER']) && is_array($data['PO_NUMBER']))
			$total_lines = count($data['PO_NUMBER']);
		// Delete previous records if any
		$this->db->where('COMPLAINT_ID', $req_id)->delete($this->comp_lines_table);

		for ($row = 0; $row < $total_lines; $row++) {
			$line = [
				'COMPLAINT_ID' => $req_id,
				'PO_NUMBER' => $data['PO_NUMBER'][$row] ?? '123123',
				'DELIVERY_NUMBER' => $data['DELIVERY_NUMBER'][$row] ?? null,
				'PRODUCT_CODE' => $data['PRODUCT_CODE'][$row] ?? null,
				'PRODUCT_DESC' => $data['PRODUCT_DESC'][$row] ?? null,
				'DELIVERY_DATE' => $data['DELIVERY_DATE'][$row] ?? null,
				'QTY' => $data['QTY'][$row] ?? null,
				'ISSUE' => $data['ISSUE'][$row] ?? null,
				'REMARK' => $data['REMARK'][$row] ?? null,
				// 'RETURN_DATE' => $data['RETURN_DATE'][$row] ?? null
			];
			$this->db->insert($this->comp_lines_table, $line);
		}
	}

	public function add_resolved_request($resolve_id, $data, $userid, $role)
	{
		// Handle status update for 'Draft' or 'Active'
		if (in_array($data['STATUS'], ['Draft', 'Active'])) {
			$this->db->set('STATUS', $data['STATUS']);
			$this->db->where('COMPLAINT_ID', $data['COMPLAINT_ID']);
			$this->db->where('COMPLAINT_NUMBER', $data['COMPLAINT_NUMBER']);
			$this->db->update($this->comp_header_table);
			return true; // Exit the function after updating the status
		}

		// Handle status update and further actions for 'Closed'
		if ($data['STATUS'] === 'Closed') {
			$this->db->set('STATUS', $data['STATUS']);
			$this->db->set('IS_RESOLVED', true);
			$this->db->where('COMPLAINT_ID', $data['COMPLAINT_ID']);
			$this->db->where('COMPLAINT_NUMBER', $data['COMPLAINT_NUMBER']);
			$this->db->update($this->comp_header_table);
		}

		// Prepare the header data
		$header_data = [
			'UUID' => $data['UUID'] ?? null,
			'COMPLAINT_ID' => $data['COMPLAINT_ID'] ?? null,
			'COMPLAINT_NUMBER' => $data['COMPLAINT_NUMBER'] ?? null,
			'RECEIVED_BY' => $data['RECEIVED_BY'] ?? null,
			'RECEIVED_DATE' => $data['RECEIVED_DATE'] ?? null,
			'ESCALATION_NEEDED' => $data['ESCALATION_NEEDED'] ?? null,
			'ACTIONS' => $data['ACTIONS'] ?? null,
			'ROOT_CAUSE' => $data['ROOT_CAUSE'] ?? null,
			'OUTCOME' => $data['OUTCOME'] ?? null,
			'CREATED_AT' => date('Y-m-d'),
		];

		// Handle existing resolved requests
		if (!in_array($resolve_id, [' ', '', 0, null])) {
			$this->db->where('RESOLUTION_ID', $resolve_id);
			$request = $this->db->get($this->comp_resolution_table)->row_array();

			if (isset($data['UPLOADED_FILES']) && !empty($data['UPLOADED_FILES'])) {
				$filesFromDB = $request['ATTACHMENTS'] ?? null;
				if (!empty($filesFromDB)) {
					$decodedFiles = json_decode($filesFromDB, true) ?? [];
					$filesToStore = array_merge($data['UPLOADED_FILES'], $decodedFiles);
					$header_data['ATTACHMENTS'] = json_encode($filesToStore);
				} else {
					$header_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);
				}
			}

			$header_data['UPDATED_BY'] = $userid;
			$header_data['UPDATED_AT'] = date('Y-m-d');

			// Update the existing record
			$this->db->where('RESOLUTION_ID', $resolve_id);
			$this->db->update($this->comp_resolution_table, $header_data);
			return true;
		} else {
			// Insert a new resolved request
			$header_data['CREATED_BY'] = $userid;
			if (isset($data['UPLOADED_FILES'])) {
				$header_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);
			}

			$inserted = $this->db->insert($this->comp_resolution_table, $header_data);
			if ($inserted) {
				$inserted_id = $this->get_column_value($this->comp_resolution_table, 'RESOLUTION_ID', ['UUID' => $header_data['UUID']]);
				$resolution_no = "RES-" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);

				$this->db->where('RESOLUTION_ID', $inserted_id);
				$this->db->update($this->comp_resolution_table, ['RESOLUTION_NUMBER' => $resolution_no]);
				return true;
			}
			return false;
		}
	}



	public function get_complaints($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
	{
		$offset = get_limit_offset($currentPage, $limit);

		// Start building the query
		$this->db->select("ch.COMPLAINT_ID, ch.COMPLAINT_NUMBER, ch.UUID, ch.CLIENT_ID, ch.CUSTOMER_NAME, ch.EMAIL, ch.COMPLAINT_RAISED_BY, ch.COMPLAINT_DATE, ch.MOBILE_NUMBER, ch.STATUS, ch.ATTACHMENTS, ch.COMPLAINT, ch.IS_RESOLVED, ch.CREATED_AT, ch.CREATED_BY, ch.VERSION,u.USER_TYPE,(SELECT COMPANY_NAME FROM xx_crm_client_detail WHERE CLIENT_ID=u.USER_ID) COMPANY_NAME");
		$this->db->from("XX_CRM_COMPL_HEADER ch");
		$this->db->join("XX_CRM_USERS u", "ch.CLIENT_ID = u.ID", "inner");

		// Apply filters dynamically
		if (!empty($filters) && is_array($filters)) {
			// Check if USER_TYPE and USER_ID are present in filters

			if (isset($filters['USER_TYPE'], $filters['USER_ID'])) {
				if ($filters['USER_TYPE'] === 'client') {
					// Apply client-specific conditions
					$this->db->where('u.USER_TYPE', 'client');
					$this->db->where('ch.CLIENT_ID', $filters['USER_ID']);
					if (in_array($filters['STATUS'], ['Draft', 'Active', 'Closed'])) {
						$this->db->where('ch.STATUS', $filters['STATUS']);
					}
				} elseif ($filters['USER_TYPE'] === 'admin') {
					// Admin can see all complaints, so no additional filtering is needed
					if (in_array($filters['STATUS'], ['Draft', 'Active', 'Closed'])) {
						$this->db->where('ch.STATUS', $filters['STATUS']);
					}
				}
			}
		}
		// Apply limit and offset only if 'list' type
		if ($type == 'list' && $limit > 0) {
			$this->db->limit($limit, ($offset > 0 ? $offset : 0));
		}

		$this->db->order_by("ch.COMPLAINT_ID", "DESC");

		// Debugging: Log the query for inspection
		// error_log("Compiled SQL Query: " . $this->db->get_compiled_select());

		// Execute query
		$query = $this->db->get();

		// error_log("Executed SQL Query: " . $this->db->last_query());

		if ($type == 'list') {
			return $query->result_array();
		} else {
			return $query->num_rows();
		}
	}






	public function getCardStats($filters = [])
	{
		// Start building the query
		$this->db->select("ch.STATUS, COUNT(ch.STATUS) AS STATUS_COUNT");
		$this->db->from("XX_CRM_COMPL_HEADER ch");
		$this->db->join("XX_CRM_USERS u", "ch.CLIENT_ID = u.ID", "inner");

		// Apply filters dynamically
		if (!empty($filters) && is_array($filters)) {
			// Check if USER_TYPE and USER_ID are present in filters
			if (isset($filters['USER_TYPE'], $filters['USER_ID'])) {
				if ($filters['USER_TYPE'] === 'client') {
					// Apply client-specific conditions
					$this->db->where('u.USER_TYPE', 'client');
					$this->db->where('ch.CLIENT_ID', $filters['USER_ID']);
				} elseif ($filters['USER_TYPE'] === 'admin') {
					// Admin can see all complaints, so no additional filtering is needed
				}
			}
		}

		// Group by status to get distinct status counts
		$this->db->group_by("ch.STATUS");

		// Order results (optional)
		$this->db->order_by("STATUS_COUNT", "DESC");

		// Execute query
		$query = $this->db->get();

		// Debugging: Uncomment the following line to check the generated query
		// print_r($this->db->last_query()); // Debug SQL query
		// die;

		// Return the result as an array
		return $query->result_array();
	}

	public function getComplaintDetail($complaint_id)
	{

		// Fetch complaint details
		$data = $this->db->select("ch.COMPLAINT_ID, ch.COMPLAINT_NUMBER, ch.UUID, ch.CLIENT_ID, ch.CUSTOMER_NAME,ch.COMPLAINT_DATE, ch.EMAIL, ch.COMPLAINT_RAISED_BY, ch.MOBILE_NUMBER, ch.STATUS, 
            ch.ATTACHMENTS, ch.COMPLAINT, ch.IS_RESOLVED, ch.CREATED_AT, 
            ch.CREATED_BY, ch.VERSION")
			->from('XX_CRM_COMPL_HEADER ch')
			->where('ch.COMPLAINT_ID', $complaint_id)
			->get()
			->row_array();
		return $data;
	}





	public function get_request_by_uuid($requestUUID)
	{
		$data = ['header' => []];

		if ($requestUUID) {
			// Fetch product details
			$data['header'] = $this->db->select("ch.COMPLAINT_ID, ch.COMPLAINT_NUMBER, ch.UUID, ch.CLIENT_ID, ch.CUSTOMER_NAME, ch.COMPLAINT_DATE, ch.EMAIL, ch.COMPLAINT_RAISED_BY, ch.MOBILE_NUMBER, 
            ch.STATUS, ch.ATTACHMENTS, ch.COMPLAINT, ch.IS_RESOLVED, ch.CREATED_AT, 
            ch.CREATED_BY, ch.VERSION,rs.RESOLUTION_ID,rs.UUID,rs.RESOLUTION_NUMBER,rs.RECEIVED_BY,rs.RECEIVED_DATE,rs.ESCALATION_NEEDED,rs.ACTIONS,rs.ROOT_CAUSE,rs.OUTCOME,rs.CREATED_BY,rs.CREATED_AT,rs.UPDATED_BY,rs.UPDATED_AT,(SELECT EMAIL ADMIN_EMAIL FROM xx_crm_users WHERE ID=rs.CREATED_BY)ADMIN_EMAIL,rs.ATTACHMENTS as ADMIN_ATTACHMENTS")
				->from('XX_CRM_COMPL_HEADER ch')
				->join("XX_CRM_COMPL_RESOLUTION rs", "rs.COMPLAINT_ID = ch.COMPLAINT_ID", "left")
				->where('ch.UUID', $requestUUID)
				->get()
				->row_array();



			// Fetch inventory details if product exists and has a PRODUCT_ID
			if (isset($data['header']['COMPLAINT_ID'])) {
				$data['lines'] = $this->db
					->select('cl.LINE_ID,cl.COMPLAINT_ID,cl.PO_NUMBER,cl.DELIVERY_NUMBER,cl.PRODUCT_CODE,cl.PRODUCT_DESC,cl.DELIVERY_DATE,cl.QTY,cl.ISSUE,cl.REMARK,cl.RETURN_DATE')
					->from('XX_CRM_COMPL_LINES cl')
					->where('cl.COMPLAINT_ID', $data['header']['COMPLAINT_ID'])
					->order_by('cl.LINE_ID')
					->get()
					->result_array(); // Fetch the result as an array of associative arrays
			}
		}

		return $data;
	}

	public function get_request_by_resolveUUId($resolveUUID)
	{


		$data['header'] = $this->db->select("*")
			->from('xx_crm_compl_resolution ch')
			->where('UUID', $resolveUUID)
			->get()
			->row_array();
		return $data;
	}

	public function delete_Request_by_id($complaintID)
	{
		$this->db->trans_start();

		$this->db->delete('XX_CRM_COMPL_HEADER', array('COMPLAINT_ID' => $complaintID));

		$this->db->delete('XX_CRM_COMPL_LINES', array('COMPLAINT_ID' => $complaintID));

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return false;
		} else {
			return true;
		}
	}

	public function getComplaintUUID($id)
	{
		return $this->db->select("UUID")
			->from('XX_CRM_COMPL_HEADER ch')
			->where('COMPLAINT_ID', $id)
			->get()
			->row_array();
	}

	public function getComplaintId($id)
	{
		return $this->db->select("COMPLAINT_ID")
			->from('XX_CRM_COMPL_HEADER ch')
			->where('UUID', $id)
			->get()
			->row_array();
	}
}
