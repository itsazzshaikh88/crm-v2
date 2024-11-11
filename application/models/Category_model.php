<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Category_model extends CI_Model
{
    protected $category_table; // Holds the name of the token table

    public function __construct()
    {
        parent::__construct();
        $this->category_table = 'xx_crm_product_categories'; // Initialize token table
    }
    // Function to add or update product
    function get_all_categories()
    {
        return $this->db->get($this->category_table)->result_array();
    }
}
