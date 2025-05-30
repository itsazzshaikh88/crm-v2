<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Organization_model extends App_Model
{
    protected $org_table;

    public function __construct()
    {
        parent::__construct();
        $this->org_table = 'xx_crm_org_details';
    }
    // Function to add or update product
    public function add_news($data, $userid)
    {
        $news_data = [
            'UUID' => uuid_v4(),
            'TYPE' => $data['TYPE'] ?? null,
            'TITLE' => $data['TITLE'] ?? null,
            'DESCRIPTION' => $data['DESCRIPTION'] ?? null,
            'CATEGORY' => $data['CATEGORY'] ?? null,
            'PRIORITY' => $data['PRIORITY'] ?? null,
            'AUDIENCE' => $data['AUDIENCE'] ?? null,
            'VISIBILITY_SCOPE' => $data['VISIBILITY_SCOPE'] ?? null,
            'PUBLISH_DATE' => $data['PUBLISH_DATE'] ?? date('Y-m-d'),
            'EXPIRY_DATE' => $data['EXPIRY_DATE'] ?? null,
            'STATUS' => $data['STATUS'] ?? null,
            'IS_PINNED' => $data['IS_PINNED'] ?? null,
            'CREATED_BY' => $userid ?? 0,
        ];

        if (isset($data['UPLOADED_FILES']))
            $news_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);

        // Insert new minutes
        $inserted = $this->db->insert($this->org_table, $news_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->org_table, 'ID', ['UUID' => $news_data['UUID']]);
            return $this->get_news_by_id($inserted_id);
        } else
            return [];
    }

    // Function to add or update product
    public function update_news($newsID, $data, $userid)
    {
        $news_data = [
            'TYPE' => $data['TYPE'] ?? null,
            'TITLE' => $data['TITLE'] ?? null,
            'DESCRIPTION' => $data['DESCRIPTION'] ?? null,
            'CATEGORY' => $data['CATEGORY'] ?? null,
            'PRIORITY' => $data['PRIORITY'] ?? null,
            'AUDIENCE' => $data['AUDIENCE'] ?? null,
            'VISIBILITY_SCOPE' => $data['VISIBILITY_SCOPE'] ?? null,
            'PUBLISH_DATE' => $data['PUBLISH_DATE'] ?? date('Y-m-d'),
            'EXPIRY_DATE' => $data['EXPIRY_DATE'] ?? null,
            'STATUS' => $data['STATUS'] ?? null,
            'IS_PINNED' => $data['IS_PINNED'] ?? null,
            'CREATED_BY' => $userid ?? null,
        ];

        // check if the data is present 
        $this->db->where('ID', $newsID);
        $news = $this->db->get($this->org_table)->row_array();

        // Append newly upoaded images
        if (isset($data['UPLOADED_FILES']) && !empty($data['UPLOADED_FILES'])) {
            $filesFromDB = $news['ATTACHMENTS'];
            if (!in_array($filesFromDB, ["", ' ', null, "\"\"", "\" \"", 'null', "''", "' '"])) {
                $decodedFiles = json_decode($filesFromDB, true);
                $filesToStore = array_merge($data['UPLOADED_FILES'], $decodedFiles);
                $news_data['ATTACHMENTS'] = json_encode($filesToStore);
            } else {
                $news_data['ATTACHMENTS'] = json_encode($data['UPLOADED_FILES']);
            }
        }

        // update new minutes
        $this->db->where('ID', $newsID)->update($this->org_table, $news_data);
        return $this->get_news_by_id($newsID);
    }

    function get_news($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("n.ID, n.UUID, n.TYPE, n.TITLE, n.DESCRIPTION, n.ATTACHMENTS, n.CATEGORY, n.PRIORITY, n.AUDIENCE, n.VISIBILITY_SCOPE, n.PUBLISH_DATE, n.EXPIRY_DATE, n.STATUS, n.IS_PINNED, n.NOTIFICATION_SENT, n.READ_COUNT, n.COMMENTS_ENABLED, n.TAGS, n.CREATED_BY, n.UPDATED_BY, n.CREATED_AT, n.UPDATED_AT");
        $this->db->from($this->org_table . " n");
        $this->db->order_by("n.ID", "DESC");

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


    public function delete_news_by_id($newsID)
    {
        $this->db->trans_start();

        $this->db->delete($this->org_table, array('ID' => $newsID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function get_news_by_key($key, $value)
    {
        $data = [];
        if ($key && $value) {
            $data = $this->db
                ->where($key, $value)
                ->get($this->org_table)
                ->row_array();
        }

        return $data;
    }

    public function get_news_by_id($newsID)
    {
        $data = [];
        if ($newsID) {
            $data = $this->db
                ->where('ID', $newsID)
                ->get($this->org_table)
                ->row_array();
        }

        return $data;
    }

    function get_org_lov($filter = [])
    {
        return $this->db->query("select DISTINCT ORG_ID, ORG_CODE from xx_crm_org_details")->result_array();
    }
}
