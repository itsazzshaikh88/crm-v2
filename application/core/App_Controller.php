<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App_Controller extends CI_Controller
{
    protected $secret_key;
    protected $userDetails;
    public function __construct()
    {
        parent::__construct();
        $this->secret_key = APP_SECRET_KEY;
        $this->isAuthenticated();
        $this->userDetails = $this->getUserDetails();
        // Share user details with all views
        $this->load->vars(['loggedInUser' => $this->userDetails]);
    }

    /**
     * Retrieves user details from the auth_token cookie.
     *
     * @return array|null Returns an associative array of user details if valid, or null if invalid or not set.
     */
    protected function getUserDetails(): ?array
    {
        $cookieName = 'auth_token';
        // Check if the cookie is set
        if (!isset($_COOKIE[$cookieName])) {
            return null; // Return null if the cookie is not set
        }

        // Split the token into payload and hash
        [$encodedPayload, $hash] = explode('.', $_COOKIE[$cookieName]);

        // Decode the payload
        $payloadJson = base64_decode($encodedPayload);
        if ($payloadJson === false) {
            return null; // Return null if decoding fails
        }

        $payload = json_decode($payloadJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null; // Return null if JSON decoding fails
        }

        // Verify the hash
        $expectedHash = hash_hmac('sha256', $payloadJson, $this->secret_key);
        // Check if the hash matches
        if (!hash_equals($expectedHash, $hash)) {
            return null; // Return null if the hash does not match
        }



        // Token is valid, return user details
        return $payload;
    }

    protected function isAuthenticated()
    {
        // Retrieve the auth token from the cookie
        $auth_token = $this->input->cookie('auth_token');
        if (!$auth_token) {
            // If no token is found, redirect to login
            redirect(base_url() . 'login');
        } else {

            $tokenData = $this->Auth_model->validate_token($auth_token);
            if (empty($tokenData) || time() > $tokenData['EXPIRY']) {
                // If token is not valid then delete cookie and redirect
                delete_cookie('auth_token');
                redirect(base_url() . 'login');
            }

            // Split the token into payload and hash
            list($encoded_payload, $token_hash) = explode('.', $auth_token);
            // Decode the payload
            $payload = json_decode(base64_decode($encoded_payload), true);
            // Recreate the hash from the payload and secret key
            $recreated_hash = hash_hmac('sha256', base64_decode($encoded_payload), $this->secret_key);
            // Validate the hash
            if ($recreated_hash === $token_hash) {
                // Hash is valid, so extract the user data
                $user_id = $payload['userid'];
                if (!$this->User_model->validate_user($user_id)) {
                    // If user is not valid, delete cookie and redirect
                    delete_cookie('auth_token');
                    redirect(base_url() . 'login');
                }
            } else {
                // If the hash doesn't match, delete cookie and redirect
                delete_cookie('auth_token');
                redirect(base_url() . 'login');
            }
        }
    }



    // append UUID to the url's 
    // Function to check and append UUID to URL if missing
    protected function validateUUID()
    {
        // Get current URL without index.php
        $currentUrl = $this->uri->uri_string();
        $uriSegments = $this->uri->segment_array();

        // Check if the last segment is a valid UUID (UUID v4 format)
        $lastSegment = end($uriSegments);
        if (!preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $lastSegment)) {
            // UUID not present in the URL, generate a new UUID
            $uuid = uuid_v4();

            // Append the UUID to the URL
            $newUrl = $currentUrl . '/' . $uuid;
            // Redirect to the new URL with the UUID
            redirect(base_url() . $newUrl);
        }
        // If the UUID is already present, do nothing
    }
}
