<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Contact_model extends App_Model
{
    protected $contact_table;
    protected $lead_activity_table;

    public function __construct()
    {
        parent::__construct();
        $this->contact_table = 'xx_crm_contacts'; // Initialize token table
    }

    function get_contacts($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("c.CONTACT_ID, c.UUID, c.FIRST_NAME, c.LAST_NAME, c.EMAIL, c.PHONE, c.MOBILE, c.COMPANY_NAME, c.JOB_TITLE, c.DEPARTMENT, c.CONTACT_SOURCE, c.LEAD_SOURCE, c.STATUS, c.ASSIGNED_TO, c.LAST_CONTACTED, c.NOTES, c.PREFERRED_CONTACT_METHOD, c.ADDRESS");
        $this->db->from($this->contact_table . " c");
        $this->db->order_by("c.CONTACT_ID", "DESC");

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

    // Function to add or update product
    public function add_contact($data, $userid)
    {
        $contactData = [
            'UUID' => $data['UUID'],
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'EMAIL' => $data['EMAIL'],
            'PHONE' => $data['PHONE'],
            'COMPANY_NAME' => $data['COMPANY_NAME'],
            'JOB_TITLE' => $data['JOB_TITLE'],
            'DEPARTMENT' => $data['DEPARTMENT'],
            'CONTACT_SOURCE' => $data['CONTACT_SOURCE'],
            'STATUS' => $data['STATUS'],
            'ASSIGNED_TO' => $data['ASSIGNED_TO'],
            'NOTES' => $data['NOTES'],
            'PREFERRED_CONTACT_METHOD' => $data['PREFERRED_CONTACT_METHOD'],
            'ADDRESS' => $data['ADDRESS']
        ];

        // Insert new lead
        $inserted = $this->db->insert($this->contact_table, $contactData);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->contact_table, 'CONTACT_ID', ['UUID' => $contactData['UUID']]);
            return true;
        } else
            return false;
    }

    // Function to add or update product
    public function update_contact($contactID, $data, $userid)
    {
        $contactData = [
            'UUID' => $data['UUID'],
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'EMAIL' => $data['EMAIL'],
            'PHONE' => $data['PHONE'],
            'COMPANY_NAME' => $data['COMPANY_NAME'],
            'JOB_TITLE' => $data['JOB_TITLE'],
            'DEPARTMENT' => $data['DEPARTMENT'],
            'CONTACT_SOURCE' => $data['CONTACT_SOURCE'],
            'STATUS' => $data['STATUS'],
            'ASSIGNED_TO' => $data['ASSIGNED_TO'],
            'NOTES' => $data['NOTES'],
            'PREFERRED_CONTACT_METHOD' => $data['PREFERRED_CONTACT_METHOD'],
            'ADDRESS' => $data['ADDRESS']
        ];

        // Insert new lead
        return $this->db->where('CONTACT_ID', $contactID)->update($this->contact_table, $contactData);
    }


    public function delete_contact_by_id($contactID)
    {
        $this->db->trans_start();

        $this->db->delete($this->contact_table, array('CONTACT_ID' => $contactID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get user by email
     *
     * @param string $email User email
     * @return array|null User data or null if not found
     */
    public function get_contact_by_email(string $email): ?array
    {
        $query = $this->db->get_where($this->contact_table, ['EMAIL' => $email]);
        return $query->row_array(); // Return user data or null
    }

    public function get_contact_by_uuid($contactUUID)
    {
        $data = [];
        if ($contactUUID) {
            $data = $this->db
                ->where('UUID', $contactUUID)
                ->get($this->contact_table)
                ->row_array();
        }

        return $data;
    }

    public function get_contact_by_id($contactID)
    {
        $data = [];
        if ($contactID) {
            $data = $this->db
                ->where('CONTACT_ID', $contactID)
                ->get($this->contact_table)
                ->row_array();
        }

        return $data;
    }

    public function get_contact_by_lead_id($leadID)
    {
        $data = [];
        if ($leadID) {
            $data = $this->db
                ->where('LEAD_SOURCE', $leadID)
                ->get($this->contact_table)
                ->row_array();
        }

        return $data;
    }
}
