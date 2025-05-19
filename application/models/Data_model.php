<?php

require_once APPPATH . 'models/App_model.php';
class Data_model extends App_Model
{
    function getOracleClientDetails()
    {
        $sql = "SELECT DISTINCT
                decode(hcasa.org_id, 145, 'IBM', 442, 'Z3P') division,
                customer_number                              client_id,
                customer_name                                company_name,
                hcasa.cust_acct_site_id                      site_id,
                ( ra.address1
                || ', '
                || ra.city
                || ', '
                || ra.province
                || ', '
                || ra.postal_code
                || ', '
                || ra.county )                               address1,
                ''                                           address2,
                ( ra.address1
                || ', '
                || ra.city
                || ', '
                || ra.province
                || ', '
                || ra.postal_code
                || ', '
                || ra.county )                               shipping_address,
                ( ra.address1
                || ', '
                || ra.city
                || ', '
                || ra.province
                || ', '
                || ra.postal_code
                || ', '
                || ra.county )                               biiling_addres,
                ra.city,
                ra.province                                  state,
                ra.county                                    country,
                ra.postal_code,
                rc.creation_date                             creation_date,
                (
                    SELECT
                        ( title )
                    FROM
                        ar_contacts_v v
                    WHERE
                            hcasa.cust_acct_site_id = v.address_id
                        AND v.customer_id = rc.customer_id
                        AND ROWNUM < 2
                )                                            title,
                (
                    SELECT
                        ( first_name )
                    FROM
                        ar_contacts_v v
                    WHERE
                            hcasa.cust_acct_site_id = v.address_id
                        AND v.customer_id = rc.customer_id
                        AND ROWNUM < 2
                )                                            name,
                (
                    SELECT
                        ( contact_number )
                    FROM
                        ar_contacts_v v
                    WHERE
                            v.customer_id = rc.customer_id
                        AND contact_number IS NOT NULL
                        AND ROWNUM < 2
                )                                            ph_no,
            /*Select (PRIMARY_PHONE_COUNTRY_CODE||'-'||PRIMARY_PHONE_AREA_CODE||'-'||PRIMARY_PHONE_NUMBER) PHONE
            from HZ_PARTIES where PARTY_NUMBER='17215'*/
            /*(select (email_address) from AR_CONTACTS_V v
            where  hcasa.cust_acct_site_id=V.address_id AND V.CUSTOMER_ID=RC.CUSTOMER_ID
            AND email_address IS NOT NULL )   */
                nvl((
                    SELECT
                        (email_address)
                    FROM
                        ar_contacts_v v
                    WHERE
                            hcasa.cust_acct_site_id = v.address_id
                        AND v.customer_id = rc.customer_id
                        AND email_address IS NOT NULL
                ), ra.email_address)                         email_address,
                (
                    SELECT
                        ( a.trx_credit_limit ) order_lmt
                    FROM
                        hz_cust_profile_amts a
                    WHERE
                            a.cust_account_id = hcust.cust_account_id
                        AND site_use_id IS NULL
                )                                            order_lmt,
                (
                    SELECT
                        ( a.overall_credit_limit ) order_lmt
                    FROM
                        hz_cust_profile_amts a
                    WHERE
                            a.cust_account_id = hcust.cust_account_id
                        AND site_use_id IS NULL
                )                                            credit_lmt,
                (
                    SELECT
                        name
                    FROM
                        ra_salesreps_all a
                    WHERE
                        hcsua.primary_salesrep_id = a.salesrep_id
                )                                            AS sales_per,
                (
                    CASE
                        WHEN ra.county = 'Saudi Arabia' THEN
                            'SAR'
                        WHEN ra.county = 'SAUDI ARABIA' THEN
                            'SAR'
                        WHEN ra.county IS NULL THEN
                            'SAR'
                        ELSE
                            'USD'
                    END
                )                                            currency,
                rt.name                                      payment_term
            FROM
                ar_customers              rc,
                hz_cust_accounts_all      hcust 
            -- ,AR_PAYMENT_SCHEDULES_ALL A
                ,
                hz_parties                ra,
                ar.hz_cust_acct_sites_all hcasa,
                hz_cust_site_uses_all     hcsua,
                ra_terms_tl               rt
            WHERE
                    rc.customer_id = hcust.cust_account_id
            --AND    A.CUSTOMER_ID = HCUST.CUST_ACCOUNT_ID
                AND ra.party_id = hcust.party_id
            --AND  HCSUA.CUST_ACCT_SITE_ID(+)= HCUST.cust_account_id   
                AND hcsua.cust_acct_site_id (+) = hcasa.cust_acct_site_id
            --AND    A.ORG_ID = DECODE(:P_DIV,'IBM',145,'Z3P',442)
            --    AND hcasa.org_id = decode(:p_div, 'IBM', 145, 'Z3P', 442)
            AND  hcasa.org_id in (145,442)
                AND hcsua.site_use_code = 'BILL_TO'
            --AND    HCUST.status =Decode(:P_Status,'Active','A','InActive','I',hcust.Status)
                AND rc.status = 'A'
                AND hcust.cust_account_id = hcasa.cust_account_id
                AND hcsua.payment_term_id = rt.term_id
                AND rt.language = 'US'
            ORDER BY
                customer_name";
        $query = $this->oracleDB->query($sql);  // Run the query
        $result = $query->result_array();       // Fetch result as an array

        $this->oracleDB->close();               // Close the connection

        return $result;                         // Return the result array

    }

    function create_user_details($user_details, $client_details, $address_details)
    {
        /*
            select * from xx_crm_client_address;
            select * from xx_crm_client_detail;
            select * from xx_crm_users;
        */

        // ## Add data into xx_crm_users
        $user_details['UUID'] = uuid_v4();
        $this->db->insert("xx_crm_users", $user_details);

        $inserted_id = $this->get_column_value("xx_crm_users", 'ID', ['UUID' => $user_details['UUID']]);

        $client_id_gen = "CL" . date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
        $this->db->where('ID', $inserted_id)->update('xx_crm_users', ['USER_ID' => $client_id_gen]);

        // ADD CLIENT DETAILS
        $client_details['CLIENT_ID'] = $client_id_gen;
        $client_details['USER_ID'] = $inserted_id;
        $this->db->insert("xx_crm_client_detail", $client_details);

        // ADD ADDRESS DETAILS
        $address_details['CLIENT_ID'] = $inserted_id;
        $this->db->insert("xx_crm_client_address", $address_details);
    }

    // function to add products from oracle to mysql
    function getOracleProductsDetails()
    {
        $sql = "SELECT
            decode(organization_id, 242, 'IBM', 444, 'Z3P')      div,
            substr(segment1, 1, 2)                               category,
            decode(enabled_flag, 'Y', 'Active', 'N', 'Inactive') status,
            segment1                                             product_name,
            description                                          product_description,
            unit_weight,
            weight_uom_code,
            unit_length,
            unit_width,
            unit_height,
            min_minmax_quantity                                  min_qty,
            max_minmax_quantity                                  max_qty,
            (
                SELECT
                    SUM(transaction_quantity)
                FROM
                    mtl_onhand_quantities
                WHERE
                        subinventory_code = '52FG'
                    AND organization_id = msi.organization_id
                    AND inventory_item_id = msi.inventory_item_id
            )                                                    onhand
        FROM
            mtl_system_items_b msi
        WHERE
            substr(segment1, 1, 2) IN ( 'FG', 'SF' )  AND organization_id IN (242, 444)";
        $query = $this->oracleDB->query($sql);  // Run the query
        $result = $query->result_array();       // Fetch result as an array

        $this->oracleDB->close();               // Close the connection

        return $result;                         // Return the result array

    }

    // add products
    function create_product_details($product_data, $inventory_data)
    {
        $product_data['UUID'] = uuid_v4();
        $this->db->insert("xx_crm_products", $product_data);

        $inserted_id = $this->get_column_value("xx_crm_products", 'PRODUCT_ID', ['UUID' => $product_data['UUID']]);
        // Create product_code in the required format
        $product_code = date('dmy') . str_pad($inserted_id, 6, '0', STR_PAD_LEFT);
        // Update the product_code field for the newly inserted product
        $this->db->where('PRODUCT_ID', $inserted_id);
        $this->db->update("xx_crm_products", ['PRODUCT_CODE' => $product_code]);
        // insert inventory details
        $inventory_data['PRODUCT_ID'] = $inserted_id;
        $this->db->insert("xx_crm_product_inventory", $inventory_data);
    }
}
