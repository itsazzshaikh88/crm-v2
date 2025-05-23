<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-3" id="customer-outstandings">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th>#</th>
                                <th>Invoice #</th>
                                <th>Invoice Date</th>
                                <th>PO #</th>
                                <th>Due Date</th>
                                <th>Currency</th>
                                <th>SOC</th>
                                <th>Total</th>
                                <th>Invoice</th>
                            </tr>
                        </thead>
                        <tbody id="customer-outstandings-tbody">

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