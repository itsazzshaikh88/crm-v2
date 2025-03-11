<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Category_model extends App_Model
{
    protected $category_table; // Holds the name of the token table

    public function __construct()
    {
        parent::__construct();
        $this->category_table = 'xx_crm_product_categories'; // Initialize token table
    }
    // Function to add or update product
    function get_all_categories()
    {
        return $this->db->get($this->category_table)->result_array();
    }

    // CRUD FOR API
    public function add_category($data, $userid)
    {
        $category_data = [
            'UUID' => uuid_v4(),
            'CATEGORY_CODE' => $data['CATEGORY_CODE'],
            'CATEGORY_NAME' => $data['CATEGORY_NAME'],
            'DESCRIPTION' => $data['DESCRIPTION']
        ];

        // Insert new lead
        $inserted = $this->db->insert($this->category_table, $category_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->category_table, 'ID', ['UUID' => $category_data['UUID']]);
            return $this->get_category_by_key("ID", $inserted_id);
        } else
            return false;
    }

    // Function to add or update product
    public function update_category($categoryID, $data, $userid)
    {
        $category_data = [
            'CATEGORY_CODE' => $data['CATEGORY_CODE'],
            'CATEGORY_NAME' => $data['CATEGORY_NAME'],
            'DESCRIPTION' => $data['DESCRIPTION']
        ];

        // Insert new lead
        if ($this->db->where('ID', $categoryID)->update($this->category_table, $category_data)) {
            return $this->get_category_by_key("ID", $categoryID);
        } else {
            return false;
        }
    }

    function get_categories($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("c.ID, c.UUID, c.CATEGORY_CODE, c.CATEGORY_NAME, c.DESCRIPTION, c.CREATED_AT, c.UPDATED_AT");
        $this->db->from("xx_crm_product_categories c");
        $this->db->order_by("c.ID", "DESC");

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


    public function delete_category_by_id($leadID)
    {
        $this->db->trans_start();

        $this->db->delete($this->category_table, array('ID' => $leadID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function get_category_by_key($key, $value)
    {
        $query = $this->db->get_where($this->category_table, [$key => $value]);
        return $query->row_array(); // Return user data or null
    }

    public function check_category_exists($key, $value, $id)
    {
        $this->db->where($key, $value);
        $this->db->where("ID !=", $id); // Exclude the current category ID
        $query = $this->db->get($this->category_table);

        return $query->row_array(); // Return category data if exists, otherwise null
    }
}
