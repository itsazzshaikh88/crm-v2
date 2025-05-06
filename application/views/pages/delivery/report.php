<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <label for="ORG">Division</label>
                        <select id="ORG" class="form-control form-control-sm">
                            <option value="">-- Select Division --</option>
                            <option value="IBM">IBM</option>
                            <option value="Z3P">Z3P</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-sm btn-light border border-secondary" onclick="filterDeliveryReport()">Filter</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-2" id="delivery-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th>#</th>
                                <th>Del #</th>
                                <th>Del Line ID</th>
                                <th>Source</th>
                                <th>SOC</th>
                                <th>Line #</th>
                                <th>Item</th>
                                <th>Desc</th>
                                <th>Cust ID</th>
                                <th>Req. Qty</th>
                                <th>Ship. Qty</th>
                                <th>Cust PO #</th>
                                <th>Pck Details</th>
                                <th># Pack</th>
                            </tr>
                        </thead>
                        <tbody id="delivery-list-tbody">

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