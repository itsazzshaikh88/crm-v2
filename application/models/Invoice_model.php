<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';

class Invoice_model extends App_Model
{


    public function __construct()
    {
        parent::__construct();
    }
    // Function to add or update product

    function get_invoices($type = 'list', $limit = 10, $currentPage = 1, $filters = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select();
        $this->db->from();
        $this->db->join();
        $this->db->join();
        $this->db->join(); // Join with xx_crm_req_header

        $this->db->order_by();

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
}
