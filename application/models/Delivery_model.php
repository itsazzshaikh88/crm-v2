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

    function get_deliveries($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = null)
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

        // ADD CONDITIONS FROM FILTER - CREATION_DATE
        $conditions = [];

        // Date filtering logic
        $fromDate = !empty($filters['FROM_DATE']) ? addslashes($filters['FROM_DATE']) : null;
        $toDate = !empty($filters['TO_DATE']) ? addslashes($filters['TO_DATE']) : null;

        if ($fromDate && $toDate) {
            // Both from and to date are set
            $conditions[] = "CREATION_DATE BETWEEN TO_DATE('$fromDate', 'YYYY-MM-DD') AND TO_DATE('$toDate', 'YYYY-MM-DD')";
        } elseif ($fromDate) {
            // Only from date is set
            $conditions[] = "CREATION_DATE >= TO_DATE('$fromDate', 'YYYY-MM-DD')";
        } elseif ($toDate) {
            // Only to date is set
            $conditions[] = "CREATION_DATE <= TO_DATE('$toDate', 'YYYY-MM-DD')";
        }

        // Remove FROM_DATE and TO_DATE from filters to avoid duplicate filtering
        unset($filters['FROM_DATE'], $filters['TO_DATE']);

        foreach ($filters as $column => $value) {
            if (is_array($value)) {
                $escapedValues = array_map(function ($v) {
                    return "'" . addslashes($v) . "'";
                }, $value);
                $conditions[] = "$column IN (" . implode(", ", $escapedValues) . ")";
            } else {
                $conditions[] = "$column = '" . addslashes($value) . "'";
            }
        }

        // Search logic
        if (!empty($search)) {
            $search = addslashes($search);
            $searchConditions = [
                "LOWER(TO_CHAR(delivery_detail_id)) LIKE LOWER('%$search%')",
                "LOWER(delivery_no) LIKE LOWER('%$search%')",
                "LOWER(TO_CHAR(delivery_line_id)) LIKE LOWER('%$search%')",
                "LOWER(source_name) LIKE LOWER('%$search%')",
                "LOWER(soc) LIKE LOWER('%$search%')",
                "LOWER(line_no) LIKE LOWER('%$search%')",
                "LOWER(item) LIKE LOWER('%$search%')",
                "LOWER(item_description) LIKE LOWER('%$search%')",
                "LOWER(TO_CHAR(customer_id)) LIKE LOWER('%$search%')",
                "LOWER(cust_po_number) LIKE LOWER('%$search%')"
            ];
            $conditions[] = "(" . implode(" OR ", $searchConditions) . ")";
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
            $sql .= " OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";
        }

        // Execute query
        $query = $this->oracleDB->query($sql);

        // Get the result based on the type
        $result = ($type === 'list') ? $query->result_array() : $query->num_rows();

        // Close the database connection
        $this->oracleDB->close();

        // Return the result
        return $result;
    }
}
