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
                            <input type="text" oninput="debouncedSearchRequestListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search by column ..">
                        </div>
                    </div>
                    <!-- Right side: Export -->
                    <div class="col-md-3 text-end">
                        <button id="exportCsvBtn" onclick="exportRequestData('csv')" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-file-csv"></i>
                        </button>
                    </div>
                </div>
                <table class="table table-row-bordered gy-3" id="request-list" style="white-space: nowrap;">
                    <thead>
                        <tr class="fw-bold fs-7 text-gray-900">
                            <th>#</th>
                            <th>Request #</th>
                            <th class="w-350">Title</th>
                            <th class="w-350">Description</th>
                            <th class="w-200">Company</th>
                            <th class="w-200">Client Name</th>
                            <th>Contact #</th>
                            <th>Email</th>
                            <th>Request Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="request-list-tbody">
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
$this->load->view('pages/requests/new-v1');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');
$this->load->view('pages/products/modals/product-list');
?>