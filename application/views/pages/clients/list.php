<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <!-- Filter/Search/Export Row -->
                <div class="row align-items-end justify-content-between mb-4">
                    <!-- Left side: Search + Filter -->
                    <div class="col-md-6 d-flex gap-2">
                        <div>
                            <label for="searchInputElement" class="mb-1">
                                <small class="text-muted"> <i class="fa-solid fa-circle-info fs-9"> </i> Search by Column Data</small>
                            </label>
                            <input type="text" oninput="debouncedSearchClientListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search...">
                        </div>
                        <div class="align-self-end">
                            <button class="btn btn-sm btn-light border border-secondary" onclick="filterClientReport()">Filter</button>
                        </div>
                    </div>

                    <!-- Right side: Export -->
                    <div class="col-md-3 text-end">
                        <button id="exportCsvBtn" onclick="exportclientData('csv')" class="btn btn-primary btn-sm">
                            <i class="fas fa-file-csv"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-3" id="client-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th>#</th>
                                <th>Client #</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Credit Limit</th>
                                <th>Order Limit</th>
                                <th>Country</th>
                                <th>Tax %</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="client-list-tbody">
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