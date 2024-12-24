<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-7">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th>#</th>
                                <th>Survey #</th>
                                <th>Survey Title</th>
                                <th>Feedback On</th>
                                <th>Company</th>
                                <th>Year</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-danger">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <img src="assets/images/survey-img.png" class="w-200px mb-2" alt="">
                                        <h4 class="text-danger fw-normal">Survey Feedback not provided by clients.</h4>
                                    </div>
                                </td>
                            </tr>
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