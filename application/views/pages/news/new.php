<style>
    .text-loader {
        position: absolute;
        top: 0;
        left: 0;
        background-color: red;
    }

    .ql-toolbar {
        border-color: #dbeafe !important;
        background-color: #fff !important;
    }

    .modal-content {

        height: auto !important;
    }

    .form-control {
        border-radius: 0px !important;
        font-size: 12px !important;
    }

    label {
        font-size: 12px !important;
    }

    .text-label-heading {
        color: #608BC1 !important;
    }

    .modal-header-bg {
        background-color: #0766AD !important;
    }

    .submit-btn-bg {
        background-color: #03346E !important;
        color: #fff !important;
    }

    .reset-btn-bg {
        background-color: #295F98 !important;
    }
</style>
<div class="modal bg-body fade " tabindex="-1" id="newNewsModal">
    <div class="modal-dialog bg-light modal-fullscreen">
        <div class="modal-content shadow-none bg-light ">
            <form onsubmit="submitNewsForm(event)" method="post" enctype="multipart/form-data" id="newsForm">
                <div class="modal-header modal-header-bg py-1">
                    <h4 class="modal-title text-white fw-normal"><i class="fa-solid fa-layer-group text-white fs-3 mb-0 me-2"></i> News Management</h4>
                    <!-- Hidden Fields: START -->
                    <input type="hidden" name="ID" id="ID">
                    <!-- Hidden Fields: END -->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-danger ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="resetNewsModal()">
                        <i class="fa-solid fa-xmark text-white fs-4"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body pb-2">
                    <div class="row mb-4">
                        <div class="text-end">
                            <button type="button" class="rounded-1 btn btn-sm btn-secondary" onclick="startOver()"><i class="fa-solid fa-rotate"></i> Start Over New News</button>
                            <button type="submit" class="rounded-1 btn btn-sm btn-success" id="submit-btn"><i class="fa-solid fa-plus"></i> Save News</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row g-1 mb-4">
                                <div class="col-md-12">
                                    <div class="d-flex flex-column gap-1">
                                        <label for="TITLE" class="text-gray-800 fw-bold  fs-6">News Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="TITLE" id="TITLE" placeholder="Enter news title .....">
                                    </div>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-TITLE"></p>
                                </div>
                            </div>
                            <div class="row g-1 mb-4">
                                <label for="DESCRIPTION" class="text-gray-800 fw-bold  fs-6">News Description <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <div id="editor" name="editor" class="bg-white border border-blue-100 mt-1" style="height: 300px;"></div>
                                </div>
                                <p class="text-danger err-lbl mb-0 fs-8" id="lbl-DESCRIPTION"></p>
                            </div>
                        </div>
                        <div class="col-md-5 border-start">
                            <div class="form-group row align-items-center mb-1">
                                <label for="ORG_ID" class="col-md-5 text-gray-800 fw-bold">Division <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-sm-7">
                                    <?= render_org_select("ORG_ID", "ORG_ID", "form-control form-control-sm border border-blue-100 text-gray-700", "Select Division") ?>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-ORG_ID"></p>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-1">
                                <label for="TYPE" class="col-md-5 text-gray-800 fw-bold">News Type <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-sm-7">
                                    <select class="form-control form-control-sm border border-blue-100 text-gray-700" name="TYPE" id="TYPE">
                                        <option value="">Select Type</option> <!-- Placeholder option -->
                                        <option selected value="news">News</option>
                                        <option value="announcement">Announcement</option>
                                    </select>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-TYPE"></p>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-1">
                                <label for="VISIBILITY_SCOPE" class="col-md-5 text-gray-800 fw-bold">Visibility Scope <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-sm-7">
                                    <select class="form-control form-control-sm border border-blue-100 text-gray-700" name="VISIBILITY_SCOPE" id="VISIBILITY_SCOPE">
                                        <option value="">Select Visibility</option> <!-- Placeholder option -->
                                        <option selected value="public">Public</option>
                                        <option value="internal">Internal</option>
                                    </select>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-VISIBILITY_SCOPE"></p>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-1">
                                <label for="PRIORITY" class="col-md-5 text-gray-800 fw-bold">Priority <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-sm-7">
                                    <select class="form-control form-control-sm border border-blue-100 text-gray-700" name="PRIORITY" id="PRIORITY">
                                        <option value="">Select Priority</option> <!-- Placeholder option -->
                                        <option value="low">Low</option>
                                        <option selected value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-PRIORITY"></p>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-1">
                                <label for="STATUS" class="col-md-5 text-gray-800 fw-bold">Status <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-sm-7">
                                    <select class="form-control form-control-sm border border-blue-100 text-gray-700" name="STATUS" id="STATUS">
                                        <option value="">Select Status</option> <!-- Placeholder option -->
                                        <option value="draft">Draft</option>
                                        <option selected value="published">Published</option>
                                        <option value="disabled">Disabled</option>
                                    </select>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-STATUS"></p>
                                </div>
                            </div>
                            <div class="row g-1 mb-4 mt-5">
                                <label for="CATEGORY_ID" class="text-gray-800 fw-bold fs-6 mb-2">News Attachment(s)</label>
                                <div class="col-md-12">
                                    <div id="upload-box" class="upload-box d-flex align-items-center justify-content-center btn-outline btn-outline-dashed btn btn-active-light-primary bg-white py-6" onclick="document.getElementById('file-input').click();">
                                        <i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary"></i>
                                        <p class="mb-0">Click to upload files</p>
                                        <input onchange="handleFileSelect(event)" type="file" id="file-input" multiple style="display:none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <!-- Uploaded files preview list -->
                                    <h6 class="my-2 fw-normal">New Attached Files</h6>
                                    <div id="file-list" class="my-2 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                </div>

                                <div class="col-md-12">
                                    <!-- Uploaded files From Server preview list -->
                                    <h6 class="my-2 fw-normal">Uploaded Files</h6>
                                    <div id="file-list-uploaded" class="my-2 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </div>
</div>