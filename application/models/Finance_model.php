<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Finance_model extends App_Model
{
    protected $credit_info_table; // Holds the name of the user table
    protected $contact_info_table; // Holds the name of the token table
    protected $formal_info_table; // Holds the name of the token table
    protected $sign_info_table; // Holds the name of the token table
    protected $zpil_info_table; // Holds the name of the token table
    protected $user_table; // Holds the name of the token table

    public function __construct()
    {
        parent::__construct();

        $this->credit_info_table = 'xx_crm_crapp_credit_info'; // Initialize user table
        $this->contact_info_table = 'xx_crm_crapp_contact_info'; // Initialize token table
        $this->formal_info_table = 'xx_crm_crapp_formal_info'; // Initialize token table
        $this->sign_info_table = 'xx_crm_crapp_sign_info'; // Initialize token table
        $this->zpil_info_table = 'xx_crm_crapp_zpil_info'; // Initialize token table
        $this->user_table = 'xx_crm_users'; // Initialize token table
    }

    function managePreviousData($input, $existing)
    {
        if (!$existing && !$input)
            return null;
        else if ($existing && !$input)
            return $existing;
        else if (!$existing && $input)
            return $input;
        return $input;
    }

    // Function to add or update product
    public function add_credits($credit_id, $data, $userid, $user_type = 'guest')
    {


        $credit_data = [
            'UUID' => $data['UUID'],
            'APPLICATION_DATE' => $data['APPLICATION_DATE'],
            'CUSTOMER_ID' => $data['CUSTOMER_ID'],
            'CREDIT_VALUE' => $data['CREDIT_VALUE'],
            'CREDIT_IN_WORDS' => $data['CREDIT_IN_WORDS'],
            'WITHIN_DAYS' => $data['WITHIN_DAYS'],
            'APPLICANT_COMMENT' => $data['APPLICANT_COMMENT']
        ];
        $contact_data = [
            'COMPANY_NAME' => $data['COMPANY_NAME'],
            'CONTACT_PERSON' => $data['CONTACT_PERSON'],
            'CONTACT_PERSON_TITLE' => $data['CONTACT_PERSON_TITLE'],
            'CONTACT_EMAIL' => $data['CONTACT_EMAIL'],
            'PHONE' => $data['PHONE'],
            'FAX' => $data['FAX'],
            'COMPANY_EMAIL' => $data['COMPANY_EMAIL'],
            'CITY' => $data['CITY'],
            'STATE' => $data['STATE'],
            'COUNTRY' => $data['COUNTRY'],
            'ZIP_CODE' => $data['ZIP_CODE'],
            'ADDRESS_SPAN' => $data['ADDRESS_SPAN'] ?? null,
            'BUSINESS_START_DATE' => $data['BUSINESS_START_DATE'],
            'BUSINESS_TYPE' => $data['BUSINESS_TYPE'] ?? null,
            'BANK_NAME' => $data['BANK_NAME'],
            'BANK_LOCATION' => $data['BANK_LOCATION'],
            'ACCOUNT_NUMBER' => $data['ACCOUNT_NUMBER'],
            'IBAN_NUMBER' => $data['IBAN_NUMBER'],
            'SWIFT_CODE' => $data['SWIFT_CODE']
        ];
        $formal_data = [
            'CRN_NUMBER' => $data['CRN_NUMBER'],
            'DATE_OF_ISSUANCE' => $data['DATE_OF_ISSUANCE'],
            'DATE_OF_EXPIRY' => $data['DATE_OF_EXPIRY'],
            'COMPANY_LOCATION' => $data['COMPANY_LOCATION'],
            'PAID_UP_CAPITAL' => $data['PAID_UP_CAPITAL'],
            'COMPANY_OWNER' => $data['COMPANY_OWNER'],
            'PERCENTAGE_OWNER' => $data['PERCENTAGE_OWNER'],
            'TOP_MANAGER' => $data['TOP_MANAGER'],
            'SIGN_NAME' => $data['SIGN_NAME'],
            'SIGN_POSITION' => $data['SIGN_POSITION'],
            'SIGN_SPECIMEN' => $data['SIGN_SPECIMEN'],
            'BUS_ACTIVITIES' => $data['BUS_ACTIVITIES'],
            'GM_NAME' => $data['GM_NAME'],
            'PUR_MGR_NAME' => $data['PUR_MGR_NAME'],
            'FIN_MGR_NAME' => $data['FIN_MGR_NAME']
        ];

        $sign_data = [
            'ZPIL_SIGN' => $data['ZPIL_SIGN'],
            'ZPIL_SIGNATORY_NAME' => $data['ZPIL_SIGNATORY_NAME'],
            'ZPIL_SIGN_POSN' => $data['ZPIL_SIGN_POSN'],
            'ZPIL_DATE' => $data['ZPIL_DATE'],
            'CLIENT_SIGN' => $data['CLIENT_SIGN'],
            'CLIENT_STAMP' => $data['CLIENT_STAMP'],
            'CLIENT_SIGN_NAME' => $data['CLIENT_SIGN_NAME'],
            'CLIENT_SIGN_DATE' => $data['CLIENT_SIGN_DATE'],
            'CHAMBER_OF_COMMERCE' => $data['CHAMBER_OF_COMMERCE']
        ];

        // Check if user type is admin
        if ($user_type === 'admin') {
            // Prepare $zpil_data
            $zpil_data = [
                'DIR_SALES_COMMENTS' => $data['DIR_SALES_COMMENTS'],
                'SALES_MGR_COMMENTS' => $data['SALES_MGR_COMMENTS'],
                'GM_COMMENTS' => $data['GM_COMMENTS'],
                'CREDIT_DIV_COMMENTS' => $data['CREDIT_DIV_COMMENTS'],
                'FIN_MGR_COMMENTS' => $data['FIN_MGR_COMMENTS'],
                'MGMT_COMMENTS' => $data['MGMT_COMMENTS'],
                'REC_CREDIT_LIMIT' => $data['REC_CREDIT_LIMIT'],
                'REC_CREDIT_PERIOD' => $data['REC_CREDIT_PERIOD'],
                'APPROVED_FINANCE' => $data['APPROVED_FINANCE'] ?? null,
                'APPROVED_MANAGEMENT' => $data['APPROVED_MANAGEMENT'] ?? null
            ];
        }

        // update code
        if (!in_array($credit_id, [' ', '', 0, null])) {

            // Unset some variables
            unset($credit_data['UUID']);

            $credit_data['UPDATED_BY'] = $userid;
            $contact_data['UPDATED_BY'] = $userid;
            $formal_data['UPDATED_BY'] = $userid;
            $sign_data['UPDATED_BY'] = $userid;

            $credit_data['UPDATED_AT'] = date('Y-m-d H:i:s');
            $contact_data['UPDATED_AT'] = date('Y-m-d H:i:s');
            $formal_data['UPDATED_AT'] = date('Y-m-d H:i:s');
            $sign_data['UPDATED_AT'] = date('Y-m-d H:i:s');
            // Log the credit data being sent to the database
            log_message('error', 'Credit Data: ' . print_r($credit_data, true));
            // Update existing credit
            $this->db->where('HEADER_ID', $credit_id);
            $this->db->update($this->credit_info_table, $credit_data);

            $this->db->where('APP_HEADER_ID', $credit_id);
            $this->db->update($this->contact_info_table, $contact_data);


            $this->db->where('APP_HEADER_ID', $credit_id);
            $this->db->update($this->formal_info_table, $formal_data);

            $this->db->where('APP_HEADER_ID', $credit_id);
            $this->db->update($this->sign_info_table, $sign_data);



            // Check if user type is admin
            if ($user_type === 'admin') {

                $existedZPILData = $this->db->where('APP_HEADER_ID', $credit_id)->get($this->zpil_info_table)->row_array();

                $zpil_data['UPDATED_BY'] = $userid;
                $zpil_data['UPDATED_AT'] = date('Y-m-d H:i:s');


                $zpil_data['CRN_ATTACHMENT'] = $this->managePreviousData($data['CRN_ATTACHMENT'] ?? null, $existedZPILData['CRN_ATTACHMENT'] ?? null);
                $zpil_data['BANK_CERTIFICATE'] = $this->managePreviousData($data['BANK_CERTIFICATE'] ?? null, $existedZPILData['BANK_CERTIFICATE'] ?? null);
                $zpil_data['OWNER_ID'] = $this->managePreviousData($data['OWNER_ID'] ?? null, $existedZPILData['OWNER_ID'] ?? null);

                // Update the existing record with new data
                $this->db->where('APP_HEADER_ID', $credit_id);
                $this->db->update($this->zpil_info_table, $zpil_data);
            }

            return true;
        } else {
            // insert code
            $credit_data['CREATED_BY'] = $userid;
            $contact_data['CREATED_BY'] = $userid;
            $formal_data['CREATED_BY'] = $userid;
            $sign_data['CREATED_BY'] = $userid;

            $credit_data['CREATED_AT'] = date('Y-m-d H:i:s');
            $contact_data['CREATED_AT'] = date('Y-m-d H:i:s');
            $formal_data['CREATED_AT'] = date('Y-m-d H:i:s');
            $sign_data['CREATED_AT'] = date('Y-m-d H:i:s');

            // Check if user type is admin
            if ($user_type === 'admin') {
                $zpil_data['CREATED_BY'] = $userid;
                $zpil_data['CREATED_AT'] = date('Y-m-d H:i:s');

                $zpil_data['CRN_ATTACHMENT'] = $data['CRN_ATTACHMENT'] ?? null;

                $zpil_data['BANK_CERTIFICATE'] = $data['BANK_CERTIFICATE'] ?? null;

                $zpil_data['OWNER_ID'] = $data['OWNER_ID'] ?? null;
            }

            // Insert new credit
            $inserted = $this->db->insert($this->credit_info_table, $credit_data);
            if ($inserted) {
                $inserted_id = $this->db->insert_id();
                // Create credit_code in the required format
                $credit_code = "CRED-" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
                // Update the credit_code field for the newly inserted credit
                $this->db->where('HEADER_ID', $inserted_id);
                $this->db->update($this->credit_info_table, ['APPLICATION_NUMBER' => $credit_code]);
                // insert inventory details
                $contact_data['APP_HEADER_ID'] = $inserted_id;
                $this->db->insert($this->contact_info_table, $contact_data);
                $formal_data['APP_HEADER_ID'] = $inserted_id;
                $this->db->insert($this->formal_info_table, $formal_data);
                $sign_data['APP_HEADER_ID'] = $inserted_id;
                $this->db->insert($this->sign_info_table, $sign_data);
                // Insert $zpil_data only if user type is admin
                if ($user_type === 'admin') {
                    $zpil_data['APP_HEADER_ID'] = $inserted_id;
                    $this->db->insert($this->zpil_info_table, $zpil_data);
                }
                return true;
            } else
                return false;
        }
    }


    function get_credits($type = 'list', $limit = 10, $currentPage = 1, $filters = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("ci.HEADER_ID, ci.UUID, ci.CUSTOMER_ID, ci.APPLICATION_NUMBER, ci.APPLICANT_COMMENT, cont.COMPANY_NAME, cont.CONTACT_PERSON, cont.PHONE, cont.COMPANY_EMAIL, u.FIRST_NAME, u.LAST_NAME, u.EMAIL, ci.CREATED_AT");
        $this->db->from("xx_crm_crapp_credit_info ci");
        $this->db->join("xx_crm_crapp_contact_info cont", "cont.APP_HEADER_ID = ci.HEADER_ID", "left");
        $this->db->join("xx_crm_users u", "u.ID = ci.CUSTOMER_ID", "left");
        $this->db->order_by("ci.HEADER_ID", "DESC");

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
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


    public function delete_Credit_by_id($creditID)
    {
        $this->db->trans_start();

        $this->db->delete('xx_crm_crapp_credit_info', array('HEADER_ID' => $creditID));
        $this->db->delete('xx_crm_crapp_contact_info', array('APP_HEADER_ID' => $creditID));
        $this->db->delete('xx_crm_crapp_formal_info', array('APP_HEADER_ID' => $creditID));
        $this->db->delete('xx_crm_crapp_sign_info', array('APP_HEADER_ID' => $creditID));
        $this->db->delete('xx_crm_crapp_zpil_info', array('APP_HEADER_ID' => $creditID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }



    public function get_credit_application_by_uuid($creditUUID, $user_type = 'guest')
    {
        $data = ['credit' => []];

        if ($creditUUID) {
            // Fetch credit details
            $this->db->select("cr.UUID, cr.HEADER_ID,cr.APPLICATION_NUMBER, cr.APPLICATION_DATE,cr.CUSTOMER_ID,cr.CREDIT_VALUE,cr.CREDIT_IN_WORDS,cr.WITHIN_DAYS,cr.APPLICANT_COMMENT,co.HEADER_ID AS CONTACT_HEADER_ID, co.APP_HEADER_ID, co.COMPANY_NAME,co.CONTACT_PERSON,co.CONTACT_PERSON_TITLE,co.CONTACT_EMAIL,co.PHONE,co.FAX,co.COMPANY_EMAIL,co.CITY, co.STATE,co.COUNTRY, co.ZIP_CODE, co.ADDRESS_SPAN,co.BUSINESS_START_DATE, co.BUSINESS_TYPE, co.BANK_NAME, co.BANK_LOCATION, co.ACCOUNT_NUMBER, co.IBAN_NUMBER, co.SWIFT_CODE ,f.HEADER_ID AS FORMAL_HEADER_ID , f.APP_HEADER_ID,f.CRN_NUMBER, f.DATE_OF_ISSUANCE, f.DATE_OF_EXPIRY, f.COMPANY_LOCATION, f.PAID_UP_CAPITAL, f.COMPANY_OWNER, f.PERCENTAGE_OWNER, f.TOP_MANAGER, f.SIGN_NAME, f.SIGN_POSITION, f.SIGN_SPECIMEN, f.BUS_ACTIVITIES, f.GM_NAME, f.PUR_MGR_NAME, f.FIN_MGR_NAME,s.APP_HEADER_ID AS SIGN_HEADER_ID, s.ZPIL_SIGN, s.ZPIL_SIGNATORY_NAME,s.ZPIL_SIGN_POSN, s.ZPIL_DATE, s.CLIENT_SIGN, s.CLIENT_STAMP, s.CLIENT_SIGN_NAME, s.CLIENT_SIGN_DATE, s.CHAMBER_OF_COMMERCE, concat(usr.FIRST_NAME, ' ', usr.LAST_NAME) as CUSTOMER_FULL_NAME")
                ->from($this->credit_info_table . ' cr') // Alias for the product table
                ->join($this->user_table . ' usr', 'usr.ID = cr.CUSTOMER_ID', 'left')
                ->join($this->contact_info_table . ' co', 'cr.HEADER_ID = co.APP_HEADER_ID', 'left')
                ->join($this->formal_info_table . ' f', 'cr.HEADER_ID = f.APP_HEADER_ID', 'left')
                ->join($this->sign_info_table . ' s', 'cr.HEADER_ID = s.APP_HEADER_ID', 'left');

            // Conditionally join zpil_info_table only if user is admin
            if ($user_type === 'admin') {
                $this->db->select('z.APP_HEADER_ID AS ZPIL_HEADER_ID, z.DIR_SALES_COMMENTS, z.SALES_MGR_COMMENTS, z.GM_COMMENTS, z.CREDIT_DIV_COMMENTS, z.FIN_MGR_COMMENTS, z.MGMT_COMMENTS, z.REC_CREDIT_LIMIT, z.REC_CREDIT_PERIOD, z.APPROVED_FINANCE, z.APPROVED_MANAGEMENT, z.CRN_ATTACHMENT, z.BANK_CERTIFICATE, z.OWNER_ID')
                    ->join($this->zpil_info_table . ' z', 'cr.HEADER_ID = z.APP_HEADER_ID', 'left');
            }
            // Apply condition for the specific UUID
            $this->db->where('cr.UUID', $creditUUID);

            // Execute the query and fetch data
            $data['credit'] = $this->db->get()->row_array();
        }

        return $data;
    }


    public function delete_file_data($mainID, $fieldID)
    {
        // Determine which column to set as NULL based on the fieldID
        switch ($fieldID) {
            case 'ATTACHMENT':
                $column = 'CRN_ATTACHMENT';
                break;
            case 'CERTIFICATE':
                $column = 'BANK_CERTIFICATE';
                break;
            case 'OWNER':
                $column = 'OWNER_ID';
                break;
            default:
                return false; // Invalid fieldID
        }

        // Update the specific column to NULL
        $this->db->set($column, null);
        $this->db->where('APP_HEADER_ID', $mainID);
        $this->db->update('xx_crm_crapp_zpil_info');

        return $this->db->affected_rows() > 0; // Returns true if rows were updated
    }



    function get_financials($type = 'list', $limit = 10, $currentPage = 1, $filters = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select();
        $this->db->from();
        $this->db->join();
        $this->db->join();
        $this->db->join(); // Join with xx_crm_req_header

        $this->db->order_by();

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
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



    function get_outstanding($type = 'list', $limit = 10, $currentPage = 1, $filters = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select();
        $this->db->from();
        $this->db->join();
        $this->db->join();
        $this->db->join(); // Join with xx_crm_req_header

        $this->db->order_by();

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
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

    function get_statements($type = 'list', $limit = 10, $currentPage = 1, $filters = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select();
        $this->db->from();
        $this->db->join();
        $this->db->join();
        $this->db->join(); // Join with xx_crm_req_header

        $this->db->order_by();

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
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

    function get_credit($type = 'list', $limit = 10, $currentPage = 1, $filters = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select();
        $this->db->from();
        $this->db->join();
        $this->db->join();
        $this->db->join(); // Join with xx_crm_req_header

        $this->db->order_by();

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
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
}
