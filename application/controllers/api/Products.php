<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'core/Api_controller.php');
class Products extends Api_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function new($product_id = null)
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

            // Set validation rules
            $this->form_validation->set_rules('DIVISION', 'Division', 'required');
            $this->form_validation->set_rules('CATEGORY_ID', 'Category ID', 'required|integer');
            $this->form_validation->set_rules('STATUS', 'Status', 'required');
            $this->form_validation->set_rules('PRODUCT_NAME', 'Product Name', 'required|min_length[3]');
            $this->form_validation->set_rules('BASE_PRICE', 'Base Price', 'required|numeric');
            $this->form_validation->set_rules('CURRENCY', 'Currency', 'required');
            $this->form_validation->set_rules('TAXABLE', 'Taxable', 'required|in_list[yes,no]');
            $this->form_validation->set_rules('TAX_PERCENTAGE', 'Tax Percentage', 'required|greater_than_equal_to[0]|less_than_equal_to[100]');

            $this->form_validation->set_rules('WIDTH', 'Width', 'required');
            $this->form_validation->set_rules('LENGTH', 'Length', 'required');
            $this->form_validation->set_rules('HEIGHT', 'Height', 'required');
            $this->form_validation->set_rules('VOLUME', 'Volume', 'required');
            $this->form_validation->set_rules('SHAPE', 'Shape', 'required');

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

            // Directory to upload files
            $uploadPath = './uploads/products/';
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];
            $uploadedFiles = null;

            // Check if files are attached
            if (!empty($_FILES['files']['name'][0])) {
                $uploadedFiles = upload_multiple_files($_FILES['files'], $uploadPath, $allowedTypes);
            }


            // Retrieve POST data and sanitize it
            $data = $this->input->post();
            $data['UPLOADED_FILES'] = $uploadedFiles ?? [];

            $data = array_map([$this->security, 'xss_clean'], $data);


            // Save Data to the product table
            $created = $this->Product_model->add_product($product_id, $data, $isAuthorized['userid']);
            if ($created) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'Product created successfully.',
                    'data' => $data,
                    'type' => $product_id != null ? 'update' : 'insert'
                ]);
            } else {
                throw new Exception('Failed to create new product.');
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
        $search = isset($data['search']) ? $data['search'] : [];

        $total_products = $this->Product_model->get_products('total', $limit, $currentPage, $filters, $search);
        $products = $this->Product_model->get_products('list', $limit, $currentPage, $filters, $search);

        $response = [
            'pagination' => [
                'total_records' => $total_products,
                'total_pages' => generatePages($total_products, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'products' => $products,
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

        // Validate input and check if `requestUUID` is provided
        if (!$data || !isset($data['searchKey']) || !isset($data['searchValue'])) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid JSON input or missing product search value or search key'
                ]));
        }

        // Retrieve product details using the provided productUUID
        // Retrieve Request details using the provided quoteUUID
        $searchKey = $data['searchKey'];
        $searchValue = $data['searchValue'];
        $productData = $this->Product_model->get_product_by_searchkey($searchKey, $searchValue);

        // Check if product data exists
        if (empty($productData['product'])) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Product not found'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Product details retrieved successfully',
                'data' => $productData
            ]));
    }

    function delete($productId)
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

        // Check if the user is admin or not
        if (isset($isAuthorized['role']) && $isAuthorized['role'] !== 'admin') {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(403) // 403 Forbidden status code
                ->set_output(json_encode(['error' => 'You do not have permission to perform this action.']));
            return;
        }

        // Validate the product ID
        if (empty($productId) || !is_numeric($productId)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['error' => 'Invalid product ID.']));
            return;
        }

        // Attempt to delete the product
        $result = $this->Product_model->delete_product_by_id($productId);
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => 'Product deleted successfully.']));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete the product.']));
        }
    }

    // Fetch product data with custom filter and options
    function filterList()
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
        $search = isset($data['search']) ? $data['search'] : [];

        $total_products = $this->Product_model->get_products('total', $limit, $currentPage, $filters, $search);
        $products = $this->Product_model->get_products('list', $limit, $currentPage, $filters, $search);

        $response = [
            'pagination' => [
                'total_records' => $total_products,
                'total_pages' => generatePages($total_products, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'products' => $products,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
}
