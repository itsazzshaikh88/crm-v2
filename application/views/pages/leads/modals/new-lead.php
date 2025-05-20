<style>
    .text-loader {
        position: absolute;
        top: 0;
        left: 0;
        background-color: red;
    }
</style>
<?php
$activities = LEAD_ACTIVITY_OPTIONS;
?>
<div class="modal bg-body fade" tabindex="-1" id="newLeadModal">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content shadow-none">
            <form onsubmit="submitLead(event)" method="post" enctype="multipart/form-data" id="leadForm">
                <div class="modal-header">
                    <h2 class="modal-title text-primary"><i class="fa-solid fa-users text-primary fs-3 mb-0 me-2"></i> Leads Management</h2>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeLeadModal()">
                        <i class="fa-solid fa-xmark text-danger fs-4"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 bg-light lead-form-elements py-6 rounded">
                            <div class="">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="text-slate-700 fw-normal">Lead Details</h3>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="startOver()"><i class="fa-solid fa-rotate"></i> Start Over New Lead</button>
                                        <button type="submit" class="btn btn-sm btn-primary" id="submit-btn"><i class="fa-solid fa-plus"></i> Save changes</button>
                                    </div>
                                </div>
                                <input type="hidden" name="UUID" id="UUID">
                                <input type="hidden" name="LEAD_ID" id="LEAD_ID">
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-12">

                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                                        <div class="form-floating w-50">
                                            <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " placeholder="" readonly name="LEAD_NUMBER" id="LEAD_NUMBER">
                                            <label for="" class="text-gray-600">Lead Number</label>
                                        </div>
                                        <div class="d-inline-flex flex-column form-floating w-50">
                                            <?= render_org_select("ORG_ID", "ORG_ID", "form-control form-control-sm border border-blue-100 text-gray-700", "Select Division") ?>
                                            <label for="ORG_ID" class="text-gray-600">Division <span class="text-danger">*</span></label>
                                            <span class="text-danger err-lbl" id="lbl-ORG_ID"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                                        <div class="d-inline-flex flex-column form-floating w-50">
                                            <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="FIRST_NAME" id="FIRST_NAME">
                                            <label for="FIRST_NAME" class="text-gray-600">First Name <span class="text-danger">*</span> </label>
                                            <span class="text-danger err-lbl" id="lbl-FIRST_NAME"></span>
                                        </div>
                                        <div class="d-inline-flex flex-column form-floating w-50">
                                            <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="LAST_NAME" id="LAST_NAME">
                                            <label for="LAST_NAME" class="text-gray-600">Last Name <span class="text-danger">*</span></label>
                                            <span class="text-danger err-lbl" id="lbl-LAST_NAME"></span>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="JOB_TITLE" id="JOB_TITLE">
                                        <label for="JOB_TITLE" class="text-gray-600">Job Title <span class="text-danger">*</span></label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-JOB_TITLE"></span>
                                    <div class="form-floating mb-2">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="COMPANY_NAME" id="COMPANY_NAME">
                                        <label for="COMPANY_NAME" class="text-gray-600">Company Name <span class="text-danger">*</span></label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-COMPANY_NAME"></span>
                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                                        <div class="d-inline-flex flex-column form-floating w-50">
                                            <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="EMAIL" id="EMAIL">
                                            <label for="EMAIL" class="text-gray-600">Email Address <span class="text-danger">*</span> </label>
                                            <span class="text-danger err-lbl" id="lbl-EMAIL"></span>
                                        </div>
                                        <div class="d-inline-flex flex-column form-floating w-50">
                                            <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="PHONE" id="PHONE">
                                            <label for="PHONE" class="text-gray-600">Contact Number <span class="text-danger">*</span></label>
                                            <span class="text-danger err-lbl" id="lbl-PHONE"></span>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="LEAD_SOURCE" id="LEAD_SOURCE">
                                        <label for="LEAD_SOURCE" class="text-gray-600">Lead Source <span class="text-danger">*</span></label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-LEAD_SOURCE"></span>
                                    <div class="form-floating mb-2">
                                        <select class="form-select text-gray-700" name="STATUS" id="STATUS" aria-label="Floating label select example">
                                            <option value="">Choose Status</option>
                                            <option value="new">New</option>
                                            <option value="contacted">Contacted</option>
                                            <option value="engaged">Engaged</option>
                                            <option value="qualified">Qualified</option>
                                            <option value="disqualified">Disqualified</option>
                                        </select>
                                        <label for="STATUS">Status <span class="text-danger">*</span></label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-STATUS"></span>
                                    <!-- ASSIGNED TO -->
                                    <div class="form-floating mb-2 position-relative">
                                        <input
                                            type="text"
                                            placeholder="Enter Value"
                                            class="form-control border border-blue-100 text-gray-700"
                                            name="ASSIGNED_TO"
                                            id="ASSIGNED_TO"
                                            readonly
                                            autocomplete="off"
                                            onclick="opensalesPersonListModal()"
                                            >
                                        <label for="ASSIGNED_TO" class="text-gray-600">Assigned To <span class="text-danger">*</span></label>

                                        <!-- Hidden ID field -->
                                        <input type="hidden" id="ASSIGNED_TO_ID" name="ASSIGNED_TO_ID" />

                                        <!-- X Icon for clearing -->
                                        <span
                                            id="clearSalesPerson"
                                            style="display:none; position:absolute; top:50%; transform:translateY(-50%); right:10px; cursor:pointer; font-weight:bold; font-size:18px; color:red;"
                                            title="Clear Assigned To"
                                            onclick="clearSalesPersonDetails()">&times;</span>
                                    </div>

                                    <span class="text-danger err-lbl" id="lbl-ASSIGNED_TO"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 border-start">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-slate-600 fw-normal"><span class="text-primary">Lead</span> Activity Feed</h3>
                                    <p class="text-gray-500"> <span><i class="fa-solid fa-wand-magic-sparkles text-warning"></i></span> Stay updated with all activities related to this <span class="text-primary">lead</span>. Add notes, tasks, and more.</p>
                                </div>
                                <div class="col-md-12 text-end d-none" id="activity-button-container">
                                    <div class="d-flex items-center justify-content-end">
                                        <button class="btn btn-sm btn-outline btn-outline-dashed btn-outline-info btn-active-light-info me-2 mb-2" type="button" onclick="openEmailActivityModal()"><i class="fa-solid fa-paper-plane text-info"></i> New Email</button>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline btn-outline-dashed btn-outline-success btn-active-light-success btn-light-success me-2 mb-2 dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-layer-group text-success"></i> Activities
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                <?php
                                                foreach ($activities as $activity):
                                                    $name = $activity['name'];
                                                    $id = $activity['id'];
                                                ?>
                                                    <li>
                                                        <button class="dropdown-item" type="button" onclick="openActivityModal('<?= $id ?>')"><?= $name ?></button>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="activity-container" class="mt-4">

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>