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
            return 'No password provided in the URL.';
        }
    }

    public function get_ip_address()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return "Details HTTP CLIENT IP - " . $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return "Details FORWARD FOR - " . $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return "Details REMOTE ADDR - " . $_SERVER['REMOTE_ADDR'];
        }
    }
    public function get_local_ip()
    {
        echo gethostbyname(gethostname());
    }

    public function get_real_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    public function get_ip_addresses()
    {
        print_r([
            'public_ip' => $this->get_real_ip(),
            'local_ip'  => gethostbyname(gethostname())
        ]);
    }

    public function get_fixed_ip()
    {
        $ip = $this->get_real_ip();
        echo ($ip === '::1' || $ip === '127.0.0.1') ? gethostbyname(gethostname()) : $ip;
    }

    public function get_browser_details()
    {
        $this->load->library('user_agent');

        beautify_array([
            'browser'         => $this->agent->browser(),
            'browser_version' => $this->agent->version(),
            'user_agent'      => $_SERVER['HTTP_USER_AGENT']
        ]);
    }

    public function get_real_browser_details()
    {
        $browser_info = get_browser(null, true);

        beautify_array([
            'browser'         => $browser_info['browser'] ?? 'Unknown',
            'browser_version' => $browser_info['version'] ?? 'Unknown',
            'user_agent'      => $_SERVER['HTTP_USER_AGENT']
        ]);
    }

    public function get_os_details()
    {
        $this->load->library('user_agent');
        beautify_array([
            'os'        => $this->agent->platform(), // Windows, Linux, Mac, etc.
            'os_version' => $this->get_os_version($_SERVER['HTTP_USER_AGENT']) // Extract version from user-agent
        ]);
    }

    private function get_os_version($user_agent)
    {
        if (!$user_agent) {
            return 'Unknown';
        }

        $windows_versions = [
            '/Windows NT 11.0/'             => 'Windows 11',
            '/Windows NT 10.0;.*\bWin64\b/' => 'Windows 11 (64-bit)',
            '/Windows NT 10.0/'             => 'Windows 10',
            '/Windows NT 6.3/'              => 'Windows 8.1',
            '/Windows NT 6.2/'              => 'Windows 8',
            '/Windows NT 6.1/'              => 'Windows 7',
            '/Windows NT 6.0/'              => 'Windows Vista',
            '/Windows NT 5.1/'              => 'Windows XP'
        ];

        foreach ($windows_versions as $pattern => $version) {
            if (preg_match($pattern, $user_agent)) {
                // If Windows 11 is detected, check if "Single Language" is mentioned
                if ($version === 'Windows 11' && stripos($user_agent, 'Single Language') !== false) {
                    return 'Windows 11 Home Single Language';
                }
                return $version;
            }
        }

        if (preg_match('/Mac OS X (\d+[_\.\d]+)/', $user_agent, $matches)) {
            return 'Mac OS X ' . str_replace('_', '.', $matches[1]);
        }

        if (preg_match('/Ubuntu|Linux/', $user_agent)) {
            return 'Linux';
        }

        return 'Unknown OS / Version';
    }

    public function get_location_details($ip = '')
    {
        $ip = $this->get_local_ip();
        $api_url = "http://ip-api.com/json/" . $ip;
        $response = @file_get_contents($api_url);

        if ($response) {
            $data = json_decode($response, true);
            return [
                'country'  => $data['country'] ?? 'Unknown',
                'region'   => $data['regionName'] ?? 'Unknown',
                'city'     => $data['city'] ?? 'Unknown',
                'latitude' => $data['lat'] ?? null,
                'longitude' => $data['lon'] ?? null
            ];
        }

        print_r(['country' => 'Unknown', 'region' => 'Unknown', 'city' => 'Unknown', 'latitude' => null, 'longitude' => null]);
    }

    function test_url()
    {
        redirect(base_url('test/get_referer_url'));
    }

    public function get_referer_url()
    {
        echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct Access';
    }

    function test()
    {
        // echo isset($_SERVER['REQUEST_URI']) ? base_url($_SERVER['REQUEST_URI']) : '';
        echo http_response_code();
    }

    
}
