<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Query extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // Fetch client Profile
    function fetch()
    {
        // Check if the authentication is valid
        $isAuthorized = $this->isAuthorized();
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401) // Set HTTP response status to 400 Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Unauthorized access. You do not have permission to perform this action.']))
                ->_display();
            exit;
        };

        try {
            // Check if the request method is POST
            if (strtolower($this->input->method()) !== 'post') {
                $this->sendHTTPResponse(405, [
                    'status' => 405,
                    'error' => 'Method Not Allowed',
                    'message' => 'The requested HTTP method is not allowed for this endpoint. Please check the API documentation for allowed methods.'
                ]);
                return;
            }

            // Get JSON Input
            $data = $this->input->raw_input_stream;
            $decodedData = json_decode($data, true);
            $inputData = $this->security->xss_clean($decodedData);

            $table_name = $inputData['tableName'] ?? '';
            $columns = $inputData['columns'] ?? '';
            $return_type = $inputData['returnType'] ?? '';
            $where = $inputData['conditions'] ?? [];

            // Validations
            if (!$table_name) {
                $this->sendHTTPResponse(400, [
                    'status' => 400,
                    'error' => 'Bad Request - Table name must be provided.',
                    'message' => 'Bad Request - Table name must be provided.'
                ]);
                return;
            }
            if (!$columns) {
                $this->sendHTTPResponse(400, [
                    'status' => 400,
                    'error' => 'Bad Request - Column Names must be provided',
                    'message' => 'Bad Request - Column Names must be provided'
                ]);
                return;
            }
            if (!$return_type) {
                $this->sendHTTPResponse(400, [
                    'status' => 400,
                    'error' => 'Bad Request - Return type must be provided.',
                    'message' => 'Bad Request - Return type must be provided.'
                ]);
                return;
            }

            // Process - Fetch Records

            $records = $this->Query_model->fetch_records($table_name, $columns, $return_type,  $where);

            $this->sendHTTPResponse(200, [
                'status' => 200,
                'message' => 'Data Retrived Successfully',
                'data' => $records,
                'number_of_rows' => count($records)
            ]);
        } catch (Exception $e) {
            // Catch any unexpected errors and respond with a standardized error
            $this->sendHTTPResponse(500, [
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => 'An unexpected error occurred on the server.',
                'details' => $e->getMessage()
            ]);
        }
    }
}
