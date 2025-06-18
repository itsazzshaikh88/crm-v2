<style>
    .text-loader {
        position: absolute;
        top: 0;
        left: 0;
        background-color: red;
    }

    .bg-primary-subtle {
        background-color: #cfe2ff !important;
        color: #084298 !important;
    }

    .bg-secondary-subtle {
        background-color: #e2e3e5 !important;
        color: #41464b !important;
    }

    .bg-success-subtle {
        background-color: #d1e7dd !important;
        color: #0f5132 !important;
    }

    .bg-info-subtle {
        background-color: #cff4fc !important;
        color: #055160 !important;
    }

    .bg-warning-subtle {
        background-color: #fff3cd !important;
        color: #664d03 !important;
    }

    .bg-danger-subtle {
        background-color: #f8d7da !important;
        color: #842029 !important;
    }

    .bg-light-subtle {
        background-color: #fefefe !important;
        color: #636464 !important;
    }

    .bg-dark-subtle {
        background-color: #ced4da !important;
        color: #1b1f22 !important;
    }
</style>
<?php
$activities = LEAD_ACTIVITY_OPTIONS;
?>
<div class="modal bg-body fade" tabindex="-1" id="newLeadModal">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content shadow-none">
            <form onsubmit="submitLead(event)" method="post" enctype="multipart/form-data" id="leadForm">
                <div class="modal-header py-2">
                    <h5 class="modal-title text-primary fw-bold"><i class="fa-solid fa-users text-primary fs-3 mb-0 me-2"></i> Leads Management</h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeLeadModal()">
                        <i class="fa-solid fa-xmark text-danger fs-4"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row ">
                        <div class="col-md-5">
                            <div class="row mb-2">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="startOver()"><i class="fa-solid fa-rotate"></i> Start Over New Lead</button>
                                    <button type="submit" class="btn btn-sm btn-primary" id="submit-btn"><i class="fa-solid fa-plus"></i> Save changes</button>
                                </div>
                            </div>
                            <!-- <div class="accordion" id="crmFormAccordion"> -->
                            <!-- Lead Details -->
                            <div class="accordion-item mb-4 border">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-4 fw-bolder bg-light text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#leadDetails">
                                        Lead Details
                                    </button>
                                </h2>
                                <div id="leadDetails" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <input type="hidden" name="LEAD_ID" id="LEAD_ID">
                                        <div class="row ">
                                            <div class="col-md-12">
                                                <div class="d-flex flex-row align-items-center justify-content-between gap-2 mb-2">
                                                    <div class="form-floating w-50">
                                                        <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " placeholder="" readonly name="LEAD_NUMBER" id="LEAD_NUMBER">
                                                        <label for="" class="text-gray-600">Lead Number</label>
                                                    </div>
                                                    <div class="d-inline-flex flex-column form-floating w-50">
                                                        <?= render_org_select("ORG_ID", "ORG_ID", "form-control form-control-sm border border-blue-100 text-gray-700", "Select Division") ?>
                                                        <label for="ORG_ID" class="text-gray-600">Division <span class="text-danger">*</span></label>
                                                        <span class="text-danger err-lbl" id="lbl-ORG_ID"></span>
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-row align-items-start justify-content-between gap-2 mb-2">
                                                    <div class="form-floating w-50">
                                                        <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " name="LEAD_SOURCE" id="LEAD_SOURCE">
                                                        <label for="LEAD_SOURCE" class="text-gray-600">Lead Source <span class="text-danger">*</span></label>
                                                        <span class="text-danger err-lbl" id="lbl-LEAD_SOURCE"></span>
                                                    </div>
                                                    <div class="form-floating w-50">
                                                        <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " name="LEAD_EVENT" id="LEAD_EVENT">
                                                        <label for="LEAD_EVENT" class="text-gray-600">Lead Event <span class="text-danger">*</span></label>
                                                        <span class="text-danger err-lbl" id="lbl-LEAD_EVENT"></span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-row align-items-start justify-content-between gap-2 mb-2">
                                                    <div class="form-floating w-50">
                                                        <select class="form-select text-gray-700" name="STATUS" id="STATUS" aria-label="Floating label select example">
                                                            <option value="">Choose Status</option>
                                                            <option value="new">New</option>
                                                            <option value="contacted">Contacted</option>
                                                            <option value="engaged">Engaged</option>
                                                            <option value="qualified">Qualified</option>
                                                            <option value="disqualified">Disqualified</option>
                                                        </select>
                                                        <label for="STATUS">Status <span class="text-danger">*</span></label>
                                                        <span class="text-danger err-lbl" id="lbl-STATUS"></span>
                                                    </div>
                                                    <div class="form-floating w-50">
                                                        <input type="date" placeholder="Enter Date" class="form-control border text-gray-700 border-blue-100 " name="FOLLOW_UP_DATE" id="FOLLOW_UP_DATE">
                                                        <label for="FOLLOW_UP_DATE" class="text-gray-600">Follow Up Date</label>
                                                    </div>
                                                </div>
                                                <!-- ASSIGNED TO -->
                                                <div class="form-floating mb-2 position-relative">
                                                    <input
                                                        type="text"
                                                        placeholder="Enter Value"
                                                        class="form-control border text-gray-700 border-blue-100"
                                                        name="ASSIGNED_TO"
                                                        id="ASSIGNED_TO"
                                                        readonly
                                                        autocomplete="off"
                                                        onclick="opensalesPersonListModal()">
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
                                </div>
                            </div>
                            <!-- Contact Details -->
                            <div class="accordion-item mb-4 border">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-4 fw-bolder bg-light text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#contactDetails">
                                        Contact Details
                                    </button>
                                </h2>
                                <div id="contactDetails" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <div class="row ">
                                            <div class="col-md-12">
                                                <div class="d-flex flex-row align-items-center justify-content-between gap-2 mb-2">
                                                    <div class="d-inline-flex flex-column form-floating w-50">
                                                        <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " name="FIRST_NAME" id="FIRST_NAME">
                                                        <label for="FIRST_NAME" class="text-gray-600">First Name <span class="text-danger">*</span> </label>
                                                        <span class="text-danger err-lbl" id="lbl-FIRST_NAME"></span>
                                                    </div>
                                                    <div class="d-inline-flex flex-column form-floating w-50">
                                                        <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " name="LAST_NAME" id="LAST_NAME">
                                                        <label for="LAST_NAME" class="text-gray-600">Last Name <span class="text-danger">*</span></label>
                                                        <span class="text-danger err-lbl" id="lbl-LAST_NAME"></span>
                                                    </div>
                                                </div>
                                                <div class="form-floating mb-2">
                                                    <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " name="JOB_TITLE" id="JOB_TITLE">
                                                    <label for="JOB_TITLE" class="text-gray-600">Job Title <span class="text-danger">*</span></label>
                                                </div>
                                                <span class="text-danger err-lbl" id="lbl-JOB_TITLE"></span>
                                                <div class="form-floating mb-2">
                                                    <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " name="COMPANY_NAME" id="COMPANY_NAME">
                                                    <label for="COMPANY_NAME" class="text-gray-600">Company Name <span class="text-danger">*</span></label>
                                                </div>
                                                <span class="text-danger err-lbl" id="lbl-COMPANY_NAME"></span>
                                                <div class="d-flex flex-row align-items-center justify-content-between gap-2 mb-2">
                                                    <div class="d-inline-flex flex-column form-floating w-50">
                                                        <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " name="EMAIL" id="EMAIL">
                                                        <label for="EMAIL" class="text-gray-600">Email Address <span class="text-danger">*</span> </label>
                                                        <span class="text-danger err-lbl" id="lbl-EMAIL"></span>
                                                    </div>
                                                    <div class="d-inline-flex flex-column form-floating w-50">
                                                        <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " name="PHONE" id="PHONE">
                                                        <label for="PHONE" class="text-gray-600">Contact Number <span class="text-danger">*</span></label>
                                                        <span class="text-danger err-lbl" id="lbl-PHONE"></span>
                                                    </div>
                                                </div>
                                                <div class="form-floating mb-2">
                                                    <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " name="PREFERRED_CONTACT_METHOD" id="PREFERRED_CONTACT_METHOD">
                                                    <label for="PREFERRED_CONTACT_METHOD" class="text-gray-600">Prefered Contact Method</label>
                                                </div>
                                                <div class="form-floating mb-2">
                                                    <input type="text" placeholder="Enter Value" class="form-control border text-gray-700 border-blue-100 " name="ADDRESS" id="ADDRESS">
                                                    <label for="ADDRESS" class="text-gray-600">Address</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Deal Details -->
                            <div class="accordion-item mb-4 border">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-4 fw-bolder bg-light text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#dealDetails">
                                        Deal Details
                                    </button>
                                </h2>
                                <div id="dealDetails" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex flex-row align-items-center justify-content-between gap-2 mb-2">
                                                    <div class="d-inline-flex w-50 form-floating">
                                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " placeholder="" readonly name="DEAL_NUMBER" id="DEAL_NUMBER">
                                                        <label for="" class="text-gray-600">Deal Number</label>
                                                    </div>
                                                    <div class="d-inline-flex w-50 form-floating">
                                                        <select class="form-select text-gray-700" name="DEAL_STATUS" id="DEAL_STATUS" aria-label="Floating label select example">
                                                            <option value="">Choose Status</option>
                                                            <option value="new">New</option>
                                                            <option value="contacted">Contacted</option>
                                                            <option value="qualified">Qualified</option>
                                                            <option value="proposal-sent">Proposal Sent</option>
                                                            <option value="negotiation">Negotiation</option>
                                                            <option value="closed-won">Closed - Won</option>
                                                            <option value="closed-lost">Closed - Lost</option>
                                                        </select>
                                                        <label for="DEAL_STATUS">Deal Status <span class="text-danger">*</span></label>
                                                    </div>
                                                    <span class="text-danger err-lbl" id="lbl-DEAL_STATUS"></span>
                                                </div>
                                                <div class="d-flex flex-row align-items-center justify-content-between gap-2 mb-2">
                                                    <div class="d-inline-flex form-floating w-50">
                                                        <select class="form-control border border-blue-100 text-gray-700 " name="DEAL_STAGE" id="DEAL_STAGE">
                                                            <option value="">Choose</option>
                                                            <option value="lead-generation">Lead Generation</option>
                                                            <option value="qualification">Qualification</option>
                                                            <option value="proposal-quote">Proposal / Quote</option>
                                                            <option value="negotiation">Negotiation</option>
                                                            <option value="closed-won">Closed - Won</option>
                                                            <option value="closed-lost">Closed - Lost</option>
                                                        </select>
                                                        <label for="DEAL_STAGE" class="text-gray-600">Deal Stage <span class="text-danger">*</span> </label>
                                                    </div>
                                                    <span class="text-danger err-lbl" id="lbl-DEAL_STAGE"></span>
                                                    <div class="d-inline-flex form-floating w-50">
                                                        <select class="form-control border border-blue-100 text-gray-700 " name="DEAL_TYPE" id="DEAL_TYPE">
                                                            <option value="">Choose</option>
                                                            <option value="new-business">New Business</option>
                                                            <option value="renewal">Renewal</option>
                                                            <option value="upsell">Upsell</option>
                                                            <option value="cross-sell">Cross-Sell</option>
                                                        </select>
                                                        <label for="DEAL_TYPE" class="text-gray-600">Deal Type <span class="text-danger">*</span></label>
                                                    </div>
                                                    <span class="text-danger err-lbl" id="lbl-DEAL_TYPE"></span>
                                                </div>
                                                <div class="d-flex flex-row align-items-center justify-content-between gap-2 mb-2">
                                                    <div class="d-inline-flex form-floating w-50">
                                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="DEAL_VALUE" id="DEAL_VALUE">
                                                        <label for="DEAL_VALUE" class="text-gray-600">Deal Value <span class="text-danger">*</span> </label>
                                                    </div>
                                                    <span class="text-danger err-lbl" id="lbl-DEAL_VALUE"></span>
                                                    <div class="d-inline-flex form-floating w-50">
                                                        <select class="form-control border border-blue-100 text-gray-700 " name="DEAL_PRIORITY" id="DEAL_PRIORITY">
                                                            <option value="">Choose</option>
                                                            <option value="high">High</option>
                                                            <option value="medium" selected>Medium</option>
                                                            <option value="low">Low</option>
                                                        </select>
                                                        <label for="DEAL_PRIORITY" class="text-gray-600">Deal Priority <span class="text-danger">*</span></label>
                                                    </div>
                                                    <span class="text-danger err-lbl" id="lbl-DEAL_PRIORITY"></span>
                                                </div>
                                                <div class="d-flex flex-row align-items-center justify-content-between gap-2 mb-2">
                                                    <div class="d-inline-flex form-floating w-50">
                                                        <input type="date" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="EXPECTED_CLOSE_DATE" id="EXPECTED_CLOSE_DATE">
                                                        <label for="EXPECTED_CLOSE_DATE" class="text-gray-600">Expected Close Date <span class="text-danger">*</span> </label>
                                                    </div>
                                                    <span class="text-danger err-lbl" id="lbl-EXPECTED_CLOSE_DATE"></span>
                                                    <div class="d-inline-flex form-floating w-50">
                                                        <input type="date" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="ACTUAL_CLOSE_DATE" id="ACTUAL_CLOSE_DATE">
                                                        <label for="ACTUAL_CLOSE_DATE" class="text-gray-600">Actual Close Date </label>
                                                    </div>
                                                    <span class="text-danger err-lbl" id="lbl-ACTUAL_CLOSE_DATE"></span>
                                                </div>
                                                <div class="d-flex flex-row align-items-center justify-content-between gap-2 mb-2">
                                                    <div class="d-inline-flex form-floating w-50">
                                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="PROBABILITY" id="PROBABILITY">
                                                        <label for="PROBABILITY" class="text-gray-600">Probability <span class="text-danger">*</span> </label>
                                                    </div>
                                                    <span class="text-danger err-lbl" id="lbl-PROBABILITY"></span>
                                                    <div class="d-inline-flex form-floating w-50">
                                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="DEAL_SOURCE" id="DEAL_SOURCE">
                                                        <label for="DEAL_SOURCE" class="text-gray-600">Deal Source <span class="text-danger">*</span></label>
                                                    </div>
                                                    <span class="text-danger err-lbl" id="lbl-DEAL_SOURCE"></span>
                                                </div>
                                                <div class="form-floating w-100 mb-2">
                                                    <textarea type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700" style="height: 120px;" name="DEAL_DESCRIPTION" id="DEAL_DESCRIPTION"></textarea>
                                                    <label for="DEAL_DESCRIPTION" class="text-gray-600">Deal Description </label>
                                                </div>
                                                <div class="form-floating w-100 mb-2">
                                                    <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="CLOSE_REASON" id="CLOSE_REASON">
                                                    <label for="CLOSE_REASON" class="text-gray-600">Close Reason </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->
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