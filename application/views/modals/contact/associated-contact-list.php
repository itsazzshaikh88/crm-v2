<div class="modal fade" tabindex="-1" id="newAssociatedContactModal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content p-0">
            <div class="modal-header d-flex flex-column align-items-center justify-content-between p-4">
                <div class="w-100 d-flex align-items-center justify-content-between p-0">
                    <h5 class="modal-title">Select Contact</h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-warning ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="btn btn-outline btn-outline-dashed btn-active-light-warning active w-100 p-4 mt-4">
                    <p class="fw-normal text-muted fs-6">
                        Enter contact details to search
                    </p>
                    <input type="text" class="form-control form-control-sm" placeholder="Search Customer ..">
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="mb-10 mt-4 py-4 px-6">
                    <!--begin::Label-->
                    <label class="required fw-bold fs-4 mb-5">Contacts List</label>
                    <!--end::Label-->
                    <div id="assoc-contact-modal-list"></div>
                </div>
            </div>
            <div class="modal-footer d-flex align-items-center justify-content-between w-100">
                <?= renderPaginate('assoc-contact-current-page', 'assoc-contact-total-pages', 'assoc-contact-page-of-pages', 'assoc-contact-range-of-records') ?>
            </div>
        </div>
    </div>
</div>