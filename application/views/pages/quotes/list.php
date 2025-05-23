<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-end justify-content-between mb-4">
                    <div class="col-md-6 d-flex gap-2">
                        <div>
                            <label for="FILTER_QUOTE_STATUS" class="mb-1">
                                <small class="text-muted">Status</small>
                            </label>
                            <select class="form-select form-select-sm" id="FILTER_QUOTE_STATUS" name="FILTER_QUOTE_STATUS">
                                <option value="">All</option>
                                <option value="draft">Draft</option>
                                <option value="approved">Approved</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Rejected</option>

                            </select>
                        </div>
                        <div>
                            <label for="searchInputElement" class="mb-1">
                                <small class="text-muted">
                                    <i class="fa-solid fa-circle-info fs-9"></i> Search by Column Data
                                </small>
                            </label>
                            <input type="text" oninput="debouncedSearchQuotationListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search by column ..">
                        </div>
                        <div class="align-self-end">
                            <button class="btn btn-sm btn-light border border-secondary" onclick="filterQuotesReport()">Filter</button>
                        </div>
                    </div>
                    <!-- Right side: Export -->
                    <div class="col-md-3 text-end">
                        <button id="exportCsvBtn" onclick="exportquoteData('csv')" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-file-csv"></i>
                        </button>
                    </div>
                </div>
                <!-- <div class="table-responsive"> -->
                <table class="table table-row-bordered gy-3" id="quote-list">
                    <thead>
                        <tr class="fw-bold fs-7 text-gray-900">
                            <th>#</th>
                            <th class="">Quotes #</th>
                            <th>Client</th>
                            <th>Employee Name</th>
                            <th>Job Title</th>
                            <th>Email</th>
                            <th>Sales Person</th>
                            <th class="">Request #</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="quote-list-tbody">

                    </tbody>
                </table>
                <!-- </div> -->
                <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->
<?php
$this->load->view('pages/quotes/quote-modal');
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');
$this->load->view('pages/products/modals/product-list');
?>