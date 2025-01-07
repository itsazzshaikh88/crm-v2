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
<div class="modal bg-body fade " tabindex="-1" id="newRequestModal">
    <div class="modal-dialog bg-light modal-fullscreen">
        <div class="modal-content shadow-none bg-light ">
            <form onsubmit="submitForm(event)" method="post" enctype="multipart/form-data" id="requestForm">
                <div class="modal-header modal-header-bg py-1">
                    <h4 class="modal-title text-white fw-normal"><i class="fa-solid fa-file-signature text-white fs-3 mb-0 me-2"></i> Request for Quotation</h4>
                    <!-- Hidden Fields: START -->
                    <input type="hidden" name="ID" id="ID">
                    <!-- Hidden Fields: END -->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-danger ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeProductModal()">
                        <i class="fa-solid fa-xmark text-white fs-4"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body pb-2 pt-0">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="text-label-heading fw-normal">Client and Request Details</h4>
                                <div class="my-4">
                                    <button type="button" class="rounded-1 btn btn-sm btn-secondary" onclick="startOver()"><i class="fa-solid fa-rotate"></i> Start Over New Request</button>
                                    <button type="submit" class="rounded-1 btn btn-sm btn-primary" id="submit-btn"><i class="fa-solid fa-plus"></i> Save Request Details</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center mb-1">
                                        <label for="CLIENT_NAME" class="col-md-3 text-gray-800 fw-bold">Client Name <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                        <div class="col-sm-8">
                                            <input type="text" value="" readonly class="form-control form-control-sm border border-blue-100 text-gray-700 " name="CLIENT_NAME" id="CLIENT_NAME" onclick="openClientListModalFromRequest()">
                                            <input type="hidden" name="CLIENT_ID" id="CLIENT_ID">
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-STATUS"></p>
                                        </div>
                                        <div class="col-sm-1 d-flex-align-items-center justify-content-center cursor-pointer" onclick="clearClientDetails()">
                                            <i class="fa-solid fa-x text-danger" title="Clear Client Details"></i>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center mb-1">
                                        <label for="CONTACT_NUMBER" class="col-md-3 text-gray-800 fw-bold">Mobile Number <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                        <div class="col-sm-6">
                                            <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="CONTACT_NUMBER" id="CONTACT_NUMBER">
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-CONTACT_NUMBER"></p>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center mb-1">
                                        <label for="EMAIL_ADDRESS" class="col-md-3 text-gray-800 fw-bold">Email Address <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                        <div class="col-sm-6">
                                            <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="EMAIL_ADDRESS" id="EMAIL_ADDRESS">
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-EMAIL_ADDRESS"></p>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center mb-1">
                                        <label for="REQUEST_TITLE" class="col-md-3 text-gray-800 fw-bold">Request Title <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                        <div class="col-sm-9">
                                            <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="REQUEST_TITLE" id="REQUEST_TITLE">
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-REQUEST_TITLE"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center mb-1">
                                        <label for="COMPANY_ADDRESS" class="col-md-3 text-gray-800 fw-bold">Company Address <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                        <div class="col-sm-9">
                                            <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="COMPANY_ADDRESS" id="COMPANY_ADDRESS">
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-COMPANY_ADDRESS"></p>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center mb-1">
                                        <label for="BILLING_ADDRESS" class="col-md-3 text-gray-800 fw-bold">Billing Address <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                        <div class="col-sm-9">
                                            <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="BILLING_ADDRESS" id="BILLING_ADDRESS">
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-BILLING_ADDRESS"></p>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center mb-1">
                                        <label for="SHIPPING_ADDRESS" class="col-md-3 text-gray-800 fw-bold">Shipping Address <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                        <div class="col-sm-9">
                                            <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="SHIPPING_ADDRESS" id="SHIPPING_ADDRESS">
                                            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-SHIPPING_ADDRESS"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <h4 class="text-label-heading fw-normal mt-4">Product Details</h4>
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-sm table-row-bordered align-middle gs-3" id="request-lines-table">
                                    <thead class="border-bottom border-gray-200 fs-6 text-gray-800 fw-bold bg-light bg-opacity-75">
                                        <tr class="bg-white">
                                            <td class="min-w-150px">Product</td>
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
                                                <select name="PRODUCT_ID[]" id="PRODUCT_ID_1" class="form-control form-control-sm border border-blue-100 text-gray-700" onclick="chooseProduct(1)">
                                                    <option value="">Choose</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="PRODUCT_DESC[]" id="PRODUCT_DESC_1">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="QUANTITY[]" id="QUANTITY_1">
                                            </td>
                                            <td>
                                                <input type="date" class="form-control form-control-sm border border-blue-100 text-gray-700" name="REQUIRED_DATE[]" id="REQUIRED_DATE_1">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="COLOR[]" id="COLOR_1">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="TRANSPORTATION[]" id="TRANSPORTATION_1">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="COMMENTS[]" id="COMMENTS_1">
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
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <h4 class="text-label-heading fw-normal mt-4">Details, Notes and Attachments</h4>
                        </div>
                        <div class="col-md-4">
                            <label for="REQUEST_DETAILS" class="text-gray-800 fw-bold mb-1">Request Details </label>
                            <textarea rows="5" placeholder="Write your request details here ...." name="REQUEST_DETAILS" id="REQUEST_DETAILS" class="form-control form-control-sm border border-blue-100 text-gray-700"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="INTERNAL_NOTES" class="text-gray-800 fw-bold mb-1">Internal Notes </label>
                            <textarea rows="5" placeholder="Write your internal notes ...." name="INTERNAL_NOTES" id="INTERNAL_NOTES" class="form-control form-control-sm border border-blue-100 text-gray-700"></textarea>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <label class="text-gray-800 fw-bold mb-1">Attachments </label>
                                <!-- Custom styled upload box -->
                                <div id="upload-box" class="upload-box d-flex align-items-center btn-outline btn-outline-dashed btn btn-active-light-primary justify-content-center py-4" onclick="document.getElementById('file-input').click();">
                                    <i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary"></i>
                                    <p class="mb-0">Click to upload files</p>
                                    <input onchange="handleFileSelect(event)" type="file" id="file-input" multiple="" style="display:none;">
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <!-- Uploaded files preview list -->
                                        <label class="text-gray-800 fw-bold mb-1">New Attachments</label>
                                        <div id="file-list" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <!-- Uploaded files From Server preview list -->
                                        <label class="text-gray-800 fw-bold mb-1">Uploaded Attachments</label>
                                        <div id="file-list-uploaded" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
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