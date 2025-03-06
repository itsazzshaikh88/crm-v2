<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-3" id="survey-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th>#</th>
                                <th>Survey Name</th>
                                <th>Survey Desc</th>
                                <th>Survey Duration</th>
                                <th>Survey Conducted</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="survey-list-tbody">
                            <!-- <tr>
                                <td colspan="6" class="text-center text-danger">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <img src="assets/images/survey-img.png" class="w-200px mb-2" alt="">
                                        <h4 class="text-danger fw-normal">survey not created yet, <a
                                                href="survey/new">Click here to create new survey</a></h4>
                                    </div>
                                </td>
                            </tr> -->
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