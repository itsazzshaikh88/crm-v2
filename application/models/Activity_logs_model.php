<?php

class Activity_logs_model extends CI_Model
{
    function get_activities_logs($type = 'list', $limit = 10, $currentPage = 1, $source = '', $filters = [], $search = [], $user = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("lg.ID, lg.USER_ID, lg.USER_TYPE, lg.ACTIVITY_TYPE, lg.DESCRIPTION, 
                            lg.MODULE, lg.PAGE_URL, lg.REQUEST_METHOD, lg.REQUEST_DATA, lg.RESPONSE_STATUS, 
                            lg.RESPONSE_TIME, lg.IP_ADDRESS, lg.BROWSER, lg.BROWSER_VERSION, lg.OS, lg.OS_VERSION, lg.DEVICE_TYPE, 
                            lg.DEVICE_NAME, lg.USER_AGENT, lg.SCREEN_RESOLUTION, lg.LOCATION_COUNTRY, lg.LOCATION_REGION, lg.LOCATION_CITY, 
                            lg.LATITUDE, lg.LONGITUDE, lg.SESSION_ID, lg.REFERER_URL, lg.IS_SUCCESS, lg.ERROR_MESSAGE, lg.CREATED_AT, lg.UPDATED_AT,
                            u.USER_TYPE, u.FIRST_NAME, u.LAST_NAME");
        $this->db->from("xx_crm_activity_log lg");
        $this->db->join("xx_crm_users u", "u.ID = lg.USER_ID");
        $this->db->order_by("lg.ID", "DESC");

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if ($source === 'my-activities')
            $this->db->where("lg.USER_ID", $user['userid'] || 0);

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
}
