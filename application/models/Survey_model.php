<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Survey_model extends App_Model
{
    protected $survey_table;
    protected $survey_header_table;
    protected $survey_line_table;

    public function __construct()
    {
        parent::__construct();
        $this->survey_table = 'xx_crm_survey'; // Initialize token table
        $this->survey_header_table = 'xx_crm_survey_header'; //survey
        $this->survey_line_table = 'xx_crm_survey_lines'; //survey
    }
    // Function to add or update product
    public function add_survey($survey_id, $data, $userid)
    {
        $survey_data = [
            'UUID' => $data['UUID'],
            'SURVEY_NAME' => $data['SURVEY_NAME'],
            'SURVEY_DESC' => $data['SURVEY_DESC'],
            'START_DATE' => $data['START_DATE'],
            'END_DATE' => $data['END_DATE'],
            'CONDUCTED_BY' => $data['CONDUCTED_BY'],
            'STATUS' => $data['STATUS'],
            'SURVEY_YEAR' => date('Y'),
            'VERSION' => '1',
            'CREATED_AT' => date('Y-m-d'),
        ];

        if (!in_array($survey_id, [' ', '', 0, null])) {
            $existedSurveyDetails = $this->db->where('SURVEY_ID', $survey_id)->get($this->survey_table)->row_array();
            // Update existing User
            $this->db->where('SURVEY_ID', $survey_id);
            $this->db->update($this->survey_table, $survey_data);

            return true;
        } else {
            // Insert new lead
            $inserted = $this->db->insert($this->survey_table, $survey_data);
            if ($inserted) {
                $inserted_id = $this->get_column_value($this->survey_table, 'SURVEY_ID', ['UUID' => $survey_data['UUID']]);
                // Create product_code in the required format
                $survey_number = "S" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
                // Update the lead_number field for the newly inserted product
                $this->db->where('SURVEY_ID', $inserted_id);
                $this->db->update($this->survey_table, ['SURVEY_NUMBER' => $survey_number]);
                return true;
            } else
                return false;
        }
    }

    public function fill_new_survey($data, $userid)
    {

        // Prepare the header details
        $header = array(
            'SURVEY_ID' => $data['SURVEY_ID'],
            'UUID' => uuid_v4(),
            'FILLED_DATE' => date('Y-m-d'),
            'CLIENT_ID' => $data['CLIENT_ID'],
            'RECOMMENDATION' => $data['RECOMMENDATION'],
            'COMMENTS' => $data['COMMENTS'],
            'SURVEY_YEAR' => date('Y'),
            'CREATED_BY' => $userid,
            'CREATED_AT' => date('Y-m-d')
        );
        // Insert new survey header
        $insert_status = $this->db->insert($this->survey_header_table, $header);

        if ($insert_status) {
            $inserted_id = $this->db->insert_id();
            $update = ['SURVEY_NUMBER' => "SN" . date('mdy') . sprintf("%06d", $inserted_id)];

            // Update the survey number for the new record
            $this->db->where('SURVEY_ID', $inserted_id);
            $this->db->update($this->survey_table, $update);

            // Prepare and save line details
            $choices = [];
            for ($i = 1; $i <= 22; $i++) {
                $option_selected = $this->input->post("survey-line-$i");
                $choices[] = ['seq' => $i, 'rating' => $option_selected];
            }
            $line = array(
                'SURVEY_HEADER_ID' => $inserted_id,
                'OPTIONS' => json_encode($choices)
            );

            $this->db->insert('xx_crm_survey_lines', $line);

            return true;
        }
    }


    function fetchSurvey()
    {


        $this->db->select("s.SURVEY_ID,s.UUID, s.SURVEY_NUMBER, s.SURVEY_NAME, s.SURVEY_DESC, s.START_DATE, s.END_DATE, s.CONDUCTED_BY, s.STATUS, s.CREATED_AT");
        $this->db->from("XX_CRM_SURVEY s");
        $this->db->order_by("s.SURVEY_ID", "DESC");


        // Execute query
        $query = $this->db->get();
        return $query->result_array();
    }



    // Function to add or update product
    public function update_survey($surveyID, $data, $userid)
    {
        $survey_data = [
            'UUID' => $data['UUID'],
            'SURVEY_NAME' => $data['SURVEY_NAME'],
            'SURVEY_DESC' => $data['SURVEY_DESC'],
            'START_DATE' => $data['START_DATE'],
            'END_DATE' => $data['END_DATE'],
            'STATUS' => $data['STATUS'],
            'SURVEY_YEAR' => $data['SURVEY_YEAR'],
            'VERSION' => $data['VERSION'],
            'UPDATED_AT' => date('Y-m-d'),
        ];

        // Insert new lead
        return $this->db->where('SURVEY_ID', $surveyID)->update($this->survey_table, $survey_data);
    }

    function get_survey($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("s.SURVEY_ID,s.UUID, s.SURVEY_NUMBER, s.SURVEY_NAME, s.SURVEY_DESC, s.START_DATE, s.END_DATE, s.CONDUCTED_BY, s.STATUS, s.CREATED_AT");
        $this->db->from("XX_CRM_SURVEY s");
        $this->db->order_by("s.SURVEY_ID", "DESC");

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


    function getfill_survey($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("sh.HEADER_ID, sh.UUID, s.SURVEY_NUMBER, sh.FILLED_DATE, sh.SURVEY_YEAR, s.SURVEY_NAME, c.COMPANY_NAME, CONCAT(u.FIRST_NAME, ' ', u.LAST_NAME) as SURVEY_USERNAME");
        $this->db->from("xx_crm_survey_header sh");
        $this->db->join("xx_crm_survey s", "s.SURVEY_ID = sh.SURVEY_ID", "left");
        $this->db->join("xx_crm_client_detail c", "c.ID = sh.CLIENT_ID", "left");
        $this->db->join("xx_crm_users u", "u.ID = sh.CLIENT_ID", "left");

        $this->db->order_by("sh.HEADER_ID",);

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


    function fetchSurveyFeedbackBYUUID($feedback_id)
    {
        $data['survey'] = [];
        $data['header'] = $this->db->where('UUID', $feedback_id)->get('xx_crm_survey_header')->row_array();
        if (isset($data['header']['HEADER_ID']))
            $data['survey'] = $this->db->where('SURVEY_ID', $data['header']['SURVEY_ID'])->get('xx_crm_survey')->row_array();
        $data['line'] = $this->db->where('SURVEY_HEADER_ID', $data['header']['HEADER_ID'])->get('xx_crm_survey_lines')->row_array();
        return $data;
    }
    public function delete_survey_by_id($surveyID)
    {
        $this->db->trans_start();

        $this->db->delete($this->survey_table, array('SURVEY_ID' => $surveyID));


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
        } else {
            return true;
        }
    }

    public function delete_survey_fill_by_id($surveyID)
    {
        $this->db->trans_start();

        $this->db->delete($this->survey_table, array('SURVEY_ID' => $surveyID));
        $this->db->delete($this->survey_header_table, array('HEADER_ID' => $surveyID));
        $this->db->delete($this->survey_line_table, array('SURVEY_HEADER_ID' => $surveyID));


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
        } else {
            return true;
        }
    }

    /**
     * Get user by email
     *
     * @param string $email User email
     * @return array|null User data or null if not found
     */
    public function get_survey_by_email(string $email): ?array
    {
        $query = $this->db->get_where($this->lead_table, ['EMAIL' => $email]);
        return $query->row_array(); // Return user data or null
    }

    public function get_survey_by_uuid($surveyUUID)
    {
        $data = [];
        if ($surveyUUID) {
            $data = $this->db->select("s.SURVEY_ID, s.UUID, s.SURVEY_NUMBER,s.SURVEY_NAME ,s.SURVEY_DESC, s.START_DATE, s.END_DATE, s.CONDUCTED_BY, s.STATUS,s.SURVEY_YEAR,s.VERSION")
                ->from($this->survey_table . " s")
                ->where('s.UUID', $surveyUUID)
                ->get()
                ->row_array();
        }

        return $data;
    }

    public function get_survey_by_id($SurveyID)
    {
        $data = [];
        if ($SurveyID) {
            $data = $this->db
                ->where('SURVEY_ID', $SurveyID)
                ->get($this->survey_table)
                ->row_array();
        }

        return $data;
    }
}
