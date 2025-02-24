<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-dashed gy-7" id="request-list" style="white-space: nowrap;">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th>#</th>
                                <th>Request #</th>
                                <th class="w-350">Title</th>
                                <th class="w-350">Description</th>
                                <th class="w-200">Client Name</th>
                                <th class="w-200">Company</th>
                                <th>Contact #</th>
                                <th>Email</th>
                                <th>Request Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="request-list-tbody">
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

<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('pages/requests/new-v1');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');
$this->load->view('pages/products/modals/product-list');
?>