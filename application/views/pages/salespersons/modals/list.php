<div class="modal fade" tabindex="-1" id="sales-person-list-modal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content p-0">
            <div class="modal-header d-flex flex-column align-items-center justify-content-between p-4 bg-light">
                <div class="w-100 d-flex align-items-center justify-content-between p-0">
                    <h5 class="modal-title">Select Sales Person</h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <input type="text" class="form-control form-control-sm" placeholder="Search Sales person by user id, by name and email .." oninput="debouncedSearchSalesPersonListFromModal(this)">
            </div>
            <div class="modal-body p-0">
                <div class="mb-10 mt-4 py-4 px-6">
                    <!--begin::Label-->
                    <label class="required fw-bold fs-4 mb-5">Sales Person List</label>
                    <!--end::Label-->
                    <div id="modal-sales-person-list">

                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex align-items-center justify-content-between w-100 bg-light pt-0">
                <?= renderPaginate('spml-current-page', 'spml-total-pages', 'spml-page-of-pages', 'spml-range-of-records', 'handleSalesPersonListPagination') ?>
            </div>
        </div>
    </div>
</div>