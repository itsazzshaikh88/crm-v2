<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <style>
        /* General Email Styles */
        body,
        table,
        td,
        a {
            -ms-text-size-adjust: 100%;
            /* Outlook */
            -webkit-text-size-adjust: 100%;
            /* iOS */
            margin: 0;
            padding: 0;
            text-size-adjust: 100%;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
            width: 100%;
        }

        /* Fix Outlook rendering of tables */
        .ExternalClass,
        .ExternalClass * {
            line-height: 100%;
        }

        /* Set the email background */
        body {
            background-color: #ffffff;
            font-family: Arial, sans-serif;
            color: #333333;
            font-size: 16px;
        }

        /* Main Table */
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .content-table {
            width: 100%;
            background-color: #f4f4f4;
            padding: 30px;
        }

        /* Header Styles */
        .email-header {
            text-align: center;
            padding: 20px 0;
        }

        .email-header img {
            max-width: 200px;
            height: auto;
        }

        .email-header h1 {
            color: #4c9c2e;
            font-size: 24px;
            margin: 20px 0;
        }

        /* Content Styles */
        .email-content {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
        }

        .email-content p {
            line-height: 1.6;
            font-size: 16px;
            color: #333333;
        }

        /* Footer Styles */
        .email-footer {
            background-color: #333333;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            width: 100%;
        }

        .email-footer a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
        }

        /* Responsive Design */
        @media screen and (max-width: 600px) {
            .content-table {
                padding: 20px;
            }

            .email-header h1 {
                font-size: 22px;
            }

            .email-content p {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <!-- Main Email Wrapper -->
    <table class="email-container" role="presentation" cellspacing="0" cellpadding="0">
        <tr>
            <td>

                <!-- Email Header -->
                <table class="email-header" role="presentation" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <img src="https://cdn-ilbbkmj.nitrocdn.com/cRYFiDiEDxSTYcPdyCcSPfHxCQdqrdfA/assets/images/optimized/rev-55dae52/zamilplastic.com/wp-content/uploads/2024/05/Zamil-plastic-Logo.png" alt="Your Logo" style="width: 120px;">

                            <h1><?= $emailViewConfig['heading'] ?? '' ?></h1>
                        </td>
                    </tr>
                </table>

                <!-- Email Content -->
                <?php
                $this->load->view('email-templates/' . $emailViewConfig['content_view']);
                ?>

                <!-- Email Footer -->
                <table class="email-footer" role="presentation" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <p>Follow us on:</p>
                            <p>
                                <a href="https://www.facebook.com/ZamilPlasticIndustriesCo/" target="_blank">Facebook</a> |
                                <a href="https://twitter.com/zamilplastic" target="_blank">Twitter</a> |
                                <a href="https://www.linkedin.com/company/zamil-plastic-industries-co/" target="_blank">LinkedIn</a>
                            </p>
                            <p>Copyright © <?= date('Y') ?> Zamil Plastics. All Rights Reserved </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>