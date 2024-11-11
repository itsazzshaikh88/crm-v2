<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function isAuthorized()
    {
        // Get the Authorization header
        $headers = $this->input->request_headers();

        // Check if the Authorization header exists and is not empty
        if (!isset($headers['Authorization']) || empty(trim($headers['Authorization']))) {
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Authorization Header Missing',
                'message' => 'The Authorization header is missing from the request or does not contain a token. Please include the header to access the API.'
            ]);
            return ['status' => false];
        }

        // Extract the token
        $authHeader = trim($headers['Authorization']); // Trim to avoid extra spaces
        // Check for valid token format
        if (strpos($authHeader, ' ') === false) {
            // If there is no space in the header, it's not valid
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Invalid Token Format',
                'message' => 'The Authorization header must be in the format "Bearer <token>".'
            ]);
            return ['status' => false];
        }

        // Assuming the format is "Bearer <token>"
        list($type, $token) = explode(" ", $authHeader, 2);



        // Check if the token type is Bearer
        if (strcasecmp($type, 'Bearer') !== 0) {
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Invalid Token Type',
                'message' => "The token type must be 'Bearer'. Please provide a valid authorization token."
            ]);
            return ['status' => false];
        }

        // Query the database to check for the token and get USER_TYPE from xx_crm_users
        $this->db->select('t.TOKEN, t.EXPIRY, t.USER_ID, u.USER_TYPE');
        $this->db->from('xx_crm_authtokens t');
        $this->db->join('xx_crm_users u', 't.USER_ID = u.ID');
        $this->db->where('t.TOKEN', $token);
        $this->db->where('t.TOKEN_TYPE', 'auth');
        $query = $this->db->get();

        // Check if the token exists and is valid
        if ($query->num_rows() === 0) {
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Token Not Found',
                'message' => "The provided token is invalid or does not exist. Please check the token and try again."
            ]);
            return ['status' => false];
        }

        $row = $query->row();
        // Check if the token is expired
        if (time() >= $row->EXPIRY) {
            $this->sendHTTPResponse(401, [
                'status' => 401,
                'error' => 'Token Expired',
                'message' => "The token has expired. Please obtain a new token to continue accessing the API."
            ]);
            return ['status' => false];
        }

        return ['status' => true, 'userid' => $row->USER_ID, 'role' => $row->USER_TYPE];
    }


    function sendHTTPResponse($statusCode, $response)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($statusCode)
            ->set_output(json_encode($response));
        return;
    }
}
