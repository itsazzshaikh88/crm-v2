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
        set_time_limit(0);
        $clients = $this->Data_model->getOracleClientDetails();

        $counter = 1;
        foreach ($clients as $client) {
            $user_details = [
                'USER_TYPE' => 'client',
                'FIRST_NAME' => $client['NAME'] ?? null,
                'LAST_NAME' => '',
                'EMAIL' => $client['EMAIL_ADDRESS'] ?? "client" . $counter . "@crm.live",
                'PASSWORD' => password_hash("User#123$", PASSWORD_ARGON2ID),
                'PHONE_NUMBER' => $client['PH_NO'] ?? "0000000000",
                'STATUS' => 'active',
                'IS_2FA_ENABLED' => 0
            ];
            $country = isset($client['COUNTRY']) ? strtoupper(trim($client['COUNTRY'])) : '';
            $taxes = in_array($country, ['KSA', 'SA', 'SAUDI ARABIA']) ? 15 : 0;

            $client_details = [
                'DIVISION' => $client['DIVISION'],
                'COMPANY_NAME' => $client['COMPANY_NAME'],
                'SITE_NAME' => $client['SITE_ID'],
                'PAYMENT_TERM' => $client["PAYMENT_TERM"],
                'CREDIT_LIMIT' => $client['CREDIT_LMT'],
                'TAXES' => $taxes,
                'CURRENCY' => $client['CURRENCY'],
                'ORDER_LIMIT' => $client['ORDER_LMT'],
                'SALES_PERSON' => $client['SALES_PER'],
                'ORA_CLIENT_ID' => $client['CLIENT_ID'],

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

    public function importSalesForecastOTOM($year = null)
    {
        // echo "Services is Stopped by developers.";
        // die;
        set_time_limit(0); // Set to 300 seconds (5 minutes), or 0 for unlimited

        $sales = $this->Data_model->getSalesForecastData($year);
        // beautify_array($sales, true);
        $counter = 1;
        foreach ($sales as $sale) {
            $new_forecast = [
                "ORG_ID" => $sale['ORG_ID'],
                "YER" => $sale['YER'],
                "CUSTOMER_NUMBER" => $sale['CUSTOMER_NUMBER'],
                "CUSTOMER_NAME" => $sale['CUSTOMER_NAME'],
                "CATEGORY_CODE" => null,
                "SUB_CATEGORY_CODE" => null,
                "ITEM_C" => $sale['ITEM_C'],
                "ITEM_DESC" => $sale['ITEM_DESC'],
                "PRODUCT_WEIGHT" => $sale['PRODUCT_WEIGHT'],
                "UOM" => $sale['UOM'],
                "SALES_MAN" => $sale['SALES_MAN'],
                "SALES_MAN_ID" => $sale['SALES_MAN_ID'],
                "REGION" => $sale['REGION'],
                "ORGANIZATION_ID" => $sale['ORGANIZATION_ID'],
                "QTY_JAN" => $sale['Q1'],
                "UNIT_JAN" => $sale['UNIT1'],
                "VALUE_JAN" => $sale['U1'],
                "QTY_FEB" => $sale['Q2'],
                "UNIT_FEB" => $sale['UNIT2'],
                "VALUE_FEB" => $sale['U2'],
                "QTY_MAR" => $sale['Q3'],
                "UNIT_MAR" => $sale['UNIT3'],
                "VALUE_MAR" => $sale['U3'],
                "QTY_APR" => $sale['Q4'],
                "UNIT_APR" => $sale['UNIT4'],
                "VALUE_APR" => $sale['U4'],
                "QTY_MAY" => $sale['Q5'],
                "UNIT_MAY" => $sale['UNIT5'],
                "VALUE_MAY" => $sale['U5'],
                "QTY_JUN" => $sale['Q6'],
                "UNIT_JUN" => $sale['UNIT6'],
                "VALUE_JUN" => $sale['U6'],
                "QTY_JUL" => $sale['Q7'],
                "UNIT_JUL" => $sale['UNIT7'],
                "VALUE_JUL" => $sale['U7'],
                "QTY_AUG" => $sale['Q8'],
                "UNIT_AUG" => $sale['UNIT8'],
                "VALUE_AUG" => $sale['U8'],
                "QTY_SEP" => $sale['Q9'],
                "UNIT_SEP" => $sale['UNIT9'],
                "VALUE_SEP" => $sale['U9'],
                "QTY_OCT" => $sale['Q10'],
                "UNIT_OCT" => $sale['UNIT10'],
                "VALUE_OCT" => $sale['U10'],
                "QTY_NOV" => $sale['Q11'],
                "UNIT_NOV" => $sale['UNIT11'],
                "VALUE_NOV" => $sale['U11'],
                "QTY_DEC" => $sale['Q12'],
                "UNIT_DEC" => $sale['UNIT12'],
                "VALUE_DEC" => $sale['U12'],
                "STATUS" => $sale['STATUS']
            ];

            $this->Data_model->create_forecast_details($new_forecast);

            $counter++;
        }

        echo "Sales Forecast - Data Imported From Oracle to MYSQL for year " . $year;
    }
}
