<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="table-responsive">
                    <table class="table align-middle table-row-bordered fs-7 gy-3 dataTable table-row-bordered " id="lead-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th class="text-center">#</th>
                                <th class="">Lead #</th>
                                <th>Name</th>
                                <th>Company Name</th>
                                <th>Role</th>
                                <th>Contact</th>
                                <th>Lead Created</th>
                                <th>Lead Source</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="lead-list-tbody">
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
$this->load->view('pages/leads/modals/new-lead');
$this->load->view('modals/activities/activity-modal');
?>