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
<div class="modal bg-body fade" tabindex="-1" id="productListFullScreenModal">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content shadow-none">
            <div class="modal-header py-2 bg-light">
                <h5 class="modal-title text-primary">Choose Product <small class="fw-normal text-black">(Click on the product to select for request)</small> </h5>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-light-danger text-danger ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row grid-product-listing" id="prod-listing-modal-grid">
                    <div class="col-md-3 col-lg-2 py-4">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" oninput="debouncedSearchProductListFromModal(this)" class="form-control form-control-sm rounded mb-4" placeholder="Search Product ..">
                            </div>
                            <div class="col-md-12 mb-0">
                                <div class="">
                                    <h6 class="fw-bold mb-0">Type</h6>
                                </div>
                                <div class="my-4">
                                    <div class="d-flex align-items-center justify-content-start gap-2">
                                        <input type="checkbox" value="Z3P" name="type" data-column-name="DIVISION" class="getFilters">
                                        <label class="mb-0 text-filter">Food</label>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start gap-2">
                                        <input type="checkbox" value="IBM" name="type" data-column-name="DIVISION" class="getFilters">
                                        <label class="mb-0 text-filter">Industrial</label>
                                    </div>
                                </div>
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
                        <div class="row" id="prod-listing-modal-grid-container">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 pt-0">
                <?= renderPaginate('prod-modal-current-page', 'prod-modal-total-pages', 'prod-modal-page-of-pages', 'prod-modal-range-of-records', 'handleProductListModalPagination') ?>
            </div>
        </div>
    </div>
</div>