<?php
$activities = DEAL_ACTIVITY_OPTIONS;
?>
<div class="modal bg-body fade" tabindex="-1" id="newDealModal">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content shadow-none">
            <form onsubmit="submitDeal(event)" method="post" enctype="multipart/form-data" id="dealForm">
                <div class="modal-header">
                    <h2 class="modal-title text-success"><i class="fa-solid fa-users text-success fs-3 mb-0 me-2"></i> Deal Management</h2>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-success ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeDealModal()">
                        <i class="fa-solid fa-xmark text-danger fs-4"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 bg-light deal-form-elements py-6">
                            <div class="">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="text-slate-700 fw-normal">Deal Details</h3>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="startOver()"><i class="fa-solid fa-rotate"></i> Start Over New Deal</button>
                                        <button type="submit" class="btn btn-sm btn-success" id="submit-btn"><i class="fa-solid fa-plus"></i> Save changes</button>
                                    </div>
                                </div>
                                <input type="hidden" name="UUID" id="UUID">
                                <input type="hidden" name="DEAL_ID" id="DEAL_ID">
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
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
                                            <label for="DEAL_STATUS">Status <span class="text-danger">*</span></label>
                                        </div>
                                        <span class="text-danger err-lbl" id="lbl-DEAL_STATUS"></span>
                                    </div>
                                    <div class="form-floating w-100 mb-2">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="DEAL_NAME" id="DEAL_NAME">
                                        <label for="DEAL_NAME" class="text-gray-600">Deal Full Name <span class="text-danger">*</span> </label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-DEAL_NAME"></span>
                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                                        <div class="d-inline-flex w-50 form-floating">
                                            <input type="email" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " placeholder="" name="EMAIL" id="EMAIL">
                                            <label for="EMAIL" class="text-gray-600">Email Address</label>
                                        </div>
                                        <span class="text-danger err-lbl" id="lbl-EMAIL"></span>
                                        <div class="d-inline-flex w-50 form-floating">
                                            <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " placeholder="" name="CONTACT_NUMBER" id="CONTACT_NUMBER">
                                            <label for="CONTACT_NUMBER">Contact Number <span class="text-danger">*</span></label>
                                        </div>
                                        <span class="text-danger err-lbl" id="lbl-CONTACT_NUMBER"></span>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="ASSOCIATED_CONTACT_ID" id="ASSOCIATED_CONTACT_ID">
                                        <label for="ASSOCIATED_CONTACT_ID" class="text-gray-600">Associated Contact </label>
                                    </div>
                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
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
                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
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
                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
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
                                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
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
                                        <input type="text" placeholder="Enter Value" class="form-control border border-blue-100 text-gray-700 " name="ASSIGNED_TO" id="ASSIGNED_TO">
                                        <label for="ASSIGNED_TO" class="text-gray-600">Assigned To <span class="text-danger">*</span> </label>
                                    </div>
                                    <span class="text-danger err-lbl" id="lbl-ASSIGNED_TO"></span>
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
                        <div class="col-md-7 border-start">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-slate-600 fw-normal"><span class="text-success">Deal</span> Activity Feed</h3>
                                    <p class="text-gray-500"> <span><i class="fa-solid fa-wand-magic-sparkles text-warning"></i></span> Stay updated with all activities related to this <span class="text-success">deal</span>. Add notes, tasks, and more.</p>
                                </div>
                                <div class="col-md-12 text-end d-none" id="activity-button-container">
                                    <div class="d-flex items-center justify-content-end">
                                        <button class="btn btn-sm btn-outline btn-outline-dashed btn-outline-info btn-active-light-info me-2 mb-2" type="button"><i class="fa-solid fa-paper-plane text-info"></i> New Email</button>
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