<div class="modal bg-body fade" tabindex="-1" id="create-new-permission-modal">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content shadow-none">
            <form onsubmit="submitForm(event)" method="post" enctype="multipart/form-data" id="new-permission-form">
                <div class="modal-header py-2">
                    <h4 class="modal-title text-danger d-flex align-items-center">
                        <i class="fa-solid fa-shield-halved text-danger me-2 fs-2"></i>
                        User Permission Management
                    </h4>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeNewPermissionModal()">
                        <i class="fa-solid fa-xmark text-danger fs-4"></i>
                    </div>
                </div>
                <div class="modal-body pt-4">
                    <input type="hidden" name="ID" id="ID">
                    <input type="hidden" name="TOTAL_RESOURCES" id="TOTAL_RESOURCES" value="0">
                    <div class="row border py-3 rounded bg-light">
                        <div class="col-md-5">
                            <div class="form-group row align-items-center mb-1">
                                <label for="ROLE_ID" class="col-md-3 text-gray-800 fw-bold">User Role <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <select name="ROLE_ID" id="ROLE_ID" class="form-control form-control-sm border border-blue-100 text-gray-700">
                                        </select>
                                        <span id="role-loading-spinner" class="d-none"><i class="fa-solid fa-spinner fa-spin text-dark"></i></span>
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-ROLE_ID"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group row align-items-center mb-1">
                                <label for="STATUS" class="col-md-5 text-gray-800 fw-bold">Permission Status <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-md-4">
                                    <select name="STATUS" id="STATUS" class="form-control form-control-sm border border-blue-100 text-gray-700">
                                        <option value="">Choose</option>
                                        <option selected value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-STATUS"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-end gap-2">
                            <button class="btn btn-sm btn-dark" type="button" onclick="fetchUserPermissions()"> <i class="fa-solid fa-search me-0 pe-0"></i></button>
                            <button class="btn btn-sm border border-danger text-danger" type="button" onclick="resetPermissions()"><i class="fa-solid fa-xmark text-danger me-0 pe-0"></i></button>
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="d-flex align-items-center justify-content-between gap-4">
                            <div class="d-flex align-items-center gap-4">
                                <h6 class="fw-bold text-dark mb-0">Choose Permission(s)</h6>
                                <span class="mb-0 fs-8 text-danger d-none" id="permission-loader"> <i class="fa fa-spinner fa-spin text-danger fs-8"></i> Loading Permissions ...</span>
                            </div>
                            <button class="btn btn-sm btn-success" id="submit-btn"> <i class="fa-solid fa-plus"></i> Save Permissions</button>
                        </div>
                        <div class="col-md-12">
                            <div id="permission-container"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>