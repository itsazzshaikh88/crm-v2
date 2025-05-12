<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Roles_model extends App_Model
{
    protected $roles_table;

    public function __construct()
    {
        parent::__construct();
        $this->roles_table = 'xx_crm_access_roles'; // Initialize token table
    }

    function get_roles($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("r.ID, r.ROLE_NAME, r.DESCRIPTION, r.CREATED_AT, r.UPDATED_AT, r.IS_ACTIVE, r.ORG_ID, r.UUID");
        $this->db->from($this->roles_table . " r");
        $this->db->order_by("r.ID", "DESC");

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
    public function add_role($data, $userid)
    {
        $roleData = [
            'UUID' => uuid_v4(),
            'ROLE_NAME' => $data['ROLE_NAME'] ?? '',
            'DESCRIPTION' => $data['DESCRIPTION'],
            'IS_ACTIVE' => isset($data['IS_ACTIVE']) && $data['IS_ACTIVE'] == '1' ? 1 : 0
        ];

        // Insert new lead
        $inserted = $this->db->insert($this->roles_table, $roleData);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->roles_table, 'ID', ['UUID' => $roleData['UUID']]);
            return $this->get_role_by_key("ID", $inserted_id);
        } else
            return false;
    }


    // Function to add or update product
    public function update_role($roleID, $data, $userid)
    {
        $roleData = [
            'ROLE_NAME' => $data['ROLE_NAME'] ?? '',
            'DESCRIPTION' => $data['DESCRIPTION'],
            'IS_ACTIVE' => isset($data['IS_ACTIVE']) && $data['IS_ACTIVE'] == '1' ? 1 : 0
        ];

        // Insert new lead
        if ($this->db->where('ID', $roleID)->update($this->roles_table, $roleData))
            return $this->get_role_by_key("ID", $roleID);
        else
            return false;
    }


    public function delete_role_by_id($roleID)
    {
        $this->db->trans_start();

        $this->db->delete($this->roles_table, array('ID' => $roleID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function update_role_status_by_id($roleID, $status = '0')
    {
        $role_data = [
            'IS_ACTIVE' => $status
        ];
        return $this->db->where('ID', $roleID)->update($this->roles_table, $role_data);
    }

    public function get_role_by_uuid($roleUUID)
    {
        return $data = $this->db
            ->where('UUID', $roleUUID)
            ->get($this->roles_table)
            ->row_array();
    }

    public function get_role_by_key($key, $value)
    {
        return $this->db
            ->where($key, $value)
            ->get($this->roles_table)
            ->row_array();
    }

    public function get_role_by_name($name)
    {
        return $this->db
            ->where("LOWER(ROLE_NAME)", strtolower($name))
            ->get($this->roles_table)
            ->row_array();
    }
}
