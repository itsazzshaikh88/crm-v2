<div class="modal fade" tabindex="-1" id="new-client-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex flex-column align-items-center justify-content-between p-4">
                <div class="w-100 d-flex align-items-center justify-content-between p-0">
                    <h5 class="modal-title">Create New Client</h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="resetNewClientForm()">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <!--end::Close-->
                </div>
            </div>
            <div class="modal-body p-0">
                <form action="" method="post" id="new_client_form">
                    <div class="bg-light px-4 py-4">
                        <h6 class="text-primary fw-normal">Client Details</h6>
                        <div class="row mb-1 g-0">
                            <div class="col-md-6">
                                <input type="text" name="FIRST_NAME" id="lbl-modal-FIRST_NAME" placeholder="Client First Name" class="form-control form-control-sm rounded-0">
                                <input type="hidden" name="UUID" id="lbl-modal-UUID" value="">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="LAST_NAME" id="lbl-modal-LAST_NAME" placeholder="Client Last Name" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                        <div class="row mb-1 g-0">
                            <div class="col-md-12">
                                <input type="text" name="COMPANY_NAME" id="lbl-modal-COMPANY_NAME" placeholder="Company Name" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                        <div class="row mb-1 g-0">
                            <div class="col-md-12">
                                <input type="text" name="SITE_NAME" id="lbl-modal-SITE_NAME" placeholder="Company Site Name" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                        <div class="row mb-1 g-0">
                            <div class="col-md-6">
                                <input type="text" name="PHONE_NUMBER" id="lbl-modal-PHONE_NUMBER" placeholder="Contact number" class="form-control form-control-sm rounded-0">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="EMAIL" id="lbl-modal-EMAIL" placeholder="Email address" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 px-4 py-4">
                        <h6 class="text-primary fw-normal">Address Details</h6>
                        <div class="row mb-1 g-0">
                            <div class="col-md-12 mb-1">
                                <input type="text" name="ADDRESS_LINE_1" id="lbl-modal-ADDRESS_LINE_1" placeholder="Address Line 1" class="form-control form-control-sm rounded-0">
                            </div>
                            <div class="col-md-12 mb-1">
                                <input type="text" name="ADDRESS_LINE_2" id="lbl-modal-ADDRESS_LINE_2" placeholder="Address Line 2" class="form-control form-control-sm rounded-0">
                            </div>
                            <div class="col-md-12 mb-1">
                                <input type="text" name="BILLING_ADDRESS" id="lbl-modal-BILLING_ADDRESS" placeholder="Billing Address" class="form-control form-control-sm rounded-0">
                            </div>
                            <div class="col-md-12">
                                <input type="text" name="SHIPPING_ADDRESS" id="lbl-modal-SHIPPING_ADDRESS" placeholder="Shipping Address" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                        <div class="row mb-1 g-0">
                            <div class="col-md-6">
                                <input type="text" name="CITY" id="lbl-modal-CITY" placeholder="City Name" class="form-control form-control-sm rounded-0">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="STATE" id="lbl-modal-STATE" placeholder="State/Provice" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                        <div class="row mb-1 g-0">
                            <div class="col-md-6">
                                <input type="text" name="COUNTRY" id="lbl-modal-COUNTRY" placeholder="Country name" class="form-control form-control-sm rounded-0">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="ZIP_CODE" id="lbl-modal-ZIP_CODE" placeholder="Zip code" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                    </div>
                    <div class="bg-light mb-4 px-4 py-2">
                        <h6 class="text-primary fw-normal">Financial Details</h6>
                        <div class="row mb-1 g-0">
                            <div class="col-md-12">
                                <input type="text" name="PAYMENT_TERM" id="lbl-modal-PAYMENT_TERM" placeholder="Payment Term" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                        <div class="row mb-1 g-0">
                            <div class="col-md-6">
                                <input type="text" name="CURRENCY" id="lbl-modal-CURRENCY" placeholder="Currency" class="form-control form-control-sm rounded-0">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="TAXES" id="lbl-modal-TAXES" placeholder="Tax Percentage" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                        <div class="row mb-1 g-0">
                            <div class="col-md-6">
                                <input type="text" name="CREDIT_LIMIT" id="lbl-modal-CREDIT_LIMIT" placeholder="Credit Limit" class="form-control form-control-sm rounded-0">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="ORDER_LIMIT" id="lbl-modal-ORDER_LIMIT" placeholder="Order Limit" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 px-4 py-4">
                        <h6 class="text-primary fw-normal">Account Login Details</h6>
                        <div class="row mb-1 g-0">
                            <div class="col-md-6">
                                <input type="text" name="USERNAME_PLACEHOLDER" id="USERNAME_PLACEHOLDER" placeholder="Account Email Address" class="form-control form-control-sm rounded-0" readonly>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="PASSWORD" id="lbl-modal-PASSWORD" placeholder="Password" class="form-control form-control-sm rounded-0">
                            </div>
                        </div>
                    </div>
                    <div class="text-end mb-4 px-4">
                        <button class="btn btn-sm btn-success" id="btn-new-client-modal">Create New Client</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>