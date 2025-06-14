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
            'ORG_ID' => $data['ORG_ID'] ?? "",
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'EMAIL' => $data['EMAIL'],
            'PHONE' => $data['PHONE'],
            'COMPANY_NAME' => $data['COMPANY_NAME'],
            'JOB_TITLE' => $data['JOB_TITLE'],
            'LEAD_SOURCE' => $data['LEAD_SOURCE'],
            'STATUS' => $data['STATUS'],
            'ASSIGNED_TO' => $data['ASSIGNED_TO'],
            'ASSIGNED_TO_ID' => $data['ASSIGNED_TO_ID'],
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
            'ORG_ID' => $data['ORG_ID'],
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'EMAIL' => $data['EMAIL'],
            'PHONE' => $data['PHONE'],
            'COMPANY_NAME' => $data['COMPANY_NAME'],
            'JOB_TITLE' => $data['JOB_TITLE'],
            'LEAD_SOURCE' => $data['LEAD_SOURCE'],
            'STATUS' => $data['STATUS'],
            'ASSIGNED_TO' => $data['ASSIGNED_TO'],
            'ASSIGNED_TO_ID' => $data['ASSIGNED_TO_ID'],
            'UPDATED_AT' => date('Y-m-d'),
        ];

        // Insert new lead
        return $this->db->where('LEAD_ID', $leadID)->update($this->lead_table, $lead_data);
    }

    function get_leads($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = null, $mode = null)
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("l.LEAD_ID, l.ORG_ID, l.LEAD_NUMBER, l.FIRST_NAME, l.LAST_NAME, l.EMAIL, l.PHONE, l.COMPANY_NAME, l.JOB_TITLE, l.LEAD_SOURCE, l.STATUS, l.ASSIGNED_TO, l.ASSIGNED_TO_ID, l.LEAD_SCORE, l.NOTES, l.CREATED_AT");
        $this->db->from("xx_crm_leads l");
        $this->db->order_by("l.LEAD_ID", "DESC");

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        // Apply search across relevant fields
        if (!empty($search)) {
            $search = strtolower($search);
            $this->db->group_start(); // Open bracket for OR conditions
            $this->db->like('LOWER(l.LEAD_NUMBER)', $search);
            $this->db->or_like('LOWER(l.FIRST_NAME)', $search);
            $this->db->or_like('LOWER(l.LAST_NAME)', $search);
            $this->db->or_like('LOWER(l.EMAIL)', $search);
            $this->db->or_like('LOWER(l.COMPANY_NAME)', $search);
            $this->db->group_end(); // Close bracket
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

    public function get_lead_details_by_id($leadID)
    {
        return $this->db->query("SELECT lds.LEAD_ID, lds.UUID, lds.LEAD_NUMBER, lds.LEAD_SOURCE, lds.LEAD_EVENT, lds.STATUS, lds.ASSIGNED_TO, lds.ASSIGNED_TO_ID, lds.LEAD_SCORE, lds.ORG_ID,
            cn.CONTACT_ID, cn.FIRST_NAME, cn.LAST_NAME, cn.EMAIL, cn.PHONE, cn.MOBILE, cn.COMPANY_NAME, cn.JOB_TITLE, cn.DEPARTMENT, cn.CONTACT_SOURCE, cn.LAST_CONTACTED, cn.PREFERRED_CONTACT_METHOD, cn.ADDRESS,
            dls.DEAL_ID, dls.DEAL_STAGE, dls.DEAL_TYPE, dls.DEAL_VALUE, dls.DEAL_PRIORITY, dls.EXPECTED_CLOSE_DATE, dls.ACTUAL_CLOSE_DATE, dls.PROBABILITY, dls.DEAL_SOURCE, dls.DEAL_STATUS, dls.DEAL_DESCRIPTION, dls.NOTES, dls.CONTRACT_TERMS, dls.CLOSE_REASON, dls.DEAL_NUMBER,
            CONCAT(u.FIRST_NAME, ' ', u.LAST_NAME) AS ASSIGNED_TO
            FROM xx_crm_leads lds
            LEFT JOIN xx_crm_contacts cn ON cn.LEAD_ID = lds.LEAD_ID
            LEFT JOIN xx_crm_deals dls ON dls.ASSOCIATED_LEAD_ID = lds.LEAD_ID
            LEFT JOIN xx_crm_users u ON u.ID = lds.ASSIGNED_TO_ID
            WHERE lds.LEAD_ID = $leadID")->row_array();
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

    public function get_lead_details_and_activities_by_id($leadID)
    {
        $data['lead'] = $this->db->query("SELECT lds.LEAD_ID, lds.UUID, lds.LEAD_NUMBER, lds.LEAD_SOURCE, lds.LEAD_EVENT, lds.STATUS, lds.ASSIGNED_TO, lds.ASSIGNED_TO_ID, lds.LEAD_SCORE, lds.ORG_ID,
            cn.CONTACT_ID, cn.FIRST_NAME, cn.LAST_NAME, cn.EMAIL, cn.PHONE, cn.MOBILE, cn.COMPANY_NAME, cn.JOB_TITLE, cn.DEPARTMENT, cn.CONTACT_SOURCE, cn.LAST_CONTACTED, cn.PREFERRED_CONTACT_METHOD, cn.ADDRESS,
            dls.DEAL_ID, dls.DEAL_STAGE, dls.DEAL_TYPE, dls.DEAL_VALUE, dls.DEAL_PRIORITY, dls.EXPECTED_CLOSE_DATE, dls.ACTUAL_CLOSE_DATE, dls.PROBABILITY, dls.DEAL_SOURCE, dls.DEAL_STATUS, dls.DEAL_DESCRIPTION, dls.NOTES, dls.CONTRACT_TERMS, dls.CLOSE_REASON, dls.DEAL_NUMBER,
            CONCAT(u.FIRST_NAME, ' ', u.LAST_NAME) AS ASSIGNED_TO
            FROM xx_crm_leads lds
            LEFT JOIN xx_crm_contacts cn ON cn.LEAD_ID = lds.LEAD_ID
            LEFT JOIN xx_crm_deals dls ON dls.ASSOCIATED_LEAD_ID = lds.LEAD_ID
            LEFT JOIN xx_crm_users u ON u.ID = lds.ASSIGNED_TO_ID
            WHERE lds.LEAD_ID = $leadID")->row_array();
        $data['activities']['data'] = $this->get_activities_by_leadID($leadID);

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
                'ORG_ID' => $lead['ORG_ID'] ?? '',
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

    // 
    // Function to add or update product
    public function add_lead_details($data, $userid)
    {
        $lead_data = [
            'UUID' => uuid_v4(),
            'ORG_ID' => $data['ORG_ID'] ?? "",
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'EMAIL' => $data['EMAIL'],
            'PHONE' => $data['PHONE'],
            'COMPANY_NAME' => $data['COMPANY_NAME'],
            'JOB_TITLE' => $data['JOB_TITLE'],
            'LEAD_SOURCE' => $data['LEAD_SOURCE'],
            'LEAD_EVENT' => $data['LEAD_EVENT'],
            'STATUS' => $data['STATUS'],
            'ASSIGNED_TO' => $data['ASSIGNED_TO'],
            'ASSIGNED_TO_ID' => $data['ASSIGNED_TO_ID'],
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

            $data['ASSOC_LEAD_ID'] = $inserted_id;
            // Add Contact details if there are any
            $data['ASSOC_CONTACT_ID'] = $this->_add_contacts_from_lead($data, $userid);
            // add deals details if there are any
            $this->_add_deal_from_lead($data, $userid);

            return $this->get_lead_by_id($inserted_id);
        } else
            return false;
    }

    function _add_contacts_from_lead($data, $userid)
    {
        $contactData = [
            'UUID' => uuid_v4(),
            'ORG_ID' => $data['ORG_ID'] ?? '',
            'FIRST_NAME' => $data['FIRST_NAME'] ?? '',
            'LAST_NAME' => $data['LAST_NAME'] ?? '',
            'EMAIL' => $data['EMAIL'] ?? '',
            'PHONE' => $data['PHONE'] ?? '',
            'COMPANY_NAME' => $data['COMPANY_NAME'] ?? '',
            'JOB_TITLE' => $data['JOB_TITLE'] ?? '',
            'LEAD_ID' => $data['ASSOC_LEAD_ID'] ?? null,
            'DEPARTMENT' => $data['DEPARTMENT'] ?? '',
            'CONTACT_SOURCE' => 'Lead',
            'STATUS' => 'Active',
            'ASSIGNED_TO' => $data['ASSIGNED_TO'] ?? '',
            'ASSIGNED_TO_ID' => $data['ASSIGNED_TO_ID'] ?? '',
            'NOTES' => $data['NOTES'] ?? '',
            'PREFERRED_CONTACT_METHOD' => $data['PREFERRED_CONTACT_METHOD'] ?? '',
            'ADDRESS' => $data['ADDRESS'] ?? ''
        ];

        // Insert new lead
        if ($this->db->insert($this->contact_table, $contactData))
            return $this->db->insert_id() ?? null;
        else return null;
    }
    function _add_deal_from_lead($data, $userid)
    {
        $deal_data = [
            'UUID' =>  uuid_v4(),
            'DEAL_NAME' => "$data[FIRST_NAME] $data[FIRST_NAME]" ?? null,
            'ORG_ID' => $data['ORG_ID'] ?? null,
            'EMAIL' => $data['EMAIL'] ?? null,
            'CONTACT_NUMBER' => $data['PHONE'] ?? null,
            'ASSOCIATED_CONTACT_ID' => $data['ASSOC_CONTACT_ID'] ?? null,
            'ASSOCIATED_LEAD_ID' => $data['ASSOC_LEAD_ID'] ?? null,
            'DEAL_STAGE' => $data['DEAL_STAGE'] ?? null,
            'DEAL_TYPE' => $data['DEAL_TYPE'] ?? null,
            'DEAL_VALUE' => $data['DEAL_VALUE'] ?? null,
            'DEAL_PRIORITY' => $data['DEAL_PRIORITY'] ?? null,
            'EXPECTED_CLOSE_DATE' => $data['EXPECTED_CLOSE_DATE'] ?? null,
            'ACTUAL_CLOSE_DATE' => $data['ACTUAL_CLOSE_DATE'] ?? null,
            'PROBABILITY' => $data['PROBABILITY'] ?? null,
            'ASSIGNED_TO' => $data['ASSIGNED_TO'] ?? null,
            'ASSIGNED_TO_ID' => $data['ASSIGNED_TO_ID'] ?? null,
            'DEAL_SOURCE' => $data['LEAD_SOURCE'] ?? null,
            'DEAL_STATUS' => $data['DEAL_STATUS'] ?? null,
            'DEAL_DESCRIPTION' => $data['DEAL_DESCRIPTION'] ?? null,
            'NOTES' => $data['NOTES'] ?? null,
            'CONTRACT_TERMS' => $data['CONTRACT_TERMS'] ?? null,
            'CLOSE_REASON' => $data['CLOSE_REASON'] ?? null,
        ];

        // Insert new deal
        $inserted = $this->db->insert("xx_crm_deals", $deal_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value("xx_crm_deals", 'DEAL_ID', ['UUID' => $deal_data['UUID']]);
            // Create product_code in the required format
            $deal_number = "D" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
            // Update the deal_number field for the newly inserted product
            $this->db->where('DEAL_ID', $inserted_id);
            $this->db->update("xx_crm_deals", ['DEAL_NUMBER' => $deal_number]);
            return true;
        } else
            return false;
    }

    // Update lead details 
    // Function to add or update product
    public function update_lead_details($leadID, $data, $userid)
    {
        $lead_data = [
            'ORG_ID' => $data['ORG_ID'] ?? "",
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'EMAIL' => $data['EMAIL'],
            'PHONE' => $data['PHONE'],
            'COMPANY_NAME' => $data['COMPANY_NAME'],
            'JOB_TITLE' => $data['JOB_TITLE'],
            'LEAD_SOURCE' => $data['LEAD_SOURCE'],
            'LEAD_EVENT' => $data['LEAD_EVENT'],
            'STATUS' => $data['STATUS'],
            'ASSIGNED_TO' => $data['ASSIGNED_TO'],
            'ASSIGNED_TO_ID' => $data['ASSIGNED_TO_ID']
        ];

        // Insert new lead
        $updated = $this->db->where('LEAD_ID', $leadID)->update($this->lead_table, $lead_data);
        if ($updated) {
            $data['ASSOC_LEAD_ID'] = $leadID;

            $contact_details = $this->db->where('LEAD_ID', $data['ASSOC_LEAD_ID'])->get($this->contact_table)->row_array();
            if (empty($contact_details))
                $data['ASSOC_CONTACT_ID'] = $this->_add_contacts_from_lead($data, $userid);
            else
                $data['ASSOC_CONTACT_ID'] = $this->_update_contacts_from_lead($data, $userid);

            // Deals Details

            $deals_details = $this->db->where('ASSOCIATED_LEAD_ID', $data['ASSOC_LEAD_ID'])->get("xx_crm_deals")->row_array();



            if (empty($deals_details))
                $this->_add_deal_from_lead($data, $userid);
            else
                $this->_update_deal_from_lead($data, $userid);

            return $this->get_lead_details_by_id($leadID);
        } else
            return false;
    }

    function _update_contacts_from_lead($data, $userid)
    {
        $contactData = [
            'ORG_ID' => $data['ORG_ID'] ?? '',
            'FIRST_NAME' => $data['FIRST_NAME'] ?? '',
            'LAST_NAME' => $data['LAST_NAME'] ?? '',
            'EMAIL' => $data['EMAIL'] ?? '',
            'PHONE' => $data['PHONE'] ?? '',
            'COMPANY_NAME' => $data['COMPANY_NAME'] ?? '',
            'JOB_TITLE' => $data['JOB_TITLE'] ?? '',
            'DEPARTMENT' => $data['DEPARTMENT'] ?? '',
            'CONTACT_SOURCE' => 'Lead',
            'STATUS' => 'Active',
            'ASSIGNED_TO' => $data['ASSIGNED_TO'] ?? '',
            'ASSIGNED_TO_ID' => $data['ASSIGNED_TO_ID'] ?? '',
            'NOTES' => $data['NOTES'] ?? '',
            'PREFERRED_CONTACT_METHOD' => $data['PREFERRED_CONTACT_METHOD'] ?? '',
            'ADDRESS' => $data['ADDRESS'] ?? ''
        ];

        // Insert new lead
        if ($this->db->where('LEAD_ID', $data['ASSOC_LEAD_ID'])->update($this->contact_table, $contactData)) {
            $contact_details = $this->db->where('LEAD_ID', $data['ASSOC_LEAD_ID'])->get($this->contact_table)->row_array();
            return $contact_details['CONTACT_ID'] ?? null;
        } else return null;
    }
    function _update_deal_from_lead($data, $userid)
    {
        $deal_data = [
            'DEAL_NAME' => "$data[FIRST_NAME] $data[FIRST_NAME]" ?? null,
            'ORG_ID' => $data['ORG_ID'] ?? null,
            'EMAIL' => $data['EMAIL'] ?? null,
            'CONTACT_NUMBER' => $data['PHONE'] ?? null,
            'ASSOCIATED_CONTACT_ID' => $data['ASSOC_CONTACT_ID'] ?? null,
            'ASSOCIATED_LEAD_ID' => $data['ASSOC_LEAD_ID'] ?? null,
            'DEAL_STAGE' => $data['DEAL_STAGE'] ?? null,
            'DEAL_TYPE' => $data['DEAL_TYPE'] ?? null,
            'DEAL_VALUE' => $data['DEAL_VALUE'] ?? null,
            'DEAL_PRIORITY' => $data['DEAL_PRIORITY'] ?? null,
            'EXPECTED_CLOSE_DATE' => $data['EXPECTED_CLOSE_DATE'] ?? null,
            'ACTUAL_CLOSE_DATE' => $data['ACTUAL_CLOSE_DATE'] ?? null,
            'PROBABILITY' => $data['PROBABILITY'] ?? null,
            'ASSIGNED_TO' => $data['ASSIGNED_TO'] ?? null,
            'ASSIGNED_TO_ID' => $data['ASSIGNED_TO_ID'] ?? null,
            'DEAL_SOURCE' => $data['LEAD_SOURCE'] ?? null,
            'DEAL_STATUS' => $data['DEAL_STATUS'] ?? null,
            'DEAL_DESCRIPTION' => $data['DEAL_DESCRIPTION'] ?? null,
            'NOTES' => $data['NOTES'] ?? null,
            'CONTRACT_TERMS' => $data['CONTRACT_TERMS'] ?? null,
            'CLOSE_REASON' => $data['CLOSE_REASON'] ?? null,
        ];

        // Insert new deal
        return $this->db->where('ASSOCIATED_LEAD_ID', $data['ASSOC_LEAD_ID'])->update("xx_crm_deals", $deal_data);
    }
}
