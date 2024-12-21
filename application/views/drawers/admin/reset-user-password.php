<div id="app_user_reset_password_drawer" class="bg-body drawer drawer-end" data-kt-drawer="true" data-kt-drawer-name="help" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'350px', 'md': '400px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#app_user_reset_password_toggle" data-kt-drawer-close="#app_user_reset_password_close" style="width: 400px !important;">

    <!--begin::Card-->
    <div class="card shadow-none rounded-0 w-100">
        <!--begin::Header-->
        <div class="card-header" id="app_user_reset_password_header">
            <h5 class="card-title fw-semibold text-gray-600">
                Reset / Change User Password
            </h5>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon explore-btn-dismiss me-n5" id="app_user_reset_password_close" onclick="clearResetPasswordForm()">
                    <i class="fa fa-close fs-2"></i>
                </button>
            </div>
        </div>
        <!--end::Header-->

        <!--begin::Body-->
        <div class="card-body" id="app_user_reset_password_body">
            <!--begin::Content-->
            <div id="app_user_reset_password_scroll" class="hover-scroll-overlay-y d-none">
                <div class="alert alert-warning" role="alert">
                    <strong>Tip:</strong> Use a strong password with at least 8 characters, including uppercase, lowercase, numbers, and special symbols.
                </div>

                <p class="text-muted">Securely update the password for <strong id="lbl-username"></strong>. Ensure it meets the security standards.</p>

                <!--begin::Support-->
                <div class="mb-10">
                    <form id="resetUserPasswordForm" method="POST" onsubmit="resetUserPassword(event)" enctype="multipart/form-data">
                        <input type="hidden" id="HIDDEN_USER_ID" name="HIDDEN_USER_ID">
                        <div class="form-floating mb-2 elements-to-hide">
                            <input type="password" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="RESET_NEW_PASSWORD" id="RESET_NEW_PASSWORD">
                            <label for="RESET_NEW_PASSWORD" class="text-gray-600">New Password<span class="text-danger">*</span></label>
                            <span class="text-danger err-lbl fs-8" id="lbl-RESET_NEW_PASSWORD"></span>
                        </div>
                        <div class="form-floating mb-2 elements-to-hide">
                            <input type="password" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="RESET_CONFIRM_PASSWORD" id="RESET_CONFIRM_PASSWORD">
                            <label for="RESET_CONFIRM_PASSWORD" class="text-gray-600">Confirm Password<span class="text-danger">*</span></label>
                            <span class="text-danger err-lbl fs-8" id="lbl-RESET_CONFIRM_PASSWORD"></span>
                        </div>
                        <!--begin::Link-->
                        <button type="submit" id="reset-password-submit-btn" class="btn btn-lg explore-btn-primary w-100">Reset User Password</button>
                        <!--end::Link-->
                    </form>
                </div>
                <!--end::Support-->
            </div>
            <!--end::Content-->

            <div id="app_skeleton_container" class="">
                <!-- Skeleton for Alert -->
                <div class="skeleton-box" style="height: 60px; width:100%; margin-bottom:20px;">
                    <div class="skeleton-box" style="width: 80px; height: 20px; margin-bottom: 5px;"></div>
                    <div class="skeleton-box" style="width: 100%; height: 14px;"></div>
                </div>
                <br />
                <!-- Skeleton for Instruction Text -->
                <p class="text-muted skeleton-box" style="height: 20px; width: 60%; margin-bottom: 10px;"></p>
                <!-- Skeleton for Form -->
                <div class="mb-10">
                    <input type="hidden" id="HIDDEN_USER_ID" name="HIDDEN_USER_ID">
                    <!-- Skeleton for New Password Input -->
                    <div class="form-floating mb-2 elements-to-hide">
                        <div class="skeleton-box" style="height: 40px; margin-bottom: 10px; width:100%;"></div>
                        <label class="text-gray-600 skeleton-box" style="height: 20px; width: 40%;"></label>
                    </div>
                    <!-- Skeleton for Confirm Password Input -->
                    <div class="form-floating mb-2 elements-to-hide">
                        <div class="skeleton-box" style="height: 40px; margin-bottom: 10px; width:100%;"></div>
                        <label class="text-gray-600 skeleton-box" style="height: 20px; width: 40%;"></label>
                    </div>
                    <!-- Skeleton for Submit Button -->
                    <div class="skeleton-box" style="width: 50%; height: 50px; margin:auto;"></div>
                </div>
            </div>
        </div>
        <!--end::Body-->
    </div>
    <!--end::Card-->
</div>