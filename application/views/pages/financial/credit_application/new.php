<?php 
 $username = $loggedInUser['username'] ?? 'Guest'; 
$usertype = $loggedInUser['userrole'] ?? 'Guest';
 $user_id = $loggedInUser['userid'] ?? ''; 
//  $email = $loggedInUser['email'] ?? 'user@guest.crm'; 
 ?> 

<script>
    const base_url = "<?= base_url(); ?>";
</script>


<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">

        <form id="form" class="form d-flex flex-column " method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <!--begin::PAGE CONTENT GOES FROM HERE-->

            <!-- first card -->
            <div class="card mb-3">
                <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-12">
                    <h2>Customer & Credit Information</h2>
                   <!-- Print button -->
                    <div class="action-buttons">
                        <button
                            type="button"
                            class="btn btn-sm btn-info pl-2 pr-2 pt-1 pb-1"
                            onclick="printApplication(document.getElementById('HEADER_ID').value, document.getElementById('UUID').value, 'credit_application')">
                            <i class="bi bi-printer"></i> Print
                        </button>
                    </div>
                </div> 

                    <h4 class="mb-12">Customer Information</h4>
                    
                    <div class="row mb-10">

                        <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
                        <input type="hidden" name="HEADER_ID" id="HEADER_ID" value="">

                        <div class="col-md-12 mb-8">
                            <div class="row">
                            <div class="col-md-6 d-flex align-items-center">
                          <!-- Application Date -->
                          <div class="col-md-4">
                            <label for="APPLICATION_DATE" class="fs-6 fw-normal required me-2">Application Date <span class="arabic-label">(تاريخ)</span></label>
                            </div>
                            <div class="col-md-8">
                            <input type="date" class="form-control" id="APPLICATION_DATE" autocomplete="off" name="APPLICATION_DATE" value="">
                            <span class="text-danger err-lbl" id="lbl-APPLICATION_DATE"></span>
                        </div>
                        </div>

                        <!-- Customer Number -->
                        <div class="col-md-6 d-flex align-items-center b-4">
                        <div class="col-md-5">
                            <label for="CUSTOMER_ID" class="fs-6 fw-normal required me-2">Customer Number <span class="arabic-label">(رقم العميل)</span></label>
                            </div>
                            <div class="col-md-7">
                            <input type="text" class="form-control" id="CUSTOMER_ID" autocomplete="off" name="CUSTOMER_ID" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                            <span class="text-danger err-lbl" id="lbl-CUSTOMER_ID"></span>
                        </div>
                        </div>
                        </div>
                        </div>

                        <div class="col-md-12 mb-3">

                            <div class="row">
                                <div class="col-md-2 d-flex align-items-center justify-content-start">
                                    <label for="CUSTOMER_NAME" class="fs-6 fw-normal required">Customer Name <span class="arabic-label">(اسم العميل)</span></span> </label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="CUSTOMER_NAME" autocomplete="off" name="CUSTOMER_NAME" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                    <span class="text-danger err-lbl" id="lbl-CUSTOMER_NAME"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <h4 class="mb-8">Credit Application for Business Account Details</h4>
                    <div class="row mb-13">
                        <div class="col-md-6">
                            <h6 class="fs-6 fw-normal">
                                We request that Zamil Plastic Industries Ltd. open in our name a
                                Credit Account Facility , to cover the purchased materials and
                                products as we would like a <span class="fw-normal">Credit Limit</span> with following details.
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fs-4 fw-normal text-right">
                                نطلب من شركة الزامل للصناعات البالستيكية احملدودة أن تفتح بأ مسنا حساب
                                الائتمان اآليت بيانه، لتغطية املواد واملنتجات املشتراة كما نود ان يكون حد
                                الائتمان كحد أقصى
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="CREDIT_VALUE" class="fs-6 fw-normal required">Credit in SAR <span class="arabic-label">(ريال سعودي)</span> </label>
                            </div>
                            <div class="col-md-4">
                                <input placeholder="Enter Credit Limit you want" type="text" class="form-control" id="CREDIT_VALUE" autocomplete="off" name="CREDIT_VALUE" value="" oninput="numToWords(this)" >
                                <span class="text-danger err-lbl" id="lbl-CREDIT_VALUE"></span>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="CREDIT_IN_WORDS" class="fs-6 fw-normal required">Credit in Words <span class="arabic-label">(بالكلمات) </span> </label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" placeholder="Credit Limit in Words" class="form-control" id="CREDIT_IN_WORDS" autocomplete="off" name="CREDIT_IN_WORDS" value="">
                                <span class="text-danger err-lbl" id="lbl-CREDIT_IN_WORDS"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="WITHIN_DAYS" class="fs-6 fw-normal required">Within Days <span class="arabic-label">(خلال أيام)</span></label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="WITHIN_DAYS" autocomplete="off" name="WITHIN_DAYS" value="" placeholder="Enter number of days in Number">
                                <span class="text-danger err-lbl" id="lbl-WITHIN_DAYS"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label fs-7">We undertake to settle all our invoices within given days <span class="arabic-text">(ونتعهد بتسوية جميع فواتيرنا خلال أيام معينة)</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="APPLICANT_COMMENT" class="fs-6 fw-normal">Applicant's comment <span class="arabic-label">(تعليق مقدم الطلب)</span></label>
                            </div>
                            <div class="col-md-9">
                                <textarea rows="5" placeholder="Write your comments here ..." class="form-control" id="APPLICANT_COMMENT" autocomplete="off" name="APPLICANT_COMMENT"></textarea>
                                <span class="text-danger err-lbl" id="lbl-APPLICANT_COMMENT"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- second card -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <h2 class="mb-15">Contact Information </h2>
                        <!-- First half left side -->
                        <div class="col-md-6 mb-10">
                            <h4 class="mb-10">Company & Contact Information</h4>

                            <div class="mb-3 row">
                                <label for="COMPANY_NAME" class="col-md-4 col-form-label fw-normal required">Company Name <span class="arabic-label">(اسم الشركة)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="COMPANY_NAME" name="COMPANY_NAME" placeholder="Enter your company name">
                                    <span class="text-danger err-lbl" id="lbl-COMPANY_NAME"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="CONTACT_PERSON" class="col-md-4 col-form-label fw-normal required">Contact Person <span class="arabic-label">(الاسم الكامل)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="CONTACT_PERSON" name="CONTACT_PERSON" placeholder="Enter Full Name">
                                    <span class="text-danger err-lbl" id="lbl-CONTACT_PERSON"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="CONTACT_PERSON_TITLE" class="col-md-4 col-form-label fw-normal required">Contact Person Title <span class="arabic-label">(المسمى الوظيفي)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="CONTACT_PERSON_TITLE" name="CONTACT_PERSON_TITLE" placeholder="Contact Person Designation in Company">
                                    <span class="text-danger err-lbl" id="lbl-CONTACT_PERSON_TITLE"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="CONTACT_EMAIL" class="col-md-4 col-form-label fw-normal required">Contact Email <span class="arabic-label">(البريد الإلكتروني)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="CONTACT_EMAIL" name="CONTACT_EMAIL" placeholder="Contact Email for communication">
                                    <span class="text-danger err-lbl" id="lbl-CONTACT_EMAIL"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                            <label for="PHONE" class="col-md-4 col-form-label fw-normal required">Phone <span class="arabic-label">(رقم الهاتف)</span></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!-- Country Code -->
                                    <input type="text" class="form-control" id="PHONE" name="PHONE" placeholder="Enter Phone with Country Code">
                                </div>
                                <small class="form-text text-muted">
                                    Format: +<country code><phone number> (e.g., +14155552671)
                                </small>
                                <span class="text-danger err-lbl" id="lbl-PHONE"></span>
                            </div>
                        </div>


                            <div class="mb-3 row">
                                <label for="FAX" class="col-md-4 col-form-label fw-normal">FAX <span class="arabic-label">(رقم الفاكس)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="FAX" name="FAX" placeholder="Provide Fax number">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="COMPANY_EMAIL" class="col-md-4 col-form-label fw-normal required">Company Email <span class="arabic-label">(البريد الإلكتروني للشركة)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="COMPANY_EMAIL" name="COMPANY_EMAIL" placeholder="Enter Company Email Address">
                                    <span class="text-danger err-lbl" id="lbl-COMPANY_EMAIL"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Second half right side -->
                        <div class="col-md-6 mb-10">
                            <h4 class="mb-10">Address Details</h4>

                            <div class="mb-3 row">
                                <label for="CITY" class="col-md-4 col-form-label fw-normal required">City: <span class="arabic-label">(اسم المدينة)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="CITY" name="CITY" placeholder="Enter City name">
                                    <span class="text-danger err-lbl" id="lbl-CITY"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="STATE" class="col-md-4 col-form-label fw-normal required">State/Province: <span class="arabic-label">(الولاية/المقاطعة)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="STATE" name="STATE" placeholder="Enter State or Province Name">
                                    <span class="text-danger err-lbl" id="lbl-STATE"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="COUNTRY" class="col-md-4 col-form-label fw-normal required">Country: <span class="arabic-label">(أمة)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="COUNTRY" name="COUNTRY" placeholder="Enter Country Name">
                                    <span class="text-danger err-lbl" id="lbl-COUNTRY"></span>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="ZIP_CODE" class="col-md-4 col-form-label fw-normal required">Zip Code: <span class="arabic-label">(الرمز البريدي)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="ZIP_CODE" name="ZIP_CODE" placeholder="Enter Zip Code">
                                    <span class="text-danger err-lbl" id="lbl-ZIP_CODE"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                        <label for="ADDRESS_SPAN" class="col-md-4 col-form-label fw-normal required">
                            How long at current address? 
                            <span class="arabic-label">(كم من مدة وجود الشركة في العنوان الحالي؟)</span>
                        </label>
                        <div class="col-md-8 mt-4">
                            <div class="d-flex align-items-center mb-2">
                                <input type="radio" class="required-input" id="ADDRESS_LESS_1" autocomplete="off" name="ADDRESS_SPAN" value="Less than 1 Year">
                                <label for="ADDRESS_LESS_1" class="mb-0 pb-0 ml-2">Less than 1 Year - أقل من 1 سنة</label>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <input type="radio" class="required-input" id="ADDRESS_LESS_5" autocomplete="off" name="ADDRESS_SPAN" value="Less than 5 Years">
                                <label for="ADDRESS_LESS_5" class="mb-0 pb-0 ml-2">Less than 5 Years - أقل من 5 سنوات</label>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <input type="radio" class="required-input" id="ADDRESS_LESS_10" autocomplete="off" name="ADDRESS_SPAN" value="Less than 10 Years">
                                <label for="ADDRESS_LESS_10" class="mb-0 pb-0 ml-2">Less than 10 Years - أقل من 10 سنوات</label>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <input type="radio" class="required-input" id="ADDRESS_MORE_10" autocomplete="off" name="ADDRESS_SPAN" value="More than 10 Years">
                                <label for="ADDRESS_MORE_10" class="mb-0 pb-0 ml-2">More than 10 Years - أكثر من 10 سنوات</label>
                            </div>
                            <span class="text-danger err-lbl" id="lbl-ADDRESS_SPAN"></span>
                        </div>
                    </div>


                        </div>
                    </div>

                    <div class="row">
                        <!-- First half left side -->
                        <div class="col-md-6">
                            <h4 class="mb-10">Business Information</h4>

                            <div class="mb-3 row">
                                <label for="BUSINESS_START_DATE" class="col-md-4 col-form-label fw-normal required">Business Started <span class="arabic-label">(تاريخ بدء النشاط)</span></label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control" id="BUSINESS_START_DATE" autocomplete="off" name="BUSINESS_START_DATE" value="">
                                    <span class="text-danger err-lbl" id="lbl-BUSINESS_START_DATE"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="BUSINESS_TYPE" class="col-md-4 col-form-label fw-normal required">Business Type <span class="arabic-label">(نوع الاعمال)</span></label>
                                <div class="col-md-8 mt-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <input type="radio" class="required-input" id="SOLE" autocomplete="off" name="BUSINESS_TYPE" value="Sole proprietorship">
                                        <label for="SOLE" class="mb-0 pb-0 ml-2">Sole proprietorship - ملكية فردية</label>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <input type="radio" class="required-input" id="PARTNERSHIP" autocomplete="off" name="BUSINESS_TYPE" value="Partnership">
                                        <label for="PARTNERSHIP" class="mb-0 pb-0 ml-2">Partnership - شراكة</label>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <input type="radio" class="required-input" id="CORPORATION" autocomplete="off" name="BUSINESS_TYPE" value="Corporation">
                                        <label for="CORPORATION" class="mb-0 pb-0 ml-2">Corporation - شركة</label>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <input type="radio" class="required-input" id="OTHER" autocomplete="off" name="BUSINESS_TYPE" value="Other">
                                        <label for="OTHER" class="mb-0 pb-0 ml-2">Other - آخرى</label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-BUSINESS_TYPE"></span>
                                </div>
                            </div>


                        </div>

                        <!-- Second half right side -->
                        <div class="col-md-6">
                            <h4 class="mb-10">Bank Details</h4>

                            <div class="mb-3 row">
                                <label for="BANK_NAME" class="col-md-4 col-form-label fw-normal required">Bank Name: <span class="arabic-label">(اسم البنك)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="BANK_NAME" autocomplete="off" name="BANK_NAME" value="" placeholder="Enter Bank Name">
                                    <span class="text-danger err-lbl" id="lbl-BANK_NAME"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="BANK_LOCATION" class="col-md-4 col-form-label fw-normal required">Bank Location: <span class="arabic-label">(موقع البنك – فرع)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="BANK_LOCATION" autocomplete="off" name="BANK_LOCATION" value="" placeholder="Enter Bank Located at">
                                    <span class="text-danger err-lbl" id="lbl-BANK_LOCATION"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="ACCOUNT_NUMBER" class="col-md-4 col-form-label fw-normal required">Acc. Number: <span class="arabic-label">(رقم الحساب المصرفي)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="ACCOUNT_NUMBER" autocomplete="off" name="ACCOUNT_NUMBER" value="" placeholder="Enter Company Bank Account Number">
                                    <span class="text-danger err-lbl" id="lbl-ACCOUNT_NUMBER"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="IBAN_NUMBER" class="col-md-4 col-form-label fw-normal required">IBAN Number: <span class="arabic-label">(IBAN رقم)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="IBAN_NUMBER" autocomplete="off" name="IBAN_NUMBER" value="" placeholder="Enter IBAN Number">
                                    <span class="text-danger err-lbl" id="lbl-IBAN_NUMBER"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="SWIFT_CODE" class="col-md-4 col-form-label fw-normal required">Swift Code: <span class="arabic-label">(رقم السويفت كود)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="SWIFT_CODE" autocomplete="off" name="SWIFT_CODE" value="" placeholder="Enter Swift Code">
                                    <span class="text-danger err-lbl" id="lbl-SWIFT_CODE"></span>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <!-- third card -->
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="mb-15">Formal Information</h2>
                    <h4 class="mb-10">Registration Details</h4>

                    <div class="col-md-12 mb-3">
                        <div class="row mb-4">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="col-md-6">
                                <label for="CRN_NUMBER" class="fs-6 fw-normal required">CRN Number <span class="arabic-label">(رقم السجل التجاري)</span> </label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="CRN_NUMBER" autocomplete="off" name="CRN_NUMBER" value="" placeholder="Enter Your Customer Registration Number">
                                <span class="text-danger err-lbl" id="lbl-CRN_NUMBER"></span>
                            </div>
                            </div>

                            <div class="col-md-6 d-flex align-items-center">
                            <div class="col-md-6">
                                <label for="DATE_OF_ISSUANCE" class="fs-6 fw-normal required">Date of Issuance <span class="arabic-label">(تاريخ الانتهاء)</span> </label>
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" id="DATE_OF_ISSUANCE" autocomplete="off" name="DATE_OF_ISSUANCE" value="">
                                <span class="text-danger err-lbl" id="lbl-DATE_OF_ISSUANCE"></span>
                            </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12 mb-3">

                    <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                            <div class="col-md-6">
                                <label for="COMPANY_LOCATION" class="fs-6 fw-normal required">Company Location <span class="arabic-label">(موقع الشركة)</span> </label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="COMPANY_LOCATION" autocomplete="off" name="COMPANY_LOCATION" placeholder="Company Location" value="">
                                <span class="text-danger err-lbl" id="lbl-COMPANY_LOCATION"></span>
                            </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                            <div class="col-md-6">
                                <label for="DATE_OF_EXPIRY" class="fs-6 fw-normal required">Date of Expiry <span class="arabic-label">(تاريخ االنتهاء) </span> </label>
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" id="DATE_OF_EXPIRY" autocomplete="off" name="DATE_OF_EXPIRY" value="">
                                <span class="text-danger err-lbl" id="lbl-DATE_OF_EXPIRY"></span>
                            </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mb-10 mt-10">Company Ownership Details</h4>

                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="PAID_UP_CAPITAL" class="fs-6 fw-normal required">Paid up Capital <span class="arabic-label">(رأس المال المدفوع)</span> </label>
                            </div>
                            <div class="col-md-3">
                                <input placeholder="Enter Paid Up Capital Amount" type="text" class="form-control" id="PAID_UP_CAPITAL" autocomplete="off" name="PAID_UP_CAPITAL" value="">
                                <span class="text-danger err-lbl" id="lbl-PAID_UP_CAPITAL"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="COMPANY_OWNER" class="fs-6 fw-normal required">Company Owner Name <span class="arabic-label">(اسم المالك/مالك الشركة)</span> </label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="Enter Company Owner Name" class="form-control" id="COMPANY_OWNER" autocomplete="off" name="COMPANY_OWNER" value="">
                                <span class="text-danger err-lbl" id="lbl-COMPANY_OWNER"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="PERCENTAGE_OWNER" class="fs-6 fw-normal required">% of Ownership <span class="arabic-label">(نسبة الملكية % لكل شريك)</span> </label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="PERCENTAGE_OWNER" autocomplete="off" name="PERCENTAGE_OWNER" value="" placeholder="Percentage Of Ownership">
                                <span class="text-danger err-lbl" id="lbl-PERCENTAGE_OWNER"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label">Provide % of ownership of each owner in the company <span class="arabic-text">(إعطاء نسبة ملكية كل مالك في الشركة)</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="TOP_MANAGER" class="fs-6 fw-normal required">Company Top Manager <span class="arabic-label">(المسئول األول بألشركة) </span> </label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control " id="TOP_MANAGER" autocomplete="off" name="TOP_MANAGER" value="" placeholder="Authorized personel name">
                                <span class="text-danger err-lbl" id="lbl-TOP_MANAGER"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label fs-7">Specify Authorized Personnel <span class="arabic-text">(المسئول األول بألشركة)</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-15">
                        <!-- First half left side -->
                        <div class="col-md-6">
                            <h4 class="mb-10">Personnel Authorized Signature for Purchasing</h4>

                            <div class="mb-3 row">
                                <label for="SIGN_NAME" class="col-md-4 col-form-label fw-normal required">Name <span class="arabic-label">(اسم)</span></label>
                                <div class="col-md-8">
                                    <input placeholder="Enter Authorized person name" type="text" class="form-control" id="SIGN_NAME" autocomplete="off" name="SIGN_NAME" value="">
                                    <span class="text-danger err-lbl" id="lbl-SIGN_NAME"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="SIGN_POSITION" class="col-md-4 col-form-label fw-normal required">Position <span class="arabic-label">(المسمى الوظيفي) </span></label>
                                <div class="col-md-8">
                                    <input placeholder="Enter Authorized person position" type="text" class="form-control" id="SIGN_POSITION" autocomplete="off" name="SIGN_POSITION" value="">
                                    <span class="text-danger err-lbl" id="lbl-SIGN_POSITION"></span>
                                </div>
                            </div>


                            <div class="mb-3 row">
                                <label for="SIGN_SPECIMEN" class="col-md-4 col-form-label fw-normal required">Signature Specimen <span class="arabic-label">(نموذج التوقيع)</span></label>
                                <div class="col-md-8">
                                    <input placeholder="Signature Specimen Details" type="text" class="form-control" id="SIGN_SPECIMEN" autocomplete="off" name="SIGN_SPECIMEN" value="">
                                    <span class="text-danger err-lbl" id="lbl-SIGN_SPECIMEN"></span>
                                </div>
                            </div>

                        </div>

                        <!-- Second half right side -->
                        <div class="col-md-6">
                            <h4 class="mb-10">Company Managers Details</h4>

                            <div class="mb-3 row">
                                <label for="BUS_ACTIVITIES" class="col-md-4 col-form-label fw-normal required">Business Activities <span class="arabic-label">(النشاط التجارية)</span></label>
                                <div class="col-md-8">
                                    <input placeholder="Write down business activities" type="text" class="form-control" id="BUS_ACTIVITIES" autocomplete="off" name="BUS_ACTIVITIES" value="">
                                    <span class="text-danger err-lbl" id="lbl-BUS_ACTIVITIES"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="GM_NAME" class="col-md-4 col-form-label fw-normal required">Gen. Manager <span class="arabic-label">(إسم و رقم المدير العام)</span></label>
                                <div class="col-md-8">
                                    <input placeholder="Enter General Manager Full Name" type="text" class="form-control" id="GM_NAME" autocomplete="off" name="GM_NAME" value="">
                                    <span class="text-danger err-lbl" id="lbl-GM_NAME"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="PUR_MGR_NAME" class="col-md-4 col-form-label fw-normal required">Pur Manager <span class="arabic-label">(إسم و رقم مدير المشتريات)</span></label>
                                <div class="col-md-8">
                                    <input placeholder="Enter Purchasing Manager Full Name" type="text" class="form-control" id="PUR_MGR_NAME" autocomplete="off" name="PUR_MGR_NAME" value="">
                                    <span class="text-danger err-lbl" id="lbl-PUR_MGR_NAME"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="FIN_MGR_NAME" class="col-md-4 col-form-label fw-normal required">Fin Manager <span class="arabic-label">(إسم و رقم المدير المالي)</span></label>
                                <div class="col-md-8">
                                    <input placeholder="Enter Finanace Manager Full Name" type="text" class="form-control" id="FIN_MGR_NAME" autocomplete="off" name="FIN_MGR_NAME" value="">
                                    <span class="text-danger err-lbl" id="lbl-FIN_MGR_NAME"></span>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>

            <!-- fourth card -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="mb-10"> Agreement - إتفاقية</h2>
                        </div>

                        <div class="col-md-6 mt-2">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <h5 class="fw-normal text-justify">
                                        <i class="bi bi-arrow-right mr-2"></i> <span class="fw-normal">Zamil Plastic Industries Limited</span>, reserves the right to withdraw the credit facility in full at its sole discretion at any time seen fit .
                                    </h5>
                                </li>
                                <li class="mb-3">
                                    <h5 class="fw-normal text-justify">
                                        <i class="bi bi-arrow-right mr-2"></i> Supply of material will be placed on hold by <span class="fw-normal">Zamil Plastic Industries Limited</span>, if the credit limit exceeds the agreed number of days. This action may also be taken if the customer does not settle his account with in the time stipulated on page 1&2 of this contract
                                    </h5>
                                </li>
                                <li>
                                    <h5 class="fw-normal text-justify">
                                        <i class="bi bi-arrow-right mr-2"></i> By submitting this application, you authorize <span class="fw-normal">Zamil Plastic Industries Limited</span> to make inquiries into the banking and business/trade references that you have supplied.
                                    </h5>
                                </li>
                            </ul>
                        </div>


                        <div class="col-md-6 mt-2">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <h4 class="text-right fw-normal" style="font-size: 15px;">
                                        إن إدارة شركة الزامل للصناعات البالستيكية المحدودة، تحتفظ لنفسها بالحق في سحب بعض/كل التسهيالت االئتمانية حسب تقديرها في أي وقت
                                    </h4>
                                </li>
                                <li class="mb-3">
                                    <h4 class="text-right fw-normal" style="font-size: 15px;">
                                        سيتم تعليق توريد المواد من قبل شركة الزامل للصناعات البالستيكية المحدودة، إذا تجاوزت الشركة عدد أاليام المتفق عليها كحد ائتماني .2ويمكن أيضا أن تتخذ هذا اإلجراء إذا كان الزبون ال يدفع فواتير حسابه في الوقت المحدد على الصفحات 1 و 2 من هذا العقد
                                    </h4>
                                </li>
                                <li class="mb-3">
                                    <h4 class="text-right fw-normal" style="font-size: 15px;">
                                        من خالل تقديم هذا الطلب،نفوض شركة الزامل للصناعات البالستيكية المحدودة .إلجراء التأكيدات الالزمة في األعمال المصرفية و مراجعة األعمال التجارية التي تقوم الشركة بتوفيرها.
                                    </h4>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <!-- fifth card -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <h2 class="mb-10"> Signatures</h2>
                        <!-- First half left side -->
                        <div class="col-md-6">
                            <h4 class="py-2 text-center mb-8">
                                Zamil Plastic Industries Limited <br>
                                الزامل للصناعات البالستيكية المحدودة
                            </h4>

                            <div class="mb-3 row">
                                <label for="ZPIL_SIGN" class="col-md-4 col-form-label fw-normal required">Signature <span class="arabic-label">(التوقيع)</span></label>
                                <div class="col-md-8">
                                    <input placeholder="Signature" type="text" class="form-control" id="ZPIL_SIGN" autocomplete="off" name="ZPIL_SIGN"  value="">
                                    <span class="text-danger err-lbl" id="lbl-ZPIL_SIGN"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="ZPIL_SIGNATORY_NAME" class="col-md-4 col-form-label fw-normal required">Signatory Name <span class="arabic-label">(اسم صاحب التوقيع)</span></label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="Signatory Name" class="form-control" id="ZPIL_SIGNATORY_NAME" autocomplete="off" name="ZPIL_SIGNATORY_NAME" value="">
                                    <span class="text-danger err-lbl" id="lbl-ZPIL_SIGNATORY_NAME"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="ZPIL_SIGN_POSN" class="col-md-4 col-form-label fw-normal required">Position Title <span class="arabic-label">(المسمى الوظيفي) </span></label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="Position Title" class="form-control" id="ZPIL_SIGN_POSN" autocomplete="off" name="ZPIL_SIGN_POSN" value="">
                                    <span class="text-danger err-lbl" id="lbl-ZPIL_SIGN_POSN"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="ZPIL_DATE" class="col-md-4 col-form-label fw-normal required">Date <span class="arabic-label">(تاريخ) </span></label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control" id="ZPIL_DATE" autocomplete="off" name="ZPIL_DATE" value="">
                                    <span class="text-danger err-lbl" id="lbl-ZPIL_DATE"></span>
                                </div>
                            </div>

                        </div>

                        <!-- Second half right side -->
                        <div class="col-md-6">
                            <h4 class="py-2 text-center mb-8">
                                Customer Signature Details <br>
                                العميل
                            </h4>


                            <div class="mb-3 row">
                                <label for="CLIENT_SIGN" class="col-md-4 col-form-label fw-normal required">Signature <span class="arabic-label">(التوقيع) </span></label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="Signature" class="form-control" id="CLIENT_SIGN" autocomplete="off" name="CLIENT_SIGN" value="">
                                    <span class="text-danger err-lbl" id="lbl-CLIENT_SIGN"></span>
                                </div>
                            </div>


                            <div class="mb-3 row">
                                <label for="CLIENT_STAMP" class="col-md-4 col-form-label fw-normal required">Company Stamp <span class="arabic-label">(ختم الشركة) </span></label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="Company Stamp" class="form-control" id="CLIENT_STAMP" autocomplete="off" name="CLIENT_STAMP" value="">
                                    <span class="text-danger err-lbl" id="lbl-CLIENT_STAMP"></span>
                                </div>
                            </div>


                            <div class="mb-3 row">
                                <label for="CLIENT_SIGN_NAME" class="col-md-4 col-form-label fw-normal required">Signatory Name <span class="arabic-label">(اسم صاحب التوقيع) </span></label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="Signatory Name" class="form-control" id="CLIENT_SIGN_NAME" autocomplete="off" name="CLIENT_SIGN_NAME" value="">
                                    <span class="text-danger err-lbl" id="lbl-CLIENT_SIGN_NAME"></span>
                                </div>
                            </div>


                            <div class="mb-3 row">
                                <label for="CLIENT_SIGN_DATE" class="col-md-4 col-form-label fw-normal required">Date <span class="arabic-label">(تاريخ) </span></label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control" id="CLIENT_SIGN_DATE" autocomplete="off" name="CLIENT_SIGN_DATE" value="">
                                    <span class="text-danger err-lbl" id="lbl-CLIENT_SIGN_DATE"></span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="CHAMBER_OF_COMMERCE" class="col-md-4 col-form-label fw-normal required">Chamber of Commerce Stamp <span class="arabic-label">(ختم الغرفة التجارية)</span></label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="Chamber of Commerce Stamp" class="form-control" id="CHAMBER_OF_COMMERCE" autocomplete="off" name="CHAMBER_OF_COMMERCE" value="">
                                    <span class="text-danger err-lbl" id="lbl-CHAMBER_OF_COMMERCE"></span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <!-- Sixth Card for Admin Only -->
            <?php if ($usertype === 'admin'): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h2 class="mb-10">For Zamil Only</h2>

                        <div class="row mb-6">
                            <h4 class="mb-8">Reference Details - مرجع</h4>

                            <div class="col-md-12 mb-2">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-start">
                                        <label for="DIR_SALES_COMMENTS" class="fs-6 fw-normal required">Direct Salesman Comments <span class="arabic-label">(تعليقات موظف المبيعات)</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="DIR_SALES_COMMENTS" autocomplete="off" name="DIR_SALES_COMMENTS" value="" placeholder="Write your comments.....">
                                        <span class="text-danger err-lbl" id="lbl-DIR_SALES_COMMENTS"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-start">
                                        <label for="SALES_MGR_COMMENTS" class="fs-6 fw-normal required">Sales Manager Comments<span class="arabic-label">(تعليقات مدير المبيعات)</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="SALES_MGR_COMMENTS" autocomplete="off" name="SALES_MGR_COMMENTS" value="" placeholder="Write your comments.....">
                                        <span class="text-danger err-lbl" id="lbl-SALES_MGR_COMMENTS"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12 mb-2">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-start">
                                        <label for="GM_COMMENTS" class="fs-6 fw-normal required">General Manager Comments <span class="arabic-label">(تعليقات مدير عام)</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="GM_COMMENTS" autocomplete="off" name="GM_COMMENTS" value="" placeholder="Write your comments.....">
                                        <span class="text-danger err-lbl" id="lbl-GM_COMMENTS"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12 mb-2">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-start">
                                        <label for="CREDIT_DIV_COMMENTS" class="fs-6 fw-normal required">Credit Division Comments <span class="arabic-label">(تعليقات قسم الائتمان)</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="CREDIT_DIV_COMMENTS" autocomplete="off" name="CREDIT_DIV_COMMENTS" value="" placeholder="Write your comments.....">
                                        <span class="text-danger err-lbl" id="lbl-CREDIT_DIV_COMMENTS"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12 mb-2">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-start">
                                        <label for="FIN_MGR_COMMENTS" class="fs-6 fw-normal required">Finance Manager Comments <span class="arabic-label">(تعليقات المدير المالي)</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="FIN_MGR_COMMENTS" autocomplete="off" name="FIN_MGR_COMMENTS" value="" placeholder="Write your comments.....">
                                        <span class="text-danger err-lbl" id="lbl-FIN_MGR_COMMENTS"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-start">
                                        <label for="MGMT_COMMENTS" class="fs-6 fw-normal required">Management Comments <span class="arabic-label">(تعليقات إدارة)</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="MGMT_COMMENTS" autocomplete="off" name="MGMT_COMMENTS" value="" placeholder="Write your comments.....">
                                        <span class="text-danger err-lbl" id="lbl-MGMT_COMMENTS"></span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-10 mb-10">
                            <h4 class="mb-12">Approved Credit Limit Details - تفاصيل الحد الائتماني المعتمد</h4>
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    <div class="col-md-6 d-flex align-items-center mb-4">
                                    <div class="col-md-6">
                                        <label for="REC_CREDIT_LIMIT" class="fs-6 fw-normal required">Final Recommended Credit Limit SAR <span class="arabic-label">(تفاصيل الحد الائتماني المعتمد)</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="REC_CREDIT_LIMIT" autocomplete="off" name="REC_CREDIT_LIMIT" value="" placeholder="Enter Credit Limit">
                                        <span class="text-danger err-lbl" id="lbl-REC_CREDIT_LIMIT"></span>
                                    </div>
                                </div>
            
                                    <div class="col-md-6 d-flex align-items-center mb-4">
                                    <div class="col-md-7">
                                        <label for="APPROVED_FINANCE" class="fs-6 fw-normal required">Approved by Finance <span class="arabic-label">(الموافقة المالي)</span></label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="radio" class="" id="FINANCE_YES" autocomplete="off" name="APPROVED_FINANCE" value="yes"> Yes
                                        <input type="radio" class="ml-4" id="FINANCE_NO" autocomplete="off" name="APPROVED_FINANCE" value="no"> No
                                        <span class="text-danger err-lbl" id="lbl-APPROVED_FINANCE"></span>
                                    </div>
                                </div>
                                </div>

                            </div>


                            <div class="col-md-12 mb-3">
                                <div class="row">
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="REC_CREDIT_PERIOD" class="fs-6 fw-normal required">Recom. Credit Period (# of Days) <span class="arabic-label">(فترة الائتمان المسموح به (# الأيام))</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="REC_CREDIT_PERIOD" autocomplete="off" name="REC_CREDIT_PERIOD" value="" placeholder="Enter Number Of Days">
                                        <span class="text-danger err-lbl" id="lbl-REC_CREDIT_PERIOD"></span>
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="col-md-7">
                                        <label for="APPROVED_MANAGEMENT" class="fs-6 fw-normal required">Approved by Management <span class="arabic-label">(الموافقة الإدارية)</span></label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="radio" class="" id="APPROVED_YES" autocomplete="off" name="APPROVED_MANAGEMENT" value="yes"> Yes
                                        <input type="radio" class="ml-4" id="APPROVED_NO" autocomplete="off" name="APPROVED_MANAGEMENT" value="no"> No
                                        <span class="text-danger err-lbl" id="lbl-APPROVED_MANAGEMENT"></span>
                                    </div>
                                </div>
                                </div>
                                </div>

                            </div>

                        <div class="row mt-8">
                            <h4 class="mb-8">Required Attachments - المرفقات المطلوبة</h4>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-start">
                                        <label for="ATTACHMENT" class="fs-6 fw-normal required">Commercial Registration Copy <span class="arabic-label"></span></label>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="ATTACHMENT" name="CRN_ATTACHMENT" class="upload-box d-flex align-items-center btn-outline btn-outline-dashed btn btn-active-light-primary"  onclick="document.getElementById('file-input-ATTACHMENT').click();">
                                            <i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary"></i>
                                            <p class="mb-0">Click to upload files</p>
                                            <input data-field="ATTACHMENT" onchange="handleFileSelect(event, 'ATTACHMENT')" type="file" id="file-input-ATTACHMENT"  style="display:none;">
                                        </div>
                                        <div id="file-list-ATTACHMENT" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                        <div id="file-list-uploaded-ATTACHMENT" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                        <span class="text-danger err-lbl" id="lbl-CRN_ATTACHMENT"></span>
                                    </div>
                                    
                                    <label class="col-md-4 arabic-text-size">صورة من السجل التجاري</label>
                                </div>
                
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-start">
                                        <label for="CERTIFICATE" class="fs-6 fw-normal required">Bank Certificate Original (stamped) <span class="arabic-label"></span></label>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="CERTIFICATE" name="BANK_CERTIFICATE" class="upload-box d-flex align-items-center btn-outline btn-outline-dashed btn btn-active-light-primary" onclick="document.getElementById('file-input-CERTIFICATE').click();">
                                            <i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary"></i>
                                            <p class="mb-0">Click to upload files</p>
                                            <input data-field="CERTIFICATE" onchange="handleFileSelect(event, 'CERTIFICATE')" type="file" id="file-input-CERTIFICATE"  style="display:none;">
                                        </div>
                                        <div id="file-list-CERTIFICATE" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                        <div id="file-list-uploaded-CERTIFICATE" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                        <span class="text-danger err-lbl" id="lbl-BANK_CERTIFICATE"></span>
                                    </div>
                                
                                    <label class="col-md-4 arabic-text-size">أصل شهادة بنكية مختومة ببيانات الحساب</label>
                                </div>
                               
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-start">
                                        <label for="OWNER" class="fs-6 fw-normal required">Owner ID copy for Company Use only <span class="arabic-label"></span></label>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="OWNER" name="OWNER_ID" class="upload-box d-flex align-items-center btn-outline btn-outline-dashed btn btn-active-light-primary" onclick="document.getElementById('file-input-OWNER').click();">
                                            <i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary"></i>
                                            <p class="mb-0">Click to upload files</p>
                                            <input data-field="OWNER" onchange="handleFileSelect(event, 'OWNER')" type="file" id="file-input-OWNER"  style="display:none;">
                                        </div>
                                        <div id="file-list-OWNER" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                        <div id="file-list-uploaded-OWNER" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                        <span class="text-danger err-lbl" id="lbl-OWNER_ID"></span>
                                    </div>
                                    
                                    <label class="col-md-4 arabic-text-size">صورة الهوية للمالك الشركة استخدم فقط</label>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            <?php endif; ?>

            <!--end::PAGE CONTENT GOES FROM HERE-->

            <div class="d-flex justify-content-end mb-10 mt-4">
                <button class="btn btn-success" id="submit-btn" type="submit">Submit Application</button>
            </div>

        </form>
    </div>
</div>
<!--end::PAGE CONTAINER-->
<!--end::PAGE CONTAINER-->
<?php
$this->load->view('loaders/full-page-loader');
?>