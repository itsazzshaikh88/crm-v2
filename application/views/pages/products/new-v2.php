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
<div class="modal bg-body fade " tabindex="-1" id="newProductModal">
    <div class="modal-dialog bg-light modal-fullscreen">
        <div class="modal-content shadow-none bg-light ">
            <form onsubmit="submitForm(event)" method="post" enctype="multipart/form-data" id="productForm">
                <div class="modal-header modal-header-bg py-1">
                    <h4 class="modal-title text-white fw-normal"><i class="fa-solid fa-layer-group text-white fs-3 mb-0 me-2"></i> Product Management</h4>
                    <!-- Hidden Fields: START -->
                    <input type="hidden" name="PRODUCT_ID" id="PRODUCT_ID">
                    <!-- Hidden Fields: END -->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-danger ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeProductModal()">
                        <i class="fa-solid fa-xmark text-white fs-4"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body pb-2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="text-label-heading fw-normal">Product Details</h4>
                                <div>
                                    <!-- <button type="button" class="rounded-1 btn btn-sm btn-secondary" onclick="startOver()"><i class="fa-solid fa-rotate"></i> Start Over New Product</button> -->
                                    <button type="submit" class="rounded-1 btn btn-sm btn-success" id="submit-btn"><i class="fa-solid fa-plus"></i> Save Product Details</button>
                                </div>
                            </div>
                            <div class="row g-1 mt-2 mb-1">
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="DIVISION" class="text-gray-800 fw-bold">Division <span class="text-danger">*</span></label>
                                        <select name="DIVISION" id="DIVISION" class="form-control form-control-sm border border-blue-100 text-gray-700 ">
                                            <option value="">Select Division</option>
                                            <option value="242">Non-Food</option>
                                            <option value="444">Food</option>

                                        </select>
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-DIVISION"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="CATEGORY_ID" class="text-gray-800 fw-bold">Categories <span class="text-danger">*</span></label>
                                        <select name="CATEGORY_ID" id="CATEGORY_ID" class="form-control form-control-sm border border-blue-100 text-gray-700 ">
                                            <option value="">Select Category</option>

                                        </select>
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-CATEGORY_ID"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="STATUS" class="text-gray-800 fw-bold">Status <span class="text-danger">*</span></label>
                                        <select name="STATUS" id="STATUS" class="form-control form-control-sm border border-blue-100 text-gray-700 ">
                                            <option value="">Select Status</option>
                                            <option selected value="active">Active</option>
                                            <option value="inactive">In-Active</option>
                                            <option value="discontinued">Discontinued</option>

                                        </select>
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-STATUS"></p>
                                </div>
                            </div>
                            <div class="row g-1 mb-1">
                                <div class="col-md-12">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="PRODUCT_NAME" class="text-gray-800 fw-bold">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="PRODUCT_NAME" id="PRODUCT_NAME">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-PRODUCT_NAME"></p>
                                </div>
                            </div>
                            <div class="row g-1 mb-1">
                                <h4 class="text-label-heading fw-normal my-2">Product Pricing</h4>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="BASE_PRICE" class="text-gray-800 fw-bold">Base Price <span class="text-danger">*</span></label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="BASE_PRICE" id="BASE_PRICE">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-BASE_PRICE"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="CURRENCY" class="text-gray-800 fw-bold">Currency <span class="text-danger">*</span></label>
                                        <input type="text" value="SAR" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="CURRENCY" id="CURRENCY">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-CURRENCY"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="TAXABLE" class="text-gray-800 fw-bold">Taxable <span class="text-danger">*</span></label>
                                        <select name="TAXABLE" id="TAXABLE" class="form-control form-control-sm border border-blue-100 text-gray-700 ">
                                            <option value="">Select </option>
                                            <option selected value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-TAXABLE"></p>
                                </div>
                            </div>
                            <div class="row g-1 mb-1">
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="TAX_PERCENTAGE" class="text-gray-800 fw-bold">Tax Percentage <span class="text-danger">*</span></label>
                                        <input type="text" value="0" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="TAX_PERCENTAGE" id="TAX_PERCENTAGE">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-TAX_PERCENTAGE"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="DISCOUNT_PERCENTAGE" class="text-gray-800 fw-bold">Discount in % </label>
                                        <input type="text" value="0" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="DISCOUNT_PERCENTAGE" id="DISCOUNT_PERCENTAGE">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-DISCOUNT_PERCENTAGE"></p>
                                </div>
                            </div>
                            <div class="row g-1 mb-1">
                                <div class="col-md-12">
                                    <h4 class="text-label-heading fw-normal my-2">Technical Specifications</h4>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="WEIGHT" class="text-gray-800 fw-bold">Product Weight</label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="WEIGHT" id="WEIGHT">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-WEIGHT"></p>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="WIDTH" class="text-gray-800 fw-bold">Product Width<span class="text-danger">*</span> </label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="WIDTH" id="WIDTH">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-WIDTH"></p>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="LENGTH" class="text-gray-800 fw-bold">Product Length<span class="text-danger">*</span> </label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="LENGTH" id="LENGTH">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-LENGTH"></p>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="HEIGHT" class="text-gray-800 fw-bold">Product Height<span class="text-danger">*</span> </label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="HEIGHT" id="HEIGHT">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-HEIGHT"></p>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="VOLUME" class="text-gray-800 fw-bold">Volume<span class="text-danger">*</span> </label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="VOLUME" id="VOLUME">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-VOLUME"></p>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="SHAPE" class="text-gray-800 fw-bold">Shape<span class="text-danger">*</span> </label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="SHAPE" id="SHAPE">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-SHAPE"></p>
                                </div>
                            </div>
                            <div class="row g-1 mb-1">
                                <h4 class="text-label-heading fw-normal my-2">Inventory Details</h4>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="MIN_QTY" class="text-gray-800 fw-bold">Minimum Quantity </label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="MIN_QTY" id="MIN_QTY">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-MIN_QTY"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="MAX_QTY" class="text-gray-800 fw-bold">Maximum Quantity </label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="MAX_QTY" id="MAX_QTY">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-MAX_QTY"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="AVL_QTY" class="text-gray-800 fw-bold">Available Quantity </label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="AVL_QTY" id="AVL_QTY">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-AVL_QTY"></p>
                                </div>
                            </div>
                            <div class="row g-1 mb-1">
                                <!-- <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="SKU" class="text-gray-800 fw-bold">SKU Number </label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="SKU" id="SKU">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="BARCODE" class="text-gray-800 fw-bold">Barcode Number </label>
                                        <input type="text" value="" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="BARCODE" id="BARCODE">
                                    </div>
                                </div> -->
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="ALLOW_BACKORDERS" class="text-gray-800 fw-bold">Allow Backorders </label>
                                        <select name="ALLOW_BACKORDERS" id="ALLOW_BACKORDERS" class="form-control form-control-sm border border-blue-100 text-gray-700 ">
                                            <option value="">Select </option>
                                            <option value="1">Yes</option>
                                            <option selected value="0">No</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 border-start border-secondarys ">
                            <div class="row g-1 mb-1">
                                <h4 class="text-label-heading fw-normal">Product Description</h4>
                                <div class="col-md-12">
                                    <div id="productDescription" name="productDescription" class="bg-white border border-blue-100 mt-1" style="height: 100px;"></div>
                                </div>
                            </div>
                            <div class="row g-1 mb-1">
                                <h4 class="text-label-heading fw-normal my-2">Product Images</h4>
                                <div class="col-md-12">
                                    <div id="upload-box" class="upload-box d-flex align-items-center justify-content-center btn-outline btn-outline-dashed btn btn-active-light-primary bg-white py-6" onclick="document.getElementById('file-input').click();">
                                        <i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary"></i>
                                        <p class="mb-0">Click to upload files</p>
                                        <input onchange="handleFileSelect(event)" type="file" id="file-input" multiple style="display:none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <!-- Uploaded files preview list -->
                                    <h6 class="my-2 fw-normal">New Attached Files</h6>
                                    <div id="file-list" class="my-2 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                </div>

                                <div class="col-md-12">
                                    <!-- Uploaded files From Server preview list -->
                                    <h6 class="my-2 fw-normal">Uploaded Files</h6>
                                    <div id="file-list-uploaded" class="my-2 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>