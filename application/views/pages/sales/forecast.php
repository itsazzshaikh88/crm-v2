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
        z-index: 1;
        background-color: white;
    }

    .sticky-start {
        position: sticky !important;
        left: 0;
        background-color: white;
        z-index: 2;
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
                    <div class="col-md-6 px-0">
                        <!-- Left: Download Buttons -->
                        <div id="download-buttons-container" class="d-none">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-light-info" onclick="downloadForecastData('excel')">
                                    <i class="fas fa-download"></i> Excel
                                </button>
                                <button type="button" class="btn btn-sm btn-light-info" onclick="downloadForecastData('csv')">
                                    <i class="fas fa-download"></i> CSV
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 px-0">
                        <!-- Right: Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end align-items-center">
                            <button type="button" class="btn btn-sm px-2" onclick="toggleFullScreen()" title="Toggle Fullscreen">
                                <i id="fullscreen-icon" class="fas fa-expand text-black"></i>
                            </button>


                            <button type="button" class="btn btn-sm px-2 text-danger" onclick="restartForecast()" title="Restart Sales Forecast">
                                <i class="fas fa-sync-alt text-danger"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-primary" onclick="createNewForecast()">
                                <i class="fas fa-plus-circle me-1"></i> New Sales Forecast
                            </button>
                            <button type="button" class="btn btn-sm btn-light-warning" onclick="createNewForecast()">
                                <i class="fas fa-lightbulb me-1"></i> Suggest Forecast
                            </button>
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
                        <button type="button" class="btn btn-sm btn-success" onclick="viewForecastRecords()">
                            <i class="fas fa-eye me-1"></i> View
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 px-0">
                        <div class="table-responsive">
                            <table class="table table-row-bordered gy-3" id="sales-forecast-list" style="white-space: nowrap;">
                                <thead>
                                    <!-- First Row: Empty columns for the first 10 fields, followed by month names -->
                                    <tr class="fw-bold fs-7 text-gray-900">
                                        <th class="sticky-start bg-white" style="left: 0; z-index: 2;"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>
                                        <th class="bg-secondary"></th>

                                        <!-- Month Names Only -->
                                        <th class="bg-secondary text-center" colspan="3">Jan</th>
                                        <th class="bg-secondary text-center" colspan="3">Feb</th>
                                        <th class="bg-secondary text-center" colspan="3">Mar</th>
                                        <th class="bg-secondary text-center" colspan="3">Apr</th>
                                        <th class="bg-secondary text-center" colspan="3">May</th>
                                        <th class="bg-secondary text-center" colspan="3">Jun</th>
                                        <th class="bg-secondary text-center" colspan="3">Jul</th>
                                        <th class="bg-secondary text-center" colspan="3">Aug</th>
                                        <th class="bg-secondary text-center" colspan="3">Sep</th>
                                        <th class="bg-secondary text-center" colspan="3">Oct</th>
                                        <th class="bg-secondary text-center" colspan="3">Nov</th>
                                        <th class="bg-secondary text-center" colspan="3">Dec</th>
                                    </tr>

                                    <!-- Second Row: Columns for each month (Qty, Unit, Value) and the actual data columns -->
                                    <tr class="fw-bold fs-7 text-gray-900">
                                        <th class="sticky-start bg-white" style="left: 0; z-index: 2;"></th>
                                        <th class="bg-light">#</th>
                                        <th class="bg-light">Cust. #</th>
                                        <th class="bg-light">Cust. Name</th>
                                        <th class="bg-light">Category</th>
                                        <th class="bg-light">Sub Category</th>
                                        <th class="bg-light">Item Code</th>
                                        <th class="bg-light">Item Description</th>
                                        <th class="bg-light">Product Weight</th>
                                        <th class="bg-light">UOM</th>
                                        <th class="bg-light">Sales Man</th>
                                        <th class="bg-light">Region</th>
                                        <th class="bg-light">Status</th>

                                        <!-- Qty, Unit, Value for each month -->
                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>

                                        <th class="bg-light text-center">Qty.</th>
                                        <th class="bg-light text-center">Unit</th>
                                        <th class="bg-light text-center">Value</th>
                                    </tr>
                                </thead>

                                <tbody id="sales-forecast-list-tbody">
                                    <!-- Data rows go here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
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