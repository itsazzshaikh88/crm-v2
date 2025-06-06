<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <label for="ORG_ID">Division</label>
                        <select id="ORG_ID" class="form-control form-control-sm">
                            <option value="">-- Select Division --</option>
                            <option selected value="145">IBM</option>
                            <option value="442">Z3P</option>
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
                    <div class="col-md-2">
                        <label for="TO_DATE"><small>Search By Column data</small></label>
                        <input type="text" oninput="debouncedSearchInvoiceData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search by column ..">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-sm btn-light border border-secondary" onclick="filterInvoiceReport()">Filter</button>
                    </div>
                </div>

                <div class="table-responsive mt-3" style="max-height: 400px; overflow: auto;">
                    <table class="table table-row-bordered gy-1" id="invoices-list" style="white-space: nowrap;">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th class="">#</th>
                                <th class="">Invoice #</th>
                                <th class="">Invoice Date</th>
                                <th class="">Customer</th>
                                <th class="">Invoice Type</th>
                                <th class="">Tax Amount</th>
                                <th class="">Total Amount</th>
                                <th class="">Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoices-list-tbody">

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