<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="row align-items-end justify-content-between mb-4">
                    <div class="col-md-6 d-flex gap-2">
                        <div>
                            <label for="FILTER_STATUS" class="mb-1">
                                <small class="text-muted">Status</small>
                            </label>
                            <select class="form-select form-select-sm" id="FILTER_STATUS" name="FILTER_STATUS">
                                <option value="">All</option>
                                <option value="new">New</option>
                                <option value="contacted">Contacted</option>
                                <option value="engaged">Engaged</option>
                                <option value="qualified">Qualified</option>
                                <option value="disqualified">Disqualified</option>
                            </select>
                        </div>
                        <div>
                            <label for="searchInputElement" class="mb-1">
                                <small class="text-muted">
                                    <i class="fa-solid fa-circle-info fs-9"></i> Search by Column Data
                                </small>
                            </label>
                            <input type="text" oninput="debouncedSearchLeadListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search by column ..">
                        </div>
                        <div class="align-self-end">
                            <button class="btn btn-sm btn-light border border-secondary" onclick="filterLeadReport()">Filter</button>
                        </div>
                    </div>

                    <!-- Right side: Export -->
                    <div class="col-md-3 text-end">
                        <button id="exportCsvBtn" onclick="exportleadData('csv')" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-file-csv"></i>
                        </button>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-row-bordered fs-7 gy-3 dataTable table-row-bordered " id="lead-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th class="text-center">#</th>
                                <th class="">Lead #</th>
                                <th>Name</th>
                                <th>Company Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Contact #</th>
                                <th>Lead Created</th>
                                <th>Lead Source</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="lead-list-tbody">
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

<!-- Include modals to add new lead  -->
<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('pages/leads/modals/new-lead');
$this->load->view('modals/activities/activity-modal');
$this->load->view('modals/activities/email-activity');
$this->load->view('pages/salespersons/modals/list');
?>