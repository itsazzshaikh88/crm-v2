<?php 
 $username = $loggedInUser['username'] ?? 'Guest'; 
$usertype = $loggedInUser['usertype'] ?? 'Guest';
 $email = $loggedInUser['email'] ?? 'user@guest.crm'; 
 ?> 

<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start  container-xxl ">
    <div class="content flex-row-fluid" id="kt_content">
        <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
        <div class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework">
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-2">
                <div class="card" id="kt_profile_details_view">
                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-9 mb-8">
                                        <h1 id="lbl-APPLICATION_NUMBER" class="border-bottom border-bottom-dashed border-secondary text-muted pb-4"></h1>
                                    </div>
                                    <div class="col-md-3 mb-8">
                                        <h1 id="lbl-APPLICATION_DATE" class="border-bottom border-bottom-dashed border-secondary text-muted pb-4"></h1>
                                    </div>

                                </div>

                                <div class="row mb-7">
                                    <h4 class="fw-normal">Credit & Contact Information</h4>
                                </div>

                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Credit in SAR (ريال سعودي)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-2">
                                        <span class="fs-6" id="lbl-CREDIT_VALUE"></span>
                                    </div>
                                    <!--end::Col-->
                                     <!--begin::Label-->
                                     <label class="col-lg-2 fw-semibold text-muted text-start">Credit in Words (بالكلمات)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <span class="fs-6" id="lbl-CREDIT_IN_WORDS"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>

                                <!--end::Row-->


                                <!--begin::Row-->
                                <div class="row mb-7">
                                   <!--begin::Label-->
                                   <label class="col-lg-4 fw-semibold text-muted text-start">Within Days (خلال أيام)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fs-6" id="lbl-WITHIN_DAYS"></span>
                                    </div>
                                    <!--end::Col-->
                                   
                                </div>

                                <!--end::Row-->

                                 <!--begin::Row-->
                                 <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Applicant's comment (تعليق مقدم الطلب)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fs-6" id="lbl-APPLICANT_COMMENT"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                
                                 <!--begin::Row-->
                                 <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Contact Person (الاسم الكامل)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fs-6" id="lbl-CONTACT_PERSON"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>

                                 <!--begin::Row-->
                                 <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Contact Person Title (المسمى الوظيفي)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fs-6" id="lbl-CONTACT_PERSON_TITLE"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                 <!--begin::Row-->
                                 <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Contact Email (البريد الإلكتروني)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fs-6" id="lbl-CONTACT_EMAIL"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>

                                 <!--begin::Row-->
                                 <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">FAX (رقم الفاكس)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fs-6" id="lbl-FAX"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->


                                    <!--begin::Row-->
                                    <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Phone (رقم الهاتف)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-2">
                                        <span class="fs-6" id="lbl-PHONE"></span>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Label-->
                                    <label class="col-lg-2 fw-semibold text-muted text-start">Company Email:</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-4 ">
                                        <span class="fs-6" id="lbl-COMPANY_EMAIL"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                 


                                <div class="row mb-7">
                                    <h4 class="fw-normal">Business Information</h4>
                                </div>

                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Business Started (تاريخ بدء النشاط)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-BUSINESS_START_DATE"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                
                                  <!--begin::Row-->
                                  <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Business Type (نوع الاعمال)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-BUSINESS_TYPE"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->




                             

                            </div>


                            <div class="col-md-4">
                                <div class="d-print-none bg-light-primary rounded border-primary border border-dashed card-rounded min-w-md-350px p-9 mb-8">
                                    <!--begin::Title-->
                                    <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">Customer Details</h6>
                                    <!--end::Title-->

                                    <!--begin::Item-->
                                    <div class="mb-6">
                                        <div class="fw-semibold text-gray-600 fs-7">Company Name:</div>

                                        <div class="fw-bold text-gray-800 fs-6" id="lbl-COMPANY_NAME"></div>
                                    </div>
                                    <!--end::Item-->

                                    <!--begin::Full Name and Customer ID on the same line-->
                                    <div class="mb-6">
                                        <!-- Full Name -->
                                            <div class="fw-semibold text-gray-600 fs-7">Customer Name:</div>
                                            <div class="fw-bold text-gray-800 fs-6" id=""><?php echo $username ?></div>
                                        
                                    </div>
                                    <!--end::Full Name and Customer ID-->
                                    <!--begin::Item-->
                                    <div class="mb-6">
                                        <div class="fw-semibold text-gray-600 fs-7">Customer ID:</div>

                                        <div class="fw-bold text-gray-800 fs-6" id="lbl-CUSTOMER_ID">
                                        </div>
                                    </div>
                                    <!--end::Item-->

                                    <!--begin::Item-->
                                    <div class="mb-6">
                                        <div class="fw-semibold text-gray-600 fs-7">Email Address:</div>

                                        <div class="fw-bold text-gray-800 fs-6" id=""><?php echo $email ?></div>
                                    </div>
                                    <!--end::Item-->

                                </div>
                                <div class="notice d-flex flex-column rounded mb-9 p-4">
                                    <div class="fs-6 text-gray-700 mb-4">
                                        <h6>Attached Files</h6>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center justify-content-start gap-4" id="fileContainer">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                            <div class="row mb-7">
                                <!-- Business Information -->
                                <div class="col-md-6">
                                    <h4 class="fw-normal text-start">Address Details</h4>
                                </div>
                                <!-- Registration Details -->
                                <div class="col-md-6">
                                    <h4 class="fw-normal">Bank Details</h4>
                                </div>
                            </div>

                                    <!--begin::Row-->
                                    <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">City: (اسم المدينة)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-CITY"></span>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Bank Name: (اسم البنك)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-BANK_NAME">************</span>
                                        <button class="btn btn-link p-0 ms-2" id="toggle-eye-BANK_NAME" onclick="toggleBankDetails('BANK_NAME')">
                                            <i class="fas fa-eye" id="eye-icon-BANK_NAME"></i>
                                        </button>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <div class="row mb-7">
                                     <!--begin::Label-->
                                     <label class="col-lg-3 fw-semibold text-muted text-start">State/Province: (الولاية/المقاطعة)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-STATE"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Bank Location: (موقع البنك – فرع)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-BANK_LOCATION">************</span>
                                        <button class="btn btn-link p-0 ms-2" id="toggle-eye-BANK_LOCATION" onclick="toggleBankDetails('BANK_LOCATION')">
                                        <i class="fas fa-eye" id="eye-icon-BANK_LOCATION"></i>
                                    </button>
                                    </div>
                                    <!--end::Col-->
                                </div>


                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Country: (أمة)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-COUNTRY"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Acc. Number: (رقم الحساب المصرفي)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-ACCOUNT_NUMBER">************</span>
                                        <button class="btn btn-link p-0 ms-2" id="toggle-eye-ACCOUNT_NUMBER" onclick="toggleBankDetails('ACCOUNT_NUMBER')">
                                        <i class="fas fa-eye" id="eye-icon-ACCOUNT_NUMBER"></i>
                                    </button>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <div class="row mb-7">
                                     <!--begin::Label-->
                                     <label class="col-lg-3 fw-semibold text-muted text-start">Zip Code: (الرمز البريدي)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-ZIP_CODE"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">IBAN Number: (IBAN رقم)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-IBAN_NUMBER">************</span>
                                        <button class="btn btn-link p-0 ms-2" id="toggle-eye-IBAN_NUMBER" onclick="toggleBankDetails('IBAN_NUMBER')">
                                        <i class="fas fa-eye" id="eye-icon-IBAN_NUMBER"></i>
                                    </button>
                                    </div>
                                    <!--end::Col-->

                                    </div>

                                 <!--begin::Row-->
                                 <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">How long at current address? (كم من مدة وجود الشركة في العنوان الحالي؟) </label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-ADDRESS_SPAN"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Swift Code: (رقم السويفت كود)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-SWIFT_CODE">************</span>
                                        <button class="btn btn-link p-0 ms-2" id="toggle-eye-SWIFT_CODE" onclick="toggleBankDetails('SWIFT_CODE')">
                                            <i class="fas fa-eye" id="eye-icon-SWIFT_CODE"></i>
                                        </button>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                            <div class="row mb-7">
                                <!-- Business Information -->
                                <div class="col-md-6">
                                    <h4 class="fw-normal text-start">Registration Details</h4>
                                </div>
                                <!-- Registration Details -->
                                <div class="col-md-6">
                                    <h4 class="fw-normal">Company Ownership Details</h4>
                                </div>
                            </div>

                                    <!--begin::Row-->
                                    <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">CRN Number (رقم السجل التجاري)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6 text-primary" id="lbl-CRN_NUMBER"></span>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Paid up Capital (رأس المال المدفوع)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-PAID_UP_CAPITAL"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <div class="row mb-7">
                                     <!--begin::Label-->
                                     <label class="col-lg-3 fw-semibold text-muted text-start">Date of Issuance (تاريخ الانتهاء)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-DATE_OF_ISSUANCE"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Company Owner Name (اسم المالك/مالك الشركة) </label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-COMPANY_OWNER"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>


                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Date of Expiry (تاريخ االنتهاء) </label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-DATE_OF_EXPIRY"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">% of Ownership (نسبة الملكية % لكل شريك)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-PERCENTAGE_OWNER"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <div class="row mb-7">
                                     <!--begin::Label-->
                                     <label class="col-lg-3 fw-semibold text-muted text-start">Company Location (موقع الشركة) </label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-COMPANY_LOCATION"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Company Top Manager (المسئول األول بألشركة) </label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-TOP_MANAGER"></span>
                                    </div>
                                    <!--end::Col-->

                                    </div>
                                
                        </div>
                    </div>
                    </div>
                    </div>


                    <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                            <div class="row mb-7">
                                <!-- Business Information -->
                                <div class="col-md-6">
                                    <h4 class="fw-normal text-start">Personnel Authorized Signature for Purchasing</h4>
                                </div>
                                <!-- Registration Details -->
                                <div class="col-md-6">
                                    <h4 class="fw-normal">Company Managers Details</h4>
                                </div>
                            </div>

                             <!--begin::Row-->
                             <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Name (اسم)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-SIGN_NAME"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Business Activities (النشاط التجارية)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-BUS_ACTIVITIES"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->

                             <!--begin::Row-->
                             <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Position (المسمى الوظيفي)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-SIGN_POSITION"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Gen. Manager (إسم و رقم المدير العام)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-GM_NAME"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->

                             <!--begin::Row-->
                             <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Signature Specimen (نموذج التوقيع)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-SIGN_SPECIMEN"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Pur Manager (إسم و رقم مدير المشتريات)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-PUR_MGR_NAME"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->


                             <!--begin::Row-->
                             <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted"></label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id=""></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Fin Manager (إسم و رقم المدير المالي)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-FIN_MGR_NAME"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->
                        </div>
                    </div>
                    </div>
                    </div>

                    <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                            <div class="row mb-7">
                                <!-- Business Information -->
                                <div class="col-md-6">
                                    <h4 class="fw-normal text-start">Zamil Plastic Industries Limited</h4>
                                </div>
                                <!-- Registration Details -->
                                <div class="col-md-6">
                                    <h4 class="fw-normal">Customer Signature Details</h4>
                                </div>
                            </div>

                             <!--begin::Row-->
                             <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Signature (التوقيع)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-ZPIL_SIGN"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Signature (التوقيع)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-CLIENT_SIGN"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->


                             <!--begin::Row-->
                             <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Signatory Name (اسم صاحب التوقيع)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-ZPIL_SIGNATORY_NAME"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Company Stamp (ختم الشركة)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-CLIENT_STAMP"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->


                             <!--begin::Row-->
                             <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Position Title (المسمى الوظيفي)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-ZPIL_SIGN_POSN"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Signatory Name (اسم صاحب التوقيع)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-CLIENT_SIGN_NAME"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->

                             <!--begin::Row-->
                             <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Date (تاريخ)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-ZPIL_DATE"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Date (تاريخ)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-CLIENT_SIGN_DATE"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->


                             <!--begin::Row-->
                             <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted"></label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id=""></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Chamber of Commerce Stamp (ختم الغرفة التجارية)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-CHAMBER_OF_COMMERCE"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->

                        </div>
                    </div>
                    </div>
                    </div>

                    <!-- for admin only  -->
                    <?php if ($usertype === 'admin'): ?>
                    <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                            <h2 class="mb-7">For Zamil Only</h2>
                            <div class="row mb-7">
                                <!-- Business Information -->
                                <div class="col-md-6">
                                    <h4 class="fw-normal text-start">Reference Details - مرجع </h4>
                                </div>
                                <!-- Registration Details -->
                                <div class="col-md-6">
                                    <h4 class="fw-normal">Approved Credit Limit Details - تفاصيل الحد الائتماني المعتمد</h4>
                                </div>
                            </div>

                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Direct Salesman Comments (تعليقات موظف المبيعات)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-DIR_SALES_COMMENTS"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Final Recommended Credit Limit SAR (تفاصيل الحد الائتماني المعتمد)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-REC_CREDIT_LIMIT"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->


                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Sales Manager Comments(تعليقات مدير المبيعات)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-SALES_MGR_COMMENTS"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Recom. Credit Period (# of Days) (فترة الائتمان المسموح به (# الأيام))</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-REC_CREDIT_PERIOD"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->


                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">General Manager Comments (تعليقات مدير عام)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-GM_COMMENTS"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Approved by Finance (الموافقة المالي)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-APPROVED_FINANCE"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Credit Division Comments (تعليقات قسم الائتمان)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-CREDIT_DIV_COMMENTS"></span>
                                    </div>
                                    <!--end::Col-->

                                      <!--begin::Label-->
                                      <label class="col-lg-3 fw-semibold text-muted">Approved by Management (الموافقة الإدارية)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-APPROVED_MANAGEMENT"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->


                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Finance Manager Comments (تعليقات المدير المالي)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-FIN_MGR_COMMENTS"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Management Comments (تعليقات إدارة)</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-3">
                                        <span class="fs-6" id="lbl-MGMT_COMMENTS"></span>
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <!--end::Row-->

                        </div>
                    </div>
                    </div>
                    </div>
                    <?php endif; ?>



            </div>
            <!--end::Main column-->
        </div>
    </div>
</div>
<?php $this->load->view('loaders/full-page-loader'); ?>


                                