<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'welcome/not_found';
$route['translate_uri_dashes'] = FALSE;

// USER DEFINED ROUTES 
$route['updates/news-and-announcements'] = "portal/news_and_announcements";

// Public Crone Job Routes
$route['public/cron/purchase-orders/sync-status'] = "public/cron/purchase_order_status_service";
$route['public/cron/notifications/deals-and-leads-followup'] = "public/services/notifications/deals_and_leads_followup";
