<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Deliveries extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Delivery_model');
    }



    function list()
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

        // $total_deliveries = $this->Delivery_model->get_deliveries('total', $limit, $currentPage, $filters);
        // $deliveries = $this->Delivery_model->get_deliveries('list', $limit, $currentPage, $filters);


        $total_deliveries = '';
        $deliveries = [];

        $response = [
            'pagination' => [
                'total_records' => $total_deliveries,
                'total_pages' => generatePages($total_deliveries, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'deliveries' => $deliveries,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
}