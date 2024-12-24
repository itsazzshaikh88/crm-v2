<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-7" id="fill-survey-list">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th>#</th>
                                <th>Survey #</th>
                                <th>Survey Name</th>
                                <th>Filled On</th>
                                <th>Filled By</th>
                                <th>Survey Year</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="fill-survey-list-tbody" class="text-gray-700">
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