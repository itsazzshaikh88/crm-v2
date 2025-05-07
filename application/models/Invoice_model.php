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

        $sql = "SELECT 
        i.INVOICE_NUMBER, i.INVOICE_DATE, i.INVOICE_TIME, i.CUSTOMER_REGISTRATION_NAME, i.PAYMENT_TYPE, 
        i.INVOICE_TYPECODE, i.INVOICE_SUB_TYPECODE, i.ALLOWANCE_CHARGE_REASON, i.ALLOWANCE_AMOUNT, 
        i.TOTAL_TAX_AMOUNT, i.TAXABLE_AMOUNT, i.TAX_EXCLUSIVE_AMOUNT, i.TAX_INCLUSIVE_AMOUNT, 
        i.ALLOWANCE_TOTAL_AMOUNT, i.PREPAID_AMOUNT, i.PAYABLE_AMOUNT, imp.PDF_PATH
        FROM XXEN_INVOICE_HEADER_2 i
        JOIN XXZP.XXEINV_INV_IMPLMNT imp 
            ON imp.INVOICE_NUMBER = i.INVOICE_NUMBER AND imp.IS_GENERATED = 1";

        $conditions = [];

        // Date filtering logic
        $fromDate = !empty($filters['FROM_DATE']) ? addslashes($filters['FROM_DATE']) : null;
        $toDate = !empty($filters['TO_DATE']) ? addslashes($filters['TO_DATE']) : null;

        if ($fromDate && $toDate) {
            // Both from and to date are set
            $conditions[] = "i.INVOICE_DATE BETWEEN TO_DATE('$fromDate', 'YYYY-MM-DD') AND TO_DATE('$toDate', 'YYYY-MM-DD')";
        } elseif ($fromDate) {
            // Only from date is set
            $conditions[] = "i.INVOICE_DATE >= TO_DATE('$fromDate', 'YYYY-MM-DD')";
        } elseif ($toDate) {
            // Only to date is set
            $conditions[] = "i.INVOICE_DATE <= TO_DATE('$toDate', 'YYYY-MM-DD')";
        }

        // Remove FROM_DATE and TO_DATE from filters to avoid duplicate filtering
        unset($filters['FROM_DATE'], $filters['TO_DATE']);

        // Add other filters
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

        // Combine WHERE clause
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY i.INVOICE_DATE DESC";

        if ($type === "list") {
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
