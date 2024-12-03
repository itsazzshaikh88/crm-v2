<form onsubmit="addLeadActivity(event)" method="post" enctype="multipart/form-data">
    <input type="hidden" name="custom-activity-modal-meeting-ACTIVITY_UUID" id="custom-activity-modal-meeting-ACTIVITY_UUID" value="">
    <input type="hidden" name="custom-activity-modal-meeting-ACTIVITY_LEAD_ID" id="custom-activity-modal-meeting-ACTIVITY_LEAD_ID" value="">
    <input type="hidden" name="custom-activity-modal-meeting-ACTIVITY_ID" id="custom-activity-modal-meeting-ACTIVITY_ID" value="">
    <div class="row d-none" id="custom-activity-modal-meeting">
        <div class="col-md-12">
            <div class="notice d-flex bg-light-info rounded border-info border border-dashed  p-6">
                <!--begin::Icon-->
                <i class="fa-solid fa-video fs-2tx text-info me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> <!--end::Icon-->

                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-grow-1 ">
                    <!--begin::Content-->
                    <div class=" fw-semibold">
                        <h4 class="text-gray-900 fw-bold">Schedule a Meeting</h4>
                        <div class="fs-7 text-gray-700 ">Plan and organize meetings effectively. Include location, attendees, and an agenda.</div>
                    </div>
                    <!--end::Content-->

                </div>
                <!--end::Wrapper-->
            </div>
        </div>
        <div class="col-md-12 my-4">
            <div class="d-flex mb-1 gap-2">
                <div class="d-inline-flex form-floating w-50">
                    <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="custom-activity-modal-meeting-lbl-LEAD_NUMBER" id="custom-activity-modal-meeting-lbl-LEAD_NUMBER" readonly>
                    <label for="custom-activity-modal-meeting-lbl-LEAD_NUMBER" class="text-gray-600">Lead Number <span class="text-danger">*</span></label>
                </div>
                <div class="d-inline-flex form-floating w-50">
                    <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="custom-activity-modal-meeting-ACTIVITY_TYPE" id="custom-activity-modal-meeting-ACTIVITY_TYPE" readonly value="Meeting">
                    <label for="ACTIVITY_TYPE" class="text-gray-600">Activity Source <span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="d-inline-flex form-floating w-100 mb-1">
                <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="AGENDA" id="AGENDA">
                <label for="AGENDA" class="text-gray-600">Meeting Agenda <span class="text-danger">*</span></label>
            </div>
            <span class="text-danger err-lbl fs-8 mb-1" id="act-lbl-AGENDA"></span>
            <div class="d-inline-flex form-floating w-100 mb-1">
                <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="LOCATION" id="LOCATION">
                <label for="LOCATION" class="text-gray-600">Meeting Location <span class="text-danger">*</span></label>
            </div>
            <span class="text-danger err-lbl fs-8 mb-1" id="act-lbl-LOCATION"></span>
            <div class="border border-blue-100 text-gray-700 mb-1 rounded py-4 px-4 bg-light-info">
                <label for="ATTENDEES" class="text-info mb-4">Meeting Attendees <span class="text-danger">*</span> <small class="ms-4 fs-9">(Use comma to add attendee)</small> </label>
                <div class="d-flex align-items-start justify-content-start flex-wrap flex-column">
                    <div class="mb-2 inline-flex">
                        <input type="text" class="form-control border border-blue-200 fs-8 px-4 py-2 rounded shadow-none fw-normal" placeholder="Enter Attendee name" onkeyup="setMeetingAttendees(event)">
                    </div>
                    <div id="meeting-attendees-container" class="flex flex-wrap align-items-center justify-content-start gap-4">
                    </div>
                </div>
            </div>
            <span class="text-danger err-lbl fs-8 mb-1" id="act-lbl-ATTENDEES"></span>
            <div id="custom-activity-modal-meeting-editor" name="custom-activity-modal-meeting-editor" class="h-100px mb-2 quill-editor-container bg-white border-blue-100"></div>
            <span class="text-danger err-lbl fs-8 mb-1" id="act-lbl-NOTES"></span>

            <div class="position-absolute start-0 top-0 h-100 w-100 d-flex flex-column align-items-center justify-content-center meeting-activity-loader-container activity-loader-container d-none">
                <div class="spinner app-mt-40"></div>
                <p><small class="text-slate-500 fw-normal">Fetching Activity Details, Please Wait ....</small></p>
            </div>

            <div class="my-3 text-end">
                <button class="btn btn-sm btn-info" id="btn-add-activities">Save Meeting</button>
            </div>
        </div>
    </div>
</form>