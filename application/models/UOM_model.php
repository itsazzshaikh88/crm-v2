<?php
defined('BASEPATH') or exit('No direct script access allowed');
class UOM_model extends App_Model
{
    protected $uom_table; // Holds the name of the token table

    public function __construct()
    {
        parent::__construct();
        $this->uom_table = 'xx_crm_unit_of_measurement'; // Initialize token table
    }
    // Function to add or update product
    function get_all_uom()
    {
        return $this->db->get($this->uom_table)->result_array();
    }

    // CRUD FOR API
    public function add_uom($data, $userid)
    {
        $uom_data = [
            'UUID' => uuid_v4(),
            'UOM_CODE' => $data['UOM_CODE'],
            'UOM_DESCRIPTION' => $data['UOM_DESCRIPTION'],
            'UOM_TYPE' => $data['UOM_TYPE'],
            'IS_ACTIVE' => $data['IS_ACTIVE']
        ];

        // Insert new lead
        $inserted = $this->db->insert($this->uom_table, $uom_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->uom_table, 'UOM_ID', ['UUID' => $uom_data['UUID']]);
            return $this->get_uom_by_key("UOM_ID", $inserted_id);
        } else
            return false;
    }

    // Function to add or update product
    public function update_uom($uomID, $data, $userid)
    {
        $uom_data = [
            'UOM_CODE' => $data['UOM_CODE'],
            'UOM_DESCRIPTION' => $data['UOM_DESCRIPTION'],
            'UOM_TYPE' => $data['UOM_TYPE'],
            'IS_ACTIVE' => $data['IS_ACTIVE']
        ];

        // Insert new lead
        if ($this->db->where('UOM_ID', $uomID)->update($this->uom_table, $uom_data)) {
            return $this->get_uom_by_key("UOM_ID", $uomID);
        } else {
            return false;
        }
    }

    function get_uom($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = null, $mode = null)
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("u.UOM_ID, u.UUID, u.UOM_CODE, u.UOM_DESCRIPTION, u.UOM_TYPE, u.CONVERSION_FACTOR, u.BASE_UOM_ID, u.IS_ACTIVE, u.CREATED_AT, u.UPDATED_AT");
        $this->db->from("xx_crm_unit_of_measurement u");
        $this->db->order_by("u.UOM_ID", "DESC");

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('LOWER(u.UOM_ID)', $search);
            $this->db->or_like('LOWER(u.UOM_CODE)', $search);
            $this->db->or_like('LOWER(u.UOM_TYPE)', $search);
            $this->db->group_end();
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


    public function delete_uom_by_id($leadID)
    {
        $this->db->trans_start();

        $this->db->delete($this->uom_table, array('UOM_ID' => $leadID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function get_uom_by_key($key, $value)
    {
        $query = $this->db->get_where($this->uom_table, [$key => $value]);
        return $query->row_array(); // Return user data or null
    }

    public function check_uom_exists($key, $value, $id)
    {
        $this->db->where($key, $value);
        $this->db->where("UOM_ID !=", $id); // Exclude the current uom UOM_ID
        $query = $this->db->get($this->uom_table);

        return $query->row_array(); // Return uom data if exists, otherwise null
    }
}
