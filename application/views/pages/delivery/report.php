<style>
    .truncate-cell {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .truncate-cell.expanded {
        white-space: normal;
        overflow: visible;
        text-overflow: unset;
        max-width: none;
        background-color: #f8f9fa;
    }

</style>
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
                            <option selected value="IBM">IBM</option>
                            <option value="Z3P">Z3P</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="FROM_DATE">From Date</label>
                        <input type="date" id="FROM_DATE" class="form-control form-control-sm"
                            value="<?php echo date('Y-m-01'); ?>" />
                    </div>

                    <div class="col-md-2">
                        <label for="TO_DATE">To Date</label>
                        <input type="date" id="TO_DATE" class="form-control form-control-sm"
                            value="<?php echo date('Y-m-t'); ?>" />
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-sm btn-light border border-secondary" onclick="filterDeliveryReport()">Filter</button>
                    </div>
                </div>
                <div class="table-responsive mt-3" style="max-height: 400px; overflow: auto;">
                    <table class="table table-row-bordered gy-1" id="delivery-list" style="white-space: nowrap;">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th class=" text-center">#</th>
                                <th class="">Del #</th>
                                <th class="">Del Line ID</th>
                                <th class="">Source</th>
                                <th class="">SOC</th>
                                <th class="">Line #</th>
                                <th class="">Item</th>
                                <th class="">Desc</th>
                                <th class="">Cust ID</th>
                                <th class="">Req. Qty</th>
                                <th class="">Ship. Qty</th>
                                <th class="">Cust PO #</th>
                                <th class="">Pck Details</th>
                                <th class=""># Pack</th>
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