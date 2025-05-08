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
                                <table class="table align-middle fs-7 gy-3 table-row-bordered " id="product-list">
                                    <thead>
                                        <tr class="fw-bold fs-7 text-gray-900">
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
                        <div class="col-md-3 col-lg-2 py-4">
                            <div class="row">
                                <div class="col-md-12 mb-0">
                                    <div class="">
                                        <h6 class="fw-bold mb-0">Type</h6>
                                    </div>
                                    <div class="my-4">
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="IBM" name="type" data-column-name="DIVISION" class="getFilters">
                                            <label class="mb-0 text-filter">Food</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="Z3P" name="type" data-column-name="DIVISION" class="getFilters">
                                            <label class="mb-0 text-filter">Industrial</label>
                                        </div>
                                    </div>
                                    <div class="">
                                        <h6 class="fw-bold mb-0">Categories</h6>
                                    </div>
                                    <div class="my-4" id="filter-categories-container"></div>
                                </div>

                                <div class="col-md-12 mb-0">
                                    <div class="">
                                        <h6 class="fw-bold mb-0">Volume / Capacity</h6>
                                    </div>
                                    <div class="my-4">
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="VOLUME" value="gm" name="volume" class="getFilters">
                                            <label class="mb-0 text-filter">GM</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="VOLUME" value="kg" name="volume" class="getFilters">
                                            <label class="mb-0 text-filter">KG</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="VOLUME" value="ml" name="volume" class="getFilters">
                                            <label class="mb-0 text-filter">ML</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="VOLUME" value="ltr" name="volume" class="getFilters">
                                            <label class="mb-0 text-filter">LTR</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-0">
                                    <div class="">
                                        <h6 class="fw-bold mb-0">Shape</h6>
                                    </div>
                                    <div class="my-4">
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="crate" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Crate</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="cup" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Cup</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="oval" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Oval</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="rectangular" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Rectangular</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="round" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Round</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="square" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Square</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="tub" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Tub</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Height Range Slider -->
                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-bold slider-margin-bottom">External Max Height</h6>
                                    <div class="slider-container">
                                        <div id="heightSlider" class="filter-elements"></div>
                                        <input name="heightSliderInput" type="hidden" id="heightSliderInput" value="" />
                                    </div>
                                </div>

                                <!-- Width Range Slider -->
                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-bold slider-margin-bottom">External Max Width</h6>
                                    <div class="slider-container">
                                        <div id="widthSlider" class="filter-elements"></div>
                                        <input name="widthSliderInput" type="hidden" id="widthSliderInput" value="" />
                                    </div>
                                </div>

                                <!-- Length Range Slider -->
                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-bold slider-margin-bottom">External Max Length</h6>
                                    <div class="slider-container">
                                        <div id="lengthSlider" class="filter-elements"></div>
                                        <input name="lengthSliderInput" type="hidden" id="lengthSliderInput" value="" />
                                    </div>
                                </div>
                            </div>

                            <div class="row my-4">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-sm btn-secondary border-danger" type="button" onclick="resetFilterOptions()">Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 col-lg-10">
                            <div class="row" id="grid-style-listing-container">

                            </div>
                        </div>
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