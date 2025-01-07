<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-7" id="purchase-list">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th>#</th>
                                <th>PO #</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Company Address</th>
                                <th>Contact #</th>
                                <th>Payment</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Qty</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="purchase-list-tbody">
                           
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
$this->load->view('pages/purchase/purchase-modal');
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');
$this->load->view('modals/products/product-list');

?>