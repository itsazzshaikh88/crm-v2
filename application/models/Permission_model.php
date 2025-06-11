<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Permission_model extends App_Model
{
    protected $permission_header_table;
    protected $permission_line_table;

    public function __construct()
    {
        parent::__construct();
        $this->permission_header_table = 'xx_crm_permission_header';
        $this->permission_line_table = 'xx_crm_permission_lines';
    }

    function get_permissions($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("ph.ID, ph.ROLE_ID, ph.ORG_ID, ph.DESCRIPTION, ph.STATUS, ph.CREATED_BY, ph.CREATED_DATE, ph.LAST_UPDATED_BY, ph.LAST_UPDATED_DATE, rl.ROLE_NAME, CONCAT(u.FIRST_NAME, ' ', u.LAST_NAME) as ASSIGNED_BY");
        $this->db->from($this->permission_header_table . " ph");
        $this->db->join("xx_crm_access_roles rl", "rl.ID = ph.ROLE_ID");
        $this->db->join("xx_crm_users u", "u.ID = ph.CREATED_BY", "LEFT");
        $this->db->order_by("ph.ID", "DESC");

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
    public function add_permission($data, $userid, $permissionID = null)
    {
        if ($permissionID == null) {
            $headerData = [
                'UUID' => uuid_v4(),
                'ROLE_ID' => $data['ROLE_ID'] ?? '',
                'STATUS' => 'ACTIVE',
                'CREATED_BY' => $userid ?? null
            ];
            // Insert new lead
            $inserted = $this->db->insert($this->permission_header_table, $headerData);
            if ($inserted) {
                $inserted_id = $this->get_column_value($this->permission_header_table, 'ID', ['UUID' => $headerData['UUID']]);
                $this->_add_permission_lines($data, $inserted_id, $userid);
                return true;
            } else
                return false;
        } else {
            $permission_details = $this->db->where('ID', $permissionID)->get('xx_crm_permission_header')->row_array();
            if (empty($permission_details)) {
                return false;
            }
            $this->_add_permission_lines($data, $permissionID, $userid);
            return true;
        }
    }

    function _add_permission_lines($data, $headerID, $userid)
    {
        // Delete Data from the lines table from the header id 
        $this->db->where('PERMISSION_HEADER_ID', $headerID)->delete('xx_crm_permission_lines');
        $total_resources = 0;
        if (isset($data['TOTAL_RESOURCES'])) {
            $total_resources = $data['TOTAL_RESOURCES'];
        }
        for ($i = 0; $i < $total_resources; $i++) {
            $line = [
                'PERMISSION_HEADER_ID' => $headerID,
                'RESOURCE_ID' => $data["RESOURCE_ID_$i"] ?? '0',
                'CAN_VIEW' => $data["CAN_VIEW_$i"] ?? '0',
                'CAN_CREATE' => $data["CAN_CREATE_$i"] ?? '0',
                'CAN_EDIT' => $data["CAN_EDIT_$i"] ?? '0',
                'CAN_DELETE' => $data["CAN_DELETE_$i"] ?? '0',
                'CAN_EXPORT' => $data["CAN_EXPORT_$i"] ?? '0',
                'CAN_PRINT' => $data["CAN_PRINT_$i"] ?? '0',
                'CAN_ASSIGN' => $data["CAN_ASSIGN_$i"] ?? '0',
                'CAN_SHARE' => $data["CAN_SHARE_$i"] ?? '0',
                'STATUS' => 'ACTIVE',
                'CREATED_BY' => $userid
            ];
            $this->db->insert('xx_crm_permission_lines', $line);
        }
    }


    // Function to add or update product
    public function update_resource($resourceID, $data, $userid)
    {
        $resourceData = [
            'RESOURCE_NAME' => $data['RESOURCE_NAME'] ?? '',
            'RESOURCE_DESCRIPTION' => $data['RESOURCE_DESCRIPTION'] ?? '',
            'RESOURCE_TYPE' => $data['RESOURCE_TYPE'] ?? '',
            'MODULE' => $data['MODULE'] ?? '',
            'MODULE_CATEGORY' => $data['MODULE_CATEGORY'] ?? '',
            'RESOURCE_PATH' => $data['RESOURCE_PATH'] ?? '',
            'IS_CLICKABLE' => isset($data['IS_CLICKABLE']) && $data['IS_CLICKABLE'] == '1' ? 1 : 0,
            'REDIRECT_TYPE' => $data['REDIRECT_TYPE'] ?? '',
            'REDIRECT_TARGET' => $data['REDIRECT_TARGET'] ?? '',
            'ON_CLICK_ACTION' => $data['ON_CLICK_ACTION'] ?? '',
            'COMPONENT_NAME' => $data['COMPONENT_NAME'] ?? '',
            'DISPLAY_ORDER' => $data['DISPLAY_ORDER'] ?? 0,
            'ICON_LABEL' => $data['ICON_LABEL'] ?? '',
            'TOOLTIP_TEXT' => $data['TOOLTIP_TEXT'] ?? '',
            'CSS_CLASS' => $data['CSS_CLASS'] ?? '',
            'VISIBILITY_CONDITION' => $data['VISIBILITY_CONDITION'] ?? '',
            'PARENT_RESOURCE_ID' => $data['PARENT_RESOURCE_ID'] ?? null,
            'IS_MENU_ITEM' => isset($data['IS_MENU_ITEM']) && $data['IS_MENU_ITEM'] == '1' ? 1 : 0,
            'IS_WIDGET' => isset($data['IS_WIDGET']) && $data['IS_WIDGET'] == '1' ? 1 : 0,
            'IS_ACTIONABLE' => isset($data['IS_ACTIONABLE']) && $data['IS_ACTIONABLE'] == '1' ? 1 : 0,
            'ASSOCIATED_ROLES' => $data['ASSOCIATED_ROLES'] ?? '',
            'TAGS' => $data['TAGS'] ?? '',
            'STATUS' => $data['STATUS'] ?? 'active',
            'ORG_ID' => $data['ORG_ID'] ?? null,
        ];

        // Insert new lead
        if ($this->db->where('ID', $resourceID)->update($this->permission_header_table, $resourceData))
            return $this->get_resource_by_key("ID", $resourceID);
        else
            return false;
    }


    public function delete_permission_by_id($permissionID)
    {
        $this->db->trans_start();

        $this->db->delete($this->permission_line_table, array('PERMISSION_HEADER_ID' => $permissionID));
        $this->db->delete($this->permission_header_table, array('ID' => $permissionID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function update_resource_status_by_id($resourceID, $status = 'active')
    {
        $resource_data = [
            'STATUS' => $status
        ];
        return $this->db->where('ID', $resourceID)->update($this->permission_header_table, $resource_data);
    }

    public function get_resource_by_uuid($resourceUUID)
    {
        return $data = $this->db
            ->where('UUID', $resourceUUID)
            ->get($this->permission_header_table)
            ->row_array();
    }

    public function get_resource_by_key($key, $value)
    {
        return $this->db
            ->where($key, $value)
            ->get($this->permission_header_table)
            ->row_array();
    }

    public function get_role_resource_permissions($roleId, $status = 'ACTIVE')
    {
        $this->db->select("
        R.ID AS RESOURCE_ID,
        R.RESOURCE_TYPE,
        R.RESOURCE_NAME,
        R.RESOURCE_PATH,
        R.REDIRECT_TARGET,
        R.IS_MENU_ITEM,
        R.MODULE,
        H.ID AS PERMISSION_HEADER_ID,
        H.ROLE_ID AS ASSIGNED_ROLE_ID,
        L.CAN_VIEW,
        L.CAN_CREATE,
        L.CAN_EDIT,
        L.CAN_DELETE,
        L.CAN_EXPORT,
        L.CAN_PRINT,
        L.CAN_ASSIGN,
        L.CAN_SHARE");
        $this->db->from('xx_crm_app_resources R');
        $this->db->join('xx_crm_permission_header H', 'H.ROLE_ID = ' . $this->db->escape($roleId) . ' AND H.STATUS = "ACTIVE"', 'left');
        $this->db->join('xx_crm_permission_lines L', 'L.PERMISSION_HEADER_ID = H.ID AND L.RESOURCE_ID = R.ID AND L.STATUS = "ACTIVE"', 'left');
        $this->db->order_by('R.RESOURCE_NAME', 'ASC');

        return $this->db->get()->result_array();
    }

    function get_assigned_permission($permissionID)
    {
        $permission_details = $this->db->where('ID', $permissionID)->get('xx_crm_permission_header')->row_array();
        if (!empty($permission_details)) {
            $roleID = $permission_details['ROLE_ID'] ?? null;
            if (!$roleID || $roleID == null)
                return [];

            // return permission details
            return $this->get_role_resource_permissions($roleID);
        }

        return [];
    }
}
