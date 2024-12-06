<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <form id="form" class="form d-flex flex-column " method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <!--begin::PAGE CONTENT GOES FROM HERE-->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="fs-1">✨ Let's Take It to the <span class="text-success">Next</span> Level!</h2>
                            <h6 class="fw-normal">
                                You’ve captured a <span class="text-warning">lead</span>—now let’s turn potential into progress. Share a bit more about this contact to pave the way for success.
                            </h6>
                            <div class="separator separator-dashed my-4"></div>
                            <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
                            <input type="hidden" name="CONTACT_ID" id="CONTACT_ID">

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="FIRST_NAME" class="fs-6 fw-bold required">Cotact Name</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="FIRST_NAME" id="FIRST_NAME" class="form-control" placeholder="Contact First Name">
                            <span class="text-danger err-lbl" id="lbl-FIRST_NAME"></span>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="LAST_NAME" id="LAST_NAME" class="form-control" placeholder="Contact Last Name">
                            <span class="text-danger err-lbl" id="lbl-LAST_NAME"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="EMAIL" class="fs-6 fw-bold required">Email Address</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="EMAIL" id="EMAIL" class="form-control" placeholder="Contact Email Address">
                            <span class="text-danger err-lbl" id="lbl-EMAIL"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="PHONE" class="fs-6 fw-bold required">Contact Number</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="PHONE" id="PHONE" class="form-control" placeholder="Contact Number">
                            <span class="text-danger err-lbl" id="lbl-PHONE"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="COMPANY_NAME" class="fs-6 fw-bold required">Company Name</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="COMPANY_NAME" id="COMPANY_NAME" class="form-control" placeholder="Company Registration Name">
                            <span class="text-danger err-lbl" id="lbl-COMPANY_NAME"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="JOB_TITLE" class="fs-6 fw-bold required">Job Title</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="JOB_TITLE" id="JOB_TITLE" class="form-control" placeholder="Contact Person Job Title">
                            <span class="text-danger err-lbl" id="lbl-JOB_TITLE"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="DEPARTMENT" class="fs-6 fw-bold required">Department</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="DEPARTMENT" id="DEPARTMENT" class="form-control" placeholder="Contact Department">
                            <span class="text-danger err-lbl" id="lbl-DEPARTMENT"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="CONTACT_SOURCE" class="fs-6 fw-bold required">Contact Source</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="CONTACT_SOURCE" id="CONTACT_SOURCE" class="form-control" placeholder="Contact Person Job Title">
                            <span class="text-danger err-lbl" id="lbl-CONTACT_SOURCE"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="STATUS" class="fs-6 fw-bold required">Status</label>
                        </div>
                        <div class="col-md-3">
                            <select name="STATUS" id="STATUS" class="form-control">
                                <option value="">Choose</option>
                                <option value="new" selected>New</option>
                                <option value="qualified">Qualified</option>
                                <option value="unqualified">Unqualified</option>
                                <option value="engaged">Engaged</option>
                                <option value="follow-up-required">Follow-up Required</option>
                                <option value="no-response">No Response</option>
                                <option value="in-active">In-Active</option>
                            </select>
                            <span class="text-danger err-lbl" id="lbl-STATUS"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="ASSIGNED_TO" class="fs-6 fw-bold required">Assigned To</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="ASSIGNED_TO" id="ASSIGNED_TO" class="form-control" placeholder="Contact Assigned To">
                            <span class="text-danger err-lbl" id="lbl-ASSIGNED_TO"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="PREFERRED_CONTACT_METHOD" class="fs-6 fw-bold required">Prefered Contact Method</label>
                        </div>
                        <div class="col-md-3">
                            <select name="PREFERRED_CONTACT_METHOD" id="PREFERRED_CONTACT_METHOD" class="form-control">
                                <option value="">Choose</option>
                                <option value="email" selected>Email</option>
                                <option value="phone-call">Phone Call</option>
                                <option value="text-message">Text Message (SMS)</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="video-call">Video Call</option>
                                <option value="in-person-meeting">In-Person Meeting</option>
                                <option value="social-media">Social Media (e.g., LinkedIn)</option>
                                <option value="no-preference">No Preference</option>
                            </select>
                            <span class="text-danger err-lbl" id="lbl-PREFERRED_CONTACT_METHOD"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="ADDRESS" class="fs-6 fw-bold required">Contact Address</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="ADDRESS" id="ADDRESS" class="form-control" placeholder="Company Registration Name">
                            <span class="text-danger err-lbl" id="lbl-ADDRESS"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="NOTES" class="fs-6 fw-bold required">Notes</label>
                        </div>
                        <div class="col-md-9">
                            <textarea type="text" name="NOTES" id="NOTES" class="form-control" placeholder="Start typing your notes here ..." rows="4"></textarea>
                            <span class="text-danger err-lbl" id="lbl-NOTES"></span>
                        </div>
                    </div>
                </div>
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