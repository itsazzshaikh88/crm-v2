<style>
    .suggestive-forcast-table thead tr th,
    .suggestive-forcast-table tbody tr td {
        padding: 5px !important;
        font-size: 12px !important;
        border: 1px solid #f7f7f7 !important;
    }
</style>
<div class="modal bg-body fade" tabindex="-1" id="suggest-sales-forecast-modal">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content shadow-none">
            <div class="modal-header py-2 px-3">
                <h2 class="modal-title text-primary fs-6 mb-0 d-flex align-items-center fw-normal">
                    <i class="fa-solid fa-chart-area text-primary me-2 fs-6"></i> Suggest Sales Forecast
                </h2>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="closeSuggestiveSalesForecastModal()">
                    <i class="fa-solid fa-xmark text-danger fs-5"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body bg-light">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Filter Elements (Without Form Tag) -->
                        <div class="row">
                            <!-- Organization Filter -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="FILTER_SF_ORG_ID">Organization</label>
                                    <select class="form-control form-control-sm" id="FILTER_SF_ORG_ID" name="FILTER_SF_ORG_ID">
                                        <option value="">-- Select Organization --</option>
                                        <option value="145">IBM</option>
                                        <option value="442">Z3P</option>
                                    </select>
                                </div>
                                <p class="text-danger err-lbl mb-0" id="lbl-filters-sf-ORG_ID"></p>
                            </div>

                            <!-- Start Year Filter -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="FILTER_SF_START_YEAR">Start Year</label>
                                    <select class="form-control form-control-sm" id="FILTER_SF_START_YEAR" name="FILTER_SF_START_YEAR">
                                        <option value="">-- Select Start Year --</option>
                                        <?php
                                        $currentYear = date("Y");
                                        for ($year = 2020; $year <= $currentYear; $year++) {
                                            echo "<option value='$year'>$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <p class="text-danger err-lbl mb-0" id="lbl-filters-sf-START_YEAR"></p>
                            </div>

                            <!-- Mode Filter -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="FILTER_SF_MODE">Forecast Mode</label>
                                    <select class="form-control form-control-sm" id="FILTER_SF_MODE" name="FILTER_SF_MODE">
                                        <option value="">-- Select Mode --</option>
                                        <option value="low">Low</option>
                                        <option value="mod">Moderate</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                                <p class="text-danger err-lbl mb-0" id="lbl-filters-sf-MODE"></p>
                            </div>

                            <!-- Total Years Filter -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="FILTER_SF_TOTAL_YEARS">Total Years</label>
                                    <select class="form-control form-control-sm" id="FILTER_SF_TOTAL_YEARS" name="FILTER_SF_TOTAL_YEARS">
                                        <option value="">-- Select Total Years --</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <p class="text-danger err-lbl mb-0" id="lbl-filters-sf-TOTAL_YEARS"></p>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button id="apply-filters" onclick="fetchSuggestiveForecast()" type="button" class="btn btn-primary btn-sm mt-3">Apply Filters</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 bg-white py-4">
                        <div class="table-responsive">
                            <table class="table table-sm suggestive-forcast-table" id="suggestive-forcast-table">
                                <thead>
                                    <tr>
                                        <th colspan="8"></th>
                                        <th colspan="2" class="text-center bg-secondary">JAN</th>
                                        <th colspan="2" class="text-center bg-secondary">FEB</th>
                                        <th colspan="2" class="text-center bg-secondary">MAR</th>
                                        <th colspan="2" class="text-center bg-secondary">APR</th>
                                        <th colspan="2" class="text-center bg-secondary">MAY</th>
                                        <th colspan="2" class="text-center bg-secondary">JUN</th>
                                        <th colspan="2" class="text-center bg-secondary">JUL</th>
                                        <th colspan="2" class="text-center bg-secondary">AUG</th>
                                        <th colspan="2" class="text-center bg-secondary">SEP</th>
                                        <th colspan="2" class="text-center bg-secondary">OCT</th>
                                        <th colspan="2" class="text-center bg-secondary">NOV</th>
                                        <th colspan="2" class="text-center bg-secondary">DEC</th>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">#</th>
                                        <th class="bg-light">Div</th>
                                        <th class="bg-light">Category</th>
                                        <th class="bg-light">Cust. #</th>
                                        <th class="bg-light">Cust. Name</th>
                                        <th class="bg-light">Item</th>
                                        <th class="bg-light">Desc</th>
                                        <th class="bg-light">USP</th>

                                        <!-- Using bg-* with opacity for lighter backgrounds -->
                                        <th class="text-center bg-success bg-opacity-25">Qty</th>
                                        <th class="text-center bg-success bg-opacity-25">Val</th>

                                        <th class="text-center bg-danger bg-opacity-25">Qty</th>
                                        <th class="text-center bg-danger bg-opacity-25">Val</th>

                                        <th class="text-center bg-primary bg-opacity-25">Qty</th>
                                        <th class="text-center bg-primary bg-opacity-25">Val</th>

                                        <th class="text-center bg-success bg-opacity-25">Qty</th>
                                        <th class="text-center bg-success bg-opacity-25">Val</th>

                                        <th class="text-center bg-danger bg-opacity-25">Qty</th>
                                        <th class="text-center bg-danger bg-opacity-25">Val</th>

                                        <th class="text-center bg-primary bg-opacity-25">Qty</th>
                                        <th class="text-center bg-primary bg-opacity-25">Val</th>

                                        <th class="text-center bg-success bg-opacity-25">Qty</th>
                                        <th class="text-center bg-success bg-opacity-25">Val</th>

                                        <th class="text-center bg-danger bg-opacity-25">Qty</th>
                                        <th class="text-center bg-danger bg-opacity-25">Val</th>

                                        <th class="text-center bg-primary bg-opacity-25">Qty</th>
                                        <th class="text-center bg-primary bg-opacity-25">Val</th>

                                        <th class="text-center bg-success bg-opacity-25">Qty</th>
                                        <th class="text-center bg-success bg-opacity-25">Val</th>

                                        <th class="text-center bg-danger bg-opacity-25">Qty</th>
                                        <th class="text-center bg-danger bg-opacity-25">Val</th>

                                        <th class="text-center bg-primary bg-opacity-25">Qty</th>
                                        <th class="text-center bg-primary bg-opacity-25">Val</th>

                                    </tr>
                                </thead>

                                <tbody id="suggestive-forcast-table-tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>