<!--begin::PAGE CONTAINER -->
<style>
    input[readonly] {
        background-color: #fbfbfb !important;
    }
</style>
<?php
$username = $loggedInUser['username'] ?? 'Guest';
$usertype = $loggedInUser['userrole'] ?? 'Guest';
$user_id = $loggedInUser['userid'] ?? '';
$email = $loggedInUser['email'] ?? 'user@guest.crm';
?>
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
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
                        <div class="row mb-3">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label class="fw-semibold text-gray-600 fs-7 ">Complaint No.</label>
                            </div>
                            <div class="col-md-7">

                                <span class="fw-bold text-gray-800 fs-6" id="lbl-COMPLAINT_NUMBER"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="COMPLAINT_DATE" class="fw-semibold text-gray-600 fs-7 ">Compalint Date</label>
                            </div>
                            <div class="col-md-7">

                                <span class="fw-bold text-gray-800 fs-6" id="lbl-COMPLAINT_DATE"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="STATUS" class="fw-semibold text-gray-600 fs-7 ">Complaint Status</label>
                            </div>
                            <div class="col-md-7">

                                <span class="fw-bold text-gray-800 fs-6" id="lbl-STATUS"></span>
                            </div>
                        </div>
                    </div>

                    <!-- ============== CLIENT DETAILS =========== -->
                    <div class="col-md-6 mb-2">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h2>Customer Details</h2>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="CUSTOMER_NAME" class="fw-semibold text-gray-600 fs-7 ">Customer Name</label>
                            </div>
                            <div class="col-md-8">

                                <span class="fw-bold text-gray-800 fs-6" id="lbl-CUSTOMER_NAME"></span>
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
                        <div class="row mb-3">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="MOBILE_NUMBER" class="fw-semibold text-gray-600 fs-7 ">Contact No.</label>
                            </div>
                            <div class="col-md-8">

                                <span class="fw-bold text-gray-800 fs-6" id="lbl-MOBILE_NUMBER"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="EMAIL" class="fw-semibold text-gray-600 fs-7 ">Email Address</label>
                            </div>
                            <div class="col-md-8">

                                <span class="fw-bold text-gray-800 fs-6" id="lbl-EMAIL"></span>
                            </div>
                        </div>
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
                        <input type="hidden" name="COMPLAINT_ID" id="COMPLAINT_ID" value="">
                        <input type="hidden" name="CLIENT_ID" id="CLIENT_ID" value="">
                        <input type="hidden" name="" id="USER_ID" value="<?= $user_id ?>">
                        <input type="hidden" name="" id="USER_EMAIL" value="<?= $email ?>">
                    </div>
                </div>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
        <div class="card mt-3 resolve d-none">
            <div class="card-body">
                <div class="">
                    <h2 class="text-success mb-5">Complaint Resolvement Details and Attachments</h2>
                    <!-- ============== Admin DETAILS =========== -->
                    <div class="row ">
                        <div class="col-md-4">
                            <div class="row mt-5  mb-2">
                                <div class="col-md-4 d-flex align-items-center justify-content-start">
                                    <label for="RECEIVED_BY" class="fw-semibold text-gray-600 fs-7">Received By</label>
                                </div>
                                <div class="col-md-8">
                                    <span class="fw-bold text-gray-800 fs-6" id="lbl-RECEIVED_BY"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mt-5 mb-2">
                                <div class="col-md-4 d-flex align-items-center justify-content-start">
                                    <label for="EMAIL" class="fw-semibold text-gray-600 fs-7">Email Address</label>
                                </div>
                                <div class="col-md-8">
                                    <span class="fw-bold text-gray-800 fs-6" id="lbl-ADMIN_EMAIL"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row mt-5 ">
                                <div class="col-md-4 d-flex align-items-center justify-content-start">
                                    <label for="ESCALATION_NEEDED" class="fw-semibold text-gray-600 fs-7">Escalation Needed</label>
                                </div>
                                <div class="col-md-8 ">
                                    <span class="fw-bold text-gray-800 fs-6" id="lbl-ESCALATION_NEEDED"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 mt-5">
                        <div class="col-md-12 my-1 mb-5 ">
                            <div class="col-md-6"> <label for="ACTIONS" class="fw-semibold text-gray-600 fs-7">Attached Files</label>
                                <div class="d-flex flex-wrap align-items-center justify-content-start gap-4" id="adminFileContainer">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mt-5">
                        <div class="col-md-6"> <label for="ACTIONS" class="fw-semibold text-gray-600 fs-7">Specific Action</label><br>
                            <span class="fw-bold text-gray-800 fs-6" id="lbl-ACTIONS"></span>
                        </div>
                        <div class="col-md-6"> <label for="ROOT_CAUSE" class="fw-semibold text-gray-600 fs-7">Root Cause </label><br>
                            <span class="fw-bold text-gray-800 fs-6" id="lbl-ROOT_CAUSE"></span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12"> <label for="OUTCOME" class="fw-semibold text-gray-600 fs-7">Process Measure Outcome</label><br>
                            <span class="fw-bold text-gray-800 fs-6" id="lbl-OUTCOME"></span>
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

    </div>
</div>
<!--end::PAGE CONTAINER-->
<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');
$this->load->view('modals/products/product-list');
?>