<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start  container-xxl ">
    <div class="content flex-row-fluid" id="kt_content">
        <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
        <div class="form row">
            <!--begin::Aside column-->
            <div class="col-md-3">
                <!--begin::Thumbnail settings-->
                <div class="card card-flush py-4 mb-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h3 class="fw-bold m-0">Product Images</h3>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body text-center pt-0">
                        <!--begin::Image input-->
                        <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                            <!--begin::Preview existing avatar-->
                            <div class="image-input-wrapper w-150px h-150px mx-auto">
                                <img src="assets/images/default-image.png" class="img-fluid" alt="" id="product-image-container" srcset="">
                            </div>
                            <!--end::Preview existing avatar-->

                        </div>
                        <!--end::Image input-->

                        <!--begin::Description-->
                        <div class="text-muted fs-7">
                            <div class="d-flex flex-wrap gap-2 justify-content-start" id="product-image-gallery">

                            </div>
                        </div>
                        <!--end::Description-->

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Thumbnail settings-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h3 class="fw-bold m-0">Technical Specification</h3>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div>
                            <label class="fw-semibold text-muted">Weight</label>
                            <p class="fw-bold" id="lbl-WEIGHT"></p>
                        </div>
                        <div>
                            <label class="fw-semibold text-muted">Dimension - Width</label>
                            <p class="fw-bold" id="lbl-WIDTH"></p>
                        </div>
                        <div>
                            <label class="fw-semibold text-muted">Dimension - Height</label>
                            <p class="fw-bold" id="lbl-HEIGHT"></p>
                        </div>
                        <div>
                            <label class="fw-semibold text-muted">Dimension - Length</label>
                            <p class="fw-bold" id="lbl-LENGTH"></p>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
            <!--end::Aside column-->

            <!--begin::Main column-->
            <div class="col-md-9">
                <div class="card mb-4" id="kt_profile_details_view">
                    <!--begin::Card header-->
                    <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Product Details</h3>
                        </div>
                        <!--end::Card title-->

                        <!--begin::Action-->
                        <a href="" id="edit-product-link" class="btn btn-primary align-self-center d-none">Edit Product</a>
                        <!--end::Action-->
                    </div>
                    <!--begin::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Division</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fs-6" id="lbl-DIVISION"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Category</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold fs-6" id="lbl-CATEGORY_NAME"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Product Status</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold fs-6 badge" id="lbl-STATUS"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Product Name</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-900" id="lbl-PRODUCT_NAME"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row mb-10">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Product Description</label>
                            <!--begin::Label-->

                            <!--begin::Label-->
                            <div class="col-lg-8">
                                <div class="d-flex bg-light rounded border-secondary border border-dashed  p-6">

                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-stack flex-grow-1 ">
                                        <!--begin::Content-->
                                        <div class=" fw-semibold" id="lbl-DESCRIPTION">
                                        </div>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                            </div>
                            <!--begin::Label-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                </div>

                <div class="card mb-4" id="kt_profile_details_view">
                    <!--begin::Card header-->
                    <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Product Pricing Details</h3>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--begin::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Base Price</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fs-6" id="lbl-BASE_PRICE"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Currency</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fs-6" id="lbl-CURRENCY"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Product Discount Type</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fs-6" id="lbl-DISCOUNT_TYPE"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Taxable</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-2 fv-row">
                                <span class="fs-6" id="lbl-TAXABLE"></span>
                            </div>
                            <!--end::Col-->
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">VAT Percentage</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-2 fv-row">
                                <span class="fs-6" id="lbl-TAX_PERCENTAGE"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                </div>

                <div class="card mb-4" id="kt_profile_details_view">
                    <!--begin::Card header-->
                    <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Product Inventory Details</h3>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--begin::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Minimum Quantity</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fs-6" id="lbl-MIN_QTY"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Maximum Quantity</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fs-6" id="lbl-MAX_QTY"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Available Quantity</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fs-6" id="lbl-AVL_QTY"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">SKU</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-2 fv-row">
                                <span class="fs-6" id="lbl-SKU"></span>
                            </div>
                            <!--end::Col-->
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Barcode</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-2 fv-row">
                                <span class="fs-6" id="BARCODE"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Allow Backorders</label>
                            <!--end::Label-->

                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fs-6" id="lbl-ALLOW_BACKORDERS"></span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
            <!--end::Main column-->
        </div>
    </div>
</div>
<?php $this->load->view('loaders/full-page-loader'); ?>