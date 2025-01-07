<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_model extends CI_Model
{

    protected $xx_crm_po_header; // Holds the name of the user table
    protected $xx_crm_po_lines; // Holds the name of the token table

    public function __construct()
    {
        parent::__construct();
        $this->xx_crm_po_header = 'xx_crm_po_header'; // Initialize user table
        $this->xx_crm_po_lines = 'xx_crm_po_lines'; // Initialize token table

    }
    function purchase_det($po_id, $data, $user_id)
    {
        $header_data = [
            'UUID' => uuid_v4(),
            'QUOTE_ID' => $data['QUOTATION_NUMBER'] ?? null,
            'CLIENT_ID' => $data['CLIENT_ID'] ?? null,
            'REQUEST_ID' => $data['REQUEST_ID'] ?? null,
            'COMPANY_NAME' => $data['COMPANY_NAME'] ?? null,
            'COMPANY_ADDRESS' => $data['COMPANY_ADDRESS'] ?? null,
            'EMAIL_ADDRESS' => $data['EMAIL_ADDRESS'] ?? null,
            'CONTACT_NUMBER' => $data['CONTACT_NUMBER'] ?? null,
            'CURRENCY' => $data['CURRENCY'] ?? null,
            'PAYMENT_TERM' => $data['PAYMENT_TERM'] ?? null,
            'PO_STATUS' => $data['STATUS'] ?? 'Pending',
            'SUBTOTAL' => $data['SUBTOTAL'] ?? 0,
            'DISCOUNT_PERCENTAGE' => $data['DISCOUNT_PERCENTAGE'] ?? 0,
            'TAX_PERCENTAGE' => $data['TAX_PERCENTAGE'] ?? 0,
            'TOTAL_AMOUNT' => $data['TOTAL_AMOUNT'] ?? 0,
            'COMMENTS' => $data['COMMENTS'] ?? 0,
            'ATTACHMENTS' => isset($data['ATTACHMENTS']) ? json_encode($data['ATTACHMENTS']) : null,
            'CREATED_BY' => $user_id,
            'CREATED_AT' => date('Y-m-d H:i:s'),
        ];

        if (!in_array($po_id, [' ', '', 0, null])) {
            // check if the data is present 
            $this->db->where('PO_ID', $po_id);
            $request = $this->db->get($this->xx_crm_po_header)->row_array();
            // Append newly upoaded images
            if (isset($data['UPLOADED_FILES']) && !empty($data['UPLOADED_FILES'])) {
                $filesFromDB = $request['ATTACHMENTS'];
                if (!in_array($filesFromDB, ["", ' ', null, "\"\"", "\" \"", 'null', "''", "' '"])) {
                    $decodedFiles = json_decode($filesFromDB, true);
                    $filesToStore = array_merge($data['UPLOADED_FILES'], $decodedFiles);
                    $header_data['ATTACHMENTS'] = json_encode($filesToStore);
                } else {
                    $header_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);
                }
            }

            // Update existing product
            $this->db->where('PO_ID', $po_id);
            $this->db->update($this->xx_crm_po_header, $header_data);

            // Check if update was successful
            if ($this->db->affected_rows() > 0) {
                // Add Lines
                $this->addRequestLines($po_id, $data);
                return true;
            } else {
                return false;
            }
        } else {
            $header_data['CREATED_BY'] = $user_id;
            if (isset($data['UPLOADED_FILES']))
                $header_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);
            // Insert new product
            $inserted = $this->db->insert($this->xx_crm_po_header, $header_data);
            if ($inserted) {
                $inserted_id = $this->db->insert_id();
                // Create request_number in the required format
                $request_number = "PO-" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
                // Update the request_number field for the newly inserted product
                $this->db->where('PO_ID', $inserted_id);
                $this->db->update($this->xx_crm_po_header, ['PO_NUMBER' => $request_number]);

                // Add Lines
                $this->addRequestLines($inserted_id, $data);
                return true;
            } else
                return false;
        }
    }

    function addRequestLines($po_id, $data)
    {
        $total_lines = 0;
        if (isset($data['PRODUCT_ID']) && is_array($data['PRODUCT_ID']))
            $total_lines = count($data['PRODUCT_ID']);
        // Delete previous records if any
        $this->db->where('po_id', $po_id)->delete($this->xx_crm_po_lines);

        for ($row = 0; $row < $total_lines; $row++) {
            $line = [
                'po_id' => $po_id,
                'PRODUCT_ID' => $data['PRODUCT_ID'][$row] ?? null,
                'PRODUCT_DESC' => $data['PRODUCT_DESC'][$row] ?? null,
                'QTY' => $data['QTY'][$row] ?? null,
                'UNIT_PRICE' => $data['UNIT_PRICE'][$row] ?? null,
                'TOTAL' => $data['TOTAL'][$row] ?? null,
                'COLOR' => $data['COLOR'][$row] ?? null,
                'TRANSPORT' => $data['TRANSPORT'][$row] ?? null,
                'SOC' => $data['SOC'][$row] ?? null,
                'REC_QTY' => $data['REC_QTY'][$row] ?? null,
                'BAL_QTY' => $data['BAL_QTY'][$row] ?? null,
            ];
            $this->db->insert($this->xx_crm_po_lines, $line);
        }
    }
    public function get_req($type = 'list', $limit = 10, $currentPage = 1, $filters = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("PO.PO_ID, PO.PO_STATUS, PO.PO_NUMBER, PO.REQUEST_ID,  PO.UUID, PO.COMPANY_NAME, PO.EMAIL_ADDRESS, 
            PO.COMPANY_ADDRESS, PO.CONTACT_NUMBER, PO.PAYMENT_TERM, PO.TOTAL_AMOUNT, 
            PO.COMMENTS, 0 as QTY");
        $this->db->from("xx_crm_po_header PO");
        $this->db->order_by("PO.PO_ID", "DESC");

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        // Apply limit and offset only if 'list' type
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
    public function get_request_by_uuid($requestUUID)
    {
        $data = ['header' => []];

        if ($requestUUID) {

            $data['header'] = $this->db->select("PO.PO_ID, PO.PO_NUMBER, PO.UUID, PO.CLIENT_ID, PO.QUOTE_ID, PO.REQUEST_ID, PO.COMPANY_NAME, PO.EMAIL_ADDRESS, PO.COMPANY_ADDRESS, PO.CONTACT_NUMBER, PO.CURRENCY, PO.PAYMENT_TERM, PO.PO_STATUS, PO.SUBTOTAL, PO.DISCOUNT_PERCENTAGE, PO.TAX_PERCENTAGE, PO.TOTAL_AMOUNT, PO.COMMENTS, PO.ATTACHMENTS, PO.ACTION_BY, PO.VERSION, PO.IS_CONVERTED, PO.CONVERTED_FROM, PO.CREATED_AT, PO.CREATED_BY, PO.UPDATED_AT, PO.UPDATED_BY, u.FIRST_NAME, u.LAST_NAME, u.EMAIL AS USER_EMAIL")
                ->from("xx_crm_po_header PO")
                ->join("xx_crm_users u", "u.ID = PO.CLIENT_ID", "left")
                ->where("PO.PO_ID", $requestUUID)
                ->get()
                ->row_array();

            // Fetch line items if header exists
            if (isset($data['header']['PO_ID'])) {
                $data['lines'] = $this->db->select("POL.LINE_ID, POL.PO_ID, POL.PRODUCT_ID, POL.PRODUCT_DESC, POL.QTY, POL.UNIT_PRICE, POL.TOTAL, POL.COLOR, POL.TRANSPORT, POL.SOC, POL.REC_QTY, POL.BAL_QTY, P.PRODUCT_NAME")
                    ->from(" xx_crm_po_lines POL")
                    ->join("xx_crm_products P", "POL.PRODUCT_ID = P.PRODUCT_ID")
                    ->where("POL.PO_ID", $data['header']['PO_ID'])
                    ->get()
                    ->result_array();

                $clientID = $data['header']['CLIENT_ID'] ?? 0;
                $data['quotes'] = $this->db->query("select QUOTE_ID,UUID,QUOTE_NUMBER from xx_crm_quotations WHERE CLIENT_ID = $clientID")->result_array();
            }
        }

        return $data;
    }


    public function get_request_by_searchkey($searchkey, $searchvalue)
    {
        $data = ['header' => [], 'lines' => [], 'quotes' => []];

        if ($searchkey) {
            // Fetch request header details
            $data['header'] = $this->db->select("PO.PO_ID, PO.PO_NUMBER, PO.UUID, PO.CLIENT_ID, PO.QUOTE_ID, PO.REQUEST_ID, PO.COMPANY_NAME, PO.EMAIL_ADDRESS, 
                PO.COMPANY_ADDRESS, PO.CONTACT_NUMBER, PO.CURRENCY, PO.PAYMENT_TERM, PO.PO_STATUS, PO.SUBTOTAL, PO.DISCOUNT_PERCENTAGE, PO.TAX_PERCENTAGE, 
                PO.TOTAL_AMOUNT, PO.COMMENTS, PO.ATTACHMENTS, PO.ACTION_BY, PO.VERSION, PO.IS_CONVERTED, PO.CONVERTED_FROM, PO.CREATED_AT, 
                PO.CREATED_BY, PO.UPDATED_AT, PO.UPDATED_BY, u.FIRST_NAME, u.LAST_NAME, u.EMAIL AS USER_EMAIL")
                ->from("xx_crm_po_header PO")
                ->join("xx_crm_users u", "u.ID = PO.CLIENT_ID", "left")
                ->where("PO." . $searchkey, $searchvalue)
                ->get()
                ->row_array();

            // Fetch line items if header exists
            if (isset($data['header']['PO_ID'])) {
                $data['lines'] = $this->db->select("POL.LINE_ID, POL.PO_ID, POL.PRODUCT_ID, POL.PRODUCT_DESC, POL.QTY, POL.UNIT_PRICE, POL.TOTAL, 
                    POL.COLOR, POL.TRANSPORT, POL.SOC, POL.REC_QTY, POL.BAL_QTY, P.PRODUCT_NAME")
                    ->from("xx_crm_po_lines POL")
                    ->join("xx_crm_products P", "POL.PRODUCT_ID = P.PRODUCT_ID", "left")
                    ->where("POL.PO_ID", $data['header']['PO_ID'])
                    ->order_by("POL.LINE_ID")
                    ->get()
                    ->result_array();

                // Fetch related quotes
                $clientID = $data['header']['CLIENT_ID'] ?? 0;
                $data['quotes'] = $this->db->select("QUOTE_ID, UUID, QUOTE_NUMBER")
                    ->from("xx_crm_quotations")
                    ->where("CLIENT_ID", $clientID)
                    ->order_by("QUOTE_ID")
                    ->get()
                    ->result_array();
            }
        }

        return $data;
    }



    function fetchClientRequests($ClientID)
    {
        echo json_encode($this->Purchase_model->fetchClientRequests($ClientID));
    }
    public function delete_Request_by_id($poUUID)
    {
        $this->db->trans_start();

        $this->db->delete('xx_crm_po_lines', array('PO_ID' => $poUUID));

        $this->db->delete('xx_crm_po_header', array('PO_ID' => $poUUID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}
