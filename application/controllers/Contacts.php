<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contacts extends App_Controller
{
    public function index()
    {
        $data['view_path'] = 'pages/contacts/list';
        $data['page_title'] = 'Contacts - Fixed Assets Application';
        $data['page_heading'] = 'Contacts';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'contacts'];
        $data['scripts'] = ['assets/js/pages/contacts/list.js'];
        $data['css_files'] = ['assets/css/pages/contacts/contact.css'];
        $data['toolbar'] = ['name' => 'new-contact', 'action' => 'list'];
        $this->load->view('layout', $data);
    }

    public function new($uuid = null)
    {
        $this->validateUUID();
        $data['uuid'] = $uuid;
        $data['view_path'] = 'pages/contacts/new';
        $data['page_title'] = 'Create or Add New Contact - CRM Application';
        $data['page_heading'] = 'Create or Add New Contact';
        $data['navlink'] = ['main-link' => ADMIN_ACTIVE_LINK, 'sub-link' => 'contacts'];
        $data['css_files'] = ['assets/css/pages/requests/new.css'];
        $data['scripts'] = [
            'assets/js/pages/contacts/new-contact.js',
            'assets/js/pages/activities/contact-activities.js'
        ];
        $data['toolbar'] = ['name' => 'new-contact', 'action' => 'form'];
        $this->load->view('layout', $data);
    }
}
