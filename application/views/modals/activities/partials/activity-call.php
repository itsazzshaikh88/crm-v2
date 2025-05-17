<form onsubmit="addLeadActivity(event)" method="post" enctype="multipart/form-data">
    <input type="hidden" name="custom-activity-modal-call-ACTIVITY_UUID" id="custom-activity-modal-call-ACTIVITY_UUID" value="">
    <input type="hidden" name="custom-activity-modal-call-ACTIVITY_LEAD_ID" id="custom-activity-modal-call-ACTIVITY_LEAD_ID" value="">
    <input type="hidden" name="custom-activity-modal-call-ACTIVITY_ID" id="custom-activity-modal-call-ACTIVITY_ID" value="">
    <div class="row d-none" id="custom-activity-modal-call">
        <div class="col-md-12">
            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed  p-6">
                <!--begin::Icon-->
                <i class="fa-solid fa-phone-volume fs-2tx text-warning me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> <!--end::Icon-->

                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-grow-1 ">
                    <!--begin::Content-->
                    <div class=" fw-semibold">
                        <h4 class="text-gray-900 fw-bold">Log a Call</h4>
                        <div class="fs-7 text-gray-700 ">Record details of your call interaction with the <span class="text-primary fw-bold">lead</span>. Include purpose, duration, and any follow-up required.</div>
                    </div>
                    <!--end::Content-->

                </div>
                <!--end::Wrapper-->
            </div>
        </div>
        <div class="col-md-12 my-4">
            <div class="d-flex mb-1 gap-2">
                <div class="d-inline-flex form-floating w-50">
                    <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="custom-activity-modal-call-lbl-LEAD_NUMBER" id="custom-activity-modal-call-lbl-LEAD_NUMBER" readonly>
                    <label for="custom-activity-modal-call-lbl-LEAD_NUMBER" class="text-gray-600">Reference Number <span class="text-danger">*</span></label>
                </div>
                <div class="d-inline-flex form-floating w-50">
                    <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="custom-activity-modal-call-ACTIVITY_TYPE" id="custom-activity-modal-call-ACTIVITY_TYPE" readonly value="Call">
                    <label for="custom-activity-modal-call-ACTIVITY_TYPE" class="text-gray-600">Activity Source <span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="d-flex mb-1 gap-2">
                <div class="w-50 d-flex flex-column">
                    <div class="d-inline-flex form-floating">
                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="CALL_DURATION" id="CALL_DURATION">
                        <label for="CALL_DURATION" class="text-gray-600">Duration of the Call <span class="text-danger">*</span></label>
                    </div>
                    <span class="text-danger err-lbl fs-8 mb-1" id="act-lbl-CALL_DURATION"></span>
                </div>
                <div class="w-50 d-flex flex-column">
                    <div class="d-inline-flex form-floating">
                        <input type="date" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="FOLLOW_UP_DATE" id="FOLLOW_UP_DATE">
                        <label for="FOLLOW_UP_DATE" class="text-gray-600">Follow-Up Date <span class="text-danger">*</span></label>
                    </div>
                    <span class="text-danger err-lbl fs-8 mb-1" id="act-lbl-FOLLOW_UP_DATE"></span>
                </div>
            </div>
            <div class="d-inline-flex form-floating w-100 mb-3">
                <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="CALL_PURPOSE" id="CALL_PURPOSE">
                <label for="CALL_PURPOSE" class="text-gray-600">Purpose of the Call <span class="text-danger">*</span></label>
            </div>
            <span class="text-danger err-lbl fs-8 mb-1" id="act-lbl-CALL_PURPOSE"></span>
            <div id="custom-activity-modal-call-editor" name="custom-activity-modal-call-editor" class="h-100px mb-2 quill-editor-container bg-white border-blue-100"></div>
            <div class="position-absolute start-0 top-0 h-100 w-100 d-flex flex-column align-items-center justify-content-center call-activity-loader-container activity-loader-container d-none">
                <div class="spinner app-mt-40"></div>
                <p><small class="text-slate-500 fw-normal">Fetching Activity Details, Please Wait ....</small></p>
            </div>
            <div class="my-3 text-end">
                <button class="btn btn-sm btn-warning" id="btn-add-activities">Save Call Log</button>
            </div>
        </div>
    </div>
</form>