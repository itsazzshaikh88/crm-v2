<!--begin::PAGE CONTAINER -->
<div id="KT_CONTENT_CONTAINER" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="KT_CONTENT">
        <form id="FORM" class="form d-flex flex-column" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <!--begin::PAGE CONTENT GOES FROM HERE-->
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="mb-4">General Details</h5>
                            <div class="row mb-2 g-2">
                                <div class="col-md-6">
                                    <div class="d-flex form-floating">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Quotation Number <span class="text-danger">*</span> </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex form-floating">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Request Number <span class="text-danger">*</span> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2 g-2">
                                <div class="col-md-12">
                                    <div class="d-flex form-floating">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Choose Client <span class="text-danger">*</span> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2 g-2">
                                <div class="col-md-12">
                                    <div class="d-flex form-floating">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Company Name <span class="text-danger">*</span> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2 g-2">
                                <div class="col-md-12">
                                    <div class="d-flex form-floating">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Company Address <span class="text-danger">*</span> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2 g-2">
                                <div class="col-md-6">
                                    <div class="d-flex form-floating">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Email Address <span class="text-danger">*</span> </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex form-floating">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Contact Number <span class="text-danger">*</span> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <h5 class="my-4">Comments and Attachments</h5>
                                <div class="col-md-12">
                                    <textarea type="text" placeholder="Enter Order Comments or Write your Notes ...." class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME"></textarea>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <input type="file" class="form-control m-0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h5 class="mb-0">Purchase Order Details</h5>
                                <button class="btn btn-sm btn-success py-2 px-4" type="button" onclick="addRow()">
                                    <i class="las la-plus fs-6 cursor-pointer text-white m-0 p-0"></i>
                                </button>
                            </div>
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-sm table-row-bordered align-middle gy-2 gs-4" id="purchase-line-table">
                                    <thead class="border-bottom border-gray-200 fs-8 text-gray-600 fw-bold bg-light bg-opacity-75">
                                        <tr>
                                            <td class="min-w-150px">Product</td>
                                            <td class="min-w-250px">Product Desc</td>
                                            <td class="min-w-250px">Sup Prod Code</td>
                                            <td class="min-w-150px">Qty</td>
                                            <td class="min-w-150px">Unit Price</td>
                                            <td class="min-w-150px">Total</td>
                                            <td class="min-w-150px">Color</td>
                                            <td class="min-w-150px">Transport</td>
                                            <td class="min-w-150px">SOC #</td>
                                            <td class="min-w-150px">Rec Qty</td>
                                            <td class="min-w-150px">Bal Qty</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody id="purchase-list-tbody">
                                        <tr>
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
                                                <button class="btn btn-sm border border-danger">
                                                    <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->

                            </div>

                            <div class="row">
                                <div class="col-md-8 mt-4">
                                    <div class="row mb-2 g-2">
                                        <div class="col-md-4">
                                            <div class="d-flex form-floating">
                                                <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                                <label for="FIRST_NAME" class="text-gray-600">Currency <span class="text-danger">*</span> </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex form-floating">
                                                <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                                <label for="FIRST_NAME" class="text-gray-600">Payment Term <span class="text-danger">*</span> </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex form-floating">
                                                <select class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                                    <option value="">Select Status</option>
                                                    <option value="Approved">Approved</option>
                                                    <option value="Rejected">Rejected</option>
                                                    <option value="Pending" selected="">Pending</option>
                                                </select>
                                                <label for="FIRST_NAME" class="text-gray-600">Status <span class="text-danger">*</span> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <div class="d-flex form-floating mb-1">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Sub Total <span class="text-danger">*</span> </label>
                                    </div>
                                    <div class="d-flex form-floating mb-1">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Discount in % <span class="text-danger">*</span> </label>
                                    </div>
                                    <div class="d-flex form-floating mb-1">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Tax in % <span class="text-danger">*</span> </label>
                                    </div>
                                    <div class="d-flex form-floating mb-1">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 rounded-1 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                        <label for="FIRST_NAME" class="text-gray-600">Total <span class="text-danger">*</span> </label>
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
<!--end::PAGE CONTAINER -->

<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');

?>