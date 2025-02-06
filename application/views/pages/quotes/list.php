<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <!-- <div class="table-responsive"> -->
                    <table class="table table-row-bordered gy-3" id="quote-list" style="white-space: nowrap;">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th>#</th>
                                <th class="">Quotes #</th>
                                <th>Client</th>
                                <th>Employee Name</th>
                                <th>Job Title</th>
                                <th>Email</th>
                                <th>Sales Person</th>
                                <th class="">Request #</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="quote-list-tbody">
                    
                        </tbody>
                    </table>
                <!-- </div> -->
                <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->
<?php
$this->load->view('pages/quotes/quote-modal');
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');
$this->load->view('modals/products/product-list');
?>