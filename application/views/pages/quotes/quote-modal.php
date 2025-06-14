<style>
    .text-loader {
        position: absolute;
        top: 0;
        left: 0;
        background-color: red;
    }

    .ql-toolbar {
        border-color: #dbeafe !important;
        background-color: #fff !important;
    }

    .modal-content {

        height: auto !important;
    }

    .form-control {
        border-radius: 0px !important;
        font-size: 12px !important;
    }

    label {
        font-size: 12px !important;
    }

    .text-label-heading {
        color: #608BC1 !important;
    }

    .modal-header-bg {
        background-color: #180161 !important;
    }

    .submit-btn-bg {
        background-color: #03346E !important;
        color: #fff !important;
    }

    .reset-btn-bg {
        background-color: #295F98 !important;
    }
</style>
<?php
$loggedInUserID = $loggedInUser['userid'];
$loggedInUserType = strtolower($loggedInUser['userrole']);
?>
<div class="modal bg-body fade " tabindex="-1" id="newQuoteModal">
    <div class="modal-dialog bg-light modal-fullscreen">
        <div class="modal-content shadow-none bg-light ">
            <form onsubmit="submitForm(event)" method="post" enctype="multipart/form-data" id="quotesForm">
                <div class="modal-header modal-header-bg py-1">
                    <h4 class="modal-title text-white fw-normal"><i class="fa-solid fa-layer-group text-white fs-3 mb-0 me-2"></i> Quotation Management</h4>

                    <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
                    <input type="hidden" name="QUOTE_ID" id="QUOTE_ID" value="">

                    <input type="hidden" name="CLIENT_ID" id="CLIENT_ID" value="<?= $loggedInUserType != 'admin' ? $loggedInUserFullDetails['info']['ID'] : '' ?>">
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-danger ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeQuoteModal()">
                        <i class="fa-solid fa-xmark text-white fs-4"></i>
                    </div>

                </div>

                <div class="modal-body pb-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-4">

                                <div class="col-md-6">

                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="text-label-heading fw-normal">Quotation Details</h4>
                                        <div>

                                            <button type="submit" class="rounded-1 btn btn-sm btn-success" id="submit-btn"><i class="fa-solid fa-plus"></i> Save Quotation Details</button>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-2 mt-4">
                                        <!-- First Row -->
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="ORG_ID" class="text-gray-800 fw-bold">Division</label>
                                                <?= render_org_select("ORG_ID", "ORG_ID", "form-control form-control-sm border border-blue-100 text-gray-700", "Select Division") ?>
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-ORG_ID"></p>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-2">
                                        <!-- First Row -->
                                        <div class="col-md-6">
                                            <!-- Textbox Section -->
                                            <div class="d-flex flex-column gap-1 flex-grow-1">
                                                <div class="w-100 d-flex align-items-center justify-content-between">
                                                    <label for="CLIENT_NAME" class="text-gray-800 fw-bold">
                                                        Client Name <span class="text-danger">*</span>
                                                    </label>
                                                    <?php
                                                    if ($loggedInUserType == 'admin'):
                                                    ?>
                                                        <label for="" class="cursor-pointer text-danger text-decoration-underline" onclick="clearClientDetailsFromQuotes()"><small>Clear Details</small></label>
                                                    <?php endif; ?>
                                                </div>
                                                <input type="text" readonly class="form-control form-control-sm border border-blue-100 text-gray-700 w-100"
                                                    placeholder="Click to choose client"
                                                    name="CLIENT_NAME" id="CLIENT_NAME"
                                                    value="<?= $loggedInUserType != 'admin' ? $loggedInUserFullDetails['info']['FIRST_NAME'] . " " . $loggedInUserFullDetails['info']['LAST_NAME'] : '' ?>"
                                                    <?= $loggedInUserType == 'admin' ? 'onclick="openClientListModalFromQuote()"' : '' ?>>
                                            </div>

                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-CLIENT_NAME"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="QUOTE_NUMBER" class="text-gray-800 fw-bold">Quote #</label>
                                                <input type="text" readonly placeholder="Auto Generated" value="" class="form-control form-control-sm border border-blue-100 text-gray-700" name="QUOTE_NUMBER" id="QUOTE_NUMBER">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-2">
                                        <!-- First Row -->
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="REQUEST_NUMBER" class="text-gray-800 fw-bold">Request Number</label>
                                                <select name="REQUEST_NUMBER" id="REQUEST_NUMBER" class="form-control form-control-sm border border-blue-100 text-gray-700" onchange="fetchRequestsDetailForQuote(this)">
                                                    <option value="">Select Request Number</option>
                                                </select>
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-REQUEST_NUMBER"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="QUOTE_STATUS" class="text-gray-800 fw-bold">Status<span class="text-danger">*</span></label>
                                                <select name="QUOTE_STATUS" id="QUOTE_STATUS" class="form-control form-control-sm border border-blue-100 text-gray-700">
                                                    <option value="">Select Status</option>
                                                    <option value="Draft">Draft</option>
                                                    <option value="Approved">Approved</option>
                                                    <option value="Pending" selected="">Pending</option>
                                                    <option value="Rejected">Rejected</option>
                                                </select>
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-QUOTE_STATUS"></p>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-2">
                                        <!-- Second Row -->
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="JOB_TITLE" class="text-gray-800 fw-bold">Job Title<span class="text-danger">*</span></label>
                                                <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700" name="JOB_TITLE" id="JOB_TITLE">
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-JOB_TITLE"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="SALES_PERSON" class="text-gray-800 fw-bold">Sales Person<span class="text-danger">*</span></label>
                                                <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700" name="SALES_PERSON" id="SALES_PERSON">
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-SALES_PERSON"></p>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-2">
                                        <!-- Third Row -->
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="EMPLOYEE_NAME" class="text-gray-800 fw-bold">Employee Name<span class="text-danger">*</span></label>
                                                <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700" name="EMPLOYEE_NAME" id="EMPLOYEE_NAME">
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-EMPLOYEE_NAME"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="COMPANY_ADDRESS" class="text-gray-800 fw-bold">Company Address<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="COMPANY_ADDRESS" id="COMPANY_ADDRESS"
                                                    value="<?= $loggedInUserType != 'admin' ? $loggedInUserFullDetails['address']['ADDRESS_LINE_1'] ?? '' : '' ?>">
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-COMPANY_ADDRESS"></p>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-2">
                                        <!-- Fourth Row -->
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="EMAIL_ADDRESS" class="text-gray-800 fw-bold">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" class="form-control form-control-sm border border-blue-100 text-gray-700" name="EMAIL_ADDRESS" id="EMAIL_ADDRESS"
                                                    value="<?= $loggedInUserType != 'admin' ? $loggedInUserFullDetails['info']['EMAIL'] : '' ?>">
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-EMAIL_ADDRESS"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="MOBILE_NUMBER" class="text-gray-800 fw-bold">Mobile Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="MOBILE_NUMBER" id="MOBILE_NUMBER"
                                                    value="<?= $loggedInUserType != 'admin' ? $loggedInUserFullDetails['info']['PHONE_NUMBER'] : '' ?>">
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-MOBILE_NUMBER"></p>
                                        </div>
                                    </div>


                                </div>

                                <div class="col-md-6 ">
                                    <div class="row g-1 mt-12 ">
                                        <h4 class="text-label-heading fw-normal my-2">Product Images</h4>
                                        <div class="col-md-12 ">
                                            <div id="upload-box" class="upload-box d-flex align-items-center justify-content-center btn-outline btn-outline-dashed btn btn-active-light-primary bg-white py-6" onclick="document.getElementById('file-input').click();">
                                                <i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary"></i>
                                                <p class="mb-0">Click to upload files</p>
                                                <input onchange="handleFileSelect(event)" type="file" id="file-input" multiple style="display:none;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <!-- Uploaded files preview list -->
                                            <h6 class="my-2 fw-normal">New Attached Files</h6>
                                            <div id="file-list" class="my-2 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Uploaded files From Server preview list -->
                                            <h6 class="my-2 fw-normal">Uploaded Files</h6>
                                            <div id="file-list-uploaded" class="my-2 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">

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
                                                <td class="min-w-150px">Sup Prod Code</td>
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
                                                    <input type="text" class="form-control" name="SUPP_PROD_CODE[]" id="SUPP_PROD_CODE_1">
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
                                                    <div id="productDescription" name="productDescription" class="bg-white border border-blue-100 mt-1" style="height: 100px;"></div>
                                                </div>
                                            </div>

                                            <!-- <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <textarea name="COMMENTS" id="COMMENTS" class="form-control"
                                                        placeholder="Write your comments here ..." rows="5"></textarea>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row mb-2">
                                                <div class="col-md-6 d-flex align-items-center justify-content-end">
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
                                                <div class="col-md-6 d-flex align-items-center justify-content-end">
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

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>