<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function gen_pass()
    {
        // Retrieve the password from the URL (e.g., ?_upass=yourpassword)
        $password = $this->input->get('_upass', TRUE);

        if ($password) {
            // Hash the password using ARGON2ID
            $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

            // Load the view and pass both the original password and hashed password
            $data = [
                'password' => $password,
                'hashed_password' => $hashed_password
            ];

            // Load the view
            $this->load->view('pages/test/password', $data);
        } else {
            // Redirect or show an error message if no password is provided
            echo 'No password provided in the URL.';
        }
    }
}
