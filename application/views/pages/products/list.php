<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="d-flex align-items-center justify-content-end gap-2 mb-4">
                    <select name="CATEGORY_ID" id="CATEGORY_ID" class="bg-light border border-light p-4 rounded w-200 filter-input">
                        <option value="">Category</option>

                    </select>
                    <button class="bg-primary text-white border-0 p-4 rounded" onclick="filterProducts()">Apply</button>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable table-row-bordered " id="product-list">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th>#</th>
                                <th>Product</th>
                                <th>UOM</th>
                                <th>Unit Price</th>
                                <th>Avl Stock</th>
                                <th>Weight</th>
                                <th>Color</th>
                                <th>Category</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="product-list-tbody">

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