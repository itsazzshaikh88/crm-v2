<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Sales extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function add_forecast()
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

            $fields = ['CUSTOMER_NUMBER', 'CUSTOMER_NAME', 'ITEM_C', 'ITEM_DESC', 'PRODUCT_WEIGHT', 'UOM', 'SALES_MAN', 'REGION', 'QTY_JAN', 'UNIT_JAN', 'VALUE_JAN', 'QTY_FEB', 'UNIT_FEB', 'VALUE_FEB', 'QTY_MAR', 'UNIT_MAR', 'VALUE_MAR', 'QTY_APR', 'UNIT_APR', 'VALUE_APR', 'QTY_MAY', 'UNIT_MAY', 'VALUE_MAY', 'QTY_JUN', 'UNIT_JUN', 'VALUE_JUN', 'QTY_JUL', 'UNIT_JUL', 'VALUE_JUL', 'QTY_AUG', 'UNIT_AUG', 'VALUE_AUG', 'QTY_SEP', 'UNIT_SEP', 'VALUE_SEP', 'QTY_OCT', 'UNIT_OCT', 'VALUE_OCT', 'QTY_NOV', 'UNIT_NOV', 'VALUE_NOV', 'QTY_DEC', 'UNIT_DEC', 'VALUE_DEC'];

            foreach ($fields as $field) {
                $label = ucwords(strtolower(str_replace("_", " ", $field)));
                $this->form_validation->set_rules($field, $label, 'required');
            }


            // Run validation
            if ($this->form_validation->run() == FALSE) {
                // Validation failed, prepare response with errors
                $errors = $this->form_validation->error_array();

                $this->sendHTTPResponse(422, [
                    'status' => 422,
                    'error' => 'Unprocessable Entity',
                    'message' => 'The submitted data failed validation.',
                    'validation_errors' => $errors
                ]);
                return;
            }

            // Retrieve POST data and sanitize it
            $data = $this->input->post();
            $data = array_map([$this->security, 'xss_clean'], $data);

            // Save Data to the product table
            $newlyCreatedForecast = $this->Sales_model->add_forecast($data, $isAuthorized['userid']);

            if ($newlyCreatedForecast) {

                $action_type = 'CREATED';
                // ***** ===== Add User Activity - STARTS ===== *****
                $userForActivity = [
                    'userid' => $isAuthorized['userid'] ?? '',
                    'role' => $isAuthorized['role'] ?? '',
                    'name' => $isAuthorized['name'] ?? ''
                ];
                $system = [
                    'IP_ADDRESS' => $this->get_local_ip(),
                    'USER_AGENT' => $this->get_user_agent(),
                    'BROWSER' => $this->get_browser_name(),
                ];

                $action = [
                    'ACTIVITY_TYPE' => "SALES FORECAST {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} new sales forecast from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
                ];

                $request = [
                    'REQUEST_URI' => $this->get_request_uri(),
                    // 'REQUEST_DATA' => $data,
                    'REQUEST_METHOD' => strtoupper($this->input->method()),
                    'RESPONSE_STATUS' => 'success'
                ];

                $this->App_model->add_activity_logs($action, $userForActivity, $system, $request);
                // ***** ===== Add User Activity - ENDS ===== *****

                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Sales Forecast Created Successfully',
                    'type' => 'insert',
                    'data' => $newlyCreatedForecast,
                ]);
            } else {
                throw new Exception('Failed to create new Forecast.');
            }
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

    function update_forecast($forecastID)
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

            // Validate the product ID
            if (empty($forecastID) || !is_numeric($forecastID)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400) // 400 Bad Request status code
                    ->set_output(json_encode(['error' => 'Invalid Forecast ID.']));
                return;
            }

            $fields = ['CUSTOMER_NUMBER', 'CUSTOMER_NAME', 'ITEM_C', 'ITEM_DESC', 'PRODUCT_WEIGHT', 'UOM', 'SALES_MAN', 'REGION', 'QTY_JAN', 'UNIT_JAN', 'VALUE_JAN', 'QTY_FEB', 'UNIT_FEB', 'VALUE_FEB', 'QTY_MAR', 'UNIT_MAR', 'VALUE_MAR', 'QTY_APR', 'UNIT_APR', 'VALUE_APR', 'QTY_MAY', 'UNIT_MAY', 'VALUE_MAY', 'QTY_JUN', 'UNIT_JUN', 'VALUE_JUN', 'QTY_JUL', 'UNIT_JUL', 'VALUE_JUL', 'QTY_AUG', 'UNIT_AUG', 'VALUE_AUG', 'QTY_SEP', 'UNIT_SEP', 'VALUE_SEP', 'QTY_OCT', 'UNIT_OCT', 'VALUE_OCT', 'QTY_NOV', 'UNIT_NOV', 'VALUE_NOV', 'QTY_DEC', 'UNIT_DEC', 'VALUE_DEC'];

            foreach ($fields as $field) {
                $label = ucwords(strtolower(str_replace("_", " ", $field)));
                $this->form_validation->set_rules($field, $label, 'required');
            }


            // Run validation
            if ($this->form_validation->run() == FALSE) {
                // Validation failed, prepare response with errors
                $errors = $this->form_validation->error_array();

                $this->sendHTTPResponse(422, [
                    'status' => 422,
                    'error' => 'Unprocessable Entity',
                    'message' => 'The submitted data failed validation.',
                    'validation_errors' => $errors
                ]);
                return;
            }

            // Retrieve POST data and sanitize it
            $data = $this->input->post();
            $data = array_map([$this->security, 'xss_clean'], $data);

            // Check if Forecast details are present in table with existing ID
            $forecast = $this->Sales_model->get_forecast_by_id($forecastID ?? 0);
            if (empty($forecast)) {
                $this->sendHTTPResponse(409, [
                    'status' => 'error',
                    'code' => 404,
                    'error' => 'Not Found',
                    'message' => 'A Forecast with provided Id does not found to update.'
                ]);
                return;
            }

            // Save Data to the Forecast table
            $updatedForecast = $this->Sales_model->update_forecast($forecastID, $data, $isAuthorized['userid']);

            if ($updatedForecast) {

                $action_type = 'UPDATED';
                // ***** ===== Add User Activity - STARTS ===== *****
                $userForActivity = [
                    'userid' => $isAuthorized['userid'] ?? '',
                    'role' => $isAuthorized['role'] ?? '',
                    'name' => $isAuthorized['name'] ?? ''
                ];
                $system = [
                    'IP_ADDRESS' => $this->get_local_ip(),
                    'USER_AGENT' => $this->get_user_agent(),
                    'BROWSER' => $this->get_browser_name(),
                ];

                $action = [
                    'ACTIVITY_TYPE' => "SALES FORECAST {$action_type}",
                    'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} sales forecast from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
                ];

                $request = [
                    'REQUEST_URI' => $this->get_request_uri(),
                    // 'REQUEST_DATA' => $data,
                    'REQUEST_METHOD' => strtoupper($this->input->method()),
                    'RESPONSE_STATUS' => 'success'
                ];

                $this->App_model->add_activity_logs($action, $userForActivity, $system, $request);
                // ***** ===== Add User Activity - ENDS ===== *****

                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Forecast Updated Successfully',
                    'type' => 'update',
                    'data' => $updatedForecast,
                ]);
            } else {
                throw new Exception('Error saving forecast details');
            }
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

    function forecast_list()
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

        // Get the raw input data from the request
        $input = $this->input->raw_input_stream;

        // Decode the JSON data
        $data = json_decode($input, true); // Decode as associative array

        // Check if data is received
        if (!$data) {
            // Handle the error if no data is received
            $this->output
                ->set_status_header(400) // Set HTTP response status to 400 Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Invalid JSON input']))
                ->_display();
            exit;
        }

        // Access the parameters
        $limit = isset($data['limit']) ? $data['limit'] : null;
        $currentPage = isset($data['currentPage']) ? $data['currentPage'] : null;
        $filters = isset($data['filters']) ? $data['filters'] : [];

        $total_forecast = $this->Sales_model->get_forecast('total', $limit, $currentPage, $filters);
        $forecasts = $this->Sales_model->get_forecast('list', $limit, $currentPage, $filters);

        $response = [
            'pagination' => [
                'total_records' => $total_forecast,
                'total_pages' => generatePages($total_forecast, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'forecasts' => $forecasts,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function detail()
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

        // Get the raw input data from the request
        $input = $this->input->raw_input_stream;

        // Decode the JSON data as an associative array
        $data = json_decode($input, true);

        // Validate input and check if `productUUID` is provided
        if (!$data || !isset($data['forecastUUID'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid JSON input or missing forecast ID'
                ]));
        }

        // Retrieve product details using the provided forecastUUID
        $forecastUUID = $data['forecastUUID'];
        $forecast = $this->Sales_model->get_forecast_by_uuid($forecastUUID);

        // Check if product data exists
        if (empty($forecast)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Forecast details not found'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Forecast details retrieved successfully',
                'data' => $forecast
            ]));
    }

    function delete_forecast($forecastID)
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

        // Validate the Forecast ID
        if (empty($forecastID) || !is_numeric($forecastID)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid Forecast ID.']));
            return;
        }

        // Attempt to delete the Forecast
        $result = $this->Sales_model->delete_forecast_by_id($forecastID);
        if ($result) {

            $action_type = 'DELETED';
            // ***** ===== Add User Activity - STARTS ===== *****
            $userForActivity = [
                'userid' => $isAuthorized['userid'] ?? '',
                'role' => $isAuthorized['role'] ?? '',
                'name' => $isAuthorized['name'] ?? ''
            ];
            $system = [
                'IP_ADDRESS' => $this->get_local_ip(),
                'USER_AGENT' => $this->get_user_agent(),
                'BROWSER' => $this->get_browser_name(),
            ];

            $action = [
                'ACTIVITY_TYPE' => "SALES FORECAST {$action_type}",
                'DESCRIPTION' => "User {$userForActivity['name']} (Role: {$userForActivity['role']}) {$action_type} sales forecast from IP {$system['IP_ADDRESS']} using {$system['BROWSER']} on " . date('D, d M Y - H:i:s')
            ];

            $request = [
                'REQUEST_URI' => $this->get_request_uri(),
                // 'REQUEST_DATA' => $data,
                'REQUEST_METHOD' => strtoupper($this->input->method()),
                'RESPONSE_STATUS' => 'success'
            ];

            $this->App_model->add_activity_logs($action, $userForActivity, $system, $request);
            // ***** ===== Add User Activity - ENDS ===== *****

            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => 'Forecast deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the Forecast.']));
        }
    }

    public function forecast_versions()
    {
        // Check if the authentication is valid
        $isAuthorized = $this->isAuthorized();
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401) // Set HTTP response status to 401 Unauthorized
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Unauthorized access. You do not have permission to perform this action.']))
                ->_display();
            exit;
        }

        // Retrieve GET parameters (division and year)
        $division = $this->input->get('division');
        $year = $this->input->get('year');

        // Check if the required parameters are provided
        if (!$division || !$year) {
            $this->output
                ->set_status_header(400) // Set HTTP response status to 400 Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Missing required parameters: division or year']))
                ->_display();
            exit;
        }

        // Fetch forecast versions based on division and year
        $versions = $this->Sales_model->get_forecast_versions($division, $year);

        // Prepare response
        $response = [
            'versions' => $versions,
        ];

        // Return the response as JSON
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function export_forecast()
    {
        $orgId = $this->input->get('ORG_ID', TRUE);
        $year = $this->input->get('YER', TRUE);
        $version = $this->input->get('VER', TRUE);
        $format = $this->input->get('format', TRUE);

        if (!$orgId || !$year || !$version || !in_array($format, ['csv', 'excel'])) {
            show_error('Invalid input or format', 400);
            return;
        }

        $this->load->model('Sales_model');
        $forecastData = $this->Sales_model->get_filtered_forecast($orgId, $year, $version);

        if (empty($forecastData)) {
            show_error('No data found for the given filters.', 404);
            return;
        }

        $filename = "sales_forecast_{$orgId}_{$year}_v{$version}." . ($format === 'csv' ? 'csv' : 'xlsx');

        if ($format === 'csv') {
            $this->_export_csv($forecastData, $filename);
        } else {
            $this->_export_excel($forecastData, $filename);
        }
    }

    private function _export_csv($data, $filename)
    {
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $output = fopen('php://output', 'w');

        // Header row
        fputcsv($output, array_keys((array)$data[0]));

        // Data rows
        foreach ($data as $row) {
            fputcsv($output, (array)$row);
        }

        fclose($output);
        exit;
    }

    private function _export_excel($data, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = array_keys((array)$data[0]);
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Data Rows
        $rowNum = 2;
        foreach ($data as $row) {
            $col = 'A';
            foreach ((array)$row as $value) {
                $sheet->setCellValue($col . $rowNum, $value);
                $col++;
            }
            $rowNum++;
        }

        // Output to browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
