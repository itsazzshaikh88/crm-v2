<div class="modal fade" tabindex="-1" id="client-list-modal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content p-0">
            <div class="modal-header d-flex flex-column align-items-center justify-content-between p-4">
                <div class="w-100 d-flex align-items-center justify-content-between p-0">
                    <h5 class="modal-title">Select or Create New Client</h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="btn btn-outline btn-outline-dashed btn-active-light-primary active w-100 p-4 mt-4">
                    <p class="fw-normal text-muted fs-6">
                        Which client would you like to create this for?
                    </p>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-center flex-grow">
                            <input type="text" class="form-control form-control-sm" placeholder="Search Customer ..">
                        </div>
                        <div class="d-flex align-items-center justify-content-center fw-bold">OR</div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="openNewClientModal()">Create New Client</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="mb-10 mt-4 py-4 px-6">
                    <!--begin::Label-->
                    <label class="required fw-bold fs-4 mb-5">Client List</label>
                    <!--end::Label-->
                    <div id="modal-client-list">

                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex align-items-center justify-content-between w-100">
                <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
            </div>
        </div>
    </div>
</div>