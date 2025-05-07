<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function importClientOTOM()
    {
        echo "Services is Stopped by developers.";
        die;
        set_time_limit(0); // Set to 300 seconds (5 minutes), or 0 for unlimited

        $clients = $this->Data_model->getOracleClientDetails();

        $counter = 1;
        foreach ($clients as $client) {
            $user_details = [
                'USER_TYPE' => 'client',
                'FIRST_NAME' => 'Client',
                'LAST_NAME' => 'Name',
                'EMAIL' => $client['EMAIL_ADDRESS'] ?? "client" . $counter . "@crm.live",
                'PASSWORD' => password_hash("User#123$", PASSWORD_ARGON2ID),
                'PHONE_NUMBER' => $client['PH_NO'] ?? "0000000000",
                'STATUS' => 'active',
                'IS_2FA_ENABLED' => 0
            ];

            $client_details = [
                'DIVISION' => $client['DIVISION'],
                'COMPANY_NAME' => $client['COMPANY_NAME'],
                'SITE_NAME' => $client['SITE_ID'],
                'PAYMENT_TERM' => '',
                'CREDIT_LIMIT' => $client['CREDIT_LMT'],
                'TAXES' => '',
                'CURRENCY' => '',
                'ORDER_LIMIT' => $client['ORDER_LMT']
            ];
            $client_address = [
                'ADDRESS_LINE_1' => $client['ADDRESS1'],
                'ADDRESS_LINE_2' => $client['ADDRESS2'],
                'BILLING_ADDRESS' => $client['BIILING_ADDRES'],
                'SHIPPING_ADDRESS' => $client['SHIPPING_ADDRESS'],
                'CITY' => $client['CITY'],
                'STATE' => $client['STATE'],
                'COUNTRY' => $client['COUNTRY'],
                'ZIP_CODE' => $client['POSTAL_CODE']
            ];

            $this->Data_model->create_user_details($user_details, $client_details, $client_address);

            $counter++;
        }

        echo "Data Imported From Oracle to MYSQL";
    }

    function _get_category_id($category)
    {
        if ($category == 'FG') {
            return 1;
        }
        if ($category == 'SF') {
            return 2;
        }
        return 0;
    }

    public function importProductsOTOM()
    {
        echo "Services is Stopped by developers.";
        die;
        set_time_limit(0); // Set to 300 seconds (5 minutes), or 0 for unlimited

        $products = $this->Data_model->getOracleProductsDetails();

        $counter = 1;
        foreach ($products as $product) {
            // PRODUCT_ID, UUID, PRODUCT_CODE, DIVISION, CATEGORY_ID, STATUS, PRODUCT_NAME, DESCRIPTION, BASE_PRICE, CURRENCY, DISCOUNT_TYPE, DISCOUNT_PERCENTAGE, TAXABLE, TAX_PERCENTAGE, PRODUCT_IMAGES, WEIGHT, HEIGHT, LENGTH, WIDTH, VOLUME, SHAPE, CREATED_BY, CREATED_AT, UPDATED_AT
            $products_data = [
                'DIVISION' => $product['DIV'],
                'CATEGORY_ID' => $this->_get_category_id($product['CATEGORY']),
                'STATUS' => 'active',
                'PRODUCT_NAME' => $product['PRODUCT_NAME'],
                'DESCRIPTION' => $product['PRODUCT_DESCRIPTION'],
                'BASE_PRICE' => '',
                'CURRENCY' => '',
                'DISCOUNT_TYPE' => '',
                'DISCOUNT_PERCENTAGE' => '',
                'TAXABLE' => '',
                'TAX_PERCENTAGE' => '',
                'PRODUCT_IMAGES' => '',
                'WEIGHT' => $product['UNIT_WEIGHT'],
                'HEIGHT' => $product['UNIT_HEIGHT'],
                'LENGTH' => $product['UNIT_LENGTH'],
                'WIDTH' => '',
                'VOLUME' => '',
                'SHAPE' => ''
            ];

            $inventory_data = [
                'SKU' =>  null,
                'MIN_QTY' => $product['MIN_QTY'],
                'MAX_QTY' => $product['MAX_QTY'],
                'AVL_QTY' => $product['ONHAND'],
                'BARCODE' =>  null,
                'ALLOW_BACKORDERS' =>  0
            ];

            $this->Data_model->create_product_details($products_data, $inventory_data);

            $counter++;
        }

        echo "Product - Data Imported From Oracle to MYSQL";
    }
}
