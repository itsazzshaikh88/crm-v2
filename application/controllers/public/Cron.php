<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    private function logServiceActivity($message, $file)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        file_put_contents($file, $logMessage, FILE_APPEND);
    }


    public function purchase_order_status_service()
    {
        $lockFile = FCPATH . 'secured/locks/po_status.lock';
        $logFile = FCPATH . 'application/logs/po_status_' . date('Y-m-d') . '.log';

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
            $ClientPOs = $this->Purchase_model->getClientPODetailsFromMYSQLToUpdate();
            $this->logServiceActivity("Started PO status update. Total POs: " . count($ClientPOs), $logFile);

            foreach ($ClientPOs as $po) {
                $poId = $po['PO_ID'] ?? 0;
                $clientPoNumber = $po['CLIENT_PO_NUMBER'] ?? '';
                if (!$poId || !$clientPoNumber) continue;

                $po_lines = $this->Purchase_model->fetchPOLinesFromMYSQL($poId);
                $this->logServiceActivity("Processing PO #{$clientPoNumber} (ID: {$poId}) with " . count($po_lines) . " lines", $logFile);

                $deliveredCount = 0;
                $notDeliveredCount = 0;
                $pendingCount = 0;

                foreach ($po_lines as $line) {
                    $line_product = $line['PRODUCT_NAME'] ?? '';
                    $lineId = $line['LINE_ID'] ?? 0;

                    if (!$line_product || !$lineId) continue;

                    $prod_soc_details = $this->Purchase_model->get_po_tracker_details($clientPoNumber, $line_product);

                    // Update MySQL PO line from Oracle details
                    if (!empty($prod_soc_details)) {
                        $lineUpdateData = [
                            'SOC' => $prod_soc_details['SOC_NUM'] ?? null,
                            'REC_QTY' => $prod_soc_details['SHIP_QTY'] ?? null,
                            'BAL_QTY' => $prod_soc_details['BAL_QTY'] ?? null
                        ];

                        $this->Purchase_model->updatePOLineDetailsFromService($lineId, $poId, $lineUpdateData);
                        $this->logServiceActivity("Updated PO Line ID {$lineId}: " . json_encode($lineUpdateData), $logFile);
                    }

                    // Status determination
                    $delStatus = $prod_soc_details['DEL'] ?? '';

                    if ($delStatus === 'DELIVERED') {
                        $deliveredCount++;
                    } elseif (in_array($delStatus, ['NOT DELIVERED', 'PARTIALLY DELIVERED'], true)) {
                        $notDeliveredCount++;
                    } else {
                        $pendingCount++;
                    }
                }

                // Final PO Status
                $newStatus = 'Pending';
                if ($notDeliveredCount > 0) {
                    $newStatus = 'Inprocess';
                } elseif ($deliveredCount === count($po_lines)) {
                    $newStatus = 'Closed';
                }

                $this->Purchase_model->updatePOStatusFromService($poId, ['PO_STATUS' => $newStatus]);
                $this->logServiceActivity("PO ID {$poId} updated to status '{$newStatus}'", $logFile);
                $this->logServiceActivity("-----------------------------------------------------------", $logFile);
            }

            $this->logServiceActivity("PO status update completed successfully.", $logFile);
            header('HTTP/1.1 200 OK');
            echo "PO status updated successfully.";
        } catch (Exception $e) {
            $this->logServiceActivity("ERROR: " . $e->getMessage(), $logFile);
            header('HTTP/1.1 500 Internal Server Error');
            echo "Error occurred during processing.";
        } finally {
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }




    public function test_purchase_order_status_service()
    {

        $ClientPOs = $this->Purchase_model->getClientPODetailsFromMYSQLToUpdate();

        foreach ($ClientPOs as $po) {
            $poId = $po['PO_ID'] ?? 0;
            $clientPoNumber = $po['CLIENT_PO_NUMBER'] ?? '';
            if (!$poId || !$clientPoNumber) continue;

            $po_lines = $this->Purchase_model->fetchPOLinesFromMYSQL($poId);

            foreach ($po_lines as $line) {
                $line_product = $line['PRODUCT_NAME'] ?? '';
                $lineId = $line['LINE_ID'] ?? 0;

                if (!$line_product || !$lineId) continue;

                $prod_soc_details = $this->Purchase_model->get_po_tracker_details($clientPoNumber, $line_product);

                beautify_array($prod_soc_details);
            }
        }
    }
}
