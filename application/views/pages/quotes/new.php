<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <form id="form" class="form d-flex flex-column " method="POST" enctype="multipart/form-data"
            onsubmit="submitForm(event)">

            <!--begin::PAGE CONTENT GOES FROM HERE-->
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex align-items-center justify-content-between mb-10">
                            <h1>
                                Quatation For:
                                <div id="choose-client-btn"
                                    class="d-inline-flex align-items-center rounded fw-normal p-2 border-0 cursor-pointer gap-4"
                                    onclick="openClientListModal('client-list-modal')">
                                    <div class="d-inline-flex align-items-center border-bottom-dashed border-danger">
                                        <h4 class="mb-0 fs-2x text-danger ">Choose Client</h4>
                                    </div>
                                    <span class="bg-white">
                                        <i class="fa-solid fa-user-plus fs-2x text-danger mb-0"></i>
                                    </span>
                                </div>
                                <div id="client-name-btn"
                                    class="d-inline-flex align-items-center rounded fw-normal p-2 border-0 cursor-pointer gap-6 d-none">
                                    <div class="d-inline-flex align-items-center border-bottom-dashed border-primary">
                                        <h4 class="mb-0 fs-2x text-primary" id="client-name-element">Client Name Here
                                            ...</h4>
                                    </div>
                                    <span class="bg-white" onclick="removeClientName()">
                                        <i class="fa fa-circle-xmark fs-3 text-danger mb-0"></i>
                                    </span>
                                </div>
                                <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
                                <input type="hidden" name="QUOTE_ID" id="QUOTE_ID" value="">

                                <input type="hidden" name="CLIENT_ID" id="CLIENT_ID" value="">
                            </h1>
                            <h4 class="text-muted fw-normal">Quote Number: <span class="text-black"
                                    id="QUOTE_NUMBER">QUO-0000-00-00</span> </h4>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="REQUEST_NUMBER" class="fs-6 fw-bold">Request Number</label>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="REQUEST_NUMBER" id="REQUEST_NUMBER"
                                        onchange="fetchRequestsDetailForQuote(this)">
                                        <option value="">Select Request Number</option>
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex align-items-center justify-content-end">
                                    <label for="" class="fs-6 fw-bold">Status</label>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="QUOTE_STATUS" id="QUOTE_STATUS">
                                        <option value="">Select Status</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Pending" selected="">Pending</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="JOB_TITLE" class="fs-6 fw-bold required">Job Title</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="JOB_TITLE" id="JOB_TITLE" class="form-control"
                                        placeholder="Enter Job Title">
                                    <span class="text-danger err-lbl" id="lbl-JOB_TITLE"></span>

                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-end">
                                    <label for="SALES_PERSON" class="fs-6 fw-bold required">Sales Person</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="SALES_PERSON" id="SALES_PERSON" class="form-control"
                                        placeholder="Enter Sales Person">
                                    <span class="text-danger err-lbl" id="lbl-SALES_PERSON"></span>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="EMPLOYEE_NAME" class="fs-6 fw-bold required">Employee Name</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Enter Employee Name"
                                        name="EMPLOYEE_NAME" id="EMPLOYEE_NAME">
                                    <span class="text-danger err-lbl" id="lbl-EMPLOYEE_NAME"></span>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-end">
                                    <label for="COMPANY_ADDRESS" class="fs-6 fw-bold required">Company Address</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Enter Company Address"
                                        name="COMPANY_ADDRESS" id="COMPANY_ADDRESS">
                                    <span class="text-danger err-lbl" id="lbl-COMPANY_ADDRESS"></span>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="EMAIL_ADDRESS" class="fs-6 fw-bold required">Email Address</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Enter Email Address"
                                        name="EMAIL_ADDRESS" id="EMAIL_ADDRESS">
                                    <span class="text-danger err-lbl" id="lbl-EMAIL_ADDRESS"></span>

                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-end">
                                    <label for="MOBILE_NUMBER" class="fs-6 fw-bold required">Mobile Number</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Enter Mobile Number"
                                        name="MOBILE_NUMBER" id="MOBILE_NUMBER">
                                    <span class="text-danger err-lbl" id="lbl-MOBILE_NUMBER"></span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <h2 class="mb-4">Product Details</h2>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-row-bordered align-middle gy-4 gs-9" id="quotes-lines-table">
                            <thead
                                class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                <tr>
                                    <td class="min-w-150px">Product</td>
                                    <td class="min-w-250px">Product Desc</td>
                                    <td class="min-w-150px">Qty</td>
                                    <td class="min-w-150px">Unit Price</td>
                                    <td class="min-w-150px">Total</td>
                                    <td class="min-w-150px">Color</td>
                                    <td class="min-w-150px">Tranportation</td>
                                    <td class="min-w-150px">Comments</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" type="button" onclick="addRow()">
                                            <i class="las la-plus fs-4 cursor-pointer text-white m-0 p-0"></i>
                                        </button>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="PRODUCT_ID[]" id="PRODUCT_ID_1" class="form-control"
                                            onclick="chooseProduct(1)">
                                            <option value="">Choose</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="DESCRIPTION[]" id="DESCRIPTION_1">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="QTY[]" id="QTY_1"
                                            oninput="updateTotal(1)">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="UNIT_PRICE[]" id="UNIT_PRICE_1"
                                            oninput="updateTotal(1)">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="TOTAL[]" id="TOTAL_1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="COLOR[]" id="COLOR_1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="TRANSPORTATION[]"
                                            id="TRANSPORTATION_1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="LINE_COMMENTS[]"
                                            id="LINE_COMMENTS_1">
                                    </td>
                                    <td>
                                        <button class="btn btn-sm border border-danger" type="button"
                                            onclick="removeRow(this)">
                                            <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>

                    <div class="my-10">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="row mt-4">
                                    <div class="col-md-3 d-flex align-items-center justify-content-start">
                                        <label for="CURRENCY" class="fs-6 fw-bold">Currency</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" id="CURRENCY" name="CURRENCY">
                                    </div>
                                    <div class="col-md-3 d-flex align-items-center justify-content-end">
                                        <label for="PAYMENT_TERM" class="fs-6 fw-bold">Payment Term</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="PAYMENT_TERM" id="PAYMENT_TERM">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <textarea name="COMMENTS" id="COMMENTS" class="form-control"
                                            placeholder="Write your comments here ..." rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-md-6 d-flex align-items-center justify-content-end" >
                                        <label for="SUB_TOTAL" class="fs-6 fw-bold">Sub Total</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="SUB_TOTAL" id="SUB_TOTAL"
                                            onchange="calculateBillingTotals()" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                                        <label for="DISCOUNT_PERCENTAGE" class="fs-6 fw-bold">Discount in %</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="DISCOUNT_PERCENTAGE"
                                            id="DISCOUNT_PERCENTAGE" onkeyup="numberInput(this)"
                                            onchange="calculateBillingTotals()">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                                        <label for="TAX_PERCENTAGE" class="fs-6 fw-bold">Tax in %</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="TAX_PERCENTAGE"
                                            id="TAX_PERCENTAGE" onchange="calculateBillingTotals()">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6 d-flex align-items-center justify-content-end" >
                                        <label for="TOTAL_AMOUNT" class="fs-6 fw-bold">Total</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="TOTAL_AMOUNT" id="TOTAL_AMOUNT"
                                            onchange="calculateBillingTotals()" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row mb-4">
                        <h2 class="mb-4">Quotes Attachments</h2>
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
                        <div class="col-md-8">
                            <label for="INTERNAL_NOTES" class="fs-6 fw-bold mb-2">Internal Notes</label>
                            <textarea name="INTERNAL_NOTES" id="INTERNAL_NOTES" class="form-control" placeholder="Write your comments here ..." rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="card mb-2">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="INTERNAL_NOTES" class="fs-6 fw-bold mb-2">Internal Notes</label>
                            <textarea name="INTERNAL_NOTES" id="INTERNAL_NOTES" class="form-control" placeholder="Write your comments here ..." rows="5"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="ATTACHMENTS" class="fs-6 fw-bold mb-2">Attachments</label>
                            <input type="file" class="form-control" name="ATTACHMENTS" id="ATTACHMENTS">
                        </div>
                    </div>
                </div>
            </div> -->
            <!--end::PAGE CONTENT GOES FROM HERE-->
            <div class="d-flex justify-content-end mb-10 mt-4">

                <button type="submit" id="submit-btn" class="btn btn-primary">
                    <span class="indicator-label">
                        Save
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
<!--end::PAGE CONTAINER-->
<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');
$this->load->view('modals/products/product-list');
?>