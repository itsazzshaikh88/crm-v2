<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="d-flex flex-column flex-xl-row">
            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-100 w-xl-300px mb-10">
                <!--begin::Card-->
                <div class="card card-flush" data-kt-sticky="true" data-kt-sticky-name="account-navbar" data-kt-sticky-offset="{default: false, xl: '80px'}" data-kt-sticky-height-offset="50" data-kt-sticky-width="{lg: '250px', xl: '300px'}" data-kt-sticky-animation="false" data-kt-sticky-left="auto" data-kt-sticky-top="90px" data-kt-sticky-zindex="95">
                    <!--begin::Card body-->
                    <div class="card-body p-10">
                        <!--begin::Summary-->
                        <div class="d-flex flex-center flex-column mb-10">
                            <!--begin::Avatar-->
                            <div class="symbol  mb-3 symbol-100px symbol-circle "><img alt="Pic" src="assets/images/avatar-user-placeholder.png"></div><!--end::Avatar-->
                            <!--begin::Name-->
                            <a href="#" class="fs-2 text-gray-800 text-hover-primary fw-bold mb-1" id="lbl-USERNAME-PLACEHOLDER">
                                User </a>
                            <!--end::Name-->

                            <!--begin::Position-->
                            <div class="fs-6 fw-semibold text-gray-500 mb-2" id="lbl-EMAIL-PLACEHOLDER">
                                Email Address </div>
                            <!--end::Position-->

                            <!--begin::Actions-->
                            <div class="d-flex flex-center">
                                <a href="#" class="btn btn-sm btn-light-primary py-2 px-4 fw-bold me-2 edit-link">Edit Details</a>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Summary-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Sidebar-->

            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-10">
                <!--begin::details View-->
                <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                    <!--begin::Card header-->
                    <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Client Details</h3>
                        </div>
                        <!--end::Card title-->

                        <!--begin::Action-->
                        <a href="#" class="btn btn-primary align-self-center edit-link">Edit Profile</a>
                        <!--end::Action-->
                    </div>
                    <!--begin::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">First Name</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-900" id="lbl-FIRST_NAME"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Last Name</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-900" id="lbl-LAST_NAME"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Email</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold fs-6" id="lbl-EMAIL"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Contact Number</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-semibold fs-6" id="lbl-PHONE_NUMBER"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Status</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-semibold fs-6 badge" id="lbl-STATUS"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <div class="separator separator-dashed my-3"></div>

                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Company Name</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-semibold fs-6" id="lbl-COMPANY_NAME"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Site Name</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-semibold fs-6" id="lbl-SITE_NAME"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Payment Term</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-2">
                                <span class="fw-semibold fs-6" id="lbl-PAYMENT_TERM"></span>
                            </div>
                            <!--end::Col-->

                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Tax Percentage</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-2">
                                <span class="fw-semibold fs-6" id="lbl-TAXES"></span>
                            </div>
                            <!--end::Col-->
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted text-end">Currency</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-3 text-end">
                                <span class="fw-semibold fs-6" id="lbl-CURRENCY"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Credit Limit</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-2">
                                <span class="fw-semibold fs-6" id="lbl-CREDIT_LIMIT"></span>
                            </div>
                            <!--end::Col-->
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted text-end">Order Limit</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-3 text-end">
                                <span class="fw-semibold fs-6" id="lbl-ORDER_LIMIT"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <div class="separator separator-dashed my-3"></div>

                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Address Line 1</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-semibold fs-6" id="lbl-ADDRESS_LINE_1"></span>
                            </div>
                            <!--end::Col-->

                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Address Line 2 (optional)</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-semibold fs-6" id="lbl-ADDRESS_LINE_2"></span>
                            </div>
                            <!--end::Col-->

                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Billing Address</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-semibold fs-6" id="lbl-BILLING_ADDRESS"></span>
                            </div>
                            <!--end::Col-->

                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Shipping Address</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-semibold fs-6" id="lbl-SHIPPING_ADDRESS"></span>
                            </div>
                            <!--end::Col-->

                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">City</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-2">
                                <span class="fw-semibold fs-6" id="lbl-CITY"></span>
                            </div>
                            <!--end::Col-->
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted text-end">State</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-3 text-end">
                                <span class="fw-semibold fs-6" id="lbl-STATE"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Country</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-2">
                                <span class="fw-semibold fs-6" id="lbl-COUNTRY"></span>
                            </div>
                            <!--end::Col-->
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted text-end">Zip Code</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-3 text-end">
                                <span class="fw-semibold fs-6" id="lbl-ZIP_CODE"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::details View-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->
<?php $this->load->view('loaders/full-page-loader'); ?>