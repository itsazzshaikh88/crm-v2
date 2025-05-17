<!--begin::PAGE CONTAINER -->
<div id="KT_CONTENT_CONTAINER" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="KT_CONTENT">
        <form id="FORM" class="form d-flex flex-column" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <!--begin::PAGE CONTENT GOES FROM HERE-->
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex align-items-center justify-content-between mb-4">
                            <h1>
                                General Details:
                                <div id="choose-client-btn" class="d-inline-flex align-items-center rounded fw-normal p-2 border-0 cursor-pointer gap-4" onclick="openClientListModal('CLIENT_LIST_MODAL')">
                                    <div class="d-inline-flex align-items-center border-bottom-dashed border-danger">
                                        <h4 class="mb-0 fs-2x text-danger">Choose Client</h4>
                                    </div>
                                    <span class="bg-white">
                                        <i class="fa-solid fa-user-plus fs-2x text-danger mb-0"></i>
                                    </span>
                                </div>
                                <div id="client-name-btn" class="d-inline-flex align-items-center rounded fw-normal p-2 border-0 cursor-pointer gap-6 d-none">
                                    <div class="d-inline-flex align-items-center border-bottom-dashed border-primary">
                                        <h4 class="mb-0 fs-2x text-primary" id="client-name-element">Client Name Here ...</h4>
                                    </div>
                                    <span class="bg-white" onclick="removeClientName()">
                                        <i class="fa fa-circle-xmark fs-3 text-danger mb-0"></i>
                                    </span>
                                </div>
                                <h4 class="text-muted fw-normal">PO Number: <span class="text-black" id="PO_NUMBER">PO-0000-00-00</span></h4>
                        </div>
                        <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
                        <input type="hidden" name="PO_ID" id="PO_ID" value="">
                        <input type="hidden" name="CLIENT_ID" id="CLIENT_ID" value="">
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="QUOTATION_NUMBER" class="fs-6 fw-bold">Quotation Number</label>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="QUOTATION_NUMBER" id="QUOTATION_NUMBER" onchange="fetchQuotesDetailForPurchase(this)">
                                        <option value="">Select Quotation</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-end">
                                    <label for="REQUEST_ID" class="fs-6 fw-bold">Request Number</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="REQUEST_ID" id="REQUEST_ID" class="form-control" placeholder="Enter request number">
                                    <span class="text-danger err-lbl" id="lbl-REQUEST_ID"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="COMPANY_NAME" class="fs-6 fw-bold">Company Name</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="COMPANY_NAME" id="COMPANY_NAME" class="form-control" placeholder="Enter company name">
                                    <span class="text-danger err-lbl" id="lbl-COMPANY_NAME"></span>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-end">
                                    <label for="COMPANY_ADDRESS" class="fs-6 fw-bold">Company Address</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="COMPANY_ADDRESS" id="COMPANY_ADDRESS" class="form-control" placeholder="Enter company address">
                                    <span class="text-danger err-lbl" id="lbl-COMPANY_ADDRESS"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="EMAIL_ADDRESS" class="fs-6 fw-bold">Email Address</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="EMAIL_ADDRESS" id="EMAIL_ADDRESS" class="form-control" placeholder="Enter email address">
                                    <span class="text-danger err-lbl" id="lbl-EMAIL_ADDRESS"></span>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-end">
                                    <label for="MOBILE_NUMBER" class="fs-6 fw-bold">Mobile Number</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="CONTACT_NUMBER" id="CONTACT_NUMBER" class="form-control" placeholder="Enter mobile number">
                                    <span class="text-danger err-lbl" id="lbl-CONTACT_NUMBER"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <h2 class="mb-4">Purchase Details</h2>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-row-bordered align-middle gy-4 gs-9" id="purchase-line-table">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                <tr>
                                    <td class="min-w-150px">Product</td>
                                    <td class="min-w-250px">Product Desc</td>
                                    <td class="min-w-150px">Qty</td>
                                    <td class="min-w-150px">Unit Price</td>
                                    <td class="min-w-150px">Total</td>
                                    <td class="min-w-150px">Color</td>
                                    <td class="min-w-150px">Transport</td>
                                    <td class="min-w-150px">SOC #</td>
                                    <td class="min-w-150px">Rec Qty</td>
                                    <td class="min-w-150px">Bal Qty</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" type="button" onclick="addRow()">
                                            <i class="las la-plus fs-4 cursor-pointer text-white m-0 p-0"></i>
                                        </button>
                                    </td>
                                </tr>
                            </thead>
                            <tbody id="purchase-list-tbody">
                                <tr>
                                    <td>
                                        <select name="PRODUCT_ID[]" id="PRODUCT_ID_1" class="form-control" onclick="chooseProduct(1)">
                                            <option value="">Select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="PRODUCT_DESC[]" id="PRODUCT_DESC_1" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="QTY[]" id="QTY_1" class="form-control" oninput="updateTotal(1)">
                                    </td>
                                    <td>
                                        <input type="text" name="UNIT_PRICE[]" id="UNIT_PRICE_1" class="form-control" oninput="updateTotal(1)">
                                    </td>
                                    <td>
                                        <input type="text" name="TOTAL[]" id="TOTAL_1" class="form-control" oninput="updateTotal(1)">
                                    </td>
                                    <td>
                                        <input type="text" name="COLOR[]" id="COLOR_1" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="TRANSPORT[]" id="TRANSPORT_1" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="SOC[]" id="SOC_1" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="REC_QTY[]" id="REC_QTY_1" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="BAL_QTY[]" id="BAL_QTY_1" class="form-control">
                                    </td>
                                    <td>
                                        <button class="btn btn-sm border border-danger">
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
                                        <input type="text" id="CURRENCY" name="CURRENCY" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="fs-6 fw-bold mb-2">Payment Term</label>
                                        <input type="text" name="PAYMENT_TERM" id="PAYMENT_TERM" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="fs-6 fw-bold mb-2">Status</label>
                                        <select name="STATUS" class="form-control" id="STATUS">
                                            <option value="">Select Status</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Rejected">Rejected</option>
                                            <option value="Pending" selected="">Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                                        <label for="" class="fs-6 fw-bold">Sub Total</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="SUBTOTAL" id="SUBTOTAL" class="form-control" oninput="calculateBillingTotals()" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                                        <label for="" class="fs-6 fw-bold">Discount in %</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="DISCOUNT_PERCENTAGE" id="DISCOUNT_PERCENTAGE" class="form-control" oninput="calculateBillingTotals()">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                                        <label for="" class="fs-6 fw-bold">Tax in %</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" id="TAX_PERCENTAGE" name="TAX_PERCENTAGE" class="form-control" oninput="calculateBillingTotals()">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                                        <label for="" class="fs-6 fw-bold">Total</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="TOTAL_AMOUNT" id="TOTAL_AMOUNT" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="" class="fs-6 fw-bold mb-2">Comments</label>
                                <textarea name="COMMENTS" id="COMMENTS" class="form-control" placeholder="Write your comments here ..." rows="5"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="fs-6 fw-bold mb-2">Attachments</label>
                                <input type="file" name="files[]" multiple class="form-control">
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <!-- Uploaded files From Server preview list -->
                                        <h6 class="fw-normal my-4">Uploaded Files</h6>
                                        <div id="file-list-uploaded" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            </div>
        </form>
    </div>
</div>
<!--end::PAGE CONTAINER -->

<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');
$this->load->view('modals/products/product-list');
?>