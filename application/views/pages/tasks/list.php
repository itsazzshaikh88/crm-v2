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

    /* Fix the first column when scrolling horizontally */
    #task-list tbody td:first-child,
    #task-list thead th:first-child {
        position: sticky;
        left: 0;
        z-index: 1;
    }

    #task-list td {
        white-space: nowrap;
    }

    /* Optional: styling for the sticky header */
    #task-list thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .fa.fa-minus,
    .fa.fa-plus {
        font-size: 0.75rem;
        /* Optional rounded background */
        line-height: 1;
        vertical-align: middle;
        color: rgb(241, 49, 15);
        /* Slightly darker text */
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fa.fa-minus:hover,
    .fa.fa-plus:hover {
        color: #0d6efd;
    }
</style>


<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-12 px-0">
                        <!-- Button Container: All buttons in one line -->
                        <div class="d-flex gap-2 justify-content-end align-items-center">
                            <!-- Download Buttons -->
                            <div id="download-buttons-container" class="d-nones">
                                <button type="button" class="btn btn-sm btn-light-info" onclick="downloadTaskData('excel')">
                                    <i class="fas fa-download"></i> Excel
                                </button>
                                <button type="button" class="btn btn-sm btn-light-info" onclick="downloadTaskData('csv')">
                                    <i class="fas fa-download"></i> CSV
                                </button>
                            </div>

                            <!-- Action Buttons -->
                            <button type="button" class="btn btn-sm px-2" onclick="toggleFullScreen()" title="Toggle Fullscreen">
                                <i id="fullscreen-icon" class="fas fa-expand text-black"></i>
                            </button>
                            <button type="button" class="btn btn-sm px-2 text-danger" onclick="reloadTaskData()" title="Reload Task Data">
                                <i class="fas fa-sync-alt text-danger"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row g-1 mb-2 align-items-end small">
                    <!-- Task Name -->
                    <div class="col-md-2">
                        <label for="TASK_NAME" class="form-label mb-1 small">Task Name</label>
                        <select name="TASK_NAME" id="TASK_NAME" class="form-select form-select-sm">
                            <option value="">All</option>
                            <!-- Add options dynamically -->
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-2">
                        <label for="STATUS" class="form-label mb-1 small">Status</label>
                        <select name="STATUS" id="STATUS" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- From Date -->
                    <div class="col-md-2">
                        <label for="START_DATE" class="form-label mb-1 small">From Date</label>
                        <input type="date" name="START_DATE" id="START_DATE" class="form-control form-control-sm">
                    </div>

                    <!-- To Date -->
                    <div class="col-md-2">
                        <label for="TARGET_DATE" class="form-label mb-1 small">To Date</label>
                        <input type="date" name="TARGET_DATE" id="TARGET_DATE" class="form-control form-control-sm">
                    </div>

                    <!-- View Button -->
                    <div class="col-md-2">
                        <button id="filterTasksBtn" class="btn btn-sm btn-primary mt-1">View</button>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-12 px-0">
                        <div class="table-responsive">
                            <table class="table table-row-bordered gy-3" id="task-list" style="white-space: nowrap;">
                                <thead>
                                    <tr class="fw-bold fs-7 text-gray-900">
                                        <th class=""></th>
                                        <th class="bg-light text-center">#</th>
                                        <th class="bg-light text-start">Task ID</th>
                                        <th class="bg-light text-start">Task Name</th>
                                        <th class="bg-light text-start">Status</th>
                                        <th class="bg-light text-start">Department</th>
                                        <th class="bg-light text-start">Consultant</th>
                                        <th class="bg-light text-center">Start Date</th>
                                        <th class="bg-light text-center">Target Date</th>
                                        <th class="bg-light text-center">End Date</th>
                                        <th class="bg-light text-end">Duration</th>
                                        <th class="bg-light text-start">Created By</th>
                                        <th class="bg-light text-center">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rendered rows will be inserted here dynamically by JS -->
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
$this->load->view('pages/tasks/new');
?>