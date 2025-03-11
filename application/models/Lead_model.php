<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Lead_model extends App_Model
{
    protected $lead_table;
    protected $contact_table;
    protected $lead_activity_table;

    public function __construct()
    {
        parent::__construct();
        $this->lead_activity_table = 'xx_crm_lead_activities';
        $this->lead_table = 'xx_crm_leads'; // Initialize token table
        $this->contact_table = 'xx_crm_contacts'; // Initialize token table
    }
    
    // Function to add or update product
    public function add_lead($data, $userid)
    {
        $lead_data = [
            'UUID' => $data['UUID'],
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'EMAIL' => $data['EMAIL'],
            'PHONE' => $data['PHONE'],
            'COMPANY_NAME' => $data['COMPANY_NAME'],
            'JOB_TITLE' => $data['JOB_TITLE'],
            'LEAD_SOURCE' => $data['LEAD_SOURCE'],
            'STATUS' => $data['STATUS'],
            'ASSIGNED_TO' => $data['ASSIGNED_TO'],
            'CREATED_AT' => date('Y-m-d'),
        ];

        // Insert new lead
        $inserted = $this->db->insert($this->lead_table, $lead_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->lead_table, 'LEAD_ID', ['UUID' => $lead_data['UUID']]);
            // Create product_code in the required format
            $lead_number = "L" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
            // Update the lead_number field for the newly inserted product
            $this->db->where('LEAD_ID', $inserted_id);
            $this->db->update($this->lead_table, ['LEAD_NUMBER' => $lead_number]);
            return true;
        } else
            return false;
    }

    // Function to add or update product
    public function update_lead($leadID, $data, $userid)
    {
        $lead_data = [
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'EMAIL' => $data['EMAIL'],
            'PHONE' => $data['PHONE'],
            'COMPANY_NAME' => $data['COMPANY_NAME'],
            'JOB_TITLE' => $data['JOB_TITLE'],
            'LEAD_SOURCE' => $data['LEAD_SOURCE'],
            'STATUS' => $data['STATUS'],
            'ASSIGNED_TO' => $data['ASSIGNED_TO'],
            'UPDATED_AT' => date('Y-m-d'),
        ];

        // Insert new lead
        return $this->db->where('LEAD_ID', $leadID)->update($this->lead_table, $lead_data);
    }

    function get_leads($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("l.LEAD_ID, l.LEAD_NUMBER, l.FIRST_NAME, l.LAST_NAME, l.EMAIL, l.PHONE, l.COMPANY_NAME, l.JOB_TITLE, l.LEAD_SOURCE, l.STATUS, l.ASSIGNED_TO, l.LEAD_SCORE, l.NOTES, l.CREATED_AT");
        $this->db->from("xx_crm_leads l");
        $this->db->order_by("l.LEAD_ID", "DESC");

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


    public function delete_lead_by_id($leadID)
    {
        $this->db->trans_start();

        $this->db->delete($this->lead_activity_table, array('LEAD_ID' => $leadID));

        $this->db->delete($this->lead_table, array('LEAD_ID' => $leadID));

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
    public function get_lead_by_email(string $email): ?array
    {
        $query = $this->db->get_where($this->lead_table, ['EMAIL' => $email]);
        return $query->row_array(); // Return user data or null
    }

    public function get_lead_by_uuid($leadUUID)
    {
        $data = [];
        if ($leadUUID) {
            $data = $this->db
                ->where('UUID', $leadUUID)
                ->get($this->lead_table)
                ->row_array();
        }

        return $data;
    }

    public function get_lead_by_id($leadID)
    {
        $data = [];
        if ($leadID) {
            $data = $this->db
                ->where('LEAD_ID', $leadID)
                ->get($this->lead_table)
                ->row_array();
        }

        return $data;
    }
    public function get_lead_and_activities_by_id($leadID)
    {
        $data = ['lead' => [], 'activities' => []];
        if ($leadID) {
            $data['lead'] = $this->db
                ->where('LEAD_ID', $leadID)
                ->get($this->lead_table)
                ->row_array();
            $data['activities']['data'] = $this->get_activities_by_leadID($leadID);
        }

        return $data;
    }

    function get_activities_by_leadID($leadID, $type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);
        $activities = [];

        if (isset($leadID)) {
            $leadID = intval($leadID);  // Sanitize leadID to ensure it's an integer

            $sql = "SELECT 
                    a.ACTIVITY_ID,
                    a.LEAD_ID,
                    a.USER_ID,
                    a.ACTIVITY_TYPE,
                    a.ACTIVITY_DATE,
                    a.STATUS,
                    a.NOTES,
                    a.CREATED_AT,
                    a.UPDATED_AT,
                    c.CALL_DURATION, c.CALL_PURPOSE, c.FOLLOW_UP_DATE,
                    e.SUBJECT, e.BODY, e.ATTACHMENTS, e.READ_STATUS,
                    m.LOCATION, m.AGENDA, m.ATTENDEES, m.OUTCOME,
                    t.TASK_DESCRIPTION, t.DUE_DATE, t.PRIORITY,
                    n.NOTE_CONTENT,
                    ev.EVENT_NAME, ev.EVENT_TYPE, ev.FEEDBACK
                FROM 
                    XX_CRM_LEAD_ACTIVITIES a
                LEFT JOIN XX_CRM_ACT_CALL c ON a.ACTIVITY_ID = c.ACTIVITY_ID
                LEFT JOIN XX_CRM_ACT_EMAIL e ON a.ACTIVITY_ID = e.ACTIVITY_ID
                LEFT JOIN XX_CRM_ACT_MEETING m ON a.ACTIVITY_ID = m.ACTIVITY_ID
                LEFT JOIN XX_CRM_ACT_TASK t ON a.ACTIVITY_ID = t.ACTIVITY_ID
                LEFT JOIN XX_CRM_ACT_NOTE n ON a.ACTIVITY_ID = n.ACTIVITY_ID
                LEFT JOIN XX_CRM_ACT_EVENT ev ON a.ACTIVITY_ID = ev.ACTIVITY_ID
                WHERE 
                    a.LEAD_ID = $leadID";  // Ensure $leadID is sanitized

            // Add filtering logic here if necessary (based on $filters or $search)
            // For example:
            // if (!empty($search)) {
            //     $sql .= " AND (a.NOTES LIKE '%" . $this->db->escape_like_str($search['notes']) . "%')";
            // }

            // Order by Activity ID descending
            $sql .= " ORDER BY a.ACTIVITY_ID DESC";

            // Add LIMIT and OFFSET for pagination
            if ($limit > 0) {
                $sql .= " LIMIT $limit";
            }

            if ($offset > 0) {
                $sql .= " OFFSET $offset";
            }

            // Execute the query
            $activities = $this->db->query($sql);

            if ($type == 'list') {
                return $activities->result_array();  // Return result as an array
            } else {
                return $activities->num_rows();  // Return the count of activities
            }
        }
    }

    public function convert_lead_by_id($leadID)
    {

        if ($leadID) {
            $lead = $this->db
                ->where('LEAD_ID', $leadID)
                ->get($this->lead_table)
                ->row_array();

            if (empty($lead)) {
                return false;
            }
            // Convert lead to contact
            $contact = [
                'UUID' => uuid_v4(),
                'FIRST_NAME' => $lead['FIRST_NAME'],
                'LAST_NAME' => $lead['LAST_NAME'],
                'EMAIL' => $lead['EMAIL'],
                'PHONE' => $lead['PHONE'],
                'COMPANY_NAME' => $lead['COMPANY_NAME'],
                'JOB_TITLE' => $lead['JOB_TITLE'],
                'CONTACT_SOURCE' => 'lead',
                'LEAD_SOURCE' => $lead['LEAD_ID'],
                'STATUS' => 'new',
                'PREFERRED_CONTACT_METHOD' => 'email'
            ];
            return $this->db->insert($this->contact_table, $contact);
        }
        return false;
    }
}
