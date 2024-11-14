<div class="modal fade" tabindex="-1" id="product-list-modal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content p-0">
            <div class="modal-header d-flex flex-column align-items-center justify-content-between bg-light">
                <div class="w-100 d-flex flex-column align-items-center justify-content-between p-0">
                    <div class="w-100 d-flex align-items-center justify-content-between border-bottom border-bottom-dashed border-secondary">
                        <h5 class="modal-title">Choose Product</h5>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal" aria-label="Close" onclick="clearModalFilterInputs()">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <div class="w-100 mt-4">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="CATEGORY_ID"><small>Search Product</small></label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Enter product name or product code" oninput="debouncedInput(event)" id="searchInput">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="CATEGORY_ID"><small>Choose Category</small></label>
                                    <select name="CATEGORY_ID" id="CATEGORY_ID" class="form-control form-control-sm ">
                                        <option value="">Choose</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="CATEGORY_ID"><small class="">Click here to filter products</small></label>
                                    <button type="button" onclick="filterProducts()" class="d-flex btn btn-sm btn-primary fw-normal">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="mb-10 mt-4 py-4 px-6">
                    <!--begin::Label-->
                    <label class="required fw-bold fs-4 mb-5">Product List</label>
                    <!--end::Label-->
                    <div id="modal-product-list">

                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex align-items-center justify-content-between w-100">
                <?= renderPaginate('prd-mdl-current-page', 'prd-mdl-total-pages', 'prd-mdl-page-of-pages', 'prd-mdl-range-of-records') ?>
            </div>
        </div>
    </div>
</div>