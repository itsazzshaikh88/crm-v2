<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Deals extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/deals/list';
        $data['page_title'] = 'Deals - Zamil CRM';
        $data['page_heading'] = 'Deals';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'deals'];
        $data['scripts'] = ['assets/js/pages/salespersons/modals/list.js', 'assets/js/pages/deals/list.js', 'assets/js/pages/deals/new-deal.js', 'assets/js/pages/activities/deal-activities.js', 'assets/js/pages/activities/email-activitiy.js'];
        $data['toolbar'] = ['name' => 'deals', 'action' => 'list'];
        $this->load->view('layout', $data);
    }
}
