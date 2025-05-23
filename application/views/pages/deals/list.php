<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="row align-items-end justify-content-between mb-4">
                    <div class="col-md-6 d-flex gap-2">
                        <div>
                            <label for="FILTER_DEAL_STATUS" class="mb-1">
                                <small class="text-muted">Status</small>
                            </label>
                            <select class="form-select form-select-sm" id="FILTER_DEAL_STATUS" name="FILTER_DEAL_STATUS">
                                <option value="">All</option>
                                <option value="new">New</option>
                                <option value="contacted">Contacted</option>
                                <option value="qualified">Qualified</option>
                                <option value="proposal-sent">Proposal Sent</option>
                                <option value="negotiation">Negotiation</option>
                                <option value="closed-won">Closed - Won</option>
                                <option value="closed-lost">Closed - Lost</option>
                            </select>
                        </div>
                        <div>
                            <label for="searchInputElement" class="mb-1">
                                <small class="text-muted">
                                    <i class="fa-solid fa-circle-info fs-9"></i> Search by Column Data
                                </small>
                            </label>
                            <input type="text" oninput="debouncedSearchDealsListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search by column ..">
                        </div>
                        <div class="align-self-end">
                            <button class="btn btn-sm btn-light border border-secondary" onclick="filterDealsReport()">Filter</button>
                        </div>
                    </div>
                    <!-- Right side: Export -->
                    <div class="col-md-3 text-end">
                        <button id="exportCsvBtn" onclick="exportdealsData('csv')" class="btn btn-primary btn-sm me-2"> <i class="fas fa-file-csv"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle fs-7 gy-3 table-row-bordered " id="deal-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th class="bg-light py-2">Name</th>
                                <th class="bg-light py-2">Contact ID</th>
                                <th class="bg-light py-2">Deat Stage</th>
                                <th class="bg-light py-2">Deal Type</th>
                                <th class="bg-light py-2">Deal Value</th>
                                <th class="bg-light py-2">Exp Close Date</th>
                                <th class="bg-light py-2">Priority</th>
                                <th class="bg-light py-2">Status</th>
                                <th class="bg-light py-2">Created On</th>
                                <th class="text-end bg-light py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody id="deal-list-tbody">


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
$this->load->view('pages/deals/modals/new-deal');
$this->load->view('modals/activities/activity-modal');
$this->load->view('modals/contact/associated-contact-list');
$this->load->view('modals/activities/email-activity');
$this->load->view('pages/salespersons/modals/list');
?>