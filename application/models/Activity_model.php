<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Activity_model extends App_Model
{
    protected $lead_table;
    protected $activity_call_table;
    protected $activity_meeting_table;
    protected $activity_note_table;

    public function __construct()
    {
        parent::__construct();
        $this->lead_activity_table = 'xx_crm_lead_activities';
        $this->activity_call_table = 'xx_crm_act_call';
        $this->activity_meeting_table = 'xx_crm_act_meeting';
        $this->activity_note_table = 'xx_crm_act_note';
    }
    // Function to add or update product
    public function add_activity($data, $userid)
    {
        $activity_type = strtolower($this->input->post('ACTION'));
        $activity_type_input = $data["custom-activity-modal-" . $activity_type . "-ACTIVITY_TYPE"] ?? '';
        $activity_uuid_input = $data["custom-activity-modal-" . $activity_type . "-ACTIVITY_UUID"] ?? '';
        $activity_leadid_input = $data["custom-activity-modal-" . $activity_type . "-ACTIVITY_LEAD_ID"] ?? '';
        $activity_data = [
            'UUID' => $activity_uuid_input,
            'LEAD_ID' => $activity_leadid_input,
            'USER_ID' => $userid,
            'ACTIVITY_TYPE' => $activity_type_input,
            'ACTIVITY_DATE' => date('Y-m-d'),
            'STATUS' => 'active',
            'NOTES' => $data['NOTES'] ?? null,
            'CREATED_AT' => date('Y-m-d'),
        ];

        // Insert new lead
        $inserted = $this->db->insert($this->lead_activity_table, $activity_data);
        if ($inserted) {
            $activity_id_created = $this->get_column_value($this->lead_activity_table, 'ACTIVITY_ID', ['UUID' => $activity_uuid_input]);
            if (strtolower($activity_type_input) === 'call') {
                $call_data = [
                    'ACTIVITY_ID' => $activity_id_created,
                    'CALL_DURATION' => $data['CALL_DURATION'],
                    'CALL_PURPOSE' => $data['CALL_PURPOSE'],
                    'FOLLOW_UP_DATE' => $data['FOLLOW_UP_DATE'],
                ];
                $this->db->insert($this->activity_call_table, $call_data);
            } else if (strtolower($activity_type_input) === 'notes') {
                $notes_data = [
                    'ACTIVITY_ID' => $activity_id_created,
                    'NOTE_CONTENT' => $data['NOTES'] ?? null,
                ];
                $this->db->insert($this->activity_note_table, $notes_data);
            } else if (strtolower($activity_type_input) === 'meeting') {
                $meeting_data = [
                    'ACTIVITY_ID' => $activity_id_created,
                    'LOCATION' => $data['LOCATION'],
                    'AGENDA' => $data['AGENDA'],
                    'ATTENDEES' => $data['ATTENDEES'],
                    'OUTCOME' => $data['NOTES'] ?? '',
                ];
                $this->db->insert($this->activity_meeting_table, $meeting_data);
            }
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

    public function get_activity_by_uuid($activityUUID)
    {
        $data = ['activity' => [], 'details' => []];
        if ($activityUUID) {
            $data['activity'] = $this->db
                ->where('UUID', $activityUUID)
                ->get($this->lead_activity_table)
                ->row_array();

            if (isset($data['activity']['ACTIVITY_ID']) && isset($data['activity']['ACTIVITY_TYPE'])) {
                $activity_id = $data['activity']['ACTIVITY_ID'];
                if (strtolower($data['activity']['ACTIVITY_TYPE']) === 'call')
                    $details_table = $this->activity_call_table;
                else if (strtolower($data['activity']['ACTIVITY_TYPE']) === 'notes')
                    $details_table = $this->activity_note_table;
                else if (strtolower($data['activity']['ACTIVITY_TYPE']) === 'meeting')
                    $details_table = $this->activity_meeting_table;

                $data['details'] = $this->db
                    ->where('ACTIVITY_ID', $activity_id)
                    ->get($details_table)
                    ->row_array();
            }
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
}
