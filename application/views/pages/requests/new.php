<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <form id="form" class="form d-flex flex-column " method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <!--begin::PAGE CONTENT GOES FROM HERE-->
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-flex align-items-center justify-content-between mb-10">
                            <h1>
                                Request For:
                                <div id="choose-client-btn" class="d-inline-flex align-items-center rounded fw-normal p-2 border-0 cursor-pointer gap-4" onclick="openClientListModal('client-list-modal')">
                                    <div class="d-inline-flex align-items-center border-bottom-dashed border-danger">
                                        <h4 class="mb-0 fs-2x text-danger ">Choose Client</h4>
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
                                <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
                                <input type="hidden" name="REQUEST_ID" id="REQUEST_ID" value="">
                                <input type="hidden" name="CLIENT_ID" id="CLIENT_ID" value="">
                            </h1>
                            <h4 class="text-muted fw-normal">Request Number: <span class="text-black">REQ-0000-00-00</span> </h4>

                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="" class="fs-6 fw-bold">Request Title</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter request title">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="" class="fs-6 fw-bold">Company Address</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter request title" name="COMPANY_ADDRESS" id="COMPANY_ADDRESS">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="" class="fs-6 fw-bold">Billing Address</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Write your billing address" name="BILLING_ADDRESS" id="BILLING_ADDRESS">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="" class="fs-6 fw-bold">Shipping Address</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Write your shipping address" name="SHIPPING_ADDRESS" id="SHIPPING_ADDRESS">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-center justify-content-start">
                                    <label for="" class="fs-6 fw-bold">Mobile Number</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Write your shipping address" name="CONTACT_NUMBER" id="CONTACT_NUMBER">
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-end">
                                    <label for="" class="fs-6 fw-bold">Email Address</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Write your shipping address" name="EMAIL_ADDRESS" id="EMAIL_ADDRESS">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-body">
                    <h2 class="mb-4">Choose Products</h2>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-row-bordered align-middle gy-4 gs-9" id="request-lines-table">
                            <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                <tr>
                                    <td class="min-w-150px">Product Code</td>
                                    <td class="min-w-250px">Product Desc</td>
                                    <td class="min-w-150px">Qty</td>
                                    <td class="min-w-150px">Req Date</td>
                                    <td class="min-w-150px">Color</td>
                                    <td class="min-w-150px">Transportation</td>
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
                                        <select name="PRODUCT_CODE[]" id="PRODUCT_CODE_1" class="form-control">
                                            <option value="">Select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="PRODUCT_DESC[]" id="PRODUCT_DESC_1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="QTY[]" id="QTY_1">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="REQ_DATE[]" id="REQ_DATE_1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="COLOR[]" id="COLOR_1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="TRANSPORTATION[]" id="TRANSPORTATION_1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="COMMENTS[]" id="COMMENTS_1">
                                    </td>
                                    <td>
                                        <button class="btn btn-sm border border-danger" type="button" onclick="removeRow(this)">
                                            <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>

                    <div class="my-10">
                        <h2 class="mb-4">Request Details and Attachments</h2>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="" class="fs-6 fw-bold mb-2">Request Details</label>
                                <textarea name="" id="" class="form-control" placeholder="Write your comments here ..." rows="5"></textarea>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="" class="fs-6 fw-bold mb-2">Internal Notes</label>
                                <textarea name="" id="" class="form-control" placeholder="Write your comments here ..." rows="5"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="fs-6 fw-bold mb-2">Attachments</label>
                                <input type="file" class="form-control">
                            </div>
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
$this->load->view('modals/clients/client-list');
?>