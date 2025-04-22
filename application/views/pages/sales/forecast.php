<style>
    /* Required for sticky columns to work properly */
    .table-responsive {
        position: relative;
        overflow-x: auto;
        max-height: 600px;
        /* Optional: controls vertical height and enables scrolling */
    }

    .table-responsive thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: white;
    }

    .sticky-start {
        position: sticky !important;
        left: 0;
        background-color: white;
        z-index: 11;
        /* Must be higher than thead */
    }

    .swal2-popup.small-swal {
        font-size: 0.85rem !important;
        padding: 1.25rem;
    }

    /* Styling for Load More Button */
    .load-more-btn-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .fs-12 {
        font-size: 12px !important;
    }
</style>


<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12 px-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <!-- Left: Download Buttons -->
                            <div class="d-flex gap-5">
                                <button type="button" class="btn btn-sm p-2 text-decoration-underline" onclick="downloadForecastData('excel')">
                                    <i class="fas fa-download"></i> Excel
                                </button>
                                <button type="button" class="btn btn-sm p-2 text-decoration-underline" onclick="downloadForecastData('csv')">
                                    <i class="fas fa-download"></i> CSV
                                </button>
                            </div>

                            <!-- Right: Action Buttons -->
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-light-primary" onclick="restartForecast()">
                                    <i class="fas fa-sync-alt me-1"></i> Restart
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" onclick="createNewForecast()">
                                    <i class="fas fa-plus-circle me-1"></i> New Sales Forecast
                                </button>
                                <button type="button" class="btn btn-sm border border-secondary bg-light" onclick="createNewForecast()">
                                    <i class="fas fa-lightbulb text-warning me-1"></i> Suggest Forecast
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row mb-2 bg-light py-2 border-top border-secondary">
                    <!-- Division Dropdown -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold fs-12">Division</label>
                        <select id="ORG_ID" class="form-select form-select-sm" onchange="fetchForecastVersions()">
                            <option value="">Select Division</option>
                            <option value="242">IBM</option>
                            <option value="444">Z3P</option>
                        </select>
                    </div>

                    <!-- Year Dropdown -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold fs-12">Year</label>
                        <select id="YER" class="form-select form-select-sm" onchange="fetchForecastVersions()">
                            <option value="">Select Year</option>
                            <?php
                            $currentYear = date('Y');
                            for ($year = 2022; $year <= $currentYear; $year++) {
                                echo "<option value=\"$year\">$year</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Version Dropdown -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold fs-12">Version</label>
                        <select id="VER" class="form-select form-select-sm">
                            <option value="">Select Version</option>
                        </select>
                    </div>

                    <!-- View Button -->
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-primary" onclick="viewForecastRecords()">
                            <i class="fas fa-eye me-1"></i> View
                        </button>
                    </div>
                </div>




                <div class="table-responsive">
                    <table class="table table-row-bordered gy-3" id="sales-forecast-list" style="white-space: nowrap;">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th class="sticky-start bg-white" style="left: 0; z-index: 2;"></th>
                                <th class="bg-secondary">#</th>
                                <th class="bg-secondary">Cust. #</th>
                                <th class="bg-secondary">Cust. Name</th>
                                <th class="bg-secondary">Item Code</th>
                                <th class="bg-secondary">Item Description</th>
                                <th class="bg-secondary">Product Weight</th>
                                <th class="bg-secondary">UOM</th>
                                <th class="bg-secondary">Sales Man</th>
                                <th class="bg-secondary">Region</th>
                                <th class="bg-secondary">Status</th>
                                <th class="bg-secondary">Qty. (Jan)</th>
                                <th class="bg-secondary">Unit (Jan)</th>
                                <th class="bg-secondary">Value (Jan)</th>
                                <th class="bg-secondary">Qty. (Feb)</th>
                                <th class="bg-secondary">Unit (Feb)</th>
                                <th class="bg-secondary">Value (Feb)</th>
                                <th class="bg-secondary">Qty. (Mar)</th>
                                <th class="bg-secondary">Unit (Mar)</th>
                                <th class="bg-secondary">Value (Mar)</th>
                                <th class="bg-secondary">Qty. (Apr)</th>
                                <th class="bg-secondary">Unit (Apr)</th>
                                <th class="bg-secondary">Value (Apr)</th>
                                <th class="bg-secondary">Qty. (May)</th>
                                <th class="bg-secondary">Unit (May)</th>
                                <th class="bg-secondary">Value (May)</th>
                                <th class="bg-secondary">Qty. (Jun)</th>
                                <th class="bg-secondary">Unit (Jun)</th>
                                <th class="bg-secondary">Value (Jun)</th>
                                <th class="bg-secondary">Qty. (Jul)</th>
                                <th class="bg-secondary">Unit (Jul)</th>
                                <th class="bg-secondary">Value (Jul)</th>
                                <th class="bg-secondary">Qty. (Aug)</th>
                                <th class="bg-secondary">Unit (Aug)</th>
                                <th class="bg-secondary">Value (Aug)</th>
                                <th class="bg-secondary">Qty. (Sep)</th>
                                <th class="bg-secondary">Unit (Sep)</th>
                                <th class="bg-secondary">Value (Sep)</th>
                                <th class="bg-secondary">Qty. (Oct)</th>
                                <th class="bg-secondary">Unit (Oct)</th>
                                <th class="bg-secondary">Value (Oct)</th>
                                <th class="bg-secondary">Qty. (Nov)</th>
                                <th class="bg-secondary">Unit (Nov)</th>
                                <th class="bg-secondary">Value (Nov)</th>
                                <th class="bg-secondary">Qty. (Dec)</th>
                                <th class="bg-secondary">Unit (Dec)</th>
                                <th class="bg-secondary">Value (Dec)</th>
                            </tr>
                        </thead>
                        <tbody id="sales-forecast-list-tbody">
                            <!-- Data rows go here -->
                        </tbody>
                    </table>
                </div>

                <!-- Load More Button Container -->
                <div id="load-more-btn-container" class="load-more-btn-container text-center" style="display: none;">
                    <button id="load-more-btn" class="load-more-btn btn btn-sm btn-secondary" onclick="loadMoreForecastData()">Load More</button>
                </div>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->

<?php
$this->load->view('loaders/full-page-loader');
?>