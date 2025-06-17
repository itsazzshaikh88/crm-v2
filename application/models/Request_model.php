<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Request_model extends App_model
{
    protected $product_table; // Holds the name of the user table
    protected $inventory_table; // Holds the name of the token table
    protected $variant_table; // Holds the name of the token table
    protected $category_table; // Holds the name of the token table
    protected $req_header_table; // Holds the name of the token table
    protected $req_lines_table; // Holds the name of the token table

    public function __construct()
    {
        parent::__construct();
        $this->product_table = 'xx_crm_products'; // Initialize user table
        $this->inventory_table = 'xx_crm_product_inventory'; // Initialize token table
        $this->variant_table = 'xx_crm_product_variants'; // Initialize token table
        $this->category_table = 'xx_crm_product_categories'; // Initialize token table
        $this->req_header_table = 'xx_crm_req_header'; // Initialize token table
        $this->req_lines_table = 'xx_crm_req_lines'; // Initialize token table
    }
    // Function to add or update product
    public function add_request($request_id, $data, $userid, $role)
    {
        $header_data = [
            'UUID' => uuid_v4(),
            'CLIENT_ID' => $data['CLIENT_ID'],
            'REQUEST_TITLE' => $data['REQUEST_TITLE'],
            'COMPANY_ADDRESS' => $data['COMPANY_ADDRESS'],
            'BILLING_ADDRESS' => $data['BILLING_ADDRESS'],
            'SHIPPING_ADDRESS' => $data['SHIPPING_ADDRESS'],
            'CONTACT_NUMBER' => $data['CONTACT_NUMBER'],
            'EMAIL_ADDRESS' => $data['EMAIL_ADDRESS'],
            'REQUEST_DETAILS' => $data['REQUEST_DETAILS'],
            'INTERNAL_NOTES' => $data['INTERNAL_NOTES'],
            'ORG_ID' => $data['ORG_ID'],
            'REVIEW_DATE' => $data['REVIEW_DATE'] ?? '',
            'NEXT_REVIEW_DATE' => $data['NEXT_REVIEW_DATE'] ?? '',
            'RESPONSIBLE_USER_ID' => $data['RESPONSIBLE_USER_ID'] ?? '',
            'REVIEW_STATUS' => $data['REVIEW_STATUS'] ?? '',
            'REVIEW_NOTES' => $data['REVIEW_NOTES'] ?? '',
            'STATUS' => 'draft',
            'ACTION_BY' => $role,
            'VERSION' => '1'
        ];
        if (!in_array($request_id, [' ', '', 0, null])) {
            // check if the data is present 
            $this->db->where('ID', $request_id);
            $request = $this->db->get($this->req_header_table)->row_array();
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
            $this->db->where('ID', $request_id);
            $this->db->update($this->req_header_table, $header_data);

            // Check if update was successful
            if ($this->db->affected_rows() > 0) {
                // Add Lines
                $this->addRequestLines($request_id, $data);
                return true;
            } else {
                return false;
            }
        } else {
            $header_data['CREATED_BY'] = $userid;
            if (isset($data['UPLOADED_FILES']))
                $header_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);
            // Insert new product
            $inserted = $this->db->insert($this->req_header_table, $header_data);
            if ($inserted) {
                $inserted_id = $this->get_column_value($this->req_header_table, 'ID', ['UUID' => $header_data['UUID']]);
                // Create request_number in the required format
                $request_number = "REQ-" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
                // Update the request_number field for the newly inserted product
                $this->db->where('ID', $inserted_id);
                $this->db->update($this->req_header_table, ['REQUEST_NUMBER' => $request_number]);

                // Add Lines
                $this->addRequestLines($inserted_id, $data);
                return true;
            } else
                return false;
        }
    }

    function addRequestLines($req_id, $data)
    {
        $total_lines = 0;
        if (isset($data['PRODUCT_ID']) && is_array($data['PRODUCT_ID']))
            $total_lines = count($data['PRODUCT_ID']);
        // Delete previous records if any
        $this->db->where('REQ_ID', $req_id)->delete($this->req_lines_table);

        for ($row = 0; $row < $total_lines; $row++) {
            $line = [
                'REQ_ID' => $req_id,
                'PRODUCT_ID' => $data['PRODUCT_ID'][$row] ?? null,
                'PRODUCT_DESC' => $data['PRODUCT_DESC'][$row] ?? null,
                'SUPP_PROD_CODE' => $data['SUPP_PROD_CODE'][$row] ?? null,
                'QUANTITY' => $data['QUANTITY'][$row] ?? null,
                'REQUIRED_DATE' => $data['REQUIRED_DATE'][$row] ?? null,
                'COLOR' => $data['COLOR'][$row] ?? null,
                'TRANSPORTATION' => $data['TRANSPORTATION'][$row] ?? null,
                'COMMENTS' => $data['COMMENTS'][$row] ?? null
            ];
            $this->db->insert($this->req_lines_table, $line);
        }
    }

    function get_requests($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $user = [], $search = null, $mode = null)
    {
        $userid = $user['userid'] ?? 0;
        $usertype = strtolower($user['role'] ?? 'guest');

        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("rh.ID, rh.REQUEST_NUMBER, rh.UUID, rh.ORG_ID, rh.REQUEST_TITLE, rh.REQUEST_DETAILS, rh.CONTACT_NUMBER, rh.EMAIL_ADDRESS, rh.STATUS, rh.ACTION_BY, rh.VERSION, rh.CREATED_AT,
        cl.COMPANY_NAME, u.FIRST_NAME, u.LAST_NAME, u.EMAIL, rh.REVIEW_DATE,rh.NEXT_REVIEW_DATE,rh.RESPONSIBLE_USER_ID,rh.REVIEW_STATUS,rh.REVIEW_NOTES");
        $this->db->from("xx_crm_req_header rh");
        $this->db->join("xx_crm_client_detail cl", "cl.USER_ID = rh.CLIENT_ID", "left");
        $this->db->join("xx_crm_users u", "u.ID = rh.CLIENT_ID", "left");
        $this->db->order_by("rh.ID", "DESC");

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        // Search condition
        if (!empty($search)) {
            $search = strtolower($search);
            $this->db->group_start();
            $this->db->like('LOWER(rh.REQUEST_NUMBER)', $search);
            $this->db->or_like('LOWER(rh.REQUEST_TITLE)', $search);
            $this->db->or_like('LOWER(cl.COMPANY_NAME)', $search);
            $this->db->or_like('LOWER(rh.EMAIL_ADDRESS)', $search);
            $this->db->group_end();
        }
        // Check if user is not an admin then get him his requests only
        if ($usertype != 'admin' && $mode != 'export') {
            $this->db->where('rh.CLIENT_ID', $userid);
        }

        // Apply limit and offset only if 'list' type and offset is greater than zero
        if ($type == 'list') {
            if ($limit > 0) {
                $this->db->limit($limit, ($offset > 0 ? $offset : 0));
            }
        }

        // Execute query
        $query = $this->db->get();

        if ($type == 'list') {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }

    public function get_request_by_uuid($requestUUID)
    {
        $data = ['header' => []];

        if ($requestUUID) {
            // Fetch product details
            $data['header'] = $this->db->select("rh.ID, rh.ORG_ID, rh.REQUEST_NUMBER, rh.UUID, rh.CLIENT_ID, rh.REQUEST_TITLE, rh.COMPANY_ADDRESS, rh.BILLING_ADDRESS, rh.SHIPPING_ADDRESS, rh.CONTACT_NUMBER, 
            rh.EMAIL_ADDRESS, rh.REQUEST_DETAILS, rh.INTERNAL_NOTES, rh.ATTACHMENTS, 
            cl.COMPANY_NAME, u.FIRST_NAME, u.LAST_NAME, u.EMAIL, CONCAT(u.FIRST_NAME, ' ', u.LAST_NAME) as FULLNAME, rh.REVIEW_DATE,rh.NEXT_REVIEW_DATE,rh.RESPONSIBLE_USER_ID,rh.REVIEW_STATUS,rh.REVIEW_NOTES")
                ->from('xx_crm_req_header rh')
                ->join('xx_crm_client_detail cl', 'cl.USER_ID = rh.CLIENT_ID', 'inner')
                ->join('xx_crm_users u', 'u.ID = rh.CLIENT_ID', 'inner')
                ->where('rh.UUID', $requestUUID)
                ->get()
                ->row_array();


            // Fetch inventory details if product exists and has a PRODUCT_ID
            if (isset($data['header']['ID'])) {
                $data['lines'] = $this->db
                    ->select('rl.LINE_ID, rl.REQ_ID, rl.PRODUCT_ID, rl.PRODUCT_DESC, rl.SUPP_PROD_CODE, rl.QUANTITY, rl.REQUIRED_DATE, 
                          rl.COLOR, rl.TRANSPORTATION, rl.COMMENTS, pr.PRODUCT_CODE,pr.PRODUCT_NAME, pr.DESCRIPTION')
                    ->from('xx_crm_req_lines rl')
                    ->join('xx_crm_products pr', 'pr.PRODUCT_ID = rl.PRODUCT_ID', 'left')
                    ->where('rl.REQ_ID', $data['header']['ID'])
                    ->order_by('rl.LINE_ID')
                    ->get()
                    ->result_array(); // Fetch the result as an array of associative arrays
            }
        }

        return $data;
    }

    public function delete_Request_by_id($requestID)
    {
        $this->db->trans_start();

        $this->db->delete('xx_crm_req_lines', array('REQ_ID' => $requestID));

        $this->db->delete('xx_crm_req_header', array('ID' => $requestID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function get_request_by_search_term($searchkey, $searchvalue)
    {
        $data = ['header' => []];

        if ($searchkey && $searchvalue) {
            // Fetch product details
            $data['header'] = $this->db->select("rh.ID, rh.ORG_ID, rh.REQUEST_NUMBER, rh.UUID, rh.CLIENT_ID, rh.REQUEST_TITLE, rh.COMPANY_ADDRESS, rh.BILLING_ADDRESS, rh.SHIPPING_ADDRESS, rh.CONTACT_NUMBER, 
            rh.EMAIL_ADDRESS, rh.REQUEST_DETAILS, rh.INTERNAL_NOTES, rh.ATTACHMENTS, 
            cl.COMPANY_NAME, u.FIRST_NAME, u.LAST_NAME, u.EMAIL, CONCAT(u.FIRST_NAME, ' ', u.LAST_NAME) as CLIENT_NAME, rh.REVIEW_DATE,rh.NEXT_REVIEW_DATE,rh.RESPONSIBLE_USER_ID,rh.REVIEW_STATUS,rh.REVIEW_NOTES")
                ->from('xx_crm_req_header rh')
                ->join('xx_crm_client_detail cl', 'cl.USER_ID = rh.CLIENT_ID', 'LEFT')
                ->join('xx_crm_users u', 'u.ID = rh.CLIENT_ID', 'LEFT')
                ->where('rh.' . $searchkey, $searchvalue)
                ->get()
                ->row_array();


            // Fetch inventory details if product exists and has a PRODUCT_ID
            if (isset($data['header']['ID'])) {
                $data['lines'] = $this->db
                    ->select('rl.LINE_ID, rl.REQ_ID, rl.PRODUCT_ID, rl.PRODUCT_DESC, rl.SUPP_PROD_CODE, rl.QUANTITY, rl.REQUIRED_DATE, 
                          rl.COLOR, rl.TRANSPORTATION, rl.COMMENTS, pr.PRODUCT_CODE,pr.PRODUCT_NAME, pr.DESCRIPTION, pr.BASE_PRICE')
                    ->from('xx_crm_req_lines rl')
                    ->join('xx_crm_products pr', 'pr.PRODUCT_ID = rl.PRODUCT_ID', 'left')
                    ->where('rl.REQ_ID', $data['header']['ID'])
                    ->order_by('rl.LINE_ID')
                    ->get()
                    ->result_array(); // Fetch the result as an array of associative arrays
            }
        }

        return $data;
    }
}
