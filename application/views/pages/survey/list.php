<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-end justify-content-between mb-4">
                    <div class="col-md-6 d-flex gap-2">
                        <!-- Status Filter -->
                        <div>
                            <label for="STATUS" class="mb-1">
                                <small class="text-muted">Status</small>
                            </label>
                            <select class="form-select form-select-sm" id="STATUS" name="STATUS">
                                <option value="">All</option>
                                <option value="draft">Draft</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div>
                            <label for="searchInputElement" class="mb-1">
                                <small class="text-muted">
                                    <i class="fa-solid fa-circle-info fs-9"></i> Search by Column Data
                                </small>
                            </label>
                            <input type="text" oninput="debouncedSearchSurveyListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search by column ..">
                        </div>
                        <div class="align-self-end">
                            <button class="btn btn-sm btn-light border border-secondary" onclick="filterSurveyReport()">Filter</button>
                        </div>
                    </div>
                    <!-- Right side: Export -->
                    <div class="col-md-3 text-end">
                        <button id="exportCsvBtn" onclick="exportsurveyData('csv')" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-file-csv"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-3" id="survey-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th>#</th>
                                <th>Survey Name</th>
                                <th>Survey Desc</th>
                                <th>Survey Duration</th>
                                <th>Survey Conducted</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="survey-list-tbody">
                            <!-- <tr>
                                <td colspan="6" class="text-center text-danger">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <img src="assets/images/survey-img.png" class="w-200px mb-2" alt="">
                                        <h4 class="text-danger fw-normal">survey not created yet, <a
                                                href="survey/new">Click here to create new survey</a></h4>
                                    </div>
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
                <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->