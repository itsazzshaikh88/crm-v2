<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Resource_model extends App_Model
{
    protected $resource_table;

    public function __construct()
    {
        parent::__construct();
        $this->resource_table = 'xx_crm_app_resources'; // Initialize token table
    }

    function get_resources($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("r.ID, r.UUID, r.RESOURCE_NAME, r.RESOURCE_DESCRIPTION, r.RESOURCE_TYPE, r.MODULE, r.MODULE_CATEGORY, r.RESOURCE_PATH, r.IS_CLICKABLE, r.REDIRECT_TYPE, r.REDIRECT_TARGET, r.ON_CLICK_ACTION, r.COMPONENT_NAME, r.DISPLAY_ORDER, r.ICON_LABEL, r.TOOLTIP_TEXT, r.CSS_CLASS, r.VISIBILITY_CONDITION, r.PARENT_RESOURCE_ID, r.IS_MENU_ITEM, r.IS_WIDGET, r.IS_ACTIONABLE, r.ASSOCIATED_ROLES, r.TAGS, r.STATUS, r.ORG_ID, r.CREATED_BY, r.CREATED_DATE, r.LAST_UPDATED_BY, r.LAST_UPDATED_DATE");
        $this->db->from($this->resource_table . " r");
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
    public function add_resource($data, $userid)
    {
        $resourceData = [
            'UUID' => uuid_v4(),
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
            'CREATED_BY' => $userid ?? null
        ];


        // Insert new lead
        $inserted = $this->db->insert($this->resource_table, $resourceData);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->resource_table, 'ID', ['UUID' => $resourceData['UUID']]);
            return $this->get_resource_by_key("ID", $inserted_id);
        } else
            return false;
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
        if ($this->db->where('ID', $resourceID)->update($this->resource_table, $resourceData))
            return $this->get_resource_by_key("ID", $resourceID);
        else
            return false;
    }


    public function delete_resource_by_id($resourceID)
    {
        $this->db->trans_start();

        $this->db->delete($this->resource_table, array('ID' => $resourceID));

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
        return $this->db->where('ID', $resourceID)->update($this->resource_table, $resource_data);
    }

    public function get_resource_by_uuid($resourceUUID)
    {
        return $data = $this->db
            ->where('UUID', $resourceUUID)
            ->get($this->resource_table)
            ->row_array();
    }

    public function get_resource_by_key($key, $value)
    {
        return $this->db
            ->where($key, $value)
            ->get($this->resource_table)
            ->row_array();
    }
}
