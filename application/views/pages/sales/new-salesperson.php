<!-- Modal Styles (Optional) -->
<style>
    /* Modal header styling */
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        border-radius: 8px 8px 0 0;
        padding: 12px;
        background-color: #007bff;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1rem;
    }

    /* Button styling */
    .btn-outline-secondary {
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .btn-primary.sales-persons {
        font-weight: 500;
        padding: 6px 12px;
        background-color: #007bff;
        border: none;
    }

    .btn-close {
        opacity: 0.6;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Input & Textarea styling */
    .form-control-sm {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }

    .form-select-sm {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }

    /* Modal footer styling */
    .modal-footer {
        padding: 10px;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }

    /* Small label styling */
    .form-label.small {
        font-size: 0.85rem;
        font-weight: 400;
        color: rgb(66, 66, 66);
    }

    .text-secondary {
        color: #777777 !important;
    }

    /* Red asterisk styling for required fields */
    .required-field::after {
        content: ' *';
        color: red;
    }
    .err-lbl {
        font-size: 10px !important;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="salesPersonModal" tabindex="-1" aria-labelledby="salesPersonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form id="salesPersonModalForm" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <div class="modal-content rounded-3 shadow-sm">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="salesPersonModalLabel">Add New Sales Person</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <!-- Hidden ID -->
                        <input type="hidden" name="ID" id="ID" value="">

                        <!-- FIRST_NAME -->
                        <div class="col-12 col-md-6">
                            <label for="FIRST_NAME" class="form-label fw-bold text-secondary small required-field">First Name</label>
                            <input type="text" class="form-control form-control-sm" id="FIRST_NAME" name="FIRST_NAME" placeholder="Enter first name">
                            <p class="text-danger err-lbl mb-0" id="lbl-FIRST_NAME"></p>
                        </div>

                        <!-- LAST_NAME -->
                        <div class="col-12 col-md-6">
                            <label for="LAST_NAME" class="form-label fw-bold text-secondary small required-field">Last Name</label>
                            <input type="text" class="form-control form-control-sm" id="LAST_NAME" name="LAST_NAME" placeholder="Enter last name">
                            <p class="text-danger err-lbl mb-0" id="lbl-LAST_NAME"></p>
                        </div>

                        <!-- EMAIL -->
                        <div class="col-12 col-md-6">
                            <label for="EMAIL" class="form-label fw-bold text-secondary small required-field">Email</label>
                            <input type="email" class="form-control form-control-sm" id="EMAIL" name="EMAIL" placeholder="Enter email address">
                            <p class="text-danger err-lbl mb-0" id="lbl-EMAIL"></p>
                        </div>

                        <!-- PHONE -->
                        <div class="col-12 col-md-6">
                            <label for="PHONE" class="form-label fw-bold text-secondary small required-field">Phone</label>
                            <input type="text" class="form-control form-control-sm" id="PHONE" name="PHONE" placeholder="Enter phone number">
                            <p class="text-danger err-lbl mb-0" id="lbl-PHONE"></p>
                        </div>

                        <!-- EMPLOYEE_ID -->
                        <div class="col-12 col-md-6">
                            <label for="EMPLOYEE_ID" class="form-label fw-bold text-secondary small">Employee ID</label>
                            <input type="text" class="form-control form-control-sm" id="EMPLOYEE_ID" name="EMPLOYEE_ID" placeholder="Employee ID">
                            <p class="text-danger err-lbl mb-0" id="lbl-EMPLOYEE_ID"></p>
                        </div>

                        <!-- DEPARTMENT -->
                        <div class="col-12 col-md-6">
                            <label for="DEPARTMENT" class="form-label fw-bold text-secondary small required-field">Department</label>
                            <input type="text" class="form-control form-control-sm" id="DEPARTMENT" name="DEPARTMENT" placeholder="Department">
                            <p class="text-danger err-lbl mb-0" id="lbl-DEPARTMENT"></p>
                        </div>

                        <!-- DESIGNATION -->
                        <div class="col-12 col-md-6">
                            <label for="DESIGNATION" class="form-label fw-bold text-secondary small required-field">Designation</label>
                            <input type="text" class="form-control form-control-sm" id="DESIGNATION" name="DESIGNATION" placeholder="Designation">
                            <p class="text-danger err-lbl mb-0" id="lbl-DESIGNATION"></p>
                        </div>

                        <!-- DATE_OF_JOINING -->
                        <div class="col-12 col-md-6">
                            <label for="DATE_OF_JOINING" class="form-label fw-bold text-secondary small required-field">Date of Joining</label>
                            <input type="date" class="form-control form-control-sm" id="DATE_OF_JOINING" name="DATE_OF_JOINING">
                            <p class="text-danger err-lbl mb-0" id="lbl-DATE_OF_JOINING"></p>
                        </div>

                        <!-- SUPERVISOR_ID -->
                        <div class="col-12 col-md-6">
                            <label for="SUPERVISOR_ID" class="form-label fw-bold text-secondary small">Supervisor ID</label>
                            <input type="number" class="form-control form-control-sm" id="SUPERVISOR_ID" name="SUPERVISOR_ID" placeholder="Supervisor ID">
                            <p class="text-danger err-lbl mb-0" id="lbl-SUPERVISOR_ID"></p>
                        </div>

                        <!-- ASSIGNED_TERRITORY -->
                        <div class="col-12 col-md-6">
                            <label for="ASSIGNED_TERRITORY" class="form-label fw-bold text-secondary small">Assigned Territory</label>
                            <input type="text" class="form-control form-control-sm" id="ASSIGNED_TERRITORY" name="ASSIGNED_TERRITORY" placeholder="Territory or region">
                            <p class="text-danger err-lbl mb-0" id="lbl-ASSIGNED_TERRITORY"></p>
                        </div>

                        <!-- CRM_ROLE -->
                        <div class="col-12 col-md-6">
                            <label for="CRM_ROLE" class="form-label fw-bold text-secondary small required-field">CRM Role</label>
                            <select class="form-select form-select-sm" id="CRM_ROLE" name="CRM_ROLE">
                                <option value="">Select role</option>
                                <option value="Sales Trainee">Sales Trainee</option>
                                <option value="Sales Associate">Sales Associate</option>
                                <option value="Sales Representative">Sales Representative</option>
                                <option value="Sales Executive">Sales Executive</option>
                                <option value="Senior Sales Executive">Senior Sales Executive</option>
                                <option value="Inside Sales Executive">Inside Sales Executive</option>
                                <option value="Field Sales Executive">Field Sales Executive</option>
                                <option value="Territory Sales Executive">Territory Sales Executive</option>
                                <option value="Key Account Executive">Key Account Executive</option>
                                <option value="Business Development Executive">Business Development Executive</option>
                                <option value="Client Acquisition Executive">Client Acquisition Executive</option>
                                <option value="Channel Sales Executive">Channel Sales Executive</option>
                                <option value="Retail Sales Executive">Retail Sales Executive</option>
                                <option value="Corporate Sales Executive">Corporate Sales Executive</option>
                                <option value="Outbound Sales Executive">Outbound Sales Executive</option>
                                <option value="Inbound Sales Executive">Inbound Sales Executive</option>
                                <option value="Tele Sales Executive">Tele Sales Executive</option>
                                <option value="B2B Sales Executive">B2B Sales Executive</option>
                                <option value="B2C Sales Executive">B2C Sales Executive</option>
                            </select>
                            <p class="text-danger err-lbl mb-0" id="lbl-CRM_ROLE"></p>
                        </div>

                        <!-- LOGIN_ACCESS -->
                        <div class="col-12 col-md-6">
                            <label for="LOGIN_ACCESS" class="form-label fw-bold text-secondary small required-field">Login Access</label>
                            <select class="form-select form-select-sm" id="LOGIN_ACCESS" name="LOGIN_ACCESS">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <p class="text-danger err-lbl mb-0" id="lbl-LOGIN_ACCESS"></p>
                        </div>

                        <!-- STATUS -->
                        <div class="col-12 col-md-6">
                            <label for="STATUS" class="form-label fw-bold text-secondary small required-field">Status</label>
                            <select class="form-select form-select-sm" id="STATUS" name="STATUS">
                                <option value="ACTIVE">Active</option>
                                <option value="INACTIVE">Inactive</option>
                                <option value="ON_LEAVE">On Leave</option>
                            </select>
                            <p class="text-danger err-lbl mb-0" id="lbl-STATUS"></p>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm sales-persons" id="submit-btn">Save Sales Person</button>
                </div>
            </div>
        </form>

    </div>
</div>