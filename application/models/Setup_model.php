<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Setup_model extends App_Model
{
    function getEmailConfig($conditions = ['IS_ACTIVE' => 1])
    {
        $this->db->select("EMAIL_PROVIDER, SMTP_SERVER, SMTP_PORT, USERNAME, PASSWORD, FROM_EMAIL, USE_SSL, USE_TLS");
        $this->db->where($conditions);
        return $this->db->get('XX_CRM_EMAIL_CONFIG')->row_array();
    }
}
