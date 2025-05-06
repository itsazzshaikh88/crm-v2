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
            i.INVOICE_NUMBER,i.INVOICE_DATE,i.INVOICE_TIME,i.CUSTOMER_REGISTRATION_NAME,i.PAYMENT_TYPE,i.INVOICE_TYPECODE,i.INVOICE_SUB_TYPECODE,
                    i.ALLOWANCE_CHARGE_REASON,i.ALLOWANCE_AMOUNT,i.TOTAL_TAX_AMOUNT,i.TAXABLE_AMOUNT,i.TAX_EXCLUSIVE_AMOUNT,
                    i.TAX_INCLUSIVE_AMOUNT,i.ALLOWANCE_TOTAL_AMOUNT,i.PREPAID_AMOUNT,i.PAYABLE_AMOUNT, imp.PDF_PATH
            FROM XXEN_INVOICE_HEADER_2 i
            JOIN XXZP.XXEINV_INV_IMPLMNT imp ON imp.INVOICE_NUMBER = i.INVOICE_NUMBER AND imp.IS_GENERATED = 1
            ORDER BY 2 DESC 
            ";
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
