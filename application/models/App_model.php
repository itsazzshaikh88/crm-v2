<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App_Model extends CI_Model
{
    protected $oracleDB;
    public function __construct()
    {
        parent::__construct();
        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, '/crm-v2/') !== false || strpos($uri, '/crm-test/') !== false) {
            $this->oracleDB = $this->load->database('oracle', TRUE);
        } else {
            // $this->oracleDB = $this->load->database('oracle', TRUE);
        }
    }

    /**
     * Insert data into a specified table
     * @param string $table - Table name
     * @param array $data - Data to insert
     * @return int|bool - Inserted row ID or FALSE on failure
     */
    public function insert($table, $data)
    {
        if ($this->db->insert($table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Batch insert data into a specified table
     * @param string $table - Table name
     * @param array $data - Array of data to insert
     * @return int|bool - Number of rows inserted or FALSE on failure
     */
    public function insert_batch($table, $data)
    {
        return $this->db->insert_batch($table, $data) ? $this->db->affected_rows() : false;
    }

    /**
     * Update data in a specified table
     * @param string $table - Table name
     * @param array $data - Data to update
     * @param array $where - Conditions for update
     * @return bool - TRUE on success or FALSE on failure
     */
    public function update($table, $data, $where)
    {
        return $this->db->update($table, $data, $where);
    }

    /**
     * Batch update data in a specified table
     * @param string $table - Table name
     * @param array $data - Array of data to update
     * @param string $where_key - Key to match for updates
     * @return int|bool - Number of rows updated or FALSE on failure
     */
    public function update_batch($table, $data, $where_key)
    {
        return $this->db->update_batch($table, $data, $where_key) ? $this->db->affected_rows() : false;
    }

    /**
     * Delete data from a specified table
     * @param string $table - Table name
     * @param array $where - Conditions for deletion
     * @return bool - TRUE on success or FALSE on failure
     */
    public function delete($table, $where)
    {
        return $this->db->delete($table, $where);
    }

    /**
     * Count rows in a specified table
     * @param string $table - Table name
     * @param array $where - Conditions for counting
     * @return int - Count of rows
     */
    public function count_rows($table, $where = [])
    {
        $this->db->where($where);
        return $this->db->count_all_results($table);
    }

    /**
     * Get rows from a specified table
     * @param string $table - Table name
     * @param array $where - Conditions for retrieval
     * @param string $fields - Specific fields to retrieve
     * @param int $limit - Limit the number of rows
     * @param int $offset - Offset for rows
     * @return array - Resulting rows
     */
    public function get_rows($table, $where = [], $fields = '*', $limit = null, $offset = null)
    {
        $this->db->select($fields);
        $this->db->where($where);
        if ($limit !== null) $this->db->limit($limit, $offset);
        $query = $this->db->get($table);
        return $query->result_array();
    }

    /**
     * Get a single row from a specified table
     * @param string $table - Table name
     * @param array $where - Conditions for retrieval
     * @param string $fields - Specific fields to retrieve
     * @return array|null - Resulting row or null if not found
     */
    public function get_row($table, $where = [], $fields = '*')
    {
        $this->db->select($fields);
        $this->db->where($where);
        $query = $this->db->get($table);
        return $query->row_array();
    }

    /**
     * Get values based on table, column, and conditions
     * @param string $table - Table name
     * @param mixed $columns - Column to select (string)
     * @param array $where - Conditions for retrieval
     * @return mixed - single column value or null
     */
    public function get_column_value($table, $column, $where = [])
    {
        $this->db->select($column);
        $this->db->where($where);
        $query = $this->db->get($table);
        $row = $query->row_array();
        return $row[$column] ?? null;
    }

    function add_activity_logs($action, $user, $system, $request)
    {
        $activity = [
            'USER_ID' => $user['userid'] ?? '0',
            'USER_TYPE' => $user['role'] ?? 'guest',
            'ACTIVITY_TYPE' => $action['ACTIVITY_TYPE'] ?? '',
            'DESCRIPTION' => $action['DESCRIPTION'] ?? '',
            'MODULE' => $action['MODULE'] ?? '',
            'PAGE_URL' => $request['REQUEST_URI'] ?? '',
            'REQUEST_METHOD' => $request['REQUEST_METHOD'] ?? '',
            'REQUEST_DATA' => json_encode($request['REQUEST_DATA'] ?? '[]'),
            'RESPONSE_STATUS' => $request['RESPONSE_STATUS'] ?? '',
            'RESPONSE_TIME' => $request['RESPONSE_TIME'] ?? '',
            'IP_ADDRESS' => $system['IP_ADDRESS'] ?? '',
            'BROWSER' => $system['BROWSER'] ?? 'Unknown',
            'BROWSER_VERSION' => '',
            'OS' => '',
            'OS_VERSION' => '',
            'DEVICE_TYPE' => '',
            'DEVICE_NAME' => '',
            'USER_AGENT' => $system['USER_AGENT'] ?? '',
            'SCREEN_RESOLUTION' => '',
            'LOCATION_COUNTRY' => '',
            'LOCATION_REGION' => '',
            'LOCATION_CITY' => '',
            'LATITUDE' => '',
            'LONGITUDE' => '',
            'SESSION_ID' => '',
            'REFERER_URL' => '',
            'IS_SUCCESS' => '',
            'ERROR_MESSAGE' => '',
        ];

        return $this->db->insert('xx_crm_activity_log', $activity);
    }
}
