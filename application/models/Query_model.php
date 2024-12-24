<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Query_model extends CI_Model
{
    function fetch_records($table_name, $columns, $return_type, $conditions = [])
    {
        // Select the specified columns
        $this->db->select($columns);
        // Apply WHERE conditions if provided
        if (!empty($conditions) && is_array($conditions)) {
            $this->db->where($conditions);
        }
        // Get the query result
        $query = $this->db->get($table_name);
        // Return the result based on the return type
        if ($return_type === 'row') {
            return $query->row_array(); // Single row as an array
        } elseif ($return_type === 'resultset') {
            return $query->result_array(); // All results as an array
        } else {
            return []; // Handle invalid return types
        }
    }
}
