<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <form id="form" class="form" method="POST" enctype="multipart/form-data"
            onsubmit="submitForm(event)">
            <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
            <input type="hidden" name="SURVEY_ID" id="SURVEY_ID" value="">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="" class="fs-6 fw-bold">Survey Number</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Auto Generated"  name="SURVEY_NUMBER" id="SURVEY_NUMBER" readonly>

                        
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="" class="fs-6 fw-bold">Survey Status</label>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="STATUS" id="STATUS">
                                <option value="">Select Status</option>
                                <option selected="" value="draft">Draft</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                            <span class="text-danger err-lbl" id="lbl-STATUS"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="" class="fs-6 fw-bold">Survey Start Date</label>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="START_DATE" id="START_DATE" class="form-control">
                            <span class="text-danger err-lbl" id="lbl-START_DATE"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="" class="fs-6 fw-bold">Survey End Date</label>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="END_DATE" id="END_DATE" class="form-control">
                            <span class="text-danger err-lbl" id="lbl-END_DATE"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="" class="fs-6 fw-bold">Conducted By</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="CONDUCTED_BY" id="CONDUCTED_BY" class="form-control" placeholder="Mention survey conducted by">
                            <span class="text-danger err-lbl" id="lbl-CONDUCTED_BY"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="" class="fs-6 fw-bold">Survey Name</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="SURVEY_NAME" id="SURVEY_NAME" class="form-control" placeholder="Enter descriptive survey name">
                            <span class="text-danger err-lbl" id="lbl-SURVEY_NAME"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="" class="fs-6 fw-bold">Survey Description</label>
                        </div>
                        <div class="col-md-9">
                            <textarea name="SURVEY_DESC" id="SURVEY_DESC" class="form-control" rows="5"
                                placeholder="Write your survey description"></textarea>
                                <span class="text-danger err-lbl" id="lbl-SURVEY_DESC"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-10 mt-5">
                
                <button type="submit" id="submit-btn" class="btn btn-primary">
                    <span class="indicator-label">
                        Save Changes
                    </span>
                </button>
            </div>
        </form>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->
<?php
$this->load->view('loaders/full-page-loader');
