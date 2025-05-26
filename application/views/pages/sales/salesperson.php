<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-4">
                <div class="row align-items-end justify-content-between mb-4">
                    <div class="col-md-9 d-flex gap-2">
                        <div>
                            <label for="searchInputElement" class="mb-1">
                                <small class="text-muted">
                                    <i class="fa-solid fa-circle-info fs-9"></i> Search by Column Data
                                </small>
                            </label>
                            <input type="text" oninput="debouncedSearchSalesPersontListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search by column ..">
                        </div>
                    </div>
                    <!-- Right side: Export -->
                    <div class="col-md-3 text-end">
                        <button id="exportCsvBtn" onclick="exportSalesPersonData('csv')" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-file-csv"></i>
                        </button>
                    </div>
                </div>
                <table class="table table-row-bordered gy-3" id="sales-person-list" style="white-space: nowrap;">
                    <thead>
                        <tr class="fw-bold fs-7 text-gray-900">
                            <th class="bg-light py-2">#</th>
                            <th class="bg-light py-2">Sales Person</th>
                            <th class="bg-light py-2">Email</th>
                            <th class="bg-light py-2">Phone</th>
                            <th class="bg-light py-2">Department</th>
                            <th class="bg-light py-2">Designation</th>
                            <th class="bg-light py-2">Date of Joining</th>
                            <th class="bg-light py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody id="sales-person-list-tbody">
                    </tbody>
                </table>

                <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->

<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('pages/sales/new-salesperson');
?>