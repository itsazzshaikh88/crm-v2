<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Deal_model extends App_Model
{
    protected $deal_table;
    protected $contact_table;
    protected $lead_activity_table;

    public function __construct()
    {
        parent::__construct();
        $this->lead_activity_table = 'xx_crm_lead_activities';
        $this->deal_table = 'xx_crm_deals'; // Initialize token table
        $this->contact_table = 'xx_crm_contacts'; // Initialize token table
    }
    // Function to add or update product
    public function add_deal($data, $userid)
    {
        $deal_data = [
            'UUID' => $data['UUID'] ?? uuid_v4(),
            'DEAL_NAME' => $data['DEAL_NAME'] ?? null,
            'EMAIL' => $data['EMAIL'] ?? null,
            'CONTACT_NUMBER' => $data['CONTACT_NUMBER'] ?? null,
            'ASSOCIATED_CONTACT_ID' => $data['ASSOCIATED_CONTACT_ID'] ?? null,
            'DEAL_STAGE' => $data['DEAL_STAGE'] ?? null,
            'DEAL_TYPE' => $data['DEAL_TYPE'] ?? null,
            'DEAL_VALUE' => $data['DEAL_VALUE'] ?? null,
            'DEAL_PRIORITY' => $data['DEAL_PRIORITY'] ?? null,
            'EXPECTED_CLOSE_DATE' => $data['EXPECTED_CLOSE_DATE'] ?? null,
            'ACTUAL_CLOSE_DATE' => $data['ACTUAL_CLOSE_DATE'] ?? null,
            'PROBABILITY' => $data['PROBABILITY'] ?? null,
            'ASSIGNED_TO' => $data['ASSIGNED_TO'] ?? null,
            'DEAL_SOURCE' => $data['DEAL_SOURCE'] ?? null,
            'DEAL_STATUS' => $data['DEAL_STATUS'] ?? null,
            'DEAL_DESCRIPTION' => $data['DEAL_DESCRIPTION'] ?? null,
            'LAST_ACTIVITY_DATE' => $data['LAST_ACTIVITY_DATE'] ?? null,
            'NOTES' => $data['NOTES'] ?? null,
            'CONTRACT_TERMS' => $data['CONTRACT_TERMS'] ?? null,
            'CLOSE_REASON' => $data['CLOSE_REASON'] ?? null,
        ];

        // Insert new deal
        $inserted = $this->db->insert($this->deal_table, $deal_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->deal_table, 'DEAL_ID', ['UUID' => $deal_data['UUID']]);
            // Create product_code in the required format
            $deal_number = "D" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
            // Update the deal_number field for the newly inserted product
            $this->db->where('DEAL_ID', $inserted_id);
            $this->db->update($this->deal_table, ['DEAL_NUMBER' => $deal_number]);
            return true;
        } else
            return false;
    }

    // Function to add or update product
    public function update_deal($dealID, $data, $userid)
    {
        $deal_data = [
            'DEAL_NAME' => $data['DEAL_NAME'] ?? null,
            'EMAIL' => $data['EMAIL'] ?? null,
            'CONTACT_NUMBER' => $data['CONTACT_NUMBER'] ?? null,
            'ASSOCIATED_CONTACT_ID' => $data['ASSOCIATED_CONTACT_ID'] ?? null,
            'DEAL_STAGE' => $data['DEAL_STAGE'] ?? null,
            'DEAL_TYPE' => $data['DEAL_TYPE'] ?? null,
            'DEAL_VALUE' => $data['DEAL_VALUE'] ?? null,
            'DEAL_PRIORITY' => $data['DEAL_PRIORITY'] ?? null,
            'EXPECTED_CLOSE_DATE' => $data['EXPECTED_CLOSE_DATE'] ?? null,
            'ACTUAL_CLOSE_DATE' => $data['ACTUAL_CLOSE_DATE'] ?? null,
            'PROBABILITY' => $data['PROBABILITY'] ?? null,
            'ASSIGNED_TO' => $data['ASSIGNED_TO'] ?? null,
            'DEAL_SOURCE' => $data['DEAL_SOURCE'] ?? null,
            'DEAL_STATUS' => $data['DEAL_STATUS'] ?? null,
            'DEAL_DESCRIPTION' => $data['DEAL_DESCRIPTION'] ?? null,
            'LAST_ACTIVITY_DATE' => $data['LAST_ACTIVITY_DATE'] ?? null,
            'NOTES' => $data['NOTES'] ?? null,
            'CONTRACT_TERMS' => $data['CONTRACT_TERMS'] ?? null,
            'CLOSE_REASON' => $data['CLOSE_REASON'] ?? null,
        ];

        // Insert new deal
        return $this->db->where('DEAL_ID', $dealID)->update($this->deal_table, $deal_data);
    }

    function get_deals($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("d.DEAL_ID, d.UUID, d.DEAL_NAME, d.ASSOCIATED_CONTACT_ID, d.DEAL_STAGE, d.DEAL_TYPE, d.DEAL_VALUE, d.DEAL_PRIORITY, d.EXPECTED_CLOSE_DATE, d.ACTUAL_CLOSE_DATE, d.PROBABILITY, d.ASSIGNED_TO, d.DEAL_SOURCE, d.DEAL_STATUS, d.DEAL_DESCRIPTION, d.CREATED_AT, d.UPDATED_AT, d.LAST_ACTIVITY_DATE, d.NOTES, d.CONTRACT_TERMS, d.CLOSE_REASON");
        $this->db->from($this->deal_table . " d");
        $this->db->order_by("d.DEAL_ID", "DESC");

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


    public function delete_deal_by_id($dealID)
    {
        $this->db->trans_start();

        $this->db->delete($this->lead_activity_table, array('LEAD_ID' => $dealID, 'ACTIVITY_SOURCE' => 'deals'));

        $this->db->delete($this->deal_table, array('DEAL_ID' => $dealID));

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
    public function get_deal_by_email(string $email): ?array
    {
        $query = $this->db->get_where($this->deal_table, ['EMAIL' => $email]);
        return $query->row_array(); // Return user data or null
    }

    public function get_deal_by_uuid($dealUUID)
    {
        $data = [];
        if ($dealUUID) {
            $data = $this->db
                ->where('UUID', $dealUUID)
                ->get($this->deal_table)
                ->row_array();
        }

        return $data;
    }

    public function get_deal_by_id($dealID)
    {
        $data = [];
        if ($dealID) {
            $data = $this->db
                ->where('DEAL_ID', $dealID)
                ->get($this->deal_table)
                ->row_array();
        }

        return $data;
    }
    public function get_deal_and_activities_by_id($dealID)
    {
        $data = ['deal' => [], 'activities' => []];
        if ($dealID) {
            $data['deal'] = $this->db
                ->where('DEAL_ID', $dealID)
                ->get($this->deal_table)
                ->row_array();
            $data['activities']['data'] = $this->get_activities_by_leadID($dealID);
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
                    a.LEAD_ID = $leadID AND a.ACTIVITY_SOURCE = 'deals'";  // Ensure $leadID is sanitized

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
}
