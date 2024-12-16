<div class="flex-lg-row-fluid ms-lg-10">
    <!--begin::Sign-in Method-->
    <div class="card  mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_signin_method">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Sign-in Method</h3>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Content-->
        <div id="kt_account_settings_signin_method" class="collapse show">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Email Address-->
                <div class="d-flex flex-wrap align-items-center">
                    <!--begin::Label-->
                    <div id="kt_signin_email">
                        <div class="fs-6 fw-bold mb-1">Email Address</div>
                        <div class="fw-semibold text-gray-600" id="loggedInUserEmailPlaceholder">

                        </div>
                    </div>
                    <!--end::Label-->

                    <!--begin::Action-->
                    <div class="ms-auto">
                        <button class="btn btn-light btn-active-light-primary">Registered Email Address for Account</button>
                    </div>
                    <!--end::Action-->
                </div>
                <!--end::Email Address-->

                <!--begin::Separator-->
                <div class="separator separator-dashed my-6"></div>
                <!--end::Separator-->

                <!--begin::Password-->
                <div class="d-flex flex-wrap align-items-center mb-10">
                    <!--begin::Label-->
                    <div id="app_signin_password">
                        <div class="fs-6 fw-bold mb-1">Password</div>
                        <div class="fw-semibold text-gray-600">************</div>
                    </div>
                    <!--end::Label-->

                    <!--begin::Edit-->
                    <div id="app_signin_password_edit" class="flex-row-fluid d-none">
                        <!--begin::Form-->
                        <form id="updatePasswordForm" class="form fv-plugins-bootstrap5 fv-plugins-framework" onsubmit="updatePassword(event)">
                            <div class="row mb-1">
                                <div class="col-lg-4">
                                    <div class="fv-row mb-0 fv-plugins-icon-container">
                                        <label for="CURRENT_PASSWORD" class="form-label fs-6 fw-bold mb-3">Current Password</label>
                                        <input type="password" class="form-control form-control-lg form-control-solid " name="CURRENT_PASSWORD" id="CURRENT_PASSWORD">
                                        <span class="text-danger err-lbl" id="lbl-CURRENT_PASSWORD"></span>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="fv-row mb-0 fv-plugins-icon-container">
                                        <label for="NEW_PASSWORD" class="form-label fs-6 fw-bold mb-3">New Password</label>
                                        <input type="password" class="form-control form-control-lg form-control-solid " name="NEW_PASSWORD" id="NEW_PASSWORD">
                                        <span class="text-danger err-lbl" id="lbl-NEW_PASSWORD"></span>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="fv-row mb-0 fv-plugins-icon-container">
                                        <label for="CONFIRM_PASSWORD" class="form-label fs-6 fw-bold mb-3">Confirm New Password</label>
                                        <input type="password" class="form-control form-control-lg form-control-solid " name="CONFIRM_PASSWORD" id="CONFIRM_PASSWORD">
                                        <span class="text-danger err-lbl" id="lbl-CONFIRM_PASSWORD"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-text mb-5">Password must be at least 8 character and contain symbols</div>

                            <div class="d-flex">
                                <button id="submit-btn" type="submit" class="btn btn-primary me-2 px-6">Update Password</button>
                                <button id="app_password_cancel" type="button" class="btn btn-color-gray-500 btn-active-light-primary px-6" onclick="togglePasswordEdit('hide')">Cancel</button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Edit-->

                    <!--begin::Action-->
                    <div id="app_signin_password_button" class="ms-auto">
                        <button class="btn btn-light btn-active-light-primary" onclick="togglePasswordEdit('show')">Reset Password</button>
                    </div>
                    <!--end::Action-->
                </div>
                <!--end::Password-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Sign-in Method-->

    <!--begin::Connected Accounts-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_connected_accounts" aria-expanded="true" aria-controls="kt_account_connected_accounts">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Two-Factor Authentication (2FA)</h3>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Content-->
        <div id="kt_account_settings_connected_accounts" class="collapse show">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div id="totp-setup">
                            
                        </div>
                    </div>
                </div>

                <!--begin::Notice-->
                <div class="notice d-flex bg-light-secondary rounded border-secondary border border-dashed  p-6" id="container-enable-disable-2fa"></div>
                <!--end::Notice-->

                <!-- 2 Step Authentication Secret and QR Code = START -->
                <!-- 2 Step Authentication Secret and QR Code = END -->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Connected Accounts-->
    <!--begin::Deactivate Account-->
    <div class="card  ">

        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_deactivate" aria-expanded="true" aria-controls="kt_account_deactivate">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Deactivate Account</h3>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Content-->
        <div id="kt_account_settings_deactivate" class="collapse show">
            <!--begin::Form-->
            <form id="kt_account_deactivate_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">

                <!--begin::Card body-->
                <div class="card-body border-top p-9">

                    <!--begin::Notice-->
                    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                        <!--begin::Icon-->
                        <i class="fa-solid fa-circle-info fs-2tx text-warning me-4"></i>
                        <!--end::Icon-->

                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack flex-grow-1 ">
                            <!--begin::Content-->
                            <div class=" fw-semibold">
                                <h4 class="text-gray-900 fw-bold">You Are Deactivating Your Account</h4>

                                <div class="fs-6 text-gray-700 ">To deactivate your account, we require you to confirm your email address or phone number. This extra step ensures that only you can make changes to your account status and keeps your information safe.</div>
                            </div>
                            <!--end::Content-->

                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Notice-->

                    <!--begin::Form input row-->
                    <div class="form-check form-check-solid fv-row fv-plugins-icon-container">
                        <input name="deactivate" class="form-check-input" type="checkbox" value="" id="deactivate">
                        <label class="form-check-label fw-semibold ps-2 fs-6" for="deactivate">I confirm my account deactivation</label>
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                    <!--end::Form input row-->
                </div>
                <!--end::Card body-->

                <!--begin::Card footer-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button id="kt_account_deactivate_account_submit" type="submit" class="btn btn-danger fw-semibold">Deactivate Account</button>
                </div>
                <!--end::Card footer-->

                <input type="hidden">
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Deactivate Account-->
</div>