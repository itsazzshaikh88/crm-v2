<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start  container-xxl ">
    <div class="content flex-row-fluid" id="kt_content">
        <form id="form" class="form d-flex flex-column flex-lg-row" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <div class="d-flex flex-column flex-row-fluid gap-4 gap-lg-4">
                <div class="d-flex flex-column gap-4 gap-lg-4">
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>General</h2>
                                <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
                                <input type="hidden" name="PRODUCT_ID" id="PRODUCT_ID" value="">
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label for="DIVISION" class="required form-label">Division</label>
                                        <select type="text" name="DIVISION" id="DIVISION" class="form-control mb-2">
                                            <option value="">Select Division</option>
                                            <option value="242">Non-Food</option>
                                            <option value="444">Food</option>
                                        </select>
                                        <span class="text-danger err-lbl" id="lbl-DIVISION"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <label class="required form-label">Categories</label>
                                                <small id="fetch-category-label" class="fw-bold text-warning ms-2 d-none">Fetching Categories ...</small>
                                            </div>
                                            <label for="CATEGORY_ID" class="cursor-pointer text-primary text-decoration-underline"><small>Add Category</small></label>
                                        </div>
                                        <select type="text" name="CATEGORY_ID" id="CATEGORY_ID" class="form-control mb-2">
                                            <option value="">Select Category</option>
                                        </select>
                                        <span class="text-danger err-lbl" id="lbl-CATEGORY_ID"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label for="STATUS" class="required form-label">Status</label>
                                        <select type="text" name="STATUS" id="STATUS" class="form-control mb-2">
                                            <option value="">Select Status</option>
                                            <option selected value="active">Active</option>
                                            <option value="inactive">In-Active</option>
                                            <option value="discontinued">Discontinued</option>
                                        </select>
                                        <span class="text-danger err-lbl" id="lbl-STATUS"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-10 fv-row">
                                <label for="PRODUCT_NAME" class="required form-label">Product Name</label>
                                <input type="text" name="PRODUCT_NAME" id="PRODUCT_NAME" class="form-control mb-2" placeholder="Product name" value="" />
                                <div class="text-muted fs-7">A product name is required and recommended to be unique.</div>
                                <span class="text-danger err-lbl" id="lbl-PRODUCT_NAME"></span>
                            </div>
                            <div>
                                <label class="form-label">Description</label>
                                <div id="productDescription" name="productDescription" class="min-h-200px mb-2"></div>
                                <div class="text-muted fs-7">Set a description to the product for better visibility.</div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Pricing</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label for="BASE_PRICE" class="required form-label">Base Price</label>
                                        <input type="text" name="BASE_PRICE" id="BASE_PRICE" class="form-control mb-2" placeholder="Product price" value="" />
                                        <div class="text-muted fs-7">Set the product price.</div>
                                        <span class="text-danger err-lbl" id="lbl-BASE_PRICE"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label for="CURRENCY" class="required form-label">Currency</label>
                                        <input type="text" name="CURRENCY" id="CURRENCY" class="form-control mb-2" placeholder="Currency" value="SAR" />
                                        <span class="text-danger err-lbl" id="lbl-CURRENCY"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-6 fw-semibold mb-2">
                                    Discount Type
                                </label>
                                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                                    <div class="col">
                                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary active d-flex text-start p-6" data-kt-button="true">
                                            <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                <input class="form-check-input discount_option" type="radio" name="DISCOUNT_TYPE" value="1" checked="checked" />
                                            </span>
                                            <span class="ms-5">
                                                <span class="fs-4 fw-bold text-gray-800 d-block">No Discount</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col">
                                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary  d-flex text-start p-6" data-kt-button="true">
                                            <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                <input class="form-check-input discount_option" type="radio" name="DISCOUNT_TYPE" value="2" />
                                            </span>
                                            <span class="ms-5">
                                                <span class="fs-4 fw-bold text-gray-800 d-block">Percentage %</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-none mb-10 fv-row" id="kt_ecommerce_add_product_discount_percentage">
                                <label class="form-label">Set Discount Percentage</label>
                                <div class="d-flex flex-column text-center mb-5">
                                    <div class="d-flex align-items-start justify-content-center mb-7">
                                        <span class="fw-bold fs-3x" id="kt_ecommerce_add_product_discount_label">0</span>
                                        <span class="fw-bold fs-4 mt-1 ms-2">%</span>
                                        <input type="hidden" name="DISCOUNT_PERCENTAGE" id="DISCOUNT_PERCENTAGE">
                                    </div>
                                    <div id="kt_ecommerce_add_product_discount_slider" class="noUi-sm"></div>
                                </div>
                                <div class="text-muted fs-7">Set a percentage discount to be applied on this product.</div>
                            </div>
                            <div class="d-flex flex-wrap gap-5">
                                <div class="fv-row w-100 flex-md-root">
                                    <label for="TAXABLE" class="required form-label">Tax Class</label>
                                    <select class="form-control mb-2" name="TAXABLE" id="TAXABLE">
                                        <option value="">Select Tax Class</option>
                                        <option value="no">Tax Free</option>
                                        <option value="yes">Taxable Goods</option>
                                    </select>
                                    <div class="text-muted fs-7">Set the product tax class.</div>
                                    <span class="text-danger err-lbl" id="lbl-TAXABLE"></span>
                                </div>
                                <div class="fv-row w-100 flex-md-root">
                                    <label for="TAX_PERCENTAGE" class="required form-label">VAT Amount (%)</label>
                                    <input type="text" class="form-control mb-2" value="" name="TAX_PERCENTAGE" id="TAX_PERCENTAGE" />
                                    <div class="text-muted fs-7">Set the product VAT about.</div>
                                    <span class="text-danger err-lbl" id="lbl-TAX_PERCENTAGE"></span>
                                </div>
                            </div>
                            <!--end:Tax-->
                        </div>
                    </div>
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Media</h2>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!-- Custom styled upload box -->
                            <div id="upload-box" class="upload-box d-flex align-items-center btn-outline btn-outline-dashed btn btn-active-light-primary" onclick="document.getElementById('file-input').click();">
                                <i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary"></i>
                                <p class="my-4 mb-0">Click to upload files</p>
                                <input onchange="handleFileSelect(event)" type="file" id="file-input" multiple style="display:none;">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Uploaded files preview list -->
                                    <h5 class="my-4">New Attached Files</h5>
                                    <div id="file-list" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Uploaded files From Server preview list -->
                                    <h5 class="my-4">Uploaded Files</h5>
                                    <div id="file-list-uploaded" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                </div>
                            </div>
                        </div>
                        <!--end::Card header-->
                    </div>
                </div>
                <div class="d-flex flex-column gap-4 gap-lg-4">
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Inventory</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-10 fv-row">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Minimum Quantity</label>
                                        <div class="d-flex gap-3">
                                            <input type="number" name="MIN_QTY" id="MIN_QTY" class="form-control mb-2" placeholder="Minimum Quantity" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Maximum Quantity</label>
                                        <div class="d-flex gap-3">
                                            <input type="number" name="MAX_QTY" id="MAX_QTY" class="form-control mb-2" placeholder="Maximum Quantity" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Available Quantity</label>
                                        <div class="d-flex gap-3">
                                            <input type="number" name="AVL_QTY" id="AVL_QTY" class="form-control mb-2" placeholder="Available   Quantity" value="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-10 fv-row">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">SKU</label>
                                        <input type="text" name="SKU" id="SKU" class="form-control mb-2" placeholder="SKU Number" value="" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Barcode</label>
                                        <input type="text" name="BARCODE" id="BARCODE" class="form-control mb-2" placeholder="Barcode Number" value="" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Allow Backorders</label>
                                        <div class="form-check form-check-custom form-check-solid mb-2">
                                            <input class="form-check-input" type="checkbox" name="ALLOW_BACKORDERS" value="" />
                                            <label class="form-check-label">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="text-muted fs-7">Allow customers to purchase products that are out of stock.</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Variations</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="" data-kt-ecommerce-catalog-add-product="auto-options">
                                <label class="form-label">Add Product Variations</label>
                                <div id="kt_ecommerce_add_product_options">
                                    <div class="form-group">
                                        <div data-repeater-list="kt_ecommerce_add_product_options" class="d-flex flex-column gap-3">
                                            <div data-repeater-item class="form-group d-flex flex-wrap align-items-center gap-5">
                                                <div class="w-100 w-md-200px">
                                                    <select class="form-control" name="VARIANT_TYPE" id="VARIANT_TYPE">
                                                        <option value="">Select a variation</option>
                                                        <option value="color">Color</option>
                                                        <option value="size">Size</option>
                                                        <option value="material">Material</option>
                                                        <option value="style">Style</option>
                                                    </select>
                                                </div>
                                                <input type="text" class="form-control mw-100 w-200px" name="product_option_value" placeholder="Variation" />
                                                <button type="button" data-repeater-delete class="btn btn-sm btn-icon btn-light-danger">
                                                    <i class="las la-times fs-1"><span class="path1"></span><span class="path2"></span></i> </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-5">
                                        <button type="button" data-repeater-create class="btn btn-sm btn-light-primary">
                                            <i class="las la-plus fs-2"></i> Add another variation
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Technical Specification</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div id="kt_ecommerce_add_product_shipping" class="">
                                <div class="mb-10 fv-row">
                                    <label for="WEIGHT" class="form-label">Weight</label>
                                    <input type="text" name="WEIGHT" id="WEIGHT" class="form-control mb-2" placeholder="Product weight" value="" />
                                    <div class="text-muted fs-7">Set a product weight in kilograms (kg).</div>
                                </div>
                                <div class="fv-row">
                                    <label class="form-label">Dimension</label>
                                    <div class="d-flex flex-wrap flex-sm-nowrap gap-3">
                                        <input type="number" name="WIDTH" id="WIDTH" class="form-control mb-2" placeholder="Width (w)" value="" />
                                        <input type="number" name="HEIGHT" id="HEIGHT" class="form-control mb-2" placeholder="Height (h)" value="" />
                                        <input type="number" name="LENGTH" id="LENGTH" class="form-control mb-2" placeholder="length (l)" value="" />
                                    </div>
                                    <div class="text-muted fs-7">Enter the product dimensions in centimeters (cm).</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mb-10">
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
<?php $this->load->view('loaders/full-page-loader'); ?>