<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <form id="form" class="form d-flex flex-column " method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <!--begin::PAGE CONTENT GOES FROM HERE-->
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-normal text-muted">✨ <i>Create Your First <span class="text-warning">Project</span> and Experience Seamless Management</i></h6>
                                <p class="fs-6 fw-normal">Project ID: <span class="text-gray-600">P00-000-00</span></p>
                            </div>
                            <div class="separator separator-dashed my-4"></div>
                            <input type="hidden" name="PROJECT_ID" id="PROJECT_ID">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="fw-normal text-muted">Project Details</h6>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="PROJECT_NAME" class="fs-6 fw-bold required">Title of a Project</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="PROJECT_NAME" id="PROJECT_NAME" class="form-control" placeholder="Contact First Name">
                            <span class="text-danger err-lbl" id="lbl-PROJECT_NAME"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="DESCRIPTION" class="fs-6 fw-bold required">Project Detailed Description</label>
                        </div>
                        <div class="col-md-9">
                            <textarea type="text" name="DESCRIPTION" id="DESCRIPTION" class="form-control" placeholder="Contact First Name" rows="5"></textarea>
                            <span class="text-danger err-lbl" id="lbl-DESCRIPTION"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="START_DATE" class="fs-6 fw-bold required">Start Date</label>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="START_DATE" id="START_DATE" class="form-control" placeholder="Contact First Name">
                            <span class="text-danger err-lbl" id="lbl-START_DATE"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-end">
                            <label for="END_DATE" class="fs-6 fw-bold">End Date</label>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="END_DATE" id="END_DATE" class="form-control" placeholder="Contact First Name">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="STATUS" class="fs-6 fw-bold required">Project Status</label>
                        </div>
                        <div class="col-md-3">
                            <select name="STATUS" id="STATUS" class="form-control" placeholder="Contact First Name">
                                <option value="">Choose</option>
                                <option selected value="NOT_STARTED">Not Started</option>
                                <option value="IN_PROGRESS">In Progress</option>
                                <option value="COMPLETED">Completed</option>
                                <option value="ON_HOLD">On Hold</option>
                            </select>
                            <span class="text-danger err-lbl" id="lbl-STATUS"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-end">
                            <label for="PRIORITY" class="fs-6 fw-bold required">Project Priority</label>
                        </div>
                        <div class="col-md-3">
                            <select name="PRIORITY" id="PRIORITY" class="form-control" placeholder="Contact First Name">
                                <option value="">Choose</option>
                                <option value="LOW">Low</option>
                                <option value="MEDIUM" selected>Medium</option>
                                <option value="HIGH">High</option>
                            </select>
                            <span class="text-danger err-lbl" id="lbl-PRIORITY"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="PROJECT_TYPE" class="fs-6 fw-bold required">Project Type</label>
                        </div>
                        <div class="col-md-3">
                            <select name="PROJECT_TYPE" id="PROJECT_TYPE" class="form-control" placeholder="Contact First Name">
                                <option value="">Choose</option>
                                <option value="INTERNAL">Internal</option>
                                <option value="CLIENT">Client</option>
                                <option value="RESEARCH">Research</option>
                                <option value="OTHER">Other</option>
                            </select>
                            <span class="text-danger err-lbl" id="lbl-PROJECT_TYPE"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-end">
                            <label for="PROJECT_MANAGER" class="fs-6 fw-bold">Project Manager</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="PROJECT_MANAGER" id="PROJECT_MANAGER" class="form-control" placeholder="Add Project manager name">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="fw-normal text-muted">Client Details</h6>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="CLIENT_NAME" class="fs-6 fw-bold required">Name of the Client</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="CLIENT_NAME" id="CLIENT_NAME" class="form-control" placeholder="Contact First Name">
                            <span class="text-danger err-lbl" id="lbl-CLIENT_NAME"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="CLIENT_CONTACT" class="fs-6 fw-bold">Client Contact Number</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="CLIENT_CONTACT" id="CLIENT_CONTACT" class="form-control" placeholder="Contact First Name">
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="CLIENT_EMAIL" class="fs-6 fw-bold">Client Email</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="CLIENT_EMAIL" id="CLIENT_EMAIL" class="form-control" placeholder="Contact Last Name">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush mb-2">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Project Budget and Progress Details</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="TOTAL_BUDGET" class="fs-6 fw-bold required">Total Budget</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="TOTAL_BUDGET" id="TOTAL_BUDGET" class="form-control" placeholder="Contact First Name" value="0">
                            <span class="text-danger err-lbl" id="lbl-TOTAL_BUDGET"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-end">
                            <label for="CURRENT_SPEND" class="fs-6 fw-bold">Total Spent</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="CURRENT_SPEND" id="CURRENT_SPEND" class="form-control" placeholder="Contact Last Name" value="0">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="PROGRESS" class="fs-6 fw-bold required">Progress (0-100%)</label>
                        </div>
                        <div class="col-md-3">
                            <input type="number" min="0" max="100" name="PROGRESS" id="PROGRESS" class="form-control" placeholder="Contact First Name" value="0">
                            <span class="text-danger err-lbl" id="lbl-PROGRESS"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-end">
                            <label for="VISIBLE" class="fs-6 fw-bold">Visibility</label>
                        </div>
                        <div class="col-md-3">
                            <select name="VISIBLE" id="VISIBLE" class="form-control" placeholder="Contact Last Name" value="0">
                                <option value="">Choose</option>
                                <option selected value="PRIVATE">Private</option>
                                <option value="PUBLIC">Public</option>
                                <option value="RESTRICTED">Restricted</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Notes and Attachments</h2>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="NOTES" class="fs-6 fw-bold">Notes</label>
                        </div>
                        <div class="col-md-9">
                            <textarea type="text" rows="5" name="NOTES" id="NOTES" class="form-control" placeholder="Contact First Name" value="0"></textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-start justify-content-start">
                            <label for="ATTACHMENTS" class="fs-6 fw-bold">Attachments</label>
                        </div>
                        <div class="col-md-9">
                            <!-- Custom styled upload box -->
                            <div id="upload-box" class="upload-box d-flex align-items-center btn-outline btn-outline-dashed btn btn-active-light-primary jusitfy-content-center text-center" onclick="document.getElementById('file-input').click();" style="width: 50%;">
                                <p class="my-4 mx-auto"> <i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary mx-auto"></i> Click to upload files</p>
                                <input onchange="handleFileSelect(event)" type="file" id="file-input" multiple style="display:none;">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Uploaded files preview list -->
                                    <h6 class="fw-normal my-4">New Attached Files</h6>
                                    <div id="file-list" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Uploaded files From Server preview list -->
                                    <h6 class="fw-normal my-4">Uploaded Files</h6>
                                    <div id="file-list-uploaded" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card header-->
            </div>
            <!--end::PAGE CONTENT GOES FROM HERE-->
            <div class="d-flex justify-content-end mb-10 mt-4">
                <a href="javascript:void(0)" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">
                    Cancel
                </a>
                <button type="submit" id="submit-btn" class="btn btn-primary">
                    <span class="indicator-label">
                        Save Changes
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
<!--end::PAGE CONTAINER-->
<?php
$this->load->view('loaders/full-page-loader');
?>