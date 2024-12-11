<style>
    .text-loader {
        position: absolute;
        top: 0;
        left: 0;
        background-color: red;
    }
</style>
<div class="modal bg-body fade" tabindex="-1" id="newMomModal">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content shadow-none">
            <form onsubmit="submitMinutes(event)" method="post" enctype="multipart/form-data" id="momForm">
                <div class="modal-header">
                    <h2 class="modal-title text-danger"><i class="fa-regular fa-clock text-danger fs-3 mb-0 me-2"></i> Minutes of Meeting</h2>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeMOMModal()">
                        <i class="fa-solid fa-xmark text-danger fs-4"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body bg-light">
                    <div class="row ">
                        <div class="col-md-5 mom-form-elements">
                            <div class="">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="text-slate-700 fw-normal">Meeting Details</h3>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="startOver()"><i class="fa-solid fa-rotate"></i> Start Over New Minutes</button>
                                        <button type="submit" class="btn btn-sm btn-danger" id="submit-btn"><i class="fa-solid fa-plus"></i> Save changes</button>
                                    </div>
                                </div>
                                <input type="hidden" name="MOM_ID" id="MOM_ID">
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " placeholder="" name="MEETING_TITLE" id="MEETING_TITLE">
                                        <label for="MEETING_TITLE" class="text-gray-600">Meeting Title <span class="text-danger">*</span></label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-MEETING_TITLE"></span>
                                    <div class="form-floating mb-2">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " placeholder="" name="AGENDA" id="AGENDA">
                                        <label for="AGENDA" class="text-gray-600">Meeting Agenda <span class="text-danger">*</span></label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-AGENDA"></span>
                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                                        <div class="d-flex flex-column w-50">
                                            <div class="d-inline-flex form-floating">
                                                <input type="date" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="MEETING_DATE" id="MEETING_DATE">
                                                <label for="MEETING_DATE" class="text-gray-600">Meeting Date <span class="text-danger">*</span> </label>
                                            </div>
                                            <span class="text-danger err-lbl" id="lbl-MEETING_DATE"></span>
                                        </div>
                                        <div class="d-flex flex-column w-50">
                                            <div class="d-inline-flex form-floating">
                                                <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="DURATION" id="DURATION">
                                                <label for="DURATION" class="text-gray-600">Meeting Duration <span class="text-danger">*</span></label>
                                            </div>
                                            <span class="text-danger err-lbl" id="lbl-DURATION"></span>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="ORGANIZER" id="ORGANIZER">
                                        <label for="ORGANIZER" class="text-gray-600">Organizer <span class="text-danger">*</span></label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-ORGANIZER"></span>
                                    <div class="form-floating mb-2">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="COMPANY_NAME" id="COMPANY_NAME">
                                        <label for="COMPANY_NAME" class="text-gray-600">Meeting With Company Name <span class="text-danger">*</span></label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-COMPANY_NAME"></span>
                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                                        <div class="d-flex flex-column w-50">
                                            <div class="d-inline-flex form-floating ">
                                                <select class="form-select text-gray-700" name="MEETING_TYPE" id="MEETING_TYPE" aria-label="Floating label select example">
                                                    <option value="">Choose Status</option>
                                                    <option value="Project Review">Project Review</option>
                                                    <option value="Sales Call">Sales Call</option>
                                                    <option value="Strategy Planning">Strategy Planning</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <label for="MEETING_TYPE" class="text-gray-600">Meeting Type <span class="text-danger">*</span> </label>
                                            </div>
                                            <span class="text-danger err-lbl" id="lbl-MEETING_TYPE"></span>
                                        </div>
                                        <div class="d-flex flex-column w-50">
                                            <div class="d-inline-flex form-floating">
                                                <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="LOCATION_PLATFORM" id="LOCATION_PLATFORM">
                                                <label for="LOCATION_PLATFORM" class="text-gray-600">Platform / Location <span class="text-danger">*</span></label>
                                            </div>
                                            <span class="text-danger err-lbl" id="lbl-LOCATION_PLATFORM"></span>
                                        </div>
                                    </div>
                                    <div class="w-100 bg-white py-4 px-4 rounded">
                                        <div class="w-100 d-flex align-items-center justify-content-between">
                                            <h6 class="text-primary fw-normal">Meeting Attendees</h6>
                                            <button type="button" class="btn btn-sm border border-success text-success fw-bold fs-8 px-4 py-2" onclick="addMeetingAttendee()"> <i class="fs-7 fa-solid fa-plus text-success mb-0"></i> Add Attendee</button>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-row-dashed table-row-gray-300 gy-2" id="attendee-table">
                                                <thead>
                                                    <tr class=" fs-7 text-gray-800">
                                                        <th>#</th>
                                                        <th>Attendee Name</th>
                                                        <th>Attendee Email</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="attendee-table-tbody">
                                                    <tr>
                                                        <td>1</td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm fs-8 text-gray-700 fw-normal" name="attendee_name[]">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm fs-8 text-gray-700 fw-normal" name="attendee_email[]">
                                                        </td>
                                                        <td class="d-flex align-items-center justify-content-center" style="vertical-align: middle;">
                                                            <div class="d-flex align-items-center justify-content-center text-center">
                                                                <a href="javascript:void(0)" onclick="removeMeetingAttendee(this)" class="d-flex align-items-center justify-content-center text-center py-2 px-2 text-danger"><i class="fa fa-trash p-0 m-0 text-danger"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="" class="fs-6 text-gray-800"><i class="fa-regular fa-calendar-days text-gray-800"></i> Minutes of Meetings / Discussion Points <span class="text-danger">*</span> </label>
                                    <textarea name="DISCUSSION_TOPICS" id="DISCUSSION_TOPICS" class="form-control my-2 fw-normal border border-blue-100" rows="6" placeholder="Start adding your minutes of meeting here ...."></textarea>
                                </div>
                                <span class="text-danger err-lbl" id="lbl-DISCUSSION_TOPICS"></span>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="" class="fs-6 text-gray-800"><i class="fa-solid fa-note-sticky text-gray-800"></i> Meeting Conclusion</label>
                                    <textarea name="DECISIONS" id="DECISIONS" class="form-control my-2 fw-normal border border-blue-100" rows="6" placeholder="Start adding your minutes of meeting here ...."></textarea>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="FOLLOW_UP_REQUIRED" name="FOLLOW_UP_REQUIRED" />
                                        <label class="form-check-label" for="FOLLOW_UP_REQUIRED">
                                            Follow up Required
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>