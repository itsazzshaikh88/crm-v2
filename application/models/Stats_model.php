<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stats_model extends CI_Model
{
    public function dashboardCardStats($user_type, $client_id = null)
    {
        // Initialize an array for the counts
        $data = [
            'total_clients' => $this->db->where('USER_TYPE', 'client')->count_all_results('xx_crm_users'),
            'purchase_orders' => 0,
            'deliveries' => 0,
            'invoices' => 0,
            'leads' => $this->db->count_all_results('xx_crm_leads'),
            'deals' => $this->db->count_all_results('xx_crm_deals'),
            'requests' => $this->db->count_all_results('xx_crm_req_header'),
            'quotations' => $this->db->count_all_results('xx_crm_quotations'),
        ];

        // If the user is not an admin, filter by CLIENT_ID, else get all counts
        $client_condition = ($user_type != 'admin') ? $client_id : null;

        // Get counts for each table
        $data['purchase_orders'] = $this->get_count('xx_crm_po_header', 'CLIENT_ID', $client_condition);
        $data['deliveries'] = 0; // $this->get_count('xx_crm_deliveries', 'CLIENT_ID', $client_condition);
        $data['invoices'] = 0; // $this->get_count('xx_crm_invoices', 'CLIENT_ID', $client_condition);

        return $data;
    }

    // Helper method to get count for a table with a dynamic column and condition
    private function get_count($table, $column_name, $client_id = null)
    {
        if ($client_id) {
            $this->db->where($column_name, $client_id);
        }
        return $this->db->count_all_results($table);
    }
}
