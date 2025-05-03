<style>
    .ql-toolbar {
        border-color: #dbeafe !important;
    }
</style>
<div class="modal fade" tabindex="-1" id="emailActivityModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body py-4">
                <div class="row mb-2">
                    <div class="col-md-12 text-end d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-normal">Send New Email</h5>
                        <!--begin::Close-->
                        <div class="btn btn-sm btn-icon btn-light-primary" data-bs-dismiss="modal" aria-label="Close" onclick="closeEmailActivityModal()">
                            <i class="fa-solid fa-xmark fs-4"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <div></div>
                </div>
                <form onsubmit="submitEmailActivityMail(event)" method="post" enctype="multipart/form-data" id="emailActivityMail">
                    <div class="row border-top border-light pt-4">
                        <div class="form-group mb-2">
                            <label for="" class="mb-2 fs-7">Recepient Email Address <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control fs-7 border-blue-100" placeholder="Enter Valid Email" name="recepient_email" id="recepient_email">
                            <span class="text-danger err-lbl fs-8 mb-1" id="act-email-lbl-RECEIPIENT"></span>
                        </div>
                        <div class="form-group mb-2">
                            <label for="" class="mb-2 fs-7">Subject <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control fs-7 border-blue-100" placeholder="Enter Valid Email" name="email_subject" id="email_subject">
                            <span class="text-danger err-lbl fs-8 mb-1" id="act-email-lbl-SUBJECT"></span>
                        </div>
                        <div class="form-group mb-2">
                            <label for="" class="mb-2 fs-7">Email Body <span class="text-danger">*</span> </label>
                            <div id="email-body-content" style="height: 300px;"></div>
                            <span class="text-danger err-lbl fs-8 mb-1" id="act-email-lbl-MESSAGE"></span>
                        </div>
                        <div class="mt-4 text-end">
                            <button class="btn btn-sm btn-light me-2" type="button" onclick="closeEmailActivityModal('cancel')">Cancel</button>
                            <button class="btn btn-sm btn-primary" id="submit-btn"> <i class="fa-solid fa-paper-plane"></i> Send Email</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>