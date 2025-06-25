<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifications extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Notification_model');
        $this->load->library('phpmailer_lib');
        set_time_limit(0);
    }

    private function logServiceActivity($message, $file)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        file_put_contents($file, $logMessage, FILE_APPEND);
    }


    public function deals_and_leads_followup()
    {
        $lockFile = FCPATH . 'secured/locks/deals_and_leads_followup.lock';
        $logFile = FCPATH . 'application/logs/deals_and_leads_followup_' . date('Y-m-d') . '.log';

        $fp = fopen($lockFile, 'c+');
        if (!$fp) {
            $this->logServiceActivity("ERROR: Cannot open lock file.", $logFile);
            header('HTTP/1.1 500 Internal Server Error');
            echo "Cannot open lock file.";
            exit;
        }

        if (!flock($fp, LOCK_EX | LOCK_NB)) {
            $this->logServiceActivity("INFO: Service already running. Skipping execution.", $logFile);
            header('HTTP/1.1 429 Too Many Requests');
            echo "Service is already running. Please try later.";
            fclose($fp);
            exit;
        }

        try {
            $interval = LEADS_NOTIFICATION_INTERVAL;
            $leadsAndDealsNotifications = $this->Lead_model->get_leads_and_deals_followup_req();

            foreach ($leadsAndDealsNotifications as $notification) {
                // Check if the notification is already sent and it has time to resend
                $shouldNotify = $this->Notification_model->shouldSendReminder($notification['source_type'] ?? 'module', $notification['record_id'] ?? 0, $interval);

                if (!$shouldNotify) {
                    $this->logServiceActivity("Notification for {$notification['source_type']} - {$notification['record_id']} is skipped", $logFile);
                    continue;
                }
                if (isset($notification['assigned_user_email'])) {
                    $emailViewConfig = [
                        'content_view' => 'leads-followup-reminders',
                        'heading' => "Follow Up Required: " . ucwords($notification['source_type'] ?? '') . " - " . $notification['customer_name'] ?? '',
                    ];
                    $emailContent = [
                        'user' => $notification['assigned_user_name'] ?? '',
                        'module' => "$notification[source_type]",
                        'company' => $notification['COMPANY_NAME'],
                        'email' => $notification['EMAIL'],
                        'phone' => $notification['PHONE'],
                        'assigned_on' => $notification['CREATED_AT'],
                        'lead_name' => $notification['customer_name'],
                        'follow_up_due' => $notification['FOLLOW_UP_DATE']
                    ];
                    $mailContent = $this->load->view('email-templates/layout', ['emailViewConfig' => $emailViewConfig, 'emailContent' => $emailContent], true);

                    $sendToEmail = $notification['assigned_user_email'] ?? 'IT@zamilplastic.com';

                    $this->_send_email([
                        'to'        => $sendToEmail,
                        'subject'   => "Follow Up Required: " . ucwords($notification['source_type'] ?? '') . " - " . $notification['customer_name'] ?? '',
                        'message'   => $mailContent,
                        'from'      => 'workflowmailer@zamilplastic.com',   // Optional
                        'from_name' => 'WorkFlow Mailer',                   // Optional
                    ]);

                    // Save details into the reminder log table
                    $reminderLogDetails = [
                        'MODULE_TYPE' => $notification['source_type'] ?? '',
                        'RECORD_ID' => $notification['record_id'] ?? '',
                        'INTERVAL_MINUTES' => $interval
                    ];
                    $this->db->insert('xx_crm_reminder_log', $reminderLogDetails);
                }
            }

            $this->logServiceActivity("Leads and Deals Notifications for Follow up date completed", $logFile);
            header('HTTP/1.1 200 OK');
            echo "Leads and Deals Notifications Sent";
        } catch (Exception $e) {
            $this->logServiceActivity("ERROR: " . $e->getMessage(), $logFile);
            header('HTTP/1.1 500 Internal Server Error');
            echo "Error occurred during processing.";
        } finally {
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    public function _send_email($params = [])
    {
        // Load PHPMailer
        $mail = $this->phpmailer_lib->load();

        // Basic validations
        if (empty($params['to']) || !filter_var($params['to'], FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'error'   => 'Invalid recipient email address.'
            ];
        }

        if (empty($params['subject'])) {
            return [
                'success' => false,
                'error'   => 'Email subject is required.'
            ];
        }

        if (empty($params['message'])) {
            return [
                'success' => false,
                'error'   => 'Email message is required.'
            ];
        }

        try {
            $mail->CharSet = 'UTF-8';
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.office365.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'workflowmailer@zamilplastic.com';
            $mail->Password   = 'Wf@20252423#';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Email headers
            $from_email = $params['from'] ?? 'workflowmailer@zamilplastic.com';
            $from_name  = $params['from_name'] ?? 'WorkFlow Mailer';

            $reply_to_email = $params['reply_to'] ?? $from_email;
            $reply_to_name  = $params['reply_to_name'] ?? $from_name;

            $mail->setFrom($from_email, $from_name);
            $mail->addReplyTo($reply_to_email, $reply_to_name);
            $mail->addAddress($params['to']);

            // Subject and Body
            $mail->Subject = $params['subject'];
            $mail->isHTML(true);
            $mail->Body    = $params['message'];
            $mail->AltBody = strip_tags($params['message']); // Plain text fallback

            // Send Email
            if (!$mail->send()) {
                return [
                    'success' => false,
                    'error'   => $mail->ErrorInfo
                ];
            }

            return [
                'success' => true,
                'details' => [
                    'to'      => $params['to'],
                    'subject' => $params['subject'],
                    'from'    => $from_email,
                    'time'    => date('Y-m-d H:i:s')
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error'   => 'Mailer Exception: ' . $e->getMessage()
            ];
        }
    }
}
