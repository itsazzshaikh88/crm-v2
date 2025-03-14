<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="row">
                    <div class="col-md-5 border-end border-light bg-light rounded pb-5">
                        <h6 class="text-primary my-4">Add New Unit of Measurement</h6>
                        <form id="form" method="post" onsubmit="submitForm(event)" enctype="multipart/form-data">
                            <input type="hidden" name="UOM_ID" id="UOM_ID">
                            <div class="form-group row align-items-center mb-1">
                                <label for="UOM_CODE" class="col-md-4 text-gray-800 fw-bold">UOM Code <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-800 fw-normal" name="UOM_CODE" id="UOM_CODE" placeholder="Enter Category Code">
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-UOM_CODE"></p>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-1">
                                <label for="UOM_DESCRIPTION" class="col-md-4 text-gray-800 fw-bold">UOM Description <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-800 fw-normal" name="UOM_DESCRIPTION" id="UOM_DESCRIPTION" placeholder="Enter Category Code">
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-UOM_DESCRIPTION"></p>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-1">
                                <label for="UOM_TYPE" class="col-md-4 text-gray-800 fw-bold">UOM Type <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-sm-8">
                                    <select class="form-control form-control-sm border border-blue-100 text-gray-800 fw-normal" name="UOM_TYPE" id="UOM_TYPE">
                                        <option value="">Choose</option>
                                        <option value="Weight">Weight</option>
                                        <option value="Volume">Volume</option>
                                        <option value="Length">Length</option>
                                        <option value="Area">Area</option>
                                        <option value="Count">Count</option>
                                        <option value="Time">Time</option>
                                        <option value="Temperature">Temperature</option>
                                        <option value="Pressure">Pressure</option>
                                        <option value="Energy">Energy</option>
                                        <option value="Power">Power</option>
                                        <option value="Speed">Speed</option>
                                        <option value="Density">Density</option>
                                        <option value="Torque">Torque</option>
                                    </select>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-UOM_TYPE"></p>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-1">
                                <label for="IS_ACTIVE" class="col-md-4 text-gray-800 fw-bold">Is Active <span class="text-danger">*</span> <span class="float-end">:</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control form-control-sm border border-blue-100 text-gray-800 fw-normal" name="IS_ACTIVE" id="IS_ACTIVE">
                                        <option value="">Choose</option>
                                        <option selected value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-IS_ACTIVE"></p>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="submit" id="submit-btn" class="btn btn-sm btn-primary">
                                    Save UOM
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-7">
                        <h6 class="text-primary my-4">UOM List</h6>
                        <table class="table align-middle table-row-bordered fs-7 gy-3 dataTable table-row-bordered" id="uom-list">
                            <thead>
                                <tr class="fw-bold fs-7 text-gray-900">
                                    <th class="text-center">#</th>
                                    <th>UOM Code</th>
                                    <th>UOM Description</th>
                                    <th>UOM Type</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="uom-list-tbody">
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->

<!-- Include modals to add new uom  -->
<?php
$this->load->view('loaders/full-page-loader');
?>