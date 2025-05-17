<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Project_model extends App_Model
{
    protected $project_table;

    public function __construct()
    {
        parent::__construct();
        $this->project_table = 'XX_CRM_PROJECTS';
    }
    // Function to add or update product
    public function add_project($data, $userid)
    {
        $project_data = [
            'UUID' => uuid_v4(),
            'PROJECT_NAME' => $data['PROJECT_NAME'] ?? null,
            'DESCRIPTION' => $data['DESCRIPTION'] ?? null,
            'STATUS' => $data['STATUS'] ?? null,
            'START_DATE' => $data['START_DATE'] ?? null,
            'END_DATE' => $data['END_DATE'] ?? null,
            'PRIORITY' => $data['PRIORITY'] ?? null,
            'PROJECT_TYPE' => $data['PROJECT_TYPE'] ?? null,
            'PROJECT_MANAGER_ID' => $data['PROJECT_MANAGER_ID'] ?? null,
            'PROJECT_MANAGER' => $data['PROJECT_MANAGER'] ?? null,
            'CLIENT_NAME' => $data['CLIENT_NAME'] ?? null,
            'CLIENT_CONTACT' => $data['CLIENT_CONTACT'] ?? null,
            'CLIENT_EMAIL' => $data['CLIENT_EMAIL'] ?? null,
            'TOTAL_BUDGET' => $data['TOTAL_BUDGET'] ?? 0,
            'CURRENT_SPEND' => $data['CURRENT_SPEND'] ?? null,
            'PROGRESS' => $data['PROGRESS'] ?? null,
            'NOTES' => $data['NOTES'] ?? null,
            'VISIBLE' => $data['VISIBLE'] ?? null,
            'CREATED_BY' => $userid ?? null,
        ];

        if (isset($data['UPLOADED_FILES']))
            $project_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);

        // Insert new minutes
        $inserted = $this->db->insert($this->project_table, $project_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->project_table, 'PROJECT_ID', ['UUID' => $project_data['UUID']]);
            // Create project_code in the required format
            $project_code = "P-" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
            // Update the project_code field for the newly inserted product
            $this->db->where('PROJECT_ID', $inserted_id);
            $this->db->update($this->project_table, ['PROJECT_CODE' => $project_code]);
            return $this->get_project_by_id($inserted_id);
        } else
            return [];
    }

    // Function to add or update product
    public function update_project($projectID, $data, $userid)
    {
        $project_data = [
            'PROJECT_NAME' => $data['PROJECT_NAME'] ?? null,
            'DESCRIPTION' => $data['DESCRIPTION'] ?? null,
            'STATUS' => $data['STATUS'] ?? null,
            'START_DATE' => $data['START_DATE'] ?? null,
            'END_DATE' => $data['END_DATE'] ?? null,
            'PRIORITY' => $data['PRIORITY'] ?? null,
            'PROJECT_TYPE' => $data['PROJECT_TYPE'] ?? null,
            'PROJECT_MANAGER_ID' => $data['PROJECT_MANAGER_ID'] ?? null,
            'PROJECT_MANAGER' => $data['PROJECT_MANAGER'] ?? null,
            'CLIENT_NAME' => $data['CLIENT_NAME'] ?? null,
            'CLIENT_CONTACT' => $data['CLIENT_CONTACT'] ?? null,
            'CLIENT_EMAIL' => $data['CLIENT_EMAIL'] ?? null,
            'TOTAL_BUDGET' => $data['TOTAL_BUDGET'] ?? 0,
            'CURRENT_SPEND' => $data['CURRENT_SPEND'] ?? null,
            'PROGRESS' => $data['PROGRESS'] ?? null,
            'NOTES' => $data['NOTES'] ?? null,
            'VISIBLE' => $data['VISIBLE'] ?? null,
            'UPDATED_BY' => $userid ?? null,
        ];

        // check if the data is present 
        $this->db->where('PROJECT_ID', $projectID);
        $project = $this->db->get($this->project_table)->row_array();

        // Append newly upoaded images
        if (isset($data['UPLOADED_FILES']) && !empty($data['UPLOADED_FILES'])) {
            $filesFromDB = $project['ATTACHMENTS'];
            if (!in_array($filesFromDB, ["", ' ', null, "\"\"", "\" \"", 'null', "''", "' '"])) {
                $decodedFiles = json_decode($filesFromDB, true);
                $filesToStore = array_merge($data['UPLOADED_FILES'], $decodedFiles);
                $project_data['ATTACHMENTS'] = json_encode($filesToStore);
            } else {
                $project_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);
            }
        }

        // update new minutes
        $this->db->where('PROJECT_ID', $projectID)->update($this->project_table, $project_data);
        return $this->get_project_by_id($projectID);
    }

    function get_projects($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("p.PROJECT_ID, p.UUID, p.PROJECT_CODE, p.PROJECT_NAME, p.DESCRIPTION, p.STATUS, p.START_DATE, p.END_DATE, p.PRIORITY, p.PROJECT_TYPE, p.PROJECT_MANAGER_ID, p.CLIENT_NAME, p.CLIENT_CONTACT, p.CLIENT_EMAIL, p.TOTAL_BUDGET, p.CURRENT_SPEND, p.PROGRESS, p.ATTACHMENTS, p.NOTES, p.CREATED_AT, p.UPDATED_AT, p.VISIBLE, p.CREATED_BY, p.UPDATED_BY");
        $this->db->from($this->project_table . " p");
        $this->db->order_by("p.PROJECT_ID", "DESC");

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


    public function delete_project_by_id($projectID)
    {
        $this->db->trans_start();

        $this->db->delete($this->project_table, array('PROJECT_ID' => $projectID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function get_project_by_uuid($projectUUID)
    {
        $data = [];
        if ($projectUUID) {
            $data = $this->db
                ->where('UUID', $projectUUID)
                ->get($this->project_table)
                ->row_array();
        }

        return $data;
    }

    public function get_project_by_id($projectID)
    {
        $data = [];
        if ($projectID) {
            $data = $this->db
                ->where('PROJECT_ID', $projectID)
                ->get($this->project_table)
                ->row_array();
        }

        return $data;
    }
}
