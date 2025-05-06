<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';

class Delivery_model extends App_Model
{


    public function __construct()
    {
        parent::__construct();
    }
    // Function to add or update product

    function get_deliveries($type = 'list', $limit = 10, $currentPage = 1, $filters = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $sql = "SELECT DISTINCT
            delivery_detail_id,
            delivery_no,
            delivery_line_id,
            source_name,
            soc,
            line_no,
            item,
            item_description,
            customer_id,
            requested_quantity,
            shipped_quantity,
            cust_po_number,
            packing_details,
            number_packing
        FROM
            wsh_delivery_detail_pack";

        // ADD CONDITIONS FROM FILTER
        // ADD CONDITIONS FROM FILTER 
        $conditions = [];
        foreach ($filters as $column => $value) {
            // If value is an array, use IN clause
            if (is_array($value)) {
                // Escape each value for safety
                $escapedValues = array_map(function ($v) {
                    return "'" . addslashes($v) . "'";
                }, $value);
                $conditions[] = "$column IN (" . implode(", ", $escapedValues) . ")";
            } else {
                // Escape single value
                $conditions[] = "$column = '" . addslashes($value) . "'";
            }
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }


        $sql .= " GROUP BY
            delivery_detail_id,
            delivery_no,
            delivery_line_id,
            source_name,
            soc,
            line_no,
            item,
            item_description,
            customer_id,
            requested_quantity,
            shipped_quantity,
            cust_po_number,
            packing_details,
            number_packing
        ORDER BY
            1 DESC ";
        if ($type == "list") {
            $sql .= "OFFSET $offset ROWS
                    FETCH NEXT $limit ROWS ONLY";
        }
        // Execute query
        $query = $this->oracleDB->query($sql);

        if ($type == 'list') {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }
}
