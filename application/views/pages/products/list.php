<style>
    .grid-product-listing .image {
        width: 100% !important;
        height: 180px !important;
    }

    .grid-product-listing .image img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
    }

    .bg-header {
        background-color: #4c9c2e;
    }

    .text-filter {
        color: #565656 !important;
    }

    /* Sliders  */
    .slider-container {
        width: 100%;
        margin: 20px 0;
        position: relative;
    }

    .noUi-tooltip {
        display: block;
        color: rgb(74, 90, 104);
        padding: 0px;
        border-radius: 4px;
        font-size: 8px;
        text-align: center;
        position: absolute;
        bottom: 150%;
        transform: translateX(-50%);
        white-space: nowrap;
    }

    /* Styling the Slider Track */
    .noUi-target {
        background: #fff;
        /* Default track color */
        border-radius: px;
        border: 1px solid #e7e7e7;
        height: 10px !important;
    }

    .noUi-connects {
        background-color: #fff;
        border: 1px solid #e7e7e7;
        height: 10px !important;
    }

    /* Coloring the selected range between handles */
    .noUi-connect {
        background: #5C7285 !important;
        height: 10px;
        /* Change this to any color */
    }

    /* Handle styling */
    .noUi-handle {
        background: #fff !important;
        border: 1px solid #5C7285 !important;
        border-radius: 50%;
        cursor: pointer;
        height: 18px !important;
        width: 18px !important;
    }

    .slider-margin-bottom {
        margin-bottom: 20px !important;
    }
</style>
<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="row mb-4">
                    <div class="col-md-12 text-end">
                        <button class="btn btn-sm border" onclick="toggleReportLayout('grid')" id="gridViewBtn">
                            <i class="fas fa-th"></i> Grid View
                        </button>
                        <button class="btn btn-sm border" onclick="toggleReportLayout('list')" id="listViewBtn">
                            <i class="fas fa-list"></i> List View
                        </button>
                        <div class="border-bottom border-light my-2"> </div>
                    </div>
                </div>
                <div>
                    <div class="row d-none" id="list-style-listing">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable table-row-bordered " id="product-list">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800">
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Description</th>
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
                        </div>
                    </div>
                    <div class="row d-none grid-product-listing" id="grid-style-listing">
                        
                    </div>

                </div>
                <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->
<?php
$this->load->view('pages/products/new-v2');
$this->load->view('loaders/full-page-loader');
?>