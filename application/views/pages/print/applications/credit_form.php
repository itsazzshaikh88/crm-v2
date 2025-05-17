<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form for Credit Facility</title>


    <style>
        /* General Table Styling */
        .table-bordered {
            border: 0px solid #dee2e6;
            width: 100%;
            border-radius: 0;
            /* Removed rounded corners */
            background-color: #ffffff;
            border-collapse: collapse;

        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 0.3rem;
            border-collapse: collapse;
            /* Reduced padding */
        }

        /* Table Heading and Subheading */
        .heading {
            font-size: 1.25rem;
            font-weight: bold;
            color: #343a40;
            padding: 0.75rem;
            /* Reduced padding */
            text-align: left;
            text-transform: none;
            background-color: #ffffff;
        }

        .subheading {
            font-size: 1.1rem;
            font-weight: normal;
            color: #495057;
            padding: 0.5rem;
            /* Reduced padding */
            text-align: left;
            background-color: #ffffff;
        }

        /* Table Rows Styling */
        .label {
            font-weight: bold;
            color: #495057;
            width: 35%;
            padding-left: 0.5rem;
            /* Reduced padding */
            background-color: #ffffff;
        }

        .value {
            font-weight: normal;
            background-color: #ffffff;
            padding-left: 0.5rem;
            /* Reduced padding */
        }

        /* Removed Box Shadow and Spacing */
        .table-bordered {
            box-shadow: none;
            margin: 0;
            /* Removed external spacing */
        }

        /* Hover Effect */
        .table-bordered tr:hover {
            background-color: #f1f1f1;
        }

        /* Arabic Labels */
        .arabic-label {
            font-style: italic;
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Text Danger (required fields) */
        .text-danger {
            color: #dc3545;
            font-size: 1rem;
        }

        /* Spacing */
        .mt-4 {
            margin-top: 1.5rem;
            /* Keeps this for the table margin at the top */
        }

        /* Additional Enhancements */
        /* .table-bordered tr:nth-child(odd) .value {
        background-color: #f8f9fa;
    }

    .table-bordered tr:nth-child(even) .value {
        background-color: #ffffff;
    } */

        /* Responsive Design */
        @media (max-width: 768px) {
            .table-bordered {
                font-size: 0.9rem;
            }

            .heading {
                font-size: 1.25rem;
            }

            .subheading {
                font-size: 1.1rem;
            }

            .label,
            .value {
                padding: 0.25rem;
                /* Further reduced padding for smaller screens */
            }
        }
    </style>

</head>

<body>

    <div class="container">
        <!-- Customer & Credit Information -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th colspan="2" class="text-center heading">Customer & Credit Information</th>
                </tr>
                <tr>
                    <th colspan="2" class="text-center subheading">Customer Information</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label">Application Date <span class="arabic-label">(تاريخ):</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="application-date"></td>
                </tr>
                <tr>
                    <td class="label">Customer Number <span class="arabic-label">(رقم العميل):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="customer-number"></td>
                </tr>
                <tr>
                    <td class="label">Customer Name <span class="arabic-label">(اسم العميل):</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="customer-name"></td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th colspan="2" class="text-center subheading">Credit Application for Business Account Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        We request that Zamil Plastic Industries Ltd. open in our name a Credit Account Facility, to
                        cover the purchased materials and products as we would like a
                        Credit Limit with following details.<br><br> <span class="arabic-label">(
                            نطلب من شركة الزامل للصناعات البالستيكية احملدودة أن تفتح بأ مسنا حساب الائتمان اآليت بيانه،
                            لتغطية المواد واملنتجات املشتراة كما نود ان يكون حد الائتمان كحد أقصى)</span>
                    </td>
                </tr>

                <tr>
                    <td class="label">Credit in SAR <span class="arabic-label">(ريال سعودي):</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="credit-value"></td>
                </tr>
                <tr>
                    <td class="label">Credit in Words <span class="arabic-label">(بالكلمات):</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="credit-in-words"></td>
                </tr>
                <tr>
                    <td class="label">Within Days <span class="arabic-label">(خلال أيام):</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="within-days"></td>
                </tr>
                <tr>
                    <td colspan="2">We undertake to settle all our invoices within given days <span
                            class="arabic-text">(ونتعهد بتسوية جميع فواتيرنا خلال أيام معينة)</span></td>
                </tr>
                <tr>
                    <td class="label">Applicant's comment <span class="arabic-label">(تعليق مقدم الطلب):</span></td>
                    <td class="value" id="applicant-comment"></td>
                </tr>
            </tbody>
        </table>



        <!-- Contact Information -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th colspan="2" class="text-center heading" style="font-size: large;">Contact Information</th>
                </tr>

            </thead>
            <thead>

                <tr>
                    <th colspan="2" class="text-center subheading">Company & Contact Information</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label">Company Name <span class="arabic-label">(اسم الشركة):</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="company-name"></td>
                </tr>
                <tr>
                    <td class="label">Contact Person <span class="arabic-label">(الاسم الكامل):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="contact-person"></td>
                </tr>
                <tr>
                    <td class="label">Contact Person Title <span class="arabic-label">(المسمى الوظيفي):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="contact-person-title"></td>
                </tr>
                <tr>
                    <td class="label">Contact Email <span class="arabic-label">(البريد الإلكتروني):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="contact-email"></td>
                </tr>
                <tr>
                    <td class="label">Phone <span class="arabic-label">(رقم الهاتف):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="phone"></td>
                </tr>
                <tr>
                    <td class="label">FAX <span class="arabic-label">(رقم الفاكس):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="fax"></td>
                </tr>
                <tr>
                    <td class="label">Company Email <span class="arabic-label">(البريد الإلكتروني للشركة):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="company-email"></td>
                </tr>
                <tr>
                    <th colspan="2" class="text-center subheading mt-2">Address Details</th>
                </tr>
                <tr>
                    <td class="label">City <span class="arabic-label">(اسم المدينة):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="city"></td>
                </tr>
                <tr>
                    <td class="label">State/Province <span class="arabic-label">(الولاية/المقاطعة):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="state"></td>
                </tr>
                <tr>
                    <td class="label">Zip Code <span class="arabic-label">(الرمز البريدي):</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="zip-code"></td>
                </tr>
                <tr>
                    <td class="label">How long at current address? <span class="arabic-label">(كم من مدة وجود الشركة في
                            العنوان
                            الحالي؟):</span> <span class="text-danger">*</span></td>
                    <td class="value" id="address-span"></td>
                </tr>
                <tr>
                    <th colspan="2" class="text-center subheading mt-2">Business Information</th>
                </tr>
                <tr>
                    <td class="label">Business Started <span class="arabic-label">(تاريخ بدء النشاط):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="business-start-date"></td>
                </tr>
                <tr>
                    <td class="label">Business Type <span class="arabic-label">(نوع الاعمال):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="business-type"></td>
                </tr>
                <tr>
                    <th colspan="2" class="text-center subheading mt-2">Bank Details</th>
                </tr>
                <tr>
                    <td class="label">Bank Name<span class="arabic-label">(اسم البنك):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="bank-name"></td>
                </tr>
                <tr>
                    <td class="label">Bank Location <span class="arabic-label">(موقع البنك – فرع):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="bank-location"></td>
                </tr>
                <tr>
                    <td class="label">Acc. Number <span class="arabic-label">(رقم الحساب المصرفي):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="account-number"></td>
                </tr>
                <tr>
                    <td class="label">IBAN Number<span class="arabic-label">(IBAN رقم):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="iban"></td>
                </tr>
                <tr>
                    <td class="label">Swift Code: <span class="arabic-label">(رقم السويفت كود):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="swift"></td>
                </tr>

            </tbody>
        </table>


        <!-- Formal Information -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th colspan="2" class="text-center heading" style="font-size: large;">Formal Information</th>
                </tr>

            </thead>
            <thead>

                <tr>
                    <th colspan="2" class="text-center subheading">Registration Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label">CRN Number <span class="arabic-label">(رقم السجل التجاري):</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="crn"></td>
                </tr>
                <tr>
                    <td class="label">Date of Issuance <span class="arabic-label">(تاريخ الانتهاء) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="date-of-issuance"></td>
                </tr>
                <tr>
                    <td class="label">Date of Expiry <span class="arabic-label">(تاريخ االنتهاء) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="date-of-expiry"></td>
                </tr>

                <tr>
                    <th colspan="2" class="text-center subheading mt-2">Company Ownership Details</th>
                </tr>
                <tr>
                    <td class="label">Paid up Capital <span class="arabic-label">(رأس المال المدفوع) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="paid-up-capital"></td>
                </tr>
                <tr>
                    <td class="label">Company Owner Name <span class="arabic-label">(اسم المالك/مالك الشركة) :</span>
                        <span class="text-danger">*</span>
                    </td>
                    <td class="value" id="company-owner"></td>
                </tr>
                <tr>
                    <td class="label">% of Ownership <span class="arabic-label">(نسبة الملكية % لكل شريك) :</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="percentage-owner"></td>
                </tr>
                <tr>
                    <td class="label">Company Top Manager <span class="arabic-label">(المسئول األول بألشركة) :</span>
                        <span class="text-danger">*</span>
                    </td>
                    <td class="value" id="top-manager"></td>
                </tr>
                <tr>
                    <th colspan="2" class="text-center subheading mt-2">Personnel Authorized Signature for Purchasing
                    </th>
                </tr>
                <tr>
                    <td class="label">Name <span class="arabic-label">(اسم) :</span> <span class="text-danger">*</span>
                    </td>
                    <td class="value" id="sign-name"></td>
                </tr>
                <tr>
                    <td class="label">Position <span class="arabic-label">(المسمى الوظيفي) :</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="sign-position"></td>
                </tr>
                <tr>
                    <td class="label">Signature Specimen <span class="arabic-label">(نموذج التوقيع) :</span> <span
                            class="text-danger">*</span>
                    </td>
                    <td class="value" id="sign-specimen"></td>
                </tr>
                <tr>
                    <th colspan="2" class="text-center subheading mt-2">Company Managers Details</th>
                </tr>
                <tr>
                    <td class="label">Business Activities <span class="arabic-label">(النشاط التجارية) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="bus-activities"></td>
                </tr>
                <tr>
                    <td class="label">Gen. Manager <span class="arabic-label">(إسم و رقم المدير العام) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="gm-name"></td>
                </tr>
                <tr>
                    <td class="label">Pur Manager <span class="arabic-label">(إسم و رقم مدير المشتريات) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="pur-mgr-name"></td>
                </tr>
                <tr>
                    <td class="label">Fin Manager <span class="arabic-label">(إسم و رقم المدير المالي) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="fin-mgr-name"></td>
                </tr>


            </tbody>
        </table>

        <!-- Agreement -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th colspan="2" class="text-center heading" style="font-size: large;"> Agreement - إتفاقية</th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td class="label"> Supply of material will be placed on hold by Zamil Plastic Industries Limited, if
                        the credit
                        limit exceeds the agreed number of days. This action may also be taken if the customer does not
                        settle his account with in the time stipulated on page 1&2 of this contract <br><br> <span
                            class="arabic-label">سيتم تعليق توريد المواد من قبل شركة الزامل للصناعات البالستيكية
                            المحدودة، إذا تجاوزت الشركة عدد أاليام المتفق عليها كحد ائتماني .2ويمكن أيضا أن تتخذ هذا
                            اإلجراء إذا كان الزبون ال يدفع فواتير حسابه في الوقت المحدد على الصفحات 1 و 2 من هذا العقد

                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="label"> By submitting this application, you authorize Zamil Plastic Industries Limited to
                        make
                        inquiries into the banking and business/trade references that you have supplied.<br><br>
                        <span class="arabic-label">من خالل تقديم هذا الطلب،نفوض شركة الزامل للصناعات البالستيكية
                            المحدودة .إلجراء التأكيدات الالزمة في األعمال المصرفية و مراجعة األعمال التجارية التي تقوم
                            الشركة بتوفيرها.

                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Zamil Plastic Industries Limited, reserves the right to withdraw the credit
                        facility in full at
                        its sole discretion at any time seen fit . <br><br> <span class="arabic-label">إن إدارة شركة
                            الزامل
                            للصناعات البالستيكية المحدودة، تحتفظ لنفسها بالحق في سحب بعض/كل التسهيالت االئتمانية حسب
                            تقديرها في أي وقت
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!--  Signatures -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th colspan="2" class="text-center heading" style="font-size: large;">Signatures</th>
                </tr>


            </thead>
            <thead>


                <tr>
                    <th colspan="2" class="text-center subheading" style="font-size: large;">Zamil Plastic Industries
                        Limited -
                        الزامل للصناعات البالستيكية المحدودة</th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td class="label">Signature <span class="arabic-label">(التوقيع) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="zpil-sign"></td>
                </tr>
                <tr>
                    <td class="label">Signatory Name <span class="arabic-label">(اسم صاحب التوقيع) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="zpil-signatory"></td>
                </tr>
                <tr>
                    <td class="label">Position Title <span class="arabic-label">(المسمى الوظيفي) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="zpil-sign-position"></td>
                </tr>
                <tr>
                    <td class="label">Date <span class="arabic-label">(تاريخ) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="zpil-date"></td>
                </tr>
            </tbody>

            <thead>


                <tr>
                    <th colspan="2" class="text-center subheading" style="font-size: large;">Customer Signature Details
                        -
                        العميل</th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td class="label">Signature <span class="arabic-label">(التوقيع) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="client-sign"></td>
                </tr>
                <tr>
                    <td class="label">Company Stamp <span class="arabic-label">(ختم الشركة) :span> <span
                                class="text-danger">*</span>
                    </td>
                    <td class="value" id="client-stamp"></td>
                </tr>
                <tr>
                    <td class="label">Signatory Name <span class="arabic-label">(اسم صاحب التوقيع) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="client-sign-name"></td>
                </tr>

                <tr>
                    <td class="label">Date <span class="arabic-label">(تاريخ) :</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="client-sign-date"></td>
                </tr>

                <tr>
                    <td class="label">Chamber of Commerce Stamp <span class="arabic-label">(ختم الغرفة التجارية)
                            :</span> <span class="text-danger">*</span></td>
                    <td class="value" id="chamber-of-commerce"></td>
                </tr>
            </tbody>
        </table>


        <!-- For Zamil Only -->

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th colspan="2" class="text-center heading" style="font-size: large;">For Zamil Only</th>
                </tr>
            </thead>

            <thead>
                <tr>
                    <th colspan="2" class="text-center subheading" style="font-size: large;">Reference Details - مرجع
                    </th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td class="label">Direct Salesman Comments <span class="arabic-label">(تعليقات موظف
                            المبيعات):</span> <span class="text-danger">*</span></td>
                    <td class="value" id="dir-sales"></td>
                </tr>
                <tr>
                    <td class="label">Sales Manager Comments<span class="arabic-label">(تعليقات مدير المبيعات):</span>
                        <span class="text-danger">*</span>
                    </td>
                    <td class="value" id="sales-mgr"></td>
                </tr>
                <tr>
                    <td class="label">General Manager Comments <span class="arabic-label">(تعليقات مدير عام):</span>
                        <span class="text-danger">*</span>
                    </td>
                    <td class="value" id="gm-comments"></td>
                </tr>
                <tr>
                    <td class="label">Credit Division Comments <span class="arabic-label">(تعليقات قسم الائتمان):</span>
                        <span class="text-danger">*</span>
                    </td>
                    <td class="value" id="credit-division-comments"></td>
                </tr>
                <tr>
                    <td class="label">Finance Manager Comments <span class="arabic-label">(تعليقات المدير
                            المالي):</span> <span class="text-danger">*</span></td>
                    <td class="value" id="fin-mgr-comments"></td>
                </tr>
                <tr>
                    <td class="label"> Management Comments <span class="arabic-label">(تعليقات إدارة):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="mgmt-comments"></td>
                </tr>
            </tbody>

            <thead>
                <tr>
                    <th colspan="2" class="text-center subheading" style="font-size: large;">Approved Credit Limit
                        Details - تفاصيل
                        الحد الائتماني المعتمد</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label">Final Recommended Credit Limit SAR <span class="arabic-label">(تفاصيل الحد
                            الائتماني
                            المعتمد):</span> <span class="text-danger">*</span></td>
                    <td class="value" id="rec-credit-limit"></td>
                </tr>
                <tr>
                    <td class="label">Recom. Credit Period (# of Days) <span class="arabic-label">(فترة الائتمان المسموح
                            به (# الأيام)):<span> <span class="text-danger">*</span>
                    </td>
                    <td class="value" id="rec-credit-period"></td>
                </tr>
                <tr>
                    <td class="label">Approved by Finance <span class="arabic-label">(الموافقة المالي):</span> <span
                            class="text-danger">*</span></td>
                    <td class="value" id="approved-finance"></td>
                </tr>

                <tr>
                    <td class="label">Approved by Management <span class="arabic-label">(الموافقة الإدارية):</span>
                        <span class="text-danger">*</span>
                    </td>
                    <td class="value" id="approved-management"></td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th colspan="2" class="text-center subheading" style="font-size: large;">Required Attachments -
                        المرفقات
                        المطلوبة
                    </th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td class="label">Commercial Registration Copy <span class="arabic-label">(صورة من السجل التجاري)
                        </span> <span class="text-danger">*</span></td>
                    <td class="value" id="crn-attachment"></td>
                </tr>
                <tr>
                    <td class="value" class="label">Bank Certificate Original (stamped) <span class="arabic-label">(أصل
                            شهادة بنكية مختومة ببيانات
                            الحساب)
                            <span> <span class="text-danger">*</span>
                    </td>
                    <td class="value" id="bank-certificate"></td>
                </tr>
                <tr>
                    <td class="label">Owner ID copy for Company Use only <span class="arabic-label">(صورة الهوية للمالك
                            الشركة استخدم
                            فقط)
                        </span> <span class="text-danger">*</span></td>
                    <td class="value" id="owner-id"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        function populateCreditForm(creditData) {
            // Populate table fields with data
            document.getElementById('application-date').textContent = creditData.APPLICATION_DATE || 'N/A';
            document.getElementById('customer-number').textContent = creditData.APPLICATION_NUMBER || 'N/A';
            document.getElementById('customer-name').textContent = creditData.CUSTOMER_FULL_NAME || 'N/A';
            document.getElementById('credit-value').textContent = creditData.CREDIT_VALUE || 'N/A';
            document.getElementById('credit-in-words').textContent = creditData.CREDIT_IN_WORDS || 'N/A';
            document.getElementById('within-days').textContent = creditData.WITHIN_DAYS || 'N/A';
            document.getElementById('applicant-comment').textContent = creditData.APPLICANT_COMMENT || 'N/A';

            document.getElementById('company-name').textContent = creditData.COMPANY_NAME || 'N/A';
            document.getElementById('contact-person').textContent = creditData.CONTACT_PERSON || 'N/A';
            document.getElementById('contact-person-title').textContent = creditData.CONTACT_PERSON_TITLE || 'N/A';
            document.getElementById('contact-email').textContent = creditData.CONTACT_EMAIL || 'N/A';
            document.getElementById('phone').textContent = creditData.PHONE || 'N/A';
            document.getElementById('fax').textContent = creditData.FAX || 'N/A';
            document.getElementById('company-email').textContent = creditData.COMPANY_EMAIL || 'N/A';

            document.getElementById('city').textContent = creditData.CITY || 'N/A';
            document.getElementById('state').textContent = creditData.STATE || 'N/A';
            document.getElementById('zip-code').textContent = creditData.ZIP_CODE || 'N/A';
            document.getElementById('address-span').textContent = creditData.ADDRESS_SPAN || 'N/A';

            document.getElementById('business-start-date').textContent = creditData.BUSINESS_START_DATE || 'N/A';
            document.getElementById('business-type').textContent = creditData.BUSINESS_TYPE || 'N/A';
            document.getElementById('bank-name').textContent = creditData.BANK_NAME || 'N/A';
            document.getElementById('bank-location').textContent = creditData.BANK_LOCATION || 'N/A';
            document.getElementById('account-number').textContent = creditData.ACCOUNT_NUMBER || 'N/A';
            document.getElementById('iban').textContent = creditData.IBAN_NUMBER || 'N/A';
            document.getElementById('swift').textContent = creditData.SWIFT_CODE || 'N/A';

            document.getElementById('crn').textContent = creditData.CRN_NUMBER || 'N/A';
            document.getElementById('date-of-issuance').textContent = creditData.DATE_OF_ISSUANCE || 'N/A';
            document.getElementById('date-of-expiry').textContent = creditData.DATE_OF_EXPIRY || 'N/A';
            document.getElementById('paid-up-capital').textContent = creditData.PAID_UP_CAPITAL || 'N/A';
            document.getElementById('company-owner').textContent = creditData.COMPANY_OWNER || 'N/A';
            document.getElementById('percentage-owner').textContent = creditData.PERCENTAGE_OWNER || 'N/A';
            document.getElementById('top-manager').textContent = creditData.TOP_MANAGER || 'N/A';

            document.getElementById('sign-name').textContent = creditData.SIGN_NAME || 'N/A';
            document.getElementById('sign-position').textContent = creditData.SIGN_POSITION || 'N/A';
            document.getElementById('sign-specimen').textContent = creditData.SIGN_SPECIMEN || 'N/A';
            document.getElementById('bus-activities').textContent = creditData.BUS_ACTIVITIES || 'N/A';
            document.getElementById('gm-name').textContent = creditData.GM_NAME || 'N/A';
            document.getElementById('pur-mgr-name').textContent = creditData.PUR_MGR_NAME || 'N/A';
            document.getElementById('fin-mgr-name').textContent = creditData.FIN_MGR_NAME || 'N/A';

            document.getElementById('zpil-sign').textContent = creditData.ZPIL_SIGN || 'N/A';
            document.getElementById('zpil-signatory').textContent = creditData.ZPIL_SIGNATORY_NAME || 'N/A';
            document.getElementById('zpil-sign-position').textContent = creditData.ZPIL_SIGN_POSN || 'N/A';
            document.getElementById('zpil-date').textContent = creditData.ZPIL_DATE || 'N/A';
            document.getElementById('client-sign').textContent = creditData.CLIENT_SIGN || 'N/A';
            document.getElementById('client-stamp').textContent = creditData.CLIENT_STAMP || 'N/A';
            document.getElementById('client-sign-name').textContent = creditData.CLIENT_SIGN_NAME || 'N/A';
            document.getElementById('client-sign-date').textContent = creditData.CLIENT_SIGN_DATE || 'N/A';
            document.getElementById('chamber-of-commerce').textContent = creditData.CHAMBER_OF_COMMERCE || 'N/A';

            document.getElementById('dir-sales').textContent = creditData.DIR_SALES_COMMENTS || 'N/A';
            document.getElementById('sales-mgr').textContent = creditData.SALES_MGR_COMMENTS || 'N/A';
            document.getElementById('gm-comments').textContent = creditData.GM_COMMENTS || 'N/A';
            document.getElementById('credit-division-comments').textContent = creditData.CREDIT_DIV_COMMENTS || 'N/A';
            document.getElementById('fin-mgr-comments').textContent = creditData.FIN_MGR_COMMENTS || 'N/A';
            document.getElementById('mgmt-comments').textContent = creditData.MGMT_COMMENTS || 'N/A';
            document.getElementById('rec-credit-limit').textContent = creditData.REC_CREDIT_LIMIT || 'N/A';
            document.getElementById('rec-credit-period').textContent = creditData.REC_CREDIT_PERIOD || 'N/A';
            document.getElementById('approved-finance').textContent = creditData.APPROVED_FINANCE || 'N/A';
            document.getElementById('approved-management').textContent = creditData.APPROVED_MANAGEMENT || 'N/A';
            document.getElementById('crn-attachment').textContent = creditData.CRN_ATTACHMENT || 'N/A';
            document.getElementById('bank-certificate').textContent = creditData.BANK_CERTIFICATE || 'N/A';
            document.getElementById('owner-id').textContent = creditData.OWNER_ID || 'N/A';

        }
    </script>

</body>

</html>