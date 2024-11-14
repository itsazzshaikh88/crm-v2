<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Product_model extends CI_Model
{
    protected $product_table; // Holds the name of the user table
    protected $inventory_table; // Holds the name of the token table
    protected $variant_table; // Holds the name of the token table
    protected $category_table; // Holds the name of the token table

    public function __construct()
    {
        parent::__construct();
        $this->product_table = 'xx_crm_products'; // Initialize user table
        $this->inventory_table = 'xx_crm_product_inventory'; // Initialize token table
        $this->variant_table = 'xx_crm_product_variants'; // Initialize token table
        $this->category_table = 'xx_crm_product_categories'; // Initialize token table
    }
    // Function to add or update product
    public function add_product($product_id, $data, $userid)
    {
        $product_data = [
            'UUID' => $data['UUID'],
            'DIVISION' => $data['DIVISION'],
            'CATEGORY_ID' => $data['CATEGORY_ID'],
            'STATUS' => $data['STATUS'],
            'PRODUCT_NAME' => $data['PRODUCT_NAME'],
            'DESCRIPTION' => $data['DESCRIPTION'],
            'BASE_PRICE' => $data['BASE_PRICE'],
            'CURRENCY' => $data['CURRENCY'],
            'DISCOUNT_TYPE' => setDiscountType($data['DISCOUNT_TYPE']),
            'DISCOUNT_PERCENTAGE' => $data['DISCOUNT_TYPE'] == '2' ? $data['DISCOUNT_PERCENTAGE'] : null,
            'TAXABLE' => $data['TAXABLE'],
            'TAX_PERCENTAGE' => $data['TAX_PERCENTAGE'],
            'WEIGHT' => $data['WEIGHT'],
            'WIDTH' => $data['WIDTH'],
            'HEIGHT' => $data['HEIGHT'],
            'LENGTH' => $data['LENGTH']
        ];
        $inventory_data = [
            'SKU' => $data['SKU'],
            'MIN_QTY' => $data['MIN_QTY'],
            'MAX_QTY' => $data['MAX_QTY'],
            'AVL_QTY' => $data['AVL_QTY'],
            'BARCODE' => $data['BARCODE'],
            'ALLOW_BACKORDERS' => isset($data['ALLOW_BACKORDERS']) ? 1 : 0
        ];
        if (!in_array($product_id, [' ', '', 0, null])) {
            // check if the data is present 
            $this->db->where('PRODUCT_ID', $product_id);
            $product = $this->db->get($this->product_table)->row_array();
            // Append newly upoaded images
            if (isset($data['UPLOADED_FILES']) && !empty($data['UPLOADED_FILES'])) {
                $filesFromDB = $product['PRODUCT_IMAGES'];
                if (!in_array($filesFromDB, ["", ' ', null, "\"\"", "\" \"", 'null', "''", "' '"])) {
                    $decodedFiles = json_decode($filesFromDB, true);
                    $filesToStore = array_merge($data['UPLOADED_FILES'], $decodedFiles);
                    $product_data['PRODUCT_IMAGES'] = json_encode($filesToStore);
                } else {
                    $product_data['PRODUCT_IMAGES'] = json_encode($data['UPLOADED_FILES']);
                }
            }

            // Update existing product
            $this->db->where('PRODUCT_ID', $product_id);
            $this->db->update($this->product_table, $product_data);

            // Check if update was successful
            if ($this->db->affected_rows() > 0) {
                $this->db->where('PRODUCT_ID', $product_id);
                $this->db->update($this->inventory_table, $inventory_data);
                return true;
            } else {
                return false;
            }
        } else {
            $product_data['CREATED_BY'] = $userid;
            if (isset($data['UPLOADED_FILES']))
                $product_data['PRODUCT_IMAGES'] = json_encode($data['UPLOADED_FILES']);
            // Insert new product
            $inserted = $this->db->insert($this->product_table, $product_data);
            if ($inserted) {
                $inserted_id = $this->db->insert_id();
                // Create product_code in the required format
                $product_code = date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
                // Update the product_code field for the newly inserted product
                $this->db->where('PRODUCT_ID', $inserted_id);
                $this->db->update($this->product_table, ['PRODUCT_CODE' => $product_code]);
                // insert inventory details
                $inventory_data['PRODUCT_ID'] = $inserted_id;
                $this->db->insert($this->inventory_table, $inventory_data);
                return true;
            } else
                return false;
        }
    }

    function get_products($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("p.PRODUCT_ID, p.UUID, p.PRODUCT_CODE, p.CATEGORY_ID, p.STATUS, p.PRODUCT_NAME, 
                   p.DESCRIPTION, p.BASE_PRICE, p.CURRENCY, p.PRODUCT_IMAGES, p.WEIGHT, 
                   p.HEIGHT, p.LENGTH, p.WIDTH, i.AVL_QTY, ct.CATEGORY_CODE");
        $this->db->from("xx_crm_products p");
        $this->db->join("xx_crm_product_inventory i", "i.PRODUCT_ID = p.PRODUCT_ID", "left");
        $this->db->join("xx_crm_product_categories ct", "ct.ID = p.CATEGORY_ID", "left");
        $this->db->order_by("p.PRODUCT_ID", "DESC");

        // Apply filters dynamically from the $filters array
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if (!empty($search) && is_array($search)) {
            if (isset($search['product'])) {
                $this->db->group_start(); // Begin group for OR conditions
                $this->db->like('p.PRODUCT_NAME', $search['product'], 'both', false);
                $this->db->or_like('p.PRODUCT_CODE', $search['product'], 'both', false);
                $this->db->group_end(); // End group for OR conditions
            }
        }


        // Apply limit and offset only if 'list' type and offset is greater than zero
        if ($type == 'list') {
            if ($limit > 0) {
                $this->db->limit($limit, ($offset > 0 ? $offset : 0));
            }
        }

        // Execute query
        $query = $this->db->get();

        if ($type == 'list') {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }

    public function get_product_by_uuid($productUUID)
    {
        $data = ['product' => []];

        if ($productUUID) {
            // Fetch product details
            $data['product'] = $this->db->select('p.*, c.CATEGORY_NAME AS CATEGORY_NAME')
                ->from($this->product_table . ' p') // Alias for the product table
                ->join($this->category_table . ' c', 'p.CATEGORY_ID = c.ID', 'left') // Join with category table
                ->where('p.UUID', $productUUID)
                ->get()
                ->row_array();


            // Fetch inventory details if product exists and has a PRODUCT_ID
            if (isset($data['product']['PRODUCT_ID'])) {
                $data['product']['inventory'] = $this->db->where('PRODUCT_ID', $data['product']['PRODUCT_ID'])
                    ->get($this->inventory_table)
                    ->row_array();
            }
        }

        return $data;
    }

    public function delete_product_by_id($productID)
    {
        $this->db->trans_start();

        $this->db->delete('xx_crm_product_inventory', array('PRODUCT_ID' => $productID));

        $this->db->delete('xx_crm_product_variants', array('PRODUCT_ID' => $productID));

        $this->db->delete('xx_crm_products', array('PRODUCT_ID' => $productID));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
        } else {
            return true;
        }
    }
}
