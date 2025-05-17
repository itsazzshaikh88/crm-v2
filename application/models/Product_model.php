<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'models/App_model.php';
class Product_model extends App_model
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
            'UUID' => uuid_v4(),
            'DIVISION' => $data['DIVISION'],
            'CATEGORY_ID' => $data['CATEGORY_ID'],
            'STATUS' => $data['STATUS'],
            'PRODUCT_NAME' => $data['PRODUCT_NAME'],
            'DESCRIPTION' => $data['DESCRIPTION'],
            'BASE_PRICE' => $data['BASE_PRICE'],
            'CURRENCY' => $data['CURRENCY'],
            'DISCOUNT_PERCENTAGE' => $data['DISCOUNT_PERCENTAGE'] == '2',
            'TAXABLE' => $data['TAXABLE'],
            'TAX_PERCENTAGE' => $data['TAX_PERCENTAGE'],
            'WEIGHT' => $data['WEIGHT'],
            'WIDTH' => $data['WIDTH'],
            'HEIGHT' => $data['HEIGHT'],
            'LENGTH' => $data['LENGTH'],
            'SHAPE' => $data['SHAPE'],
            'VOLUME' => $data['VOLUME'],
        ];
        $inventory_data = [
            'SKU' => $data['SKU'] ?? null,
            'MIN_QTY' => $data['MIN_QTY'],
            'MAX_QTY' => $data['MAX_QTY'],
            'AVL_QTY' => $data['AVL_QTY'],
            'BARCODE' => $data['BARCODE'] ?? null,
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
            $updated_action = $this->db->update($this->product_table, $product_data);

            // Check if update was successful
            if ($updated_action) {
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
                $inserted_id = $this->get_column_value($this->product_table, 'PRODUCT_ID', ['UUID' => $product_data['UUID']]);
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
                   p.HEIGHT, p.LENGTH, p.WIDTH, p.VOLUME, p.SHAPE, i.AVL_QTY, ct.CATEGORY_CODE");
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

    function get_products_filters($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = null)
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->select("p.PRODUCT_ID, p.UUID, p.PRODUCT_CODE, p.CATEGORY_ID, p.STATUS, p.PRODUCT_NAME, 
                   p.DESCRIPTION, p.BASE_PRICE, p.CURRENCY, p.PRODUCT_IMAGES, p.WEIGHT, 
                   p.HEIGHT, p.LENGTH, p.WIDTH, p.VOLUME, p.SHAPE, i.AVL_QTY, ct.CATEGORY_CODE");
        $this->db->from("xx_crm_products p");
        $this->db->join("xx_crm_product_inventory i", "i.PRODUCT_ID = p.PRODUCT_ID", "left");
        $this->db->join("xx_crm_product_categories ct", "ct.ID = p.CATEGORY_ID", "left");
        $this->db->order_by("p.PRODUCT_ID", "DESC");


        // apply filters
        if (!empty($filters) && is_array($filters)) {
            $fields = ['DIVISION' => 'p.DIVISION', 'CATEGORY_ID' => 'p.CATEGORY_ID', 'VOLUME' => 'LOWER(p.VOLUME)', 'SHAPE' => 'LOWER(p.SHAPE)'];

            foreach ($fields as $key => $column) {
                if (!empty($filters[$key])) {
                    $values = is_string($filters[$key]) ? array_map('trim', explode(',', $filters[$key])) : $filters[$key];

                    // Remove empty values
                    $values = array_filter($values);

                    if (!empty($values)) {
                        $this->db->group_start();
                        foreach ($values as $value) {
                            $this->db->or_like($column, strtolower($value), 'both');
                        }
                        $this->db->group_end();
                    }
                }
            }
        }

        // if (!empty($filters) && is_array($filters)) {
        //     // Division Filters
        //     if (isset($filters['DIVISION'])) {
        //         $divisionFilter = $filters['DIVISION'];

        //         // Convert to an array if it's a comma-separated string
        //         if (is_string($divisionFilter)) {
        //             $divisions = array_map('trim', explode(',', $divisionFilter));
        //         } elseif (is_array($divisionFilter)) {
        //             $divisions = $divisionFilter;
        //         } else {
        //             $divisions = [];
        //         }

        //         // Remove empty values
        //         $divisions = array_filter($divisions);

        //         // Apply division filter using LIKE with OR conditions
        //         if (!empty($divisions)) {
        //             $this->db->group_start(); // Open grouping for OR conditions
        //             foreach ($divisions as $division) {
        //                 $this->db->or_like('p.DIVISION', $division, "both"); // Apply LIKE filter
        //             }
        //             $this->db->group_end(); // Close grouping
        //         }
        //     }

        //     if (isset($filters['VOLUME'])) {
        //         $volumeFilters = $filters['VOLUME'];

        //         // Convert to an array if it's a comma-separated string
        //         if (is_string($volumeFilters)) {
        //             $volumes = array_map('trim', explode(',', $volumeFilters));
        //         } elseif (is_array($volumeFilters)) {
        //             $volumes = $volumeFilters;
        //         } else {
        //             $volumes = [];
        //         }

        //         // Remove empty values
        //         $volumes = array_filter($volumes);

        //         // Apply volumes filter using LIKE with OR conditions
        //         if (!empty($volumes)) {
        //             $this->db->group_start(); // Open grouping for OR conditions
        //             foreach ($volumes as $volume) {
        //                 $this->db->or_like('LOWER(p.VOLUME)', strtolower($volume), 'both');
        //             }
        //             $this->db->group_end(); // Close grouping
        //         }
        //     }
        // }


        if (isset($search) && $search != null) {
            $this->db->group_start(); // Begin group for OR conditions
            $this->db->like('p.PRODUCT_NAME', $search, 'both', false);
            $this->db->or_like('p.PRODUCT_CODE', $search, 'both', false);
            $this->db->group_end(); // End group for OR conditions
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

    public function get_product_by_searchkey($searchKey, $searchValue)
    {
        $data = ['product' => []];

        // Fetch product details
        $data['product'] = $this->db->select('p.*, c.CATEGORY_NAME AS CATEGORY_NAME')
            ->from($this->product_table . ' p') // Alias for the product table
            ->join($this->category_table . ' c', 'p.CATEGORY_ID = c.ID', 'left') // Join with category table
            ->where('p.' . $searchKey, $searchValue)
            ->get()
            ->row_array();


        // Fetch inventory details if product exists and has a PRODUCT_ID
        if (isset($data['product']['PRODUCT_ID'])) {
            $data['product']['inventory'] = $this->db->where('PRODUCT_ID', $data['product']['PRODUCT_ID'])
                ->get($this->inventory_table)
                ->row_array();
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


    function fetchProductFilters()
    {
        return $filters = [
            'shapes' => $this->db->query("")->result_array(),
            'heights' => $this->db->query("")->result_array(),
            'widths' => $this->db->query("")->result_array(),
            'lengths' => $this->db->query("")->result_array(),
        ];
    }


    // Item Codes
    function get_item_codes($type = 'list', $limit = 10, $currentPage = 1, $filters = [], $search = [])
    {
        $offset = get_limit_offset($currentPage, $limit);

        $this->db->distinct();
        $this->db->select("p.PRODUCT_CODE, p.PRODUCT_NAME");
        $this->db->from("xx_crm_products p");
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
}
