<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="row">
                    <div class="col-md-5 border-end border-light bg-light rounded pb-5">
                        <h6 class="text-primary my-4">Add New Category</h6>
                        <form id="form" method="post" onsubmit="submitForm(event)" enctype="multipart/form-data">
                            <input type="hidden" name="ID" id="ID">
                            <div class="form-group row align-items-center mb-2">
                                <label for="CATEGORY_CODE" class="col-md-4 text-gray-800 fw-bold">Category Code <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-800 fw-normal" name="CATEGORY_CODE" id="CATEGORY_CODE" placeholder="Enter Category Code">
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-CATEGORY_CODE"></p>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-2">
                                <label for="CATEGORY_NAME" class="col-md-4 text-gray-800 fw-bold">Category Name <span class="text-danger">*</span> <span class="float-end">:</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-800 fw-normal" name="CATEGORY_NAME" id="CATEGORY_NAME" placeholder="Enter Category Name ...">
                                    <p class="text-danger err-lbl mb-0 fs-8" id="lbl-CATEGORY_NAME"></p>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-2">
                                <label for="DESCRIPTION" class="col-md-4 text-gray-800 fw-bold">Description <span class="float-end">:</span> </label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control form-control-sm border border-blue-100 text-gray-800 fw-normal" name="DESCRIPTION" id="DESCRIPTION" placeholder="Enter Category Description (Optional) ..."></textarea>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button type="submit" id="submit-btn" class="btn btn-sm btn-primary">
                                    Save Category
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-7">
                        <h6 class="text-primary my-4">Categories List</h6>
                        <div class="d-flex flex-wrap gap-3 mb-3 align-items-end">


                            <div>
                                <label for="searchInputElement" class="mb-1">
                                    <small class="text-muted">
                                        <i class="fa-solid fa-circle-info fs-9"></i> Search by Column Data
                                    </small>
                                </label>
                                <input type="text" oninput="debouncedSearchCategoryListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search...">
                            </div>
                            <div class="ms-auto d-flex gap-2 mt-2">
                                <button onclick="exportCategoryData('csv')" class="btn btn-primary btn-sm">
                                    <i class="fas fa-file-csv"></i>
                                </button>

                            </div>
                        </div>
                        <table class="table align-middle table-row-bordered fs-7 gy-3 dataTable table-row-bordered" id="category-list">
                            <thead>
                                <tr class="fw-bold fs-7 text-gray-900">
                                    <th class="text-center">#</th>
                                    <th>Code</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="category-list-tbody">
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->

<!-- Include modals to add new category  -->
<?php
$this->load->view('loaders/full-page-loader');
?>