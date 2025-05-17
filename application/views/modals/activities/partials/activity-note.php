<form onsubmit="addLeadActivity(event)" method="post" enctype="multipart/form-data">
    <input type="hidden" name="custom-activity-modal-notes-ACTIVITY_UUID" id="custom-activity-modal-notes-ACTIVITY_UUID" value="">
    <input type="hidden" name="custom-activity-modal-notes-ACTIVITY_LEAD_ID" id="custom-activity-modal-notes-ACTIVITY_LEAD_ID" value="">
    <input type="hidden" name="custom-activity-modal-notes-ACTIVITY_ID" id="custom-activity-modal-notes-ACTIVITY_ID" value="">
    <div class="row d-none" id="custom-activity-modal-notes">
        <div class="col-md-12">
            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed  p-6">
                <!--begin::Icon-->
                <i class="fa-solid fa-notes-medical fs-2tx text-primary me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> <!--end::Icon-->

                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-grow-1 ">
                    <!--begin::Content-->
                    <div class=" fw-semibold">
                        <h4 class="text-gray-900 fw-bold">Add a Note</h4>
                        <div class="fs-7 text-gray-700 ">Document important observations, insights, or any key points related to the <span class="fw-bold text-primary">lead</span>.</div>
                    </div>
                    <!--end::Content-->

                </div>
                <!--end::Wrapper-->
            </div>
        </div>
        <div class="col-md-12 my-4">
            <div class="d-flex mb-1 gap-2">
                <div class="d-inline-flex form-floating w-50">
                    <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="custom-activity-modal-notes-lbl-LEAD_NUMBER" id="custom-activity-modal-notes-lbl-LEAD_NUMBER" readonly>
                    <label for="custom-activity-modal-notes-lbl-LEAD_NUMBER" class="text-gray-600">Reference Number <span class="text-danger">*</span></label>
                </div>
                <div class="d-inline-flex form-floating w-50">
                    <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="custom-activity-modal-notes-ACTIVITY_TYPE" id="custom-activity-modal-notes-ACTIVITY_TYPE" readonly value="Notes">
                    <label for="ACTIVITY_TYPE" class="text-gray-600">Activity Source <span class="text-danger">*</span></label>
                </div>
            </div>
            <div id="custom-activity-modal-notes-editor" name="custom-activity-modal-notes-editor" class="h-100px mb-2 quill-editor-container bg-white border-blue-100"></div>
            <span class="text-danger err-lbl fs-8 mb-1" id="act-lbl-NOTES"></span>

            <div class="position-absolute start-0 top-0 h-100 w-100 d-flex flex-column align-items-center justify-content-center notes-activity-loader-container activity-loader-container d-none">
                <div class="spinner app-mt-40"></div>
                <p><small class="text-slate-500 fw-normal">Fetching Activity Details, Please Wait ....</small></p>
            </div>

            <div class="my-3 text-end">
                <button class="btn btn-sm btn-primary" id="btn-add-activities">Save Note</button>
            </div>
        </div>
    </div>
</form>