<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Sales_model extends App_Model
{
    protected $forecast_table;

    public function __construct()
    {
        parent::__construct();
        $this->forecast_table = 'xx_crm_sales_forecast'; // Initialize token table
    }

    function get_forecast_versions($division, $year)
    {
        return $this->db->query("SELECT DISTINCT VER FROM xx_crm_sales_forecast WHERE ORG_ID = $division AND YER = $year")->result_array();
    }

    function get_forecast($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("RECORD_ID, f.ORG_ID, f.YER, f.CUSTOMER_NUMBER, f.CATEGORY_CODE, f.SUB_CATEGORY_CODE, f.CUSTOMER_NAME, f.ITEM_C, f.ITEM_DESC, f.PRODUCT_WEIGHT, f.UOM, f.SALES_MAN, f.SALES_MAN_ID, f.REGION, f.ORGANIZATION_ID, f.QTY_JAN, f.UNIT_JAN, f.VALUE_JAN, f.QTY_FEB, f.UNIT_FEB, f.VALUE_FEB, f.QTY_MAR, f.UNIT_MAR, f.VALUE_MAR, f.QTY_APR, f.UNIT_APR, f.VALUE_APR, f.QTY_MAY, f.UNIT_MAY, f.VALUE_MAY, f.QTY_JUN, f.UNIT_JUN, f.VALUE_JUN, f.QTY_JUL, f.UNIT_JUL, f.VALUE_JUL, f.QTY_AUG, f.UNIT_AUG, f.VALUE_AUG, f.QTY_SEP, f.UNIT_SEP, f.VALUE_SEP, f.QTY_OCT, f.UNIT_OCT, f.VALUE_OCT, f.QTY_NOV, f.UNIT_NOV, f.VALUE_NOV, f.QTY_DEC, f.UNIT_DEC, f.VALUE_DEC, f.FORECAST_TYPE, f.IS_BASED_ON, f.BASED_ON_NO_OF_YEARS, f.CREATED_BY, f.CREATION_DATE, f.LAST_UPDATED_BY, f.LAST_UPDATE_LOGIN, f.LAST_UPDATE_DATE, f.STATUS, f.VER, f.VERSION_KEY, f.WF_STATUS, f.ATTRIBUTE1, f.ATTRIBUTE2, f.ATTRIBUTE3, f.ATTRIBUTE4, f.ATTRIBUTE5, f.ATTRIBUTE6, f.ATTRIBUTE7, f.ATTRIBUTE8, f.ATTRIBUTE9, f.ATTRIBUTE10, f.ATTRIBUTE11, f.ATTRIBUTE12, f.ATTRIBUTE13, f.ATTRIBUTE14, f.ATTRIBUTE15");
        $this->db->from($this->forecast_table . " f");
        $this->db->order_by("f.RECORD_ID", "DESC");

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

    // Function to add or update product
    public function add_forecast($data, $userid)
    {
        $forecastData = $data;
        $forecastData['UUID'] = uuid_v4();
        // Add other fields which are not coming in from user

        /*
                RECORD_ID, ORG_ID, YER, CUSTOMER_NUMBER, CUSTOMER_NAME, ITEM_C, ITEM_DESC, PRODUCT_WEIGHT, UOM, SALES_MAN, SALES_MAN_ID, REGION, ORGANIZATION_ID, QTY_JAN, UNIT_JAN, VALUE_JAN, QTY_FEB, UNIT_FEB, VALUE_FEB, QTY_MAR, UNIT_MAR, VALUE_MAR, QTY_APR, UNIT_APR, VALUE_APR, QTY_MAY, UNIT_MAY, VALUE_MAY, QTY_JUN, UNIT_JUN, VALUE_JUN, QTY_JUL, UNIT_JUL, VALUE_JUL, QTY_AUG, UNIT_AUG, VALUE_AUG, QTY_SEP, UNIT_SEP, VALUE_SEP, QTY_OCT, UNIT_OCT, VALUE_OCT, QTY_NOV, UNIT_NOV, VALUE_NOV, QTY_DEC, UNIT_DEC, VALUE_DEC, FORECAST_TYPE, IS_BASED_ON, BASED_ON_NO_OF_YEARS, CREATED_BY, CREATION_DATE, LAST_UPDATED_BY, LAST_UPDATE_LOGIN, LAST_UPDATE_DATE, STATUS, VER, VERSION_KEY, WF_STATUS, ATTRIBUTE1, ATTRIBUTE2, ATTRIBUTE3, ATTRIBUTE4, ATTRIBUTE5, ATTRIBUTE6, ATTRIBUTE7, ATTRIBUTE8, ATTRIBUTE9, ATTRIBUTE10, ATTRIBUTE11, ATTRIBUTE12, ATTRIBUTE13, ATTRIBUTE14, ATTRIBUTE15
        */

        // Insert new lead
        $inserted = $this->db->insert($this->forecast_table, $forecastData);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->forecast_table, 'RECORD_ID', ['UUID' => $forecastData['UUID']]);
            return $this->get_forecast_by_id($inserted_id);
        } else
            return false;
    }

    // Function to add or update product
    public function update_forecast($forecastID, $data, $userid)
    {
        $forecastData = $data;

        // unset some columns that will not get updated
        unset($forecastData['RECORD_ID']);
        // update record
        if ($this->db->where('RECORD_ID', $forecastID)->update($this->forecast_table, $forecastData)) {
            return $this->get_forecast_by_id($forecastID);
        } else
            return false;
    }


    public function delete_forecast_by_id($forecastID)
    {
        $this->db->trans_start();

        $this->db->delete($this->forecast_table, array('RECORD_ID' => $forecastID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function get_forecast_by_uuid($forecastUUID)
    {
        $data = [];
        if ($forecastUUID) {
            $data = $this->db
                ->where('UUID', $forecastUUID)
                ->get($this->forecast_table)
                ->row_array();
        }

        return $data;
    }

    public function get_forecast_by_id($forecastID)
    {
        $data = [];
        if ($forecastID) {
            $data = $this->db
                ->where('RECORD_ID', $forecastID)
                ->get($this->forecast_table)
                ->row_array();
        }

        return $data;
    }

    public function get_filtered_forecast($orgId, $year, $version)
    {
        $this->db->where('ORG_ID', $orgId);
        $this->db->where('YER', $year);
        $this->db->where('VER', $version);
        return $this->db->get($this->forecast_table)->result();
    }
}
