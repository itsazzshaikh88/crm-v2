<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="table-responsive">
                    <table class="table align-middle table-row-bordered fs-7 gy-3 table-row-bordered " id="contact-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th class="text-center">#</th>
                                <th>Contact</th>
                                <th>Company Name</th>
                                <th>Job Title</th>
                                <th>Contact Details</th>
                                <th>Assigned To</th>
                                <th>Contact Source</th>
                                <th>Prefered Method</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="contact-list-tbody"></tbody>
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
$this->load->view('modals/contact/new-contact');
?>