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
    public function update_activity($activity_id, $data, $userid)
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
        ];

        // Insert new lead
        $updated = $this->db->where('ACTIVITY_ID', $activity_id)->update($this->lead_activity_table, $activity_data);
        if ($updated) {
            if (strtolower($activity_type_input) === 'call') {
                $call_data = [
                    'CALL_DURATION' => $data['CALL_DURATION'],
                    'CALL_PURPOSE' => $data['CALL_PURPOSE'],
                    'FOLLOW_UP_DATE' => $data['FOLLOW_UP_DATE'],
                ];
                $this->db->where('ACTIVITY_ID', $activity_id)->update($this->activity_call_table, $call_data);
            } else if (strtolower($activity_type_input) === 'notes') {
                $notes_data = [
                    'NOTE_CONTENT' => $data['NOTES'] ?? null,
                ];
                $this->db->where('ACTIVITY_ID', $activity_id)->update($this->activity_note_table, $notes_data);
            } else if (strtolower($activity_type_input) === 'meeting') {
                $meeting_data = [
                    'LOCATION' => $data['LOCATION'],
                    'AGENDA' => $data['AGENDA'],
                    'ATTENDEES' => $data['ATTENDEES'],
                    'OUTCOME' => $data['NOTES'] ?? '',
                ];
                $this->db->where('ACTIVITY_ID', $activity_id)->update($this->activity_meeting_table, $meeting_data);
            }
            return true;
        } else
            return false;
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

    public function get_activity_by_id($activityID)
    {
        $data = ['activity' => [], 'details' => []];
        if ($activityID) {
            $data['activity'] = $this->db
                ->where('ACTIVITY_ID', $activityID)
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

    public function delete_activity_by_id($activityID)
    {
        if ($activityID) {
            $data['activity'] = $this->db
                ->where('ACTIVITY_ID', $activityID)
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

                // Delete Activity details and then delete activity
                $this->db
                    ->where('ACTIVITY_ID', $activity_id)
                    ->delete($details_table);

                $this->db
                    ->where('ACTIVITY_ID', $activityID)
                    ->delete($this->lead_activity_table);
                return true;
            }
        }
        return false;
    }
}
