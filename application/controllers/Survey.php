<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Survey extends App_Controller
{
    public function index()
    {

        $user_type = $this->userDetails['usertype'] ?? 'Guest';
        $is_admin = $user_type == 'admin' ? true : false;
        $is_client = $user_type == 'client' ? true : false;


        if ($is_admin) {
            $data['view_path'] = 'pages/survey/admin/admin';
        } elseif ($is_client) {
            $data['view_path'] = 'pages/survey/user/home';
        }
        // $data['view_path'] = 'pages/survey/user/home';

        $data['page_title'] = 'Customer Survey - CRM Application';
        $data['page_heading'] = 'Customer Survey';
        $data['navlink'] = 'survey';

        $this->load->view('layout', $data);
    }
    public function new($uuid = null)
    {
        $this->validateUUID();
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/survey/new';
        $data['page_title'] = 'Create New Survey - CRM Application';
        $data['page_heading'] = 'Create New Survey';
        $data['navlink'] = 'survey';
        $data['scripts'] = ['assets/js/pages/Survey/new.js'];
        // $data['SURVEY_ID'] = $survey_id;

        $data['toolbar'] = ['name' => 'survey', 'action' => 'form'];
        $this->load->view('layout', $data);
    }
    public function list()
    {
        $data['view_path'] = 'pages/survey/list';
        $data['page_title'] = 'Survey List - CRM Application';
        $data['page_heading'] = 'Zamil Customer Survey List';
        $data['navlink'] = 'survey';
        $data['scripts'] = ['assets/js/pages/Survey/list.js'];
        $data['toolbar'] = ['name' => 'survey', 'action' => 'list'];

        $this->load->view('layout', $data);
    }
    // public function view()
    // {
    //     $data['view_path'] = 'pages/survey/response';
    //     $data['page_title'] = 'Survey Feedbacks - CRM Application';
    //     $data['page_heading'] = 'Customer Feedback - Survey';
    //     $data['navlink'] = 'survey';
    //     $data['scripts'] = ['assets/js/pages/Survey/view.js'];
    //     $data['toolbar'] = ['name' => 'survey', 'action' => 'view'];

    //     $this->load->view('layout', $data);
    // }
    public function fill($survey_id = null)
    {
        $data['view_path'] = 'pages/survey/user/fill-survey';
        $data['page_title'] = 'Survey Feedbacks - CRM Application';
        $data['page_heading'] = 'Customer Satisfaction Survey';
        $data['navlink'] = 'survey';
        $data['scripts'] = ['assets/js/pages/Survey/fill-survey.js'];
        $data['selected_link'] = 'survey';
        $data['SURVEY_ID'] = $survey_id;
        $data['currentLoggedInuser'] = $this->userDetails;

        $this->load->view('layout', $data);
    }

    public function choose()
    {
        $data['view_path'] = 'pages/survey/user/survey_list';
        $data['page_title'] = 'Survey Feedbacks - CRM Application';
        $data['page_heading'] = 'Open Survey';
        $data['navlink'] = 'survey';
        $data['scripts'] = ['assets/js/pages/Survey/survey-list.js'];
        $data['selected_link'] = 'survey';
        $this->load->view('layout', $data);
    }
    public function feedback()
    {
        $data['view_path'] = 'pages/survey/user/fill-survey-list';
        $data['page_title'] = 'Survey Feedbacks - CRM Application';
        $data['page_heading'] = 'Customer Feedback - Survey';
        $data['navlink'] = 'survey';
        $data['scripts'] = ['assets/js/pages/Survey/fill-survey-list.js'];
        $data['selected_link'] = 'survey';
        $this->load->view('layout', $data);
    }
    public function responses($survey_id = null)
    {
        $data['view_path'] = 'pages/survey/survey-feedback';
        $data['page_title'] = 'Survey Feedbacks - CRM Application';
        $data['page_heading'] = 'Customer Feedback - Survey';
        $data['navlink'] = 'survey';
        $data['scripts'] = ['assets/js/pages/Survey/survey-response.js'];

        $data['selected_link'] = 'survey';
        $data['SURVEY_ID'] = $survey_id;
        $this->load->view('layout', $data);
    }
}
