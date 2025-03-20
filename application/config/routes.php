<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'welcome/not_found';
$route['translate_uri_dashes'] = FALSE;

// USER DEFINED ROUTES 
$route['updates/news-and-announcements'] = "portal/news_and_announcements";
