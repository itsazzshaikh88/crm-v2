<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-end justify-content-between mb-4">


                    <div class="col-md-6 d-flex gap-2">
                        <div>
                            <label for="FILTER_STATUS" class="mb-1">
                                <small class="text-muted">Status</small>
                            </label>
                            <select class="form-select form-select-sm" id="FILTER_STATUS" name="FILTER_STATUS">
                                <option value="">All</option> <!-- Placeholder option -->
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="disabled">Disabled</option>

                            </select>
                        </div>

                        <div>
                            <label for="FILTER_TYPE" class="mb-1">
                                <small class="text-muted">Type</small>
                            </label>
                            <select class="form-select form-select-sm" id="FILTER_TYPE" name="FILTER_TYPE">
                                <option value="">All</option> <!-- Placeholder option -->
                                <option value="news">News</option>
                                <option value="announcement">Announcement</option>
                            </select>
                        </div>

                        <div>
                            <label for="searchInputElement" class="mb-1">
                                <small class="text-muted">
                                    <i class="fa-solid fa-circle-info fs-9"></i> Search by Column Data
                                </small>
                            </label>
                            <input type="text" oninput="debouncedSearchNewsListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search by column ..">
                        </div>
                        <div class="align-self-end">
                            <button class="btn btn-sm btn-light border border-secondary" onclick="filterNewsReport()">Filter</button>
                        </div>
                    </div>

                    <!-- Right side: Export -->
                    <div class="col-md-3 text-end">
                        <button id="exportCsvBtn" onclick="exportNewsData('csv')" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-file-csv"></i>
                        </button>
                    </div>

                </div>
                <table class="table table-row-bordered gy-3" id="news-list" style="white-space: nowrap;">
                    <thead>
                        <tr class="fw-bold fs-7 text-gray-900">
                            <th>#</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Audience</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="news-list-tbody">
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
$this->load->view('pages/news/new');
?>