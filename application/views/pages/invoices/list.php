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
                                <th>Invoice #</th>
                                <th>Invoice Date</th>
                                <th>PO #</th>
                                <th>Due Date</th>
                                <th>Currency</th>
                                <th>SOC #</th>
                                <th>Total</th>
                                <th>View Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center text-danger">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <img src="assets/images/no-data.png" class="no-data-img-table" alt="">
                                        <h4 class="text-danger">No data available</h4>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php include_once 'application/views/common/paginate.php' ?>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->