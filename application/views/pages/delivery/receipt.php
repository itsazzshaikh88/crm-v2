<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= base_url() ?>">
    <meta charset="UTF-8">
    <title>Delivery Receipt</title>
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            background: #fff;
        }

        .receipt {
            width: 780px;
            margin: auto;
            padding: 15px;
            border: 1px solid #000;
        }

        h2 {
            text-align: center;
            font-size: 16px;
            margin: 5px 0 15px;
            text-transform: uppercase;
        }

        .info-table,
        .product-table,
        .ack-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td,
        .product-table th,
        .product-table td,
        .ack-table th,
        .ack-table td {
            border: 1px solid #000;
            padding: 4px 6px;
        }

        .info-table td {
            border: none;
            padding: 2px 6px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-block {
            width: 49%;
        }

        .note {
            font-style: italic;
            margin: 5px 0;
            font-size: 11px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        .footer {
            font-size: 11px;
            margin-top: 5px;
        }

        .print-btn {
            margin-top: 10px;
            text-align: center;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="receipt">
        <h2>Delivery Receipt</h2>

        <div class="info-section">
            <div class="info-block">
                <p><strong>Seller:</strong> ZAMIL PLASTIC INDUSTRIES COMPANY</p>
                <p><strong>Phone:</strong> 470 1555</p>
                <p><strong>Delivery No:</strong> IM-66458/223953</p>
                <p><strong>Packing Invoice No:</strong> 2510989, 2510988</p>
                <p><strong>Export Invoice Ref:</strong> CUST</p>
            </div>
            <div class="info-block">
                <p><strong>Place of Delivery:</strong> Al-Kharj, Saudi Arabia</p>
                <p><strong>Consignee:</strong> FG3240</p>
                <p><strong>Address:</strong> Riyadh, SA</p>
                <p><strong>Truck No:</strong> FG3240</p>
                <p><strong>Truck Spec:</strong> ZPI.LWH.FM.8</p>
            </div>
        </div>

        <table class="info-table">
            <tr>
                <td><strong>SOC #</strong></td>
                <td>YASER</td>
                <td><strong>Date</strong></td>
                <td>23-MAR-25</td>
                <td><strong>Customer Ref</strong></td>
                <td>53745/0510573079</td>
            </tr>
        </table>

        <table class="product-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Description</th>
                    <th>UOM</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>25100376</td>
                    <td>100gm Round IML Gishta Lite (AL Marai F3 ART_00746 SKU_347100_1542794_C2248---19_Nov_22)</td>
                    <td>Pcs</td>
                    <td>168,960</td>
                </tr>
                <tr>
                    <td>25100053</td>
                    <td>100gm Round IML Gishta Lite (AL Marai F3 ART_00746 SKU_347100_1542794_C2248---19_Nov_22)</td>
                    <td>Pcs</td>
                    <td>42,240</td>
                </tr>
            </tbody>
        </table>

        <table class="ack-table">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Name</th>
                    <th>Signature</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Shipping Officer</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Delivered By</td>
                    <td>YASER</td>
                    <td></td>
                    <td>23-MAR-25</td>
                </tr>
                <tr>
                    <td>Received By</td>
                    <td>MOHAMMAD</td>
                    <td></td>
                    <td>23-MAR-25</td>
                </tr>
                <tr>
                    <td>Customer Collection</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="note">
            ⚠️ Note: Claims/Return of goods will not be entertained strictly after 7 Days.
        </div>

        <div class="footer">
            <strong>Color Copies:</strong> White: Customer Blue: Customer Ackn. Yellow: Accounts Pink: W/H Green: Shipping Light Green: Sales
        </div>

        <div class="print-btn">
            <button onclick="window.print()">🖨️ Print This Page</button>
        </div>
    </div>

</body>

</html>