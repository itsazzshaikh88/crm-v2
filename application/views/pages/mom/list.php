<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable table-row-bordered " id="mom-list">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-900">
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