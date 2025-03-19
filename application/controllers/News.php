<?php
defined('BASEPATH') or exit('No direct script access allowed');

class News extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/news/list';
        $data['page_title'] = 'Product Listing - CRM Application';
        $data['page_heading'] = 'News and Announcements';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'news'];
        $data['scripts'] = ['assets/js/pages/news/list.js', 'assets/js/pages/news/new.js'];
        $data['toolbar'] = ['name' => 'news', 'action' => 'list'];
        $this->load->view('layout', $data);
    }

    public function view($news_id = null)
    {
        $data['news_id'] = $news_id;
        $data['view_path'] = 'pages/news/view';
        $data['page_title'] = 'News Details - CRM Application';
        $data['page_heading'] = 'News Details';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'news'];
        $data['css_files'] = [];
        $data['scripts'] = ['assets/js/pages/news/view.js'];
        $data['toolbar'] = ['name' => 'news', 'action' => 'view'];
        $this->load->view('layout', $data);
    }
}
