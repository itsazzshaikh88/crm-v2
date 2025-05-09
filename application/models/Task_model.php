<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Task_model extends App_Model
{
    protected $task_table;
    protected $task_comment_table;

    public function __construct()
    {
        parent::__construct();
        $this->task_table = 'xx_crm_tasks';
        $this->task_comment_table = 'xx_crm_task_comments';
    }

    public function get_task($type = 'list', $limit = 10, $currentPage = 1, $filters = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("ID, TASK_ID, TASK_NAME, STATUS, DEPARTMENT_ID, CONSULTANT_ID, START_DATE, TARGET_DATE, END_DATE, DURATION, CREATED_BY, UPDATED_BY, CREATED_AT, PARENT_ID, ORG_ID");
        $this->db->from($this->task_table);
        $this->db->where('IS_DELETED', 0);
        $this->db->order_by("ID", "ASC");

        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if ($type == 'list') {
            if ($limit > 0) {
                $this->db->limit($limit, ($offset > 0 ? $offset : 0));
            }
            return $this->db->get()->result_array();
        } else {
            return $this->db->count_all_results();
        }
    }


    // Function to add or update product
    public function add_task($data, $userid)
    {
        $taskData = $data;
        $taskData['UUID'] = uuid_v4();

        // Insert new lead
        $inserted = $this->db->insert($this->task_table, $taskData);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->task_table, 'ID', ['UUID' => $taskData['UUID']]);
            $taskID = "T-" . date('mdy') . sprintf("%06d", $inserted_id);
            // update records   
            $this->db->where('ID', $inserted_id)->update($this->task_table, ['TASK_ID' => $taskID]);

            return $this->get_task_by_id($inserted_id);
        } else
            return false;
    }

    // Function to add or update product
    public function update_task($taskID, $data, $userid)
    {
        $taskData = $data;

        // unset some columns that will not get updated
        unset($taskData['ID']);
        // update record
        if ($this->db->where('ID', $taskID)->update($this->task_table, $taskData)) {
            return $this->get_task_by_id($taskID);
        } else
            return false;
    }


    public function delete_task_by_id($taskID)
    {
        $this->db->trans_start();

        // Recursively delete all children
        $this->_delete_task_recursive($taskID);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    private function _delete_task_recursive($taskID)
    {
        // Get all child tasks of this task
        $this->db->where('PARENT_ID', $taskID);
        $query = $this->db->get($this->task_table);
        $children = $query->result();

        // Recursively delete each child
        foreach ($children as $child) {
            $this->_delete_task_recursive($child->ID);
        }

        // Delete this task after its children are deleted
        $this->db->delete($this->task_table, array('ID' => $taskID));
    }


    public function get_task_by_uuid($taskUUID)
    {
        $data = [];
        if ($taskUUID) {
            $data = $this->db
                ->where('UUID', $taskUUID)
                ->get($this->task_table)
                ->row_array();
        }

        return $data;
    }

    public function get_task_by_id($taskID)
    {
        $data = [];
        if ($taskID) {
            $data = $this->db
                ->where('ID', $taskID)
                ->get($this->task_table)
                ->row_array();
        }

        return $data;
    }

    public function get_task_comment_by_id($commentID)
    {
        $data = [];
        if ($commentID) {
            $data = $this->db
                ->where('ID', $commentID)
                ->get($this->task_comment_table)
                ->row_array();
        }

        return $data;
    }


    public function get_filtered_task($orgId, $year, $version)
    {
        $this->db->where('ORG_ID', $orgId);
        $this->db->where('YER', $year);
        $this->db->where('VER', $version);
        return $this->db->get($this->task_table)->result();
    }


    public function get_task_details_by_id($id)
    {
        return $this->db->get_where($this->task_table, ['ID' => $id])->row_array();
    }

    public function get_task_children_recursive($parentId)
    {
        $children = $this->db->get_where($this->task_table, ['PARENT_ID' => $parentId])->result_array();
        foreach ($children as &$child) {
            $child['children'] = $this->get_task_children_recursive($child['ID']);
        }
        return $children;
    }

    public function get_comments_recursive($taskID, $parentID = 0)
    {
        $this->db->select('c.*, u.USER_TYPE, CONCAT(u.FIRST_NAME, " ", u.LAST_NAME) AS FULL_NAME');
        $this->db->from('xx_crm_task_comments c');
        $this->db->join('xx_crm_users u', 'u.ID = c.CREATED_BY', 'left');
        $this->db->where('c.TASK_ID', $taskID);
        $this->db->where('c.PARENT_ID', $parentID);
        $this->db->order_by('c.CREATED_AT', 'ASC');

        $comments = $this->db->get()->result_array();

        foreach ($comments as &$comment) {
            $comment['replies'] = $this->get_comments_recursive($taskID, $comment['ID']);
        }

        return $comments;
    }


    // Add new commnets
    // Function to add or update product
    public function add_comment($data, $userid)
    {
        $taskCommentData = $data;
        $taskCommentData['UUID'] = uuid_v4();
        $taskCommentData['CREATED_BY'] = $userid;

        // unset some columns that will not get updated
        unset($taskCommentData['ID']);

        // Insert new lead
        $inserted = $this->db->insert($this->task_comment_table, $taskCommentData);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->task_comment_table, 'ID', ['UUID' => $taskCommentData['UUID']]);
            return $this->get_task_comment_by_id($inserted_id);
        } else
            return false;
    }

    // Function to add or update product
    public function update_comment($commentID, $data, $userid)
    {
        $taskCommentData = $data;

        unset($taskCommentData['ID']);
        // update record
        if ($this->db->where('ID', $commentID)->update($this->task_comment_table, $taskCommentData)) {
            return $this->get_task_comment_by_id($commentID);
        } else
            return false;
    }

    // In your Comment Model (e.g., Comment_model.php)

    public function delete_comment_by_id($commentID)
    {
        // Start a transaction to ensure data integrity
        $this->db->trans_start();

        // Recursively delete all child comments
        $this->_delete_comment_recursive($commentID);

        // Complete the transaction
        $this->db->trans_complete();

        return $this->db->trans_status(); // Return status of transaction (TRUE for success, FALSE for failure)
    }

    private function _delete_comment_recursive($commentID)
    {
        // Step 1: Get all child comments of this comment
        $this->db->where('PARENT_ID', $commentID);
        $query = $this->db->get('xx_crm_task_comments');  // Assuming your comments table is 'xx_crm_task_comments'
        $children = $query->result();

        // Step 2: Recursively delete each child comment
        foreach ($children as $child) {
            $this->_delete_comment_recursive($child->ID);  // Recursively delete the child comments
        }

        // Step 3: Delete this comment after its children are deleted
        $this->db->delete('xx_crm_task_comments', array('ID' => $commentID));
    }
}
