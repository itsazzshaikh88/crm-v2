<!-- Create User Modal  -->
<div class="modal fade" tabindex="-1" id="create-new-user-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Create New User</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeNewUserModal()">
                    <i class="fa fa-close fs-1"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body bg-light">
                <form onsubmit="submitForm(event)" class="form d-flex flex-column " id="form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="ID" id="ID" value="">
                    <!-- Input Fields  -->
                    <div class="form-floating mb-2">
                        <?= render_org_select("ORG_ID", "ORG_ID", "form-control form-control-sm border border-blue-100 text-gray-700", "Select Division") ?>
                        <label for="ORG_ID" class="text-gray-600">Division<span class="text-danger">*</span></label>
                        <span class="text-danger err-lbl fs-8" id="lbl-ORG_ID"></span>
                    </div>
                    <div class="d-flex mb-2 gap-2">
                        <div class="d-inline-flex flex-column form-floating w-50">
                            <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                            <label for="FIRST_NAME" class="text-gray-600">First Name <span class="text-danger">*</span></label>
                            <span class="text-danger err-lbl fs-8" id="lbl-FIRST_NAME"></span>
                        </div>
                        <div class="d-inline-flex flex-column form-floating w-50">
                            <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="LAST_NAME" id="LAST_NAME">
                            <label for="LAST_NAME" class="text-gray-600">Last Name<span class="text-danger">*</span></label>
                            <span class="text-danger err-lbl fs-8" id="lbl-LAST_NAME"></span>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="EMAIL" id="EMAIL">
                        <label for="EMAIL" class="text-gray-600">Email<span class="text-danger">*</span></label>
                        <span class="text-danger err-lbl fs-8" id="lbl-EMAIL"></span>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="PHONE_NUMBER" id="PHONE_NUMBER">
                        <label for="PHONE_NUMBER" class="text-gray-600">Contact Number<span class="text-danger">*</span></label>
                        <span class="text-danger err-lbl fs-8" id="lbl-PHONE_NUMBER"></span>
                    </div>
                    <div class="d-flex mb-2 gap-2">
                        <div class="d-inline-flex form-floating w-50">
                            <select class="form-control border border-blue-100 text-gray-700 " name="USER_TYPE" id="USER_TYPE">
                                <option value="">Choose</option>
                                <option selected value="admin">Admin</option>
                                <option value="subadmin">Sub Admin</option>
                                <option value="client">Client</option>
                                <option value="employee">Employee</option>
                                <option value="vendor">Vendor</option>
                            </select>
                            <label for="USER_TYPE" class="text-gray-600">User Type <span class="text-danger">*</span></label>
                            <span class="text-danger err-lbl fs-8" id="lbl-USER_TYPE"></span>
                        </div>
                        <div class="d-inline-flex form-floating w-50">
                            <select class="form-control border border-blue-100 text-gray-700 " name="STATUS" id="STATUS">
                                <option value="">Choose</option>
                                <option selected value="active">Active</option>
                                <option value="inactive">In -Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="locked">Locked</option>
                            </select>
                            <label for="STATUS" class="text-gray-600">Status<span class="text-danger">*</span></label>
                            <span class="text-danger err-lbl fs-8" id="lbl-STATUS"></span>
                        </div>
                    </div>
                    <div class="form-floating mb-2 elements-to-hide">
                        <input type="password" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="NEW_PASSWORD" id="NEW_PASSWORD">
                        <label for="NEW_PASSWORD" class="text-gray-600">Password<span class="text-danger">*</span></label>
                        <span class="text-danger err-lbl fs-8" id="lbl-NEW_PASSWORD"></span>
                    </div>
                    <div class="form-floating mb-2 elements-to-hide">
                        <input type="password" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="CONFIRM_PASSWORD" id="CONFIRM_PASSWORD">
                        <label for="CONFIRM_PASSWORD" class="text-gray-600">Confirm Password<span class="text-danger">*</span></label>
                        <span class="text-danger err-lbl fs-8" id="lbl-CONFIRM_PASSWORD"></span>
                    </div>
                    <div class="text-end my-4">
                        <button class="btn btn-success" type="submit" id="submit-btn"> <i class="fa fa-plus"></i> Save User Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>