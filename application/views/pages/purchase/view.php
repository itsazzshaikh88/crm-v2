<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start  container-xxl ">
    <div class="content flex-row-fluid" id="kt_content">
        <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
        <div class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework">
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <div class="card" id="kt_profile_details_view">
                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12 mb-8">
                                        <h1 id="main-lbl-REQUEST_TITLE" class="border-bottom border-bottom-dashed border-secondary text-muted pb-4"></h1>
                                    </div>
                                </div>
                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Email </label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fs-6 text-primary" id="lbl-EMAIL_ADDRESS"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">number</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fs-6" id="lbl-CONTACT_NUMBER"></span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Payment</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fs-6" id="lbl-PAYMENT_TERM"></span>
                                        <!-- <span class="fs-6" id="lbl-COMPANY_ADDRESS"></span> -->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">amount</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fs-6" id="lbl-TOTAL_AMOUNT"></span>
                                    </div>
                                    
                                    
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <!-- <div class="row mb-7">
                                    begin::Label-->
                                    <!-- <label class="col-lg-4 fw-semibold text-muted">Shipping Address</label> -->
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <!-- <div class="col-lg-8">
                                        <span class="fs-6" id="lbl-SHIPPING_ADDRESS"></span>
                                    </div> -->
                                    <!--end::Col-->
                                <!-- </div>  -->
                                <!--end::Row-->
                                <!--begin::Row-->
                                <!-- <div class="row mb-7">
                                    begin::Label-->
                                    <!-- <label class="col-lg-4 fw-semibold text-muted">Contact Number</label> -->
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <!-- <div class="col-lg-2">
                                        <span class="fs-6" id="lbl-CONTACT_NUMBER"></span>
                                    </div> -->
                                    <!--end::Col-->
                                    <!--begin::Label-->
                                    <!-- <label class="col-lg-2 fw-semibold text-muted text-start">Email Address</label> -->
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <!-- <div class="col-lg-4 ">
                                        <span class="fs-6" id="lbl-EMAIL_ADDRESS"></span>
                                    </div>
                                    end::Col
                                </div>  -->
                                <!--end::Row-->
                                <!--begin::Row-->
                                <!-- <div class="row mb-7"> -->
                                    <!--begin::Label-->
                                    <!-- <label class="col-lg-4 fw-semibold text-muted">Request Details</label> -->
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <!-- <div class="col-lg-8 bg-lighten rounded py-4">
                                        <span class="fs-6" id="lbl-REQUEST_DETAILS"></span>
                                    </div> -->
                                    <!--end::Col-->
                                <!-- </div> -->
                                <!--end::Row-->
                                <!--begin::Row-->
                                <!-- <div class="row mb-7"> -->
                                    <!--begin::Label-->
                                    <!-- <label class="col-lg-4 fw-semibold text-muted">Internal Notes</label> -->
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <!-- <div class="col-lg-8 bg-lighten rounded py-4">
                                        <span class="fs-6" id="lbl-INTERNAL_NOTES"></span>
                                    </div> -->
                                    <!--end::Col-->
                                <!-- </div> -->
                                <!--end::Row-->
                            </div>
                            <div class="col-md-4">
                                <div class="d-print-none bg-light-primary rounded border-primary border border-dashed card-rounded min-w-md-350px p-9 mb-8">
                                    <!--begin::Title-->
                                    <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">Client Details</h6>
                                    <!--end::Title-->

                                    <!--begin::Item-->
                                    <div class="mb-6">
                                        <div class="fw-semibold text-gray-600 fs-7">Company Name:</div>

                                        <div class="fw-bold text-gray-800 fs-6" id="lbl-COMPANY_NAME"></div>
                                    </div>
                                    <!--end::Item-->

                                    <!--begin::Item-->
                                    <!-- <div class="mb-6">
                                        <div class="fw-semibold text-gray-600 fs-7">Full Name:</div>

                                        <div class="fw-bold text-gray-800 fs-6" id="lbl-FULLNAME">
                                        </div>
                                    </div> -->
                                    <!--end::Item-->

                                </div>
                                <div class="notice d-flex flex-column rounded mb-9 p-4">
                                    <div class="fs-6 text-gray-700 mb-4">
                                        <h6>Attached Files</h6>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center justify-content-start gap-4" id="fileContainer">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4>Purchase Lines Details</h4>
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed gy-5 dataTable" id="kt_table_customers_payment" style="width: 100%;">
                                <colgroup>
                                    <col data-dt-column="0" style="width: 300px;">
                                    <col data-dt-column="1" style="width: 139.625px;">
                                    <col data-dt-column="2" style="width: 122.531px;">
                                    <col data-dt-column="3" style="width: 238.109px;">
                                    <col data-dt-column="4" style="width: 200px;">
                                </colgroup>
                                <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                    <tr class="text-start text-muted text-uppercase gs-0" role="row">
                                        <!-- <th>Product Details</th> -->
                                        <th>Quantity</th>
                                        <th>TOTAL</th>
                                        <th>Comments</th>
                                         <th>Color</th>
                                         <th>Transport</th>
                                       
                                    </tr>
                                </thead>
                                <tbody class="fs-6 fw-semibold text-gray-600" id="purchase-lines">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Main column-->
        </div>
    </div>
</div>
<?php $this->load->view('loaders/full-page-loader'); ?>