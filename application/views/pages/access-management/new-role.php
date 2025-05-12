<!-- Create Role Modal  -->
<div class="modal fade" tabindex="-1" id="create-new-role-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Create New Role</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeNewRoleModal()">
                    <i class="fa fa-close fs-1"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body bg-light">
                <form onsubmit="submitForm(event)" class="form d-flex flex-column" id="new-role-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="ID" id="ID" value="">

                    <!-- Role Name -->
                    <div class="form-floating mb-2">
                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700" name="ROLE_NAME" id="ROLE_NAME">
                        <label for="ROLE_NAME" class="text-gray-600">Role Name<span class="text-danger">*</span></label>
                        <span class="text-danger err-lbl fs-8" id="lbl-ROLE_NAME"></span>
                    </div>

                    <!-- Description -->
                    <div class="mb-2">
                        <textarea placeholder="Enter Role description" class="form-control border border-blue-100 text-gray-700" name="DESCRIPTION" id="DESCRIPTION"></textarea>
                    </div>

                    <!-- Is Active -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="IS_ACTIVE" name="IS_ACTIVE" checked>
                        <label class="form-check-label text-gray-700" for="IS_ACTIVE">
                            Is Active
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="text-end my-4">
                        <button class="btn btn-success" type="submit" id="submit-btn">
                            <i class="fa fa-plus"></i> Save Role Details
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>