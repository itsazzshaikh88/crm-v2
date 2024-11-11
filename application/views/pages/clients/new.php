<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <form id="form" class="form d-flex flex-column" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <!--begin::PAGE CONTENT GOES FROM HERE-->
            <div class="card mb-2">
                <div class="card-body">
                    <h2>Personal Details</h2>
                    <input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
                    <input type="hidden" name="ID" id="ID" value="">
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="FIRST_NAME" class="fs-6 fw-bold required">Client Name</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="FIRST_NAME" id="FIRST_NAME" placeholder="Customer First Name">
                            <span class="text-danger err-lbl" id="lbl-FIRST_NAME"></span>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="LAST_NAME" id="LAST_NAME" placeholder="Customer Last Name">
                            <span class="text-danger err-lbl" id="lbl-LAST_NAME"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="COMPANY_NAME" class="fs-6 fw-bold required">Company Name</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="COMPANY_NAME" id="COMPANY_NAME" placeholder="Enter company name">
                            <span class="text-danger err-lbl" id="lbl-COMPANY_NAME"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="SITE_NAME" class="fs-6 fw-bold required">Site Name</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="SITE_NAME" id="SITE_NAME" placeholder="Enter company name">
                            <span class="text-danger err-lbl" id="lbl-SITE_NAME"></span>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-end">
                            <label for="STATUS" class="fs-6 fw-bold required">Account Status</label>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="STATUS" id="STATUS">
                                <option value="">Select Status</option>
                                <option selected value="active">Active</option>
                                <option value="inactive">In-Active</option>
                            </select>
                            <span class="text-danger err-lbl" id="lbl-STATUS"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="PHONE_NUMBER" class="fs-6 fw-bold required">Contact Number</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="PHONE_NUMBER" id="PHONE_NUMBER" placeholder="Customer contact details">
                            <span class="text-danger err-lbl" id="lbl-PHONE_NUMBER"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-end">
                            <label for="EMAIL" class="fs-6 fw-bold required">Email Address</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="EMAIL" id="EMAIL" placeholder="Client email address" oninput="setUsername(this)">
                            <span class="text-danger err-lbl" id="lbl-EMAIL"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <h2>Address Details</h2>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="ADDRESS_LINE_1" class="fs-6 fw-bold required">Address Line 1</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="ADDRESS_LINE_1" id="ADDRESS_LINE_1" placeholder="Address Line 1">
                            <span class="text-danger err-lbl" id="lbl-ADDRESS_LINE_1"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="ADDRESS_LINE_2" class="fs-6 fw-bold">Address Line 2 <small>(optional)</small></label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="ADDRESS_LINE_2" id="ADDRESS_LINE_2" placeholder="Address Line 2">
                            <span class="text-danger err-lbl" id="lbl-ADDRESS_LINE_2"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="BILLING_ADDRESS" class="fs-6 fw-bold required">Billing Address</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="BILLING_ADDRESS" id="BILLING_ADDRESS" placeholder="Billing Address">
                            <span class="text-danger err-lbl" id="lbl-BILLING_ADDRESS"></span>
                            <div class="mt-2 d-flex align-items-center justify-content-start gap-2">
                                <input type="checkbox" onchange="setShippingAddress(this)" /> <small class="text-gray-800">Shipping address is same as billing address</small>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="SHIPPING_ADDRESS" class="fs-6 fw-bold required">Shipping Address</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="SHIPPING_ADDRESS" id="SHIPPING_ADDRESS" placeholder="Shipping Address">
                            <span class="text-danger err-lbl" id="lbl-SHIPPING_ADDRESS"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="CITY" class="fs-6 fw-bold required">City Name</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="CITY" id="CITY" placeholder="Enter city name">
                            <span class="text-danger err-lbl" id="lbl-CITY"></span>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-end">
                            <label for="STATE" class="fs-6 fw-bold required">State / Province</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="STATE" id="STATE" placeholder="provide state name">
                            <span class="text-danger err-lbl" id="lbl-STATE"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="COUNTRY" class="fs-6 fw-bold required">Country Name</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="COUNTRY" id="COUNTRY" placeholder="Customer country name">
                            <span class="text-danger err-lbl" id="lbl-COUNTRY"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-end">
                            <label for="ZIP_CODE" class="fs-6 fw-bold required">Zip Code</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="ZIP_CODE" id="ZIP_CODE" placeholder="Area postal code">
                            <span class="text-danger err-lbl" id="lbl-ZIP_CODE"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <h2>Financial Details</h2>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="PAYMENT_TERM" class="fs-6 fw-bold required">Payment Term</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="PAYMENT_TERM" id="PAYMENT_TERM" placeholder="Enter payment term">
                            <span class="text-danger err-lbl" id="lbl-PAYMENT_TERM"></span>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-end">
                            <label for="CURRENCY" class="fs-6 fw-bold required">Currency </label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="CURRENCY" id="CURRENCY" placeholder="Enter 3 digit currency code">
                            <span class="text-danger err-lbl" id="lbl-CURRENCY"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="CREDIT_LIMIT" class="fs-6 fw-bold required">Credit Limit</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="CREDIT_LIMIT" id="CREDIT_LIMIT" placeholder="Add Credit Limit">
                            <span class="text-danger err-lbl" id="lbl-CREDIT_LIMIT"></span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-end">
                            <label for="ORDER_LIMIT" class="fs-6 fw-bold required">Order Limit</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="ORDER_LIMIT" id="ORDER_LIMIT" placeholder="Define Max order limit">
                            <span class="text-danger err-lbl" id="lbl-ORDER_LIMIT"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 d-flex align-items-center justify-content-start">
                            <label for="TAXES" class="fs-6 fw-bold">Tax Percentage</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="TAXES" id="TAXES" placeholder="Add Tax percentage">
                            <span class="text-danger err-lbl" id="lbl-TAXES"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-2" id="login-details-container">
                <div class="card-body">
                    <h2>Login Details</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-4 d-flex align-items-center justify-content-start">
                                    <label for="USERNAME_PLACEHOLDER" class="fs-6 fw-bold required">Login Username</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control bg-light" name="USERNAME_PLACEHOLDER" id="USERNAME_PLACEHOLDER" placeholder="Account Username - same as email" readonly>
                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 d-flex align-items-center justify-content-start">
                                    <label for="PASSWORD" class="fs-6 fw-bold required">Account Password</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="password" class="form-control" name="PASSWORD" id="PASSWORD" placeholder="Set client account password">
                                    <span class="text-danger err-lbl" id="lbl-PASSWORD"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 border border-dashed border-warning py-2">
                            <div class="">
                                <p class="mb-0 fw-bold text-warning">Password Requirements</p>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Must be at least <strong>8-16 characters</strong> long.</li>
                                    <li class="list-group-item">Include at least one <strong>uppercase letter (A-Z)</strong> and at least one <strong>lowercase letter (a-z)</strong>.</li>
                                    <li class="list-group-item">Include at least one <strong>number (0-9)</strong>.</li>
                                    <li class="list-group-item">Include at least one <strong>special character</strong> (e.g., <code>!@#$%^&*</code>).</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-10">
                <a href="javascript:void(0)" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">
                    Cancel
                </a>
                <button type="submit" id="submit-btn" class="btn btn-primary">
                    <span class="indicator-label">
                        Save Changes
                    </span>
                </button>
            </div>
            <!--end::PAGE CONTENT GOES FROM HERE-->
        </form>
    </div>
</div>
<!--end::PAGE CONTAINER-->
<?php $this->load->view('loaders/full-page-loader'); ?>