<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';

class Quotes_model extends App_Model
{
    protected $product_table; // Holds the name of the user table
    protected $inventory_table; // Holds the name of the token table
    protected $variant_table; // Holds the name of the token table
    protected $category_table; // Holds the name of the token table
    protected $quotations_table; // Holds the name of the token table
    protected $quotation_lines_table; // Holds the name of the token table

    public function __construct()
    {
        parent::__construct();
        $this->product_table = 'xx_crm_products'; // Initialize user table
        $this->inventory_table = 'xx_crm_product_inventory'; // Initialize token table
        $this->variant_table = 'xx_crm_product_variants'; // Initialize token table
        $this->category_table = 'xx_crm_product_categories'; // Initialize token table
        $this->quotations_table = 'xx_crm_quotations'; // Initialize token table
        $this->quotation_lines_table = 'xx_crm_quotation_lines'; // Initialize token table
    }
    // Function to add or update product
    public function add_quote($quote_id, $data, $userid, $role)
    {
        $header_data = [
            'UUID' => $data['UUID'],
            'ORG_ID' => $data['ORG_ID'] ?? '',
            'CLIENT_ID' => $data['CLIENT_ID'],
            'REQUEST_ID' => $data['REQUEST_NUMBER'],
            'COMPANY_ADDRESS' => $data['COMPANY_ADDRESS'],
            'JOB_TITLE' => $data['JOB_TITLE'],
            'EMPLOYEE_NAME' => $data['EMPLOYEE_NAME'],
            'MOBILE_NUMBER' => $data['MOBILE_NUMBER'],
            'EMAIL_ADDRESS' => $data['EMAIL_ADDRESS'],
            'SALES_PERSON' => $data['SALES_PERSON'],
            'CURRENCY' => $data['CURRENCY'],
            'PAYMENT_TERM' => $data['PAYMENT_TERM'],
            'SUB_TOTAL' => $data['SUB_TOTAL'],
            'DISCOUNT_PERCENTAGE' => $data['DISCOUNT_PERCENTAGE'],
            'TAX_PERCENTAGE' => $data['TAX_PERCENTAGE'],
            'TOTAL_AMOUNT' => $data['TOTAL_AMOUNT'],
            'COMMENTS' => $data['COMMENTS'],
            // 'INTERNAL_NOTES' => $data['INTERNAL_NOTES'],
            'QUOTE_STATUS' => $data['QUOTE_STATUS'],
            'ACTION_BY' => $role,
            'VERSION' => '1'
        ];
        if (!in_array($quote_id, [' ', '', 0, null])) {
            // check if the data is present 
            $this->db->where('QUOTE_ID', $quote_id);
            $request = $this->db->get($this->quotations_table)->row_array();
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
            $this->db->where('QUOTE_ID', $quote_id);
            $this->db->update($this->quotations_table, $header_data);

            $this->addQuoteLines($quote_id, $data);
            return true;
        } else {
            $header_data['CREATED_BY'] = $userid;
            if (isset($data['UPLOADED_FILES']))
                $header_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);
            // Insert new product
            $inserted = $this->db->insert($this->quotations_table, $header_data);
            if ($inserted) {
                $inserted_id = $this->db->insert_id();
                // Create request_number in the required format
                $request_number = "QUO-" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
                // Update the request_number field for the newly inserted product
                $this->db->where('QUOTE_ID', $inserted_id);
                $this->db->update($this->quotations_table, ['QUOTE_NUMBER' => $request_number]);

                // Add Lines
                $this->addQuoteLines($inserted_id, $data);
                return true;
            } else
                return false;
        }
    }

    function addQuoteLines($req_id, $data)
    {
        $total_lines = 0;
        if (isset($data['PRODUCT_ID']) && is_array($data['PRODUCT_ID']))
            $total_lines = count($data['PRODUCT_ID']);
        // Delete previous records if any
        $this->db->where('QUOTE_ID', $req_id)->delete($this->quotation_lines_table);

        for ($row = 0; $row < $total_lines; $row++) {
            $line = [
                'QUOTE_ID' => $req_id,
                'PRODUCT_ID' => $data['PRODUCT_ID'][$row] ?? null,
                'PRODUCT' => $data['PRODUCT'][$row] ?? null,
                'SUPP_PROD_CODE' => $data['SUPP_PROD_CODE'][$row] ?? null,
                'DESCRIPTION' => $data['DESCRIPTION'][$row] ?? null,
                'QTY' => $data['QTY'][$row] ?? null,
                'UNIT_PRICE' => $data['UNIT_PRICE'][$row] ?? null,
                'TOTAL' => $data['TOTAL'][$row] ?? null,
                'COLOR' => $data['COLOR'][$row] ?? null,
                'TRANSPORTATION' => $data['TRANSPORTATION'][$row] ?? null,
                'LINE_COMMENTS' => $data['LINE_COMMENTS'][$row] ?? null
            ];
            $this->db->insert($this->quotation_lines_table, $line);
        }
    }

    function get_quotes($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $user = [])
    {
        $userid = $user['userid'] ?? 0;
        $usertype = strtolower($user['role'] ?? 'guest');
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("qu.UUID,qu.QUOTE_ID,qu.ORG_ID, qu.QUOTE_NUMBER, qu.CLIENT_ID,qu.EMPLOYEE_NAME ,qu.JOB_TITLE, qu.EMAIL_ADDRESS, qu.SALES_PERSON,rh.REQUEST_NUMBER, qu.QUOTE_STATUS,qu.TOTAL_AMOUNT, qu.ACTION_BY, qu.VERSION, qu.CREATED_AT,
        cl.COMPANY_NAME, ");
        $this->db->from("xx_crm_quotations qu");
        $this->db->join("xx_crm_client_detail cl", "cl.USER_ID = qu.CLIENT_ID", "left");
        $this->db->join("xx_crm_users u", "u.ID = qu.CLIENT_ID", "left");
        $this->db->join('xx_crm_req_header rh', 'rh.ID = qu.REQUEST_ID', 'left'); // Join with xx_crm_req_header

        $this->db->order_by("qu.QUOTE_ID", "DESC");

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        // Check if user is not an admin then get him his quotes only
        if ($usertype != 'admin') {
            $this->db->where('qu.CLIENT_ID', $userid);
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



    public function get_quote_by_uuid($quoteUUID)
    {
        $data = ['header' => [], 'lines' => [], 'requests' => []];

        if ($quoteUUID) {
            // Fetch product details
            $data['header'] = $this->db->select("qu.QUOTE_ID, qu.ORG_ID, qu.REQUEST_ID,qu.QUOTE_NUMBER, qu.UUID, qu.CLIENT_ID, qu.QUOTE_STATUS, qu.JOB_TITLE, qu.SALES_PERSON, qu.EMPLOYEE_NAME, qu.COMPANY_ADDRESS, qu.EMAIL_ADDRESS,  
            qu.MOBILE_NUMBER, qu.CURRENCY, qu.PAYMENT_TERM, qu.SUB_TOTAL, qu.DISCOUNT_PERCENTAGE, qu.TAX_PERCENTAGE, qu.TOTAL_AMOUNT, qu.COMMENTS, qu.INTERNAL_NOTES, qu.ATTACHMENTS, 
            cl.COMPANY_NAME, u.FIRST_NAME, u.LAST_NAME, u.EMAIL, CONCAT(u.FIRST_NAME, ' ', u.LAST_NAME) as FULLNAME")
                ->from('xx_crm_quotations qu')
                ->join('xx_crm_client_detail cl', 'cl.USER_ID = qu.CLIENT_ID', 'inner')
                ->join('xx_crm_users u', 'u.ID = qu.CLIENT_ID', 'inner')
                ->join('xx_crm_req_header rh', 'rh.CLIENT_ID = qu.CLIENT_ID', 'inner') // Join with xx_crm_req_header
                ->where('qu.UUID', $quoteUUID)
                ->get()
                ->row_array();

            // Fetch inventory details if product exists and has a PRODUCT_ID
            if (isset($data['header']['QUOTE_ID'])) {
                // if (isset($data['header']['UUID'])) { 

                $data['lines'] = $this->db
                    ->select('ql.LINE_ID, ql.QUOTE_ID, ql.SUPP_PROD_CODE, ql.CUST_PROD_CODE, ql.PRODUCT_ID, ql.DESCRIPTION, ql.QTY, ql.UNIT_PRICE, ql.TOTAL,
                          ql.COLOR, ql.TRANSPORTATION, ql.LINE_COMMENTS, pr.PRODUCT_CODE,pr.PRODUCT_NAME, pr.DESCRIPTION')
                    ->from('xx_crm_quotation_lines ql')
                    ->join('xx_crm_products pr', 'pr.PRODUCT_ID = ql.PRODUCT_ID', 'left')
                    ->where('ql.QUOTE_ID', $data['header']['QUOTE_ID'])
                    ->order_by('ql.LINE_ID')
                    ->get()
                    ->result_array(); // Fetch the result as an array of associative arrays

                // Fetch requests details
                $data['requests'] = $this->db
                    ->select('rh.ID, rh.REQUEST_NUMBER, rh.REQUEST_TITLE, rh.COMPANY_ADDRESS, rh.BILLING_ADDRESS, rh.SHIPPING_ADDRESS, rh.CONTACT_NUMBER, 
            rh.EMAIL_ADDRESS, rh.REQUEST_DETAILS, rh.INTERNAL_NOTES, rh.ATTACHMENTS')
                    ->from('xx_crm_req_header rh')
                    ->where('rh.CLIENT_ID', $data['header']['CLIENT_ID'])
                    ->order_by('rh.ID')
                    ->get()
                    ->result_array();
            }
        }

        return $data;
    }

    public function get_quote_by_searchkey($searchkey, $searchvalue)
    {

        $data = ['header' => [], 'lines' => [], 'requests' => []];

        if ($searchkey) {
            // Fetch product details
            $data['header'] = $this->db->select("qu.QUOTE_ID, qu.ORG_ID, qu.REQUEST_ID,qu.QUOTE_NUMBER, qu.UUID, qu.CLIENT_ID, qu.QUOTE_STATUS, qu.JOB_TITLE, qu.SALES_PERSON, qu.EMPLOYEE_NAME, qu.COMPANY_ADDRESS, qu.EMAIL_ADDRESS,  
            qu.MOBILE_NUMBER, qu.CURRENCY, qu.PAYMENT_TERM, qu.SUB_TOTAL, qu.DISCOUNT_PERCENTAGE, qu.TAX_PERCENTAGE, qu.TOTAL_AMOUNT, qu.COMMENTS, qu.INTERNAL_NOTES, qu.ATTACHMENTS, 
            cl.COMPANY_NAME, u.FIRST_NAME, u.LAST_NAME, u.EMAIL, CONCAT(u.FIRST_NAME, ' ', u.LAST_NAME) as FULLNAME, rh.REQUEST_NUMBER")
                ->from('xx_crm_quotations qu')
                ->join('xx_crm_client_detail cl', 'cl.USER_ID = qu.CLIENT_ID', 'left')
                ->join('xx_crm_users u', 'u.ID = qu.CLIENT_ID', 'left')
                ->join('xx_crm_req_header rh', 'rh.CLIENT_ID = qu.CLIENT_ID', 'left') // Join with xx_crm_req_header
                ->where('qu.' . $searchkey, $searchvalue)
                ->get()
                ->row_array();



            // Fetch inventory details if product exists and has a PRODUCT_ID
            if (isset($data['header']['QUOTE_ID'])) {
                // if (isset($data['header']['UUID'])) { 

                $data['lines'] = $this->db
                    ->select('ql.LINE_ID, ql.QUOTE_ID, ql.PRODUCT_ID, ql.DESCRIPTION, ql.QTY, ql.UNIT_PRICE, ql.TOTAL, ql.SUPP_PROD_CODE, ql.CUST_PROD_CODE,
                          ql.COLOR, ql.TRANSPORTATION, ql.LINE_COMMENTS, pr.PRODUCT_CODE,pr.PRODUCT_NAME, pr.DESCRIPTION')
                    ->from('xx_crm_quotation_lines ql')
                    ->join('xx_crm_products pr', 'pr.PRODUCT_ID = ql.PRODUCT_ID', 'left')
                    ->where('ql.QUOTE_ID', $data['header']['QUOTE_ID'])
                    ->order_by('ql.LINE_ID')
                    ->get()
                    ->result_array(); // Fetch the result as an array of associative arrays

                // Fetch requests details
                $data['requests'] = $this->db
                    ->select('rh.ID, rh.REQUEST_NUMBER, rh.REQUEST_TITLE, rh.COMPANY_ADDRESS, rh.BILLING_ADDRESS, rh.SHIPPING_ADDRESS, rh.CONTACT_NUMBER, 
                    rh.EMAIL_ADDRESS, rh.REQUEST_DETAILS, rh.INTERNAL_NOTES, rh.ATTACHMENTS')
                    ->from('xx_crm_req_header rh')
                    ->where('rh.CLIENT_ID', $data['header']['CLIENT_ID'])
                    ->order_by('rh.ID')
                    ->get()
                    ->result_array();
            }
        }

        return $data;
    }

    public function delete_quote_by_id($requestID)
    {
        $this->db->trans_start();

        $this->db->delete('xx_crm_quotations', array('QUOTE_ID' => $requestID));

        $this->db->delete('xx_crm_quotation_lines', array('QUOTE_ID' => $requestID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function convert_new_quote_by_id($requestID)
    {
        if ($requestID) {
            // Fetch the original quote
            $quote = $this->db->where('QUOTE_ID', $requestID)->get('xx_crm_quotations')->row_array();
            if (empty($quote)) {
                return false;
            }

            // Prepare new quote data
            $new_quote = [
                'UUID' => uuid_v4(),
                'CLIENT_ID' => $quote['CLIENT_ID'],
                'ORG_ID' => $quote['ORG_ID'] ?? '',
                'REQUEST_ID' => $quote['REQUEST_ID'],
                'EMPLOYEE_NAME' => $quote['EMPLOYEE_NAME'],
                'JOB_TITLE' => $quote['JOB_TITLE'],
                // 'QUOTE_NUMBER' => ,
                'EMAIL_ADDRESS' => $quote['EMAIL_ADDRESS'],
                'MOBILE_NUMBER' => $quote['MOBILE_NUMBER'],
                'CURRENCY' => $quote['CURRENCY'],
                'PAYMENT_TERM' => $quote['PAYMENT_TERM'],
                'SUB_TOTAL' => $quote['SUB_TOTAL'],
                'DISCOUNT_PERCENTAGE' => $quote['DISCOUNT_PERCENTAGE'],
                'TAX_PERCENTAGE' => $quote['TAX_PERCENTAGE'],
                'TOTAL_AMOUNT' => $quote['TOTAL_AMOUNT'],
                'COMMENTS' => $quote['COMMENTS'],
                'INTERNAL_NOTES' => $quote['INTERNAL_NOTES'],
                'ATTACHMENTS' => $quote['ATTACHMENTS'],
                'SALES_PERSON' => $quote['SALES_PERSON'],
                'QUOTE_STATUS' => 'Draft', // Use a constant for statuses
            ];

            // Start transaction
            $this->db->trans_begin();

            // Insert new quote
            $this->db->insert('xx_crm_quotations', $new_quote);
            if ($this->db->affected_rows() === 0) {
                $this->db->trans_rollback();
                return false;
            }

            // Get the new quote ID
            $new_quote_id = $this->db->insert_id();

            // Generate quote number
            $quotes_number = "QUO-" . date('dmy') . str_pad($new_quote_id, 6, '0', STR_PAD_LEFT);

            // Update the quote number
            $this->db->where('QUOTE_ID', $new_quote_id);
            $this->db->update('xx_crm_quotations', ['QUOTE_NUMBER' => $quotes_number]);

            if ($this->db->affected_rows() === 0) {
                $this->db->trans_rollback();
                return false;
            }


            // Fetch the original quote lines
            $lines = $this->db->where('QUOTE_ID', $requestID)->get('xx_crm_quotation_lines')->result_array();
            if (!empty($lines)) {
                foreach ($lines as $line) {
                    $new_line = [
                        'QUOTE_ID' => $new_quote_id, // Associate with new quote ID
                        'PRODUCT_ID' => $line['PRODUCT_ID'],
                        'DESCRIPTION' => $line['DESCRIPTION'],
                        'SUPP_PROD_CODE' => $line['SUPP_PROD_CODE'],
                        'CUST_PROD_CODE' => $line['CUST_PROD_CODE'],
                        'QTY' => $line['QTY'],
                        'UNIT_PRICE' => $line['UNIT_PRICE'],
                        'TOTAL' => $line['TOTAL'],
                        'COLOR' => $line['COLOR'],
                        'TRANSPORTATION' => $line['TRANSPORTATION'],
                        'LINE_COMMENTS' => $line['LINE_COMMENTS'],
                    ];

                    // Insert new line
                    $this->db->insert('xx_crm_quotation_lines', $new_line);
                    if ($this->db->affected_rows() === 0) {
                        $this->db->trans_rollback();
                        return false;
                    }
                }
            }

            // Commit transaction
            $this->db->trans_commit();
            // if new quotations is created then retuen new 
            $createdQuoatation = $this->get_quote_by_searchkey("QUOTE_ID", $new_quote_id);
            return $createdQuoatation;
        }

        return false;
    }


    function fetchClientRequests($ClientID)
    {
        return $this->db->select('ID, UUID, REQUEST_NUMBER')
            ->from('xx_crm_req_header')
            ->where('CLIENT_ID', $ClientID)
            ->get()
            ->result_array();
    }


    function fetchClientQuotes($ClientID)
    {
        return $this->db->query("select QUOTE_ID, UUID , QUOTE_NUMBER from xx_crm_quotations where CLIENT_ID = $ClientID")->result_array();
    }
    public function create_new_quote_from_request($requestID, $user_id, $role, $request)
    {
        if ($requestID) {

            if (empty($request ?? []) || empty($request['header'] ?? [])) {
                return false;
            }

            $user_details = $this->db->where('ID', $request['header']['CLIENT_ID'] ?? 0)->get('xx_crm_users')->row_array();
            $client_details = $this->db->where('USER_ID', $request['header']['CLIENT_ID'] ?? 0)->get('xx_crm_client_detail')->row_array();
            $client_address_details = $this->db->where('CLIENT_ID', $request['header']['CLIENT_ID'] ?? 0)->get('xx_crm_client_address')->row_array();

            $employee_name = $user_details['FIRST_NAME'] ?? '' . " " . $user_details['LAST_NAME'] ?? '';
            // Prepare new quote data
            $new_quote = [
                'UUID' => uuid_v4(),
                'CLIENT_ID' => $request['header']['CLIENT_ID'] ?? '',
                'ORG_ID' => $request['header']['ORG_ID'] ?? '',
                'REQUEST_ID' => $request['header']['ID'] ?? '',
                'COMPANY_ADDRESS' => $client_address_details['ADDRESS_LINE_1'] ?? '',
                'EMPLOYEE_NAME' => $employee_name,
                'JOB_TITLE' => '',
                'EMAIL_ADDRESS' => $request['header']['EMAIL_ADDRESS'],
                'MOBILE_NUMBER' => $request['header']['CONTACT_NUMBER'],
                'CURRENCY' => $client_details['CURRENCY'] ?? '',
                'PAYMENT_TERM' => $client_details['PAYMENT_TERM'] ?? '',
                'SUB_TOTAL' => '',
                'DISCOUNT_PERCENTAGE' => '',
                'TAX_PERCENTAGE' => '',
                'TOTAL_AMOUNT' => '',
                'COMMENTS' => '',
                'INTERNAL_NOTES' => '',
                'ATTACHMENTS' => '',
                'SALES_PERSON' => '',
                'QUOTE_STATUS' => 'Draft',
                'VERSION' => 1,
                'IS_CONVERTED' => 0,
                'CREATED_BY' => $user_id
            ];

            // Start transaction
            $this->db->trans_begin();

            // Insert new quote
            $this->db->insert('xx_crm_quotations', $new_quote);
            if ($this->db->affected_rows() === 0) {
                $this->db->trans_rollback();
                return false;
            }

            // Get the new quote ID
            $new_quote_id = $this->db->insert_id();

            // Generate quote number
            $quotes_number = "QUO-" . date('dmy') . str_pad($new_quote_id, 6, '0', STR_PAD_LEFT);

            // Update the quote number
            $this->db->where('QUOTE_ID', $new_quote_id);
            $this->db->update('xx_crm_quotations', ['QUOTE_NUMBER' => $quotes_number]);

            if ($this->db->affected_rows() === 0) {
                $this->db->trans_rollback();
                return false;
            }


            // insert quote lines
            $lines = $request['lines'] ?? [];
            if (!empty($lines)) {
                foreach ($lines as $line) {
                    $new_line = [
                        'QUOTE_ID' => $new_quote_id, // Associate with new quote ID
                        'PRODUCT_ID' => $line['PRODUCT_ID'],
                        'DESCRIPTION' => $line['PRODUCT_DESC'],
                        'QTY' => $line['QUANTITY'],
                        'UNIT_PRICE' => $line['BASE_PRICE'],
                        'TOTAL' => ((float)$line['QUANTITY'] * (float)$line['BASE_PRICE']),
                        'COLOR' => '',
                        'TRANSPORTATION' => '',
                        'LINE_COMMENTS' => '',
                    ];

                    // Insert new line
                    $this->db->insert('xx_crm_quotation_lines', $new_line);
                    if ($this->db->affected_rows() === 0) {
                        $this->db->trans_rollback();
                        return false;
                    }
                }
            }

            // Commit transaction
            $this->db->trans_commit();
            // if new quotations is created then retuen new 
            $createdQuoatation = $this->get_quote_by_searchkey("QUOTE_ID", $new_quote_id);
            return $createdQuoatation;
        }

        return false;
    }
}
