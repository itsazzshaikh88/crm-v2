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
        $this->load->view('pages/potal/news/list');
    }
}
