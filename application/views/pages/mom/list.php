<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="row align-items-end justify-content-between mb-4">
                    <div class="col-md-6 d-flex gap-2">
                        <div>
                            <label for="searchInputElement" class="mb-1">
                                <small class="text-muted">
                                    <i class="fa-solid fa-circle-info fs-9"></i> Search by Column Data
                                </small>
                            </label> <input type="text" oninput="debouncedSearchMOMsListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search by column ..">
                        </div>

                    </div>
                    <!-- Right side: Export -->
                    <div class="col-md-3 text-end">
                        <button id="exportCsvBtn" onclick="exportMOMsData('csv')" class="btn btn-primary btn-sm me-2"> <i class="fas fa-file-csv"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-7 gy-3 dataTable table-row-bordered " id="mom-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th>#</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Duration</th>
                                <th>Platform Location</th>
                                <th>Organizer</th>
                                <th>Attendees</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="mom-list-tbody"></tbody>
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
$this->load->view('pages/mom/modals/new-minutes');
?>