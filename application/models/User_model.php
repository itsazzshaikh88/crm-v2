<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class User_model extends App_Model
{
    protected $user_table; // Holds the name of the user table
    protected $user_2fa_table; // Holds the name of the user table
    protected $client_table; // Holds the name of the user table
    protected $client_address_table; // Holds the name of the user table

    public function __construct()
    {
        parent::__construct();
        $this->user_table = 'xx_crm_users'; // Initialize user table
        $this->user_2fa_table = 'XX_CRM_USER_2FA_DETAILS'; // Initialize user 2fa table
        $this->client_table = 'xx_crm_client_detail'; // Initialize client table
        $this->client_address_table = 'xx_crm_client_address'; // Initialize client address table

    }

    /**
     * Create a new user
     *
     * @param array $data User data
     * @return int Inserted user ID
     */
    public function create_user(array $data)
    {
        $this->db->insert($this->user_table, $data);
        return $this->db->insert_id();
    }

    /**
     * Get user by ID
     *
     * @param int $user_id User ID
     * @return array|null User data or null if not found
     */
    public function get_user_by_id(int $user_id): ?array
    {
        $query = $this->db->get_where($this->user_table, ['ID' => $user_id]);
        return $query->row_array(); // Return user data or null
    }

    /**
     * Get user by email
     *
     * @param string $email User email
     * @return array|null User data or null if not found
     */
    public function get_user_by_email(string $email): ?array
    {
        $query = $this->db->get_where($this->user_table, ['email' => $email]);
        return $query->row_array(); // Return user data or null
    }

    /**
     * Update user data
     *
     * @param int $user_id User ID
     * @param array $data User data to update
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_user(int $user_id, array $data): bool
    {
        $this->db->where('id', $user_id);
        return $this->db->update($this->user_table, $data);
    }

    /**
     * Delete a user
     *
     * @param int $user_id User ID
     * @return bool TRUE on success, FALSE on failure
     */
    public function delete_user(int $user_id): bool
    {
        $this->db->where('id', $user_id);
        return $this->db->delete($this->user_table);
    }

    /**
     * Get all users
     *
     * @return array List of users
     */
    public function get_all_users(): array
    {
        $query = $this->db->get($this->user_table);
        return $query->result_array(); // Return array of user data
    }

    /**
     * Get all users by query
     *
     * @return array List of users
     */
    public function get_all_users_by_query(): array
    {
        $query = $this->db->query("SELECT * FROM XX_CRM_USERS");
        return $query->result_array(); // Return array of user data
    }

    /**
     * Check if a user exists by user ID
     *
     * @param int $user_id User ID
     * @return bool TRUE if user exists, FALSE otherwise
     */
    public function validate_user(int $user_id)
    {
        $query = $this->db->get_where($this->user_table, ['id' => $user_id]);
        return $query->row(); // Return true if user exists
    }



    // Functions to create clients
    function get_clients($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = null)
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("u.ID,u.UUID, u.ORG_ID, u.USER_ID, u.FIRST_NAME, u.LAST_NAME, u.EMAIL,u.PHONE_NUMBER, u.STATUS, cd.COMPANY_NAME, cd.CREDIT_LIMIT, cd.TAXES, cd.ORDER_LIMIT,  ca.ADDRESS_LINE_1, ca.ADDRESS_LINE_2, ca.BILLING_ADDRESS, ca.SHIPPING_ADDRESS, ca.CITY, ca.STATE, ca.COUNTRY, ca.ZIP_CODE, cd.PAYMENT_TERM, cd.CURRENCY");
        $this->db->from($this->user_table . " u");
        $this->db->join($this->client_table . " cd", "cd.USER_ID = u.ID", "left");
        $this->db->join($this->client_address_table . " ca", "ca.CLIENT_ID = cd.USER_ID", "left");
        // Add the WHERE condition
        $this->db->where("u.USER_TYPE", 'client');
        $this->db->order_by("u.ID", "DESC");


        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if (isset($search) && $search != null) {
            $search = strtolower($search);
            $this->db->group_start(); // Begin group for OR conditions
            $this->db->like('LOWER(u.ID)', $search, 'both', false);
            $this->db->or_like('LOWER(u.USER_ID)', $search, 'both', false);
            $this->db->or_like('LOWER(u.FIRST_NAME)', $search, 'both', false);
            $this->db->or_like('LOWER(u.LAST_NAME)', $search, 'both', false);
            $this->db->or_like('LOWER(u.EMAIL)', $search, 'both', false);
            $this->db->or_like('LOWER(cd.COMPANY_NAME)', $search, 'both', false);
            $this->db->group_end(); // End group for OR conditions

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

    // Functions to create clients
    function get_users($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = null)
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("u.ID, u.USER_ID, u.UUID, u.ORG_ID, u.USER_TYPE, u.FIRST_NAME, u.LAST_NAME, u.EMAIL, u.PASSWORD, u.PHONE_NUMBER, u.STATUS, u.CREATED_AT, u.UPDATED_AT, u.IS_2FA_ENABLED");
        $this->db->from($this->user_table . " u");
        $this->db->order_by("u.ID", "DESC");


        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if (isset($search) && $search != null) {
            $this->db->group_start(); // Begin group for OR conditions
            $this->db->like('u.USER_ID', $search, 'both', false);
            $this->db->or_like('CONCAT(u.FIRST_NAME, " ", u.LAST_NAME)', $search, 'both', false);
            $this->db->or_like('u.EMAIL', $search, 'both', false);
            $this->db->group_end(); // End group for OR conditions
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

    // Function to add or update client
    public function add_client($client_id, $data, $userid)
    {
        $users_data = [
            'UUID' => $data['UUID'],
            'ORG_ID' => $data['ORG_ID'] ?? '',
            'USER_TYPE' => 'client',
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'EMAIL' => $data['EMAIL'],
            'PHONE_NUMBER' => $data['PHONE_NUMBER']
        ];
        $client_details = [
            'COMPANY_NAME' => $data['COMPANY_NAME'],
            'SITE_NAME' => $data['SITE_NAME'],
            'PAYMENT_TERM' => $data['PAYMENT_TERM'],
            'CREDIT_LIMIT' => $data['CREDIT_LIMIT'],
            'TAXES' => $data['TAXES'],
            'CURRENCY' => $data['CURRENCY'],
            'ORDER_LIMIT' => $data['ORDER_LIMIT']
        ];
        $client_address = [
            'ADDRESS_LINE_1' => $data['ADDRESS_LINE_1'],
            'ADDRESS_LINE_2' => $data['ADDRESS_LINE_2'],
            'BILLING_ADDRESS' => $data['BILLING_ADDRESS'],
            'SHIPPING_ADDRESS' => $data['SHIPPING_ADDRESS'],
            'CITY' => $data['CITY'],
            'STATE' => $data['STATE'],
            'COUNTRY' => $data['COUNTRY'],
            'ZIP_CODE' => $data['ZIP_CODE']
        ];
        if (!in_array($client_id, [' ', '', 0, null])) {
            $existedUserDetails = $this->db->where('ID', $client_id)->get($this->user_table)->row_array();
            // Update existing client
            $this->db->where('ID', $client_id);
            $this->db->update($this->user_table, $users_data);

            // Check if client details are present if then update else insert
            $existedClientDetails = $this->db->where('USER_ID', $client_id)->get($this->client_table)->row_array();
            if (!empty($existedClientDetails)) {
                $this->db->where('USER_ID', $client_id);
                $this->db->update($this->client_table, $client_details);
            } else {
                // insert client details
                $client_details['CLIENT_ID'] = $existedUserDetails['USER_ID'] ?? "CL-" . str_pad($client_id, 6, '0', STR_PAD_LEFT);
                $client_details['USER_ID'] = $client_id;
                $this->db->insert($this->client_table, $client_details);
            }


            // Check if client address details are present if yes update else insert
            $existedClientAddressDetails = $this->db->where('CLIENT_ID', $client_id)->get($this->client_address_table)->row_array();
            if (!empty($existedClientAddressDetails)) {
                $this->db->where('CLIENT_ID', $client_id);
                $this->db->update($this->client_address_table, $client_address);
            } else {
                // Insert client address details
                $client_address['CLIENT_ID'] = $client_id;
                $this->db->insert($this->client_address_table, $client_address);
            }

            return true;
        } else {
            // Insert new client
            $inserted = $this->db->insert($this->user_table, $users_data);
            if ($inserted) {
                $inserted_id = $this->db->insert_id();
                $client_id = "CL-" . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
                // Generate the hashed password using ARGON2ID
                $hashedPassword = password_hash($data['PASSWORD'], PASSWORD_ARGON2ID);
                $this->db->where('ID', $inserted_id);
                $this->db->update($this->user_table, ['USER_ID' => $client_id, 'PASSWORD' => $hashedPassword]);
                // insert client details
                $client_details['CLIENT_ID'] = $client_id;
                $client_details['USER_ID'] = $inserted_id;
                $this->db->insert($this->client_table, $client_details);
                // Insert client address details
                $client_address['CLIENT_ID'] = $inserted_id;
                $this->db->insert($this->client_address_table, $client_address);
                return true;
            } else
                return false;
        }
    }

    public function get_client_by_uuid($clientUUID)
    {
        $data = [];
        if ($clientUUID) {
            $data = $this->db->select("u.ID, u.USER_ID, u.UUID, u.ORG_ID, u.USER_TYPE, u.FIRST_NAME, u.LAST_NAME, u.EMAIL, u.PHONE_NUMBER, u.STATUS, cd.COMPANY_NAME, cd.SITE_NAME, cd.PAYMENT_TERM, cd.CREDIT_LIMIT, cd.TAXES, cd.CURRENCY, cd.ORDER_LIMIT, ca.ADDRESS_LINE_1, ca.ADDRESS_LINE_2, ca.BILLING_ADDRESS, ca.SHIPPING_ADDRESS, ca.CITY, ca.STATE, ca.COUNTRY, ca.ZIP_CODE")
                ->from($this->user_table . " u")
                ->join($this->client_table . " cd", "cd.USER_ID = u.ID", "left")
                ->join($this->client_address_table . " ca", "ca.CLIENT_ID = cd.USER_ID", "left")
                ->where('u.UUID', $clientUUID)
                ->get()
                ->row_array();
        }

        return $data;
    }

    public function delete_client_by_id($clientID)
    {
        $this->db->trans_start();

        $this->db->delete($this->client_address_table, array('CLIENT_ID' => $clientID));

        $this->db->delete($this->client_table, array('USER_ID' => $clientID));

        $this->db->delete($this->user_table, array('ID' => $clientID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
        } else {
            return true;
        }
    }

    public function delete_user_by_id($userID)
    {
        $user = $this->get_user_by_id($userID);
        $userType = $user['USER_TYPE'] ?? ' guest';
        $this->db->trans_start();
        if ($userType === 'client') {
            $this->db->delete($this->client_address_table, array('CLIENT_ID' => $userID));
            $this->db->delete($this->client_table, array('USER_ID' => $userID));
        }

        $this->db->delete($this->user_table, array('ID' => $userID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
        } else {
            return true;
        }
    }

    // Function to update user password
    function update_password($password, $userid)
    {
        // Generate the hashed password using ARGON2ID
        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
        $this->db->where('ID', $userid);
        return $this->db->update($this->user_table, ['PASSWORD' => $hashedPassword]);
    }

    // Multi factor authentication account enable and disable
    public function get_2fa_details(int $user_id): ?array
    {
        $query = $this->db->get_where($this->user_2fa_table, ['USER_ID' => $user_id, 'IS_ACTIVE' => TRUE]);
        return $query->row_array(); // Return user data or null
    }

    function set_2fa_status($userid, $action)
    {
        return $this->db->where('ID', $userid)->update($this->user_table, ['IS_2FA_ENABLED' => $action === 'enable' ? 1 : 0]);
    }

    public function add_2fa_details($data)
    {
        return $this->db->insert($this->user_2fa_table, $data);
    }

    public function getUserDetail($user_id, $email)
    {
        // Query to fetch user details from the 'users' table
        $this->db->select('ID,FIRST_NAME, LAST_NAME, PHONE_NUMBER, USER_ID, ORG_ID');
        $this->db->where('ID', $user_id);
        $this->db->where('EMAIL', $email);
        $query = $this->db->get('xx_crm_users');


        // Check if user exists and return result
        if ($query->num_rows() > 0) {
            return $query->row();  // Return the first matching record
        } else {
            return null;  // No user found
        }
    }

    public function add_user($data, $userid)
    {
        $user_data = [
            'UUID' => uuid_v4(),
            'ORG_ID' => $data['ORG_ID'] ?? '',
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'EMAIL' => $data['EMAIL'],
            'PHONE_NUMBER' => $data['PHONE_NUMBER'],
            'USER_TYPE' => $data['USER_TYPE'],
            'STATUS' => $data['STATUS'],
            'IS_2FA_ENABLED' => 0,
            'PASSWORD' => password_hash($data['NEW_PASSWORD'], PASSWORD_ARGON2ID)
        ];

        // Insert new lead
        $inserted = $this->db->insert($this->user_table, $user_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->user_table, 'ID', ['UUID' => $user_data['UUID']]);
            // Create product_code in the required format
            $user_gen_id = "U-" . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
            // Update the user_gen_id field for the newly inserted product
            $this->db->where('ID', $inserted_id);
            $this->db->update($this->user_table, ['USER_ID' => $user_gen_id]);
            return $this->get_user_by_id($inserted_id);
        } else
            return false;
    }

    public function update_user_details($userID, $data, $created_by)
    {
        $user_data = [
            'ORG_ID' => $data['ORG_ID'] ?? '',
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'PHONE_NUMBER' => $data['PHONE_NUMBER'],
            'USER_TYPE' => $data['USER_TYPE'],
            'STATUS' => $data['STATUS']
        ];

        // Insert new lead
        $updated = $this->db->where('ID', $userID)->update($this->user_table, $user_data);
        if ($updated) {
            return $this->get_user_by_id($userID);
        } else
            return false;
    }

    public function update_user_profile_details($userID, $data, $created_by)
    {
        $user_data = [
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME' => $data['LAST_NAME'],
            'PHONE_NUMBER' => $data['PHONE_NUMBER']
        ];

        // Insert new lead
        $updated = $this->db->where('ID', $userID)->update($this->user_table, $user_data);
        if ($updated) {
            return $this->get_user_by_id($userID);
        } else
            return false;
    }

    public function update_user_password($userID, $data, $created_by)
    {

        // Insert new lead
        return $this->db->where('ID', $userID)->update($this->user_table, ['PASSWORD' => password_hash($data['RESET_NEW_PASSWORD'], PASSWORD_ARGON2ID)]);
    }

    function get_logged_in_user($user)
    {
        $userid = $user['userid'] ?? 0;
        $usertype = strtolower($user['usertype'] ?? 'guest');
        $user_details = ['info' => [], 'details' => [], 'address' => []];
        $user_details['info'] = $this->db->where('ID', $userid)->get('xx_crm_users')->row_array();
        unset($user_details['info']['PASSWORD']);
        if ($usertype != 'admin') {
            $user_details['details'] = $this->db->where('USER_ID', $userid)->get('xx_crm_client_detail')->row_array();
            $user_details['address'] = $this->db->where('CLIENT_ID', $userid)->get('xx_crm_client_address')->row_array();
        }
        return $user_details;
    }
}
