<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Notification_model extends App_Model
{
    protected $notification_table;

    public function __construct()
    {
        parent::__construct();
        $this->notification_table = 'xx_crm_reminder_log';
    }

    public function shouldSendReminder($module_type, $record_id, $interval_minutes = null)
    {
        // Basic input validation
        if (empty($module_type) || empty($record_id)) {
            return true; // Invalid inputs, default to notifying
        }

        // Fetch the latest notification log for this module/record/user
        $this->db->select('SENT_AT, INTERVAL_MINUTES');
        $this->db->from('xx_crm_reminder_log');
        $this->db->where('MODULE_TYPE', $module_type);
        $this->db->where('RECORD_ID', $record_id);
        $this->db->order_by('SENT_AT', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        // No previous reminder found
        if ($query->num_rows() === 0) {
            return true;
        }

        $row = $query->row();

        $interval = $row->INTERVAL_MINUTES ?? 0;

        if (!$interval || !is_numeric($interval)) {
            return true; // No valid interval defined, allow notification
        }

        // Calculate next valid time to send
        $next_send_time = strtotime($row->SENT_AT . " +{$interval} minutes");
        $current_time = time();

        if ($current_time >= $next_send_time) {
            return true; // Enough time has passed, allow sending
        }

        return false; // Too early to send again
    }
}
