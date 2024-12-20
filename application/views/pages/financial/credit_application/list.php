<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-dashed gy-7" id="credit-list">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th>#</th>
                                <th class="w-350">Credit Details</th>
                                <th class="w-250">Company Details</th>
                                <th>Contact #</th>
                                <th>Email</th>
                                <th>Credit Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="credit-list-tbody">
                            
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
?>
