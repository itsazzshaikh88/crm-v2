<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Mom_model extends App_Model
{
    protected $mom_table;

    public function __construct()
    {
        parent::__construct();
        $this->mom_table = 'XX_CRM_MINUTES_OF_MEETING'; // Initialize token table
    }
    // Function to add or update product
    public function add_mom($data, $userid)
    {
        $mom_data = [
            'UUID' => uuid_v4(),
            'MEETING_TITLE' => $data['MEETING_TITLE'] ?? null,
            'MEETING_DATE' => $data['MEETING_DATE'] ?? null,
            'DURATION' => $data['DURATION'] ?? null,
            'LOCATION_PLATFORM' => $data['LOCATION_PLATFORM'] ?? null,
            'ORGANIZER' => $data['ORGANIZER'] ?? null,
            'AGENDA' => $data['AGENDA'] ?? null,
            'MEETING_TYPE' => $data['MEETING_TYPE'] ?? null,
            'DISCUSSION_TOPICS' => $data['DISCUSSION_TOPICS'] ?? null,
            'DECISIONS' => $data['DECISIONS'] ?? null,
            'COMPANY_NAME' => $data['COMPANY_NAME'] ?? null,
            'GENERAL_NOTES' => $data['GENERAL_NOTES'] ?? null,
            'FOLLOW_UP_REQUIRED' => $data['FOLLOW_UP_REQUIRED'] ?? 0,
            'ASSOCIATED_PROJECT_ID' => $data['ASSOCIATED_PROJECT_ID'] ?? null,
            'TAGS' => $data['TAGS'] ?? null,
            'CREATED_BY' => $userid ?? null,
        ];

        // Make attendees
        $total_attendees = count($data['attendee_name'] ?? []);
        $attendees = [];
        for ($i = 0; $i < $total_attendees; $i++) {
            $attendees[] = ['name' => $data['attendee_name'][$i], 'email' => $data['attendee_email'][$i]];
        }
        if (!empty($attendees)) {
            $mom_data['ATTENDEES'] = json_encode($attendees);
        }

        // Insert new minutes
        $inserted = $this->db->insert($this->mom_table, $mom_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->mom_table, 'MOM_ID', ['UUID' => $mom_data['UUID']]);
            return $this->get_mom_by_id($inserted_id);
        } else
            return [];
    }

    // Function to add or update product
    public function update_mom($momID, $data, $userid)
    {
        $mom_data = [
            'MEETING_TITLE' => $data['MEETING_TITLE'] ?? null,
            'MEETING_DATE' => $data['MEETING_DATE'] ?? null,
            'DURATION' => $data['DURATION'] ?? null,
            'LOCATION_PLATFORM' => $data['LOCATION_PLATFORM'] ?? null,
            'ORGANIZER' => $data['ORGANIZER'] ?? null,
            'AGENDA' => $data['AGENDA'] ?? null,
            'MEETING_TYPE' => $data['MEETING_TYPE'] ?? null,
            'DISCUSSION_TOPICS' => $data['DISCUSSION_TOPICS'] ?? null,
            'DECISIONS' => $data['DECISIONS'] ?? null,
            'COMPANY_NAME' => $data['COMPANY_NAME'] ?? null,
            'GENERAL_NOTES' => $data['GENERAL_NOTES'] ?? null,
            'FOLLOW_UP_REQUIRED' => $data['FOLLOW_UP_REQUIRED'] ?? 0,
            'ASSOCIATED_PROJECT_ID' => $data['ASSOCIATED_PROJECT_ID'] ?? null,
            'TAGS' => $data['TAGS'] ?? null
        ];

        // Update attendees
        $total_attendees = count($data['attendee_name'] ?? []);
        $attendees = [];
        for ($i = 0; $i < $total_attendees; $i++) {
            $attendees[] = ['name' => $data['attendee_name'][$i], 'email' => $data['attendee_email'][$i]];
        }
        if (!empty($attendees)) {
            $mom_data['ATTENDEES'] = json_encode($attendees);
        } else {
            $mom_data['ATTENDEES'] = null;
        }

        // update new minutes
        $this->db->where('MOM_ID', $momID)->update($this->mom_table, $mom_data);
        return $this->get_mom_by_id($momID);
    }

    function get_moms($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("m.MOM_ID, m.UUID, m.MEETING_TITLE, m.MEETING_DATE, m.DURATION, m.LOCATION_PLATFORM, m.ORGANIZER, m.ATTENDEES, m.AGENDA, m.MEETING_TYPE, m.DISCUSSION_TOPICS, m.DECISIONS, m.COMPANY_NAME, m.GENERAL_NOTES, m.FOLLOW_UP_REQUIRED, m.ATTACHMENTS, m.ASSOCIATED_PROJECT_ID, m.TAGS, m.CREATED_BY, m.LAST_UPDATED_BY, m.VERSION, m.CREATED_AT, m.UPDATED_AT");
        $this->db->from($this->mom_table . " m");
        $this->db->order_by("m.MOM_ID", "DESC");

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


    public function delete_mom_by_id($momID)
    {
        $this->db->trans_start();

        $this->db->delete($this->mom_table, array('MOM_ID' => $momID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function get_mom_by_uuid($momUUID)
    {
        $data = [];
        if ($momUUID) {
            $data = $this->db
                ->where('UUID', $momUUID)
                ->get($this->mom_table)
                ->row_array();
        }

        return $data;
    }

    public function get_mom_by_id($momID)
    {
        $data = [];
        if ($momID) {
            $data = $this->db
                ->where('MOM_ID', $momID)
                ->get($this->mom_table)
                ->row_array();
        }

        return $data;
    }
}
