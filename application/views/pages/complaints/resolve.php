<!--begin::PAGE CONTAINER -->
<style>
    input[readonly] {
        background-color: #fbfbfb !important;
    }
</style>
<?php
$username = $loggedInUser['username'] ?? 'Guest';
$usertype = $loggedInUser['usertype'] ?? 'Guest';
$user_id = $loggedInUser['userid'] ?? '';
$email = $loggedInUser['email'] ?? 'user@guest.crm';
?>
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <form id="form" class="form d-flex flex-column " method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <!--begin::PAGE CONTENT GOES FROM HERE-->
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <!-- ============== Complaint DETAILS =========== -->
                        <div class="col-md-6 mb-2">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h2>Complaint Details</h2>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="COMPLAINT_NUMBER" class="fs-6 fw-bold required">Complaint No.</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" name="COMPLAINT_NUMBER" id="COMPLAINT_NUMBER" class="form-control" placeholder="Generated Automatically" readonly>
                                    <!-- <span class="text-danger err-lbl" id="lbl-COMPLAINT_NUMBER"></span> -->
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="RECEIVED_DATE" class="fs-6 fw-bold required">Received On</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="date" class="form-control" name="RECEIVED_DATE" id="RECEIVED_DATE" value="" readonly>
                                    <span class="text-danger err-lbl" id="lbl-RECEIVED_DATE"></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="STATUS" class="fs-6 fw-bold required">Complaint Status</label>
                                </div>
                                <div class="col-md-7">
                                    <select class="form-control" placeholder="Write your billing address" name="STATUS" id="STATUS" onchange="setStatusClose(this)">
                                        <option value="Draft">Draft</option>
                                        <option value="Active">Active</option>
                                        <option value="Closed">Closed</option>
                                    </select>
                                    <!-- <span class="text-danger err-lbl" id="lbl-STATUS"></span> -->
                                </div>
                            </div>
                            <div class="row mb-2">
                                <span class="text-danger" id="status-remark">Please change status to Close to resolve particular Complaint Request</span>
                            </div>
                        </div>

                        <!-- ============== Admin DETAILS =========== -->
                        <div class="col-md-6 mb-2">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h2>Admin Details</h2>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 d-flex align-items-center justify-content-start">
                                    <label for="RECEIVED_BY" class="fs-6 fw-bold required">Received By</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="RECEIVED_BY" id="RECEIVED_BY" class="form-control" placeholder="Enter Customer Name" autocomplete="off" value="<?= $username ?>">
                                    <span class="text-danger err-lbl" id="lbl-RECEIVED_BY"></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 d-flex align-items-center justify-content-start">
                                    <label for="EMAIL" class="fs-6 fw-bold required">Email Address</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="email" class="form-control" placeholder="Enter Email Address" id="EMAIL" autocomplete="off" value="<?= $email ?>" readonly>
                                    <!-- <span class="text-danger err-lbl" id="lbl-EMAIL"></span> -->
                                </div>
                            </div>
                            <div class="row mt-5 ">
                                <div class="col-md-4 d-flex align-items-center justify-content-start">
                                    <label for="ESCALATION_NEEDED" class="fs-6 fw-bold required">Escalation Needed</label>
                                </div>
                                <div class="col-md-8 mt-2">
                                    <input type="radio" name="ESCALATION_NEEDED" id="ESCALATION_YES" value="1"> Yes
                                    <input type="radio" name="ESCALATION_NEEDED" id="ESCALATION_NO" value="0" class="ms-3"> No
                                    <span class="text-danger err-lbl" id="lbl-ESCALATION_NEEDED"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-body">
                    <div class="">
                        <div class="row mb-4">
                            <h2 class="mb-4">Complaint Resolvement Details and Attachments</h2>
                            <div class="col-md-12 my-4">
                                <div class="">
                                    <!-- Custom styled upload box -->
                                    <div id="upload-box" class="upload-box d-flex align-items-center btn-outline btn-outline-dashed btn btn-active-light-primary justify-content-center py-8" onclick="document.getElementById('file-input').click();">
                                        <i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary"></i>
                                        <p class="my-4">Click to upload files</p>
                                        <input onchange="handleFileSelect(event)" type="file" id="file-input" multiple style="display:none;">
                                    </div>
                                    <div class="row mt-4">
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
                        <div class="row mb-3">
                            <div class="col-md-6"> <label for="ACTIONS" class="fs-6 fw-bold mb-2">Specific Action</label>
                                <textarea name="ACTIONS" id="ACTIONS" class="form-control" placeholder="Please write about actions taken in details (Min. 5 Characters)..." rows="5"></textarea>
                                <span class="text-danger err-lbl" id="lbl-ACTIONS"></span>
                            </div>
                            <div class="col-md-6"> <label for="ROOT_CAUSE" class="fs-6 fw-bold mb-2">Root Cause </label>
                                <textarea name="ROOT_CAUSE" id="ROOT_CAUSE" class="form-control" placeholder="Please write about root cause in detail (Min. 5 Characters)..." rows="5"></textarea>
                                <span class="text-danger err-lbl" id="lbl-ROOT_CAUSE"></span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="OUTCOME" class="fs-6 fw-bold mb-2">Process Measure Outcome</label>
                                <textarea name="OUTCOME" id="OUTCOME" class="form-control" placeholder="Please write complaint outcome in detail (Min. 5 Characters)..." rows="5"></textarea>
                                <span class="text-danger err-lbl" id="lbl-OUTCOME"></span>
                            </div>
                            <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
                            <input type="hidden" name="COMPLAINT_ID" id="COMPLAINT_ID" value="">
                            <input type="hidden" name="RESOLUTION_ID" id="RESOLUTION_ID" value="">
                            <input type="hidden" name="RESOLUTION_NUMBER" id="RESOLUTION_NUMBER" value="">
                            <input type="hidden" name="" id="USER_ID" value="<?= $user_id ?>">
                            <input type="hidden" name="" id="USER_TYPE" value="<?= $usertype ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!--end::PAGE CONTENT GOES FROM HERE-->
            <div class="d-flex justify-content-end mb-10 mt-4 ">
                <button onclick="cancelFormAndReload()" type="button" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">
                    Cancel
                </button>
                <button type="submit" id="submit-btn" class="btn btn-primary">
                    <span class="indicator-label">
                        Save Changes
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-center">
                <!-- <h1 class="modal-title" id="complaintModalLabel">Complaint Details</h5> -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!--begin::PAGE CONTENT GOES FROM HERE-->
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <h2>Complaint Details</h2>
                            </div>
                        </div>
                        <div class="row"> <!-- ============== Complaint DETAILS =========== -->
                            <div class="col-md-6 mb-2">
                                <div class="row mb-3">
                                    <div class="col-md-3 d-flex align-items-center justify-content-start">
                                        <label for="COMPLAINT_DATE" class="fw-semibold text-gray-600 fs-7 ">Complaint Date</label>
                                    </div>
                                    <div class="col-md-7">

                                        <span class="fw-bold text-gray-800 fs-6" id="lbl-COMPLAINT_DATE"></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 d-flex align-items-center justify-content-start">
                                        <label for="COMPLAINT_RAISED_BY" class="fw-semibold text-gray-600 fs-7 ">Complaint By</label>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="fw-bold text-gray-800 fs-6" id="lbl-COMPLAINT_RAISED_BY"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- ============== CLIENT DETAILS =========== -->
                            <div class="col-md-6 mb-2">
                                <!-- <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h2>Customer Details</h2>
                                    </div>
                                </div> -->
                                <div class="row mb-3">
                                    <div class="col-md-3 d-flex align-items-center justify-content-start">
                                        <label for="CUSTOMER_NAME" class="fw-semibold text-gray-600 fs-7 ">Customer Name</label>
                                    </div>
                                    <div class="col-md-8">

                                        <span class="fw-bold text-gray-800 fs-6" id="lbl-CUSTOMER_NAME"></span>
                                    </div>
                                </div>
                                <!-- <div class="row mb-3">
                                    <div class="col-md-3 d-flex align-items-center justify-content-start">
                                        <label for="COMPLAINT_RAISED_BY" class="fw-semibold text-gray-600 fs-7 ">Complaint By</label>
                                    </div>
                                    <div class="col-md-8">

                                        <span class="fw-bold text-gray-800 fs-6" id="lbl-COMPLAINT_RAISED_BY"></span>
                                    </div>
                                </div> -->
                                <div class="row mb-3">
                                    <div class="col-md-3 d-flex align-items-center justify-content-start">
                                        <label for="MOBILE_NUMBER" class="fw-semibold text-gray-600 fs-7 ">Contact No.</label>
                                    </div>
                                    <div class="col-md-8">

                                        <span class="fw-bold text-gray-800 fs-6" id="lbl-MOBILE_NUMBER"></span>
                                    </div>
                                </div>
                                <!-- <div class="row mb-3">
                                    <div class="col-md-3 d-flex align-items-center justify-content-start">
                                        <label for="EMAIL" class="fw-semibold text-gray-600 fs-7 ">Email Address</label>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="fw-bold text-gray-800 fs-6" id="lbl-EMAIL"></span>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <h2 class="mb-4">Complaint Product Details</h2>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table table-row-bordered align-middle gy-4 gs-9" id="complaint-lines-table">
                                <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                    <tr>
                                        <td class="min-w-150px">PO Number</td>
                                        <td class="min-w-150px">Delivery Number</td>
                                        <td class="min-w-150px">Product Code</td>
                                        <td class="min-w-250px">Product Description</td>
                                        <td class="min-w-150px">Delivery Date</td>
                                        <td class="min-w-150px">Quantity</td>
                                        <td class="min-w-250px">Issue</td>
                                        <td class="min-w-250px">Remarks</td>
                                        <td>

                                        </td>
                                    </tr>
                                </thead>
                                <tbody class="fs-6 fw-semibold text-gray-600" id="complaint-lines">
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <div class="row mb-4">
                                <h2 class="mb-4">Complaint Details and Attachments</h2>
                                <div class="notice d-flex flex-column rounded mb-9 p-4">
                                    <div class="fs-6 text-gray-700 mb-4">
                                        <h6>Attached Files</h6>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center justify-content-start gap-4" id="fileContainer">

                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label for="COMPLAINT" class="fw-semibold text-gray-600 fs-7 mb-2">Complaint Details</label>
                                    <br>
                                    <span class="fw-bold text-gray-800 fs-6" id="lbl-COMPLAINT"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::PAGE CONTENT GOES FROM HERE-->

            </div>
        </div>
    </div>
</div>
<!--end::PAGE CONTAINER-->
<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');
$this->load->view('modals/products/product-list');
?>