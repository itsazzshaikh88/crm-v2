<div class="flex-lg-row-fluid ms-lg-10">
    <form id="edit-profile-form" method="POST" enctype="multipart/form-data" onsubmit="updateUserProfile(event)">
        <!--begin::details View-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer d-flex align-items-center justify-content-between">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit Profile Details</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!--begin::Row-->
                <div class="row mb-2 align-items-center">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-bold text-muted">User ID <span class="float-end">:</span> </label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="USER_ID" id="USER_ID" readonly>
                        <input type="hidden" class="form-control" name="ID" id="ID" readonly>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="row mb-2 align-items-center">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-bold text-muted">User Type <span class="float-end">:</span> </label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="USER_TYPE" id="USER_TYPE" readonly>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="row mb-2 align-items-center">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-bold text-muted">First Name <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="FIRST_NAME" id="FIRST_NAME">
                        <p class="mb-0 text-danger err-lbl" id="lbl-FIRST_NAME"></p>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="row mb-2 align-items-center">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-bold text-muted">Last Name <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="LAST_NAME" id="LAST_NAME">
                        <p class="mb-0 text-danger err-lbl" id="lbl-LAST_NAME"></p>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="row mb-2 align-items-center">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-bold text-muted">Email Address <span class="float-end">:</span> </label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="EMAIL" id="EMAIL" readonly>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="row mb-2 align-items-center">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-bold text-muted">Contact Number <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="PHONE_NUMBER" id="PHONE_NUMBER">
                        <p class="mb-0 text-danger err-lbl" id="lbl-PHONE_NUMBER"></p>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <div class="text-end mt-5">
                    <button type="submit" id="submit-btn" class="btn btn-sm btn-primary">Update Profile</button>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::details View-->
    </form>
</div>

<?php $this->load->view('loaders/full-page-loader'); ?>