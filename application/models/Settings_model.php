<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Settings_model extends App_Model
{
    protected $session_table;
    protected $security_question_table;

    public function __construct()
    {
        parent::__construct();
        $this->session_table = 'xx_crm_user_ses_settings';
        $this->security_question_table = 'xx_crm_user_sec_question';
    }

    // Function to add or update product
    public function add_session_management($data, $userid)
    {
        $session_data = [
            'SESSION_TIMEOUT_MINUTES' => $data['SESSION_TIMEOUT_MINUTES'],
            'REMEMBER_ME_DAYS' => $data['REMEMBER_ME_DAYS'],
            'USER_ID' => $userid,
            'TIMEZONE' => $data['TIMEZONE'],
            'PREFERRED_LANGUAGE' => $data['PREFERRED_LANGUAGE'],
            'UI_THEME' => $data['UI_THEME'],
        ];
        $session_data['UUID'] = uuid_v4();

        // Insert new lead
        $inserted = $this->db->insert($this->session_table, $session_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->session_table, 'ID', ['UUID' => $session_data['UUID']]);
            return $this->get_session_details_by_id($inserted_id);
        } else
            return false;
    }

    function get_session_details_by_id($id)
    {
        return $this->db->where('ID', $id)->get($this->session_table)->row_array();
    }

    function get_session_details_by_userid($userid)
    {
        return $this->db->where('USER_ID', $userid)->get($this->session_table)->row_array();
    }

    // Function to add or update product
    public function update_session_management($recordID, $data, $userid)
    {
        // unset some columns that will not get updated
        $session_data = [
            'SESSION_TIMEOUT_MINUTES' => $data['SESSION_TIMEOUT_MINUTES'],
            'REMEMBER_ME_DAYS' => $data['REMEMBER_ME_DAYS'],
            'USER_ID' => $userid,
            'TIMEZONE' => $data['TIMEZONE'],
            'PREFERRED_LANGUAGE' => $data['PREFERRED_LANGUAGE'],
            'UI_THEME' => $data['UI_THEME'],
        ];

        if ($this->db->where('ID', $recordID)->update($this->session_table, $session_data)) {
            return $this->get_session_details_by_id($recordID);
        } else
            return false;
    }

    // Function to add or update product
    public function add_security_question_details($data, $userid)
    {
        $question_data = [
            'QUESTION' => $data['QUESTION'],
            'ANSWER' => password_hash(trim(strtolower($data['ANSWER'])), PASSWORD_ARGON2ID),
            'USER_ID' => $userid
        ];
        $question_data['UUID'] = uuid_v4();

        // Insert new lead
        $inserted = $this->db->insert($this->security_question_table, $question_data);
        if ($inserted) {
            $inserted_id = $this->get_column_value($this->security_question_table, 'ID', ['UUID' => $question_data['UUID']]);
            return $this->get_security_question_details_by_id($inserted_id);
        } else
            return false;
    }

    function get_security_question_details_by_id($id)
    {
        return $this->db->where('ID', $id)->get($this->security_question_table)->row_array();
    }

    function get_security_question_details_by_userid($userid)
    {
        return $this->db->where('USER_ID', $userid)->get($this->security_question_table)->row_array();
    }

    // Function to add or update product
    public function update_security_question_details($recordID, $data, $userid)
    {
        // unset some columns that will not get updated
        $question_data = [
            'QUESTION' => $data['QUESTION'],
            'ANSWER' => password_hash(trim(strtolower($data['ANSWER'])), PASSWORD_ARGON2ID),
        ];

        if ($this->db->where('ID', $recordID)->update($this->security_question_table, $question_data)) {
            return $this->get_security_question_details_by_id($recordID);
        } else
            return false;
    }
}
