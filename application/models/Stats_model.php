<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stats_model extends CI_Model
{
    function dashboardCardStats()
    {
        $data = ['total_clients' => 0, 'purchase_orders' => 0, 'deliveries' => 0, 'invoices' => 0];
        // Get all clients
        $data['total_clients'] = $this->db->where('USER_TYPE', 'client')->count_all_results('xx_crm_users');
        // Get All Purchase Orders

        // get all deliveries

        // get all invoices

        return $data;
    }
}
