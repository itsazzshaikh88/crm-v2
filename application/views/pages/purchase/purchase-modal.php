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

    #purchase-line-table thead tr th,
    #purchase-line-table tbody tr td {
        padding: 3px !important;
    }

    #purchase-line-table tbody tr td .form-control {
        border-radius: 5px !important;
    }
</style>
<div class="modal bg-body fade " tabindex="-1" id="newPurchaseModal">
    <div class="modal-dialog bg-light modal-fullscreen">
        <div class="modal-content shadow-none bg-light ">
            <form onsubmit="submitForm(event)" method="post" enctype="multipart/form-data" id="purchaseForm">
                <div class="modal-header modal-header-bg py-1">
                    <h4 class="modal-title text-white fw-normal"><i class="fa-solid fa-layer-group text-white fs-3 mb-0 me-2"></i> Purchase Management</h4>
                    <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
                    <input type="hidden" name="PO_ID" id="PO_ID" value="">

                    <input type="hidden" name="CLIENT_ID" id="CLIENT_ID" value="">
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-danger ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closePurchaseModal()">
                        <i class="fa-solid fa-xmark text-white fs-4"></i>
                    </div>

                </div>

                <div class="modal-body pb-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-4">

                                <div class="col-md-6">

                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="text-label-heading fw-normal">
                                            Purchase Order
                                            <span id="PO_NUMBER" class="ms-2 text-danger"></span>
                                        </h4>
                                        <div>

                                            <button type="submit" class="rounded-1 btn btn-sm btn-success" id="submit-btn"><i class="fa-solid fa-plus"></i> Save PO Details</button>
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-4 mb-2">
                                        <!-- First Row -->
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="ORG_ID" class="text-gray-800 fw-bold">Division<span class="text-danger">*</span></label>
                                                <?= render_org_select("ORG_ID", "ORG_ID", "form-control form-control-sm border border-blue-100 text-gray-700", "Select Division") ?>
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-ORG_ID"></p>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-2">
                                        <!-- First Row -->
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="CLIENT_NAME" class="text-gray-800 fw-bold">Client Name<span class="text-danger">*</span></label>
                                                <input type="text" value="" readonly class="form-control form-control-sm border border-blue-100 text-gray-700" name="CLIENT_NAME" id="CLIENT_NAME" onclick="openClientListModalFromPurchase()">
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-CLIENT_NAME"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="CLIENT_PO_NUMBER" class="text-gray-800 fw-bold">Client PO #</label>
                                                <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700" name="CLIENT_PO_NUMBER" id="CLIENT_PO_NUMBER">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-2">
                                        <!-- First Row -->
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="QUOTATION_NUMBER" class="text-gray-800 fw-bold">Quotation Number<span class="text-danger">*</span></label>
                                                <select name="QUOTATION_NUMBER" id="QUOTATION_NUMBER" class="form-control form-control-sm border border-blue-100 text-gray-700" onchange="fetchQuotesDetailForPurchase(this)">
                                                    <option value="">Select Quotation Number</option>
                                                </select>
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-QUOTATION_NUMBER"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="REQUEST_NUMBER" class="text-gray-800 fw-bold">Request Number<span class="text-danger">*</span></label>
                                                <input name="REQUEST_NUMBER" id="REQUEST_NUMBER" class="form-control form-control-sm border border-blue-100 text-gray-700" onchange="fetchRequestsDetailForQuote(this)">
                                            </div>
                                            <!-- <p class="text-danger err-lbl mb-0 fs-8" id="lbl-REQUEST_NUMBER"></p> -->
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-2">
                                        <!-- Second Row -->
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="COMPANY_NAME" class="text-gray-800 fw-bold">Company Name<span class="text-danger">*</span></label>
                                                <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700" name="COMPANY_NAME" id="COMPANY_NAME">

                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-COMPANY_NAME"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="COMPANY_ADDRESS" class="text-gray-800 fw-bold">Company Address<span class="text-danger">*</span></label>
                                                <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700" name="COMPANY_ADDRESS" id="COMPANY_ADDRESS">
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-COMPANY_ADDRESS"></p>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-2">
                                        <!-- Third Row -->
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="EMAIL_ADDRESS" class="text-gray-800 fw-bold">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" value="" class="form-control form-control-sm border border-blue-100 text-gray-700" name="EMAIL_ADDRESS" id="EMAIL_ADDRESS">
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-EMAIL_ADDRESS"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-1">
                                                <label for="CONTACT_NUMBER" class="text-gray-800 fw-bold">Mobile Number<span class="text-danger">*</span></label>
                                                <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700" name="CONTACT_NUMBER" id="CONTACT_NUMBER">
                                            </div>
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-CONTACT_NUMBER"></p>
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
                                <div class="mb-2 d-flex align-items-center justify-content-between">
                                    <h2 class="mb-4">Product Details</h2>
                                    <button class="btn btn-sm btn-success" type="button" onclick="addRow()">
                                        <i class="las la-plus fs-6 cursor-pointer text-white m-0 p-0"></i>
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table table-row-bordered align-middle gy-4 gs-9" id="purchase-line-table">
                                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                            <tr>
                                                <th>#</th>
                                                <th class="min-w-150px">Product</th>
                                                <th class="min-w-250px">Product Desc</th>
                                                <th class="min-w-250px">Sup Prod Code</th>
                                                <th class="min-w-150px">Qty</th>
                                                <th class="min-w-150px">Unit Price</th>
                                                <th class="min-w-150px">Total</th>
                                                <th class="min-w-150px">Color</th>
                                                <th class="min-w-150px">Transport</th>
                                                <th class="min-w-150px">SOC #</th>
                                                <th class="min-w-150px">Rec Qty</th>
                                                <th class="min-w-150px">Bal Qty</th>
                                                <th>

                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <select name="PRODUCT_ID[]" id="PRODUCT_ID_1" class="form-control form-control-sm" onclick="chooseProduct(1)">
                                                        <option value="">Select</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="PRODUCT_DESC[]" id="PRODUCT_DESC_1" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input type="text" name="SUPP_PROD_CODE[]" id="SUPP_PROD_CODE_1" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input type="text" name="QTY[]" id="QTY_1" class="form-control form-control-sm" oninput="updateTotal(1)">
                                                </td>
                                                <td>
                                                    <input type="text" name="UNIT_PRICE[]" id="UNIT_PRICE_1" class="form-control form-control-sm" oninput="updateTotal(1)">
                                                </td>
                                                <td>
                                                    <input type="text" name="TOTAL[]" id="TOTAL_1" class="form-control form-control-sm" oninput="updateTotal(1)">
                                                </td>
                                                <td>
                                                    <input type="text" name="COLOR[]" id="COLOR_1" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input type="text" name="TRANSPORT[]" id="TRANSPORT_1" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input type="text" name="SOC[]" id="SOC_1" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input type="text" name="REC_QTY[]" id="REC_QTY_1" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input type="text" name="BAL_QTY[]" id="BAL_QTY_1" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm border border-danger" onclick="removeRow(this)">
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
                                                <div class="col-md-4">
                                                    <label for="" class="fs-6 fw-bold mb-2">Currency</label>
                                                    <input type="text" id="CURRENCY" name="CURRENCY" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="fs-6 fw-bold mb-2">Payment Term</label>
                                                    <input type="text" name="PAYMENT_TERM" id="PAYMENT_TERM" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="fs-6 fw-bold mb-2">Status</label>
                                                    <select name="STATUS" class="form-control form-control-sm" id="STATUS">
                                                        <option value="">Select Status</option>
                                                        <option value="Approved">Approved</option>
                                                        <option value="Rejected">Rejected</option>
                                                        <option value="Pending" selected="">Pending</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mt-4">

                                                <div class="col-md-12">
                                                    <div id="productDescription" name="productDescription" class="bg-white border border-blue-100 mt-1" style="height: 100px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row mb-2">
                                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                                    <label for="" class="fs-6 fw-bold">Sub Total</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="SUBTOTAL" id="SUBTOTAL" class="form-control form-control-sm" oninput="calculateBillingTotals()" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                                    <label for="" class="fs-6 fw-bold">Discount in %</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="DISCOUNT_PERCENTAGE" id="DISCOUNT_PERCENTAGE" class="form-control form-control-sm" oninput="calculateBillingTotals()">
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                                    <label for="" class="fs-6 fw-bold">Tax in %</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" id="TAX_PERCENTAGE" name="TAX_PERCENTAGE" class="form-control form-control-sm" oninput="calculateBillingTotals()">
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                                    <label for="" class="fs-6 fw-bold">Total</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="TOTAL_AMOUNT" id="TOTAL_AMOUNT" class="form-control form-control-sm" readonly>
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