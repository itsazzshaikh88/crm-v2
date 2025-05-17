<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Portal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function news_and_announcements()
    {
        $data['account'] = $this->input->get('account') ?? 'admin-central';
        $data['view_path'] = 'pages/potal/news/list';
        $data['page_title'] = 'News and Announcements - Zamil CRM';
        $data['navlink'] = 'news';
        $data['scripts'] = ['assets/js/pages/potal/news/list.js'];
        $this->load->view('front/layout', $data);
    }
}
