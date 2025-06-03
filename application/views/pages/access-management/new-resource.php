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

    .btn-primary {
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 20px;
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
</style>
<!-- Modal -->
<div class="modal fade" id="create-new-resource-modal" tabindex="-1" aria-labelledby="createNewResourceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="new-resource-form" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <div class="modal-content rounded-3 shadow-sm">
                <div class="modal-header bg-light py-3">
                    <h5 class="modal-title fw-normal" id="createNewResourceLabel">Create New Resource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Basic Info Section -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-8">
                            <label for="RESOURCE_NAME" class="form-label small text-secondary required-field">Resource Name</label>
                            <input type="text" id="RESOURCE_NAME" name="RESOURCE_NAME" class="form-control form-control-sm">
                            <p class="text-danger err-lbl mb-0" id="lbl-RESOURCE_NAME"></p>
                            <input type="hidden" id="ID" name="ID">
                        </div>
                        <div class="col-md-4">
                            <label for="RESOURCE_TYPE" class="form-label small text-secondary required-field">Resource Type</label>
                            <select id="RESOURCE_TYPE" name="RESOURCE_TYPE" class="form-select form-select-sm">
                                <option value="page">Page</option>
                                <option value="widget">Widget</option>
                                <option value="api">API</option>
                                <option value="section">Section</option>
                                <option value="button">Button</option>
                            </select>
                            <p class="text-danger err-lbl mb-0" id="lbl-RESOURCE_TYPE"></p>
                        </div>
                        <div class="col-12">
                            <label for="RESOURCE_DESCRIPTION" class="form-label small text-secondary">Resource Description</label>
                            <textarea id="RESOURCE_DESCRIPTION" name="RESOURCE_DESCRIPTION" class="form-control form-control-sm" rows="2"></textarea>
                            <p class="text-danger err-lbl mb-0" id="lbl-RESOURCE_DESCRIPTION"></p>
                        </div>
                    </div>

                    <!-- Type & Module Section -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label for="MODULE" class="form-label small text-secondary">Module</label>
                            <input type="text" id="MODULE" name="MODULE" class="form-control form-control-sm">
                            <p class="text-danger err-lbl mb-0" id="lbl-MODULE"></p>
                        </div>

                        <div class="col-md-8">
                            <label for="RESOURCE_PATH" class="form-label small text-secondary">Resource Path</label>
                            <input type="text" id="RESOURCE_PATH" name="RESOURCE_PATH" class="form-control form-control-sm">
                            <p class="text-danger err-lbl mb-0" id="lbl-RESOURCE_PATH"></p>
                        </div>
                    </div>

                    <!-- Behavior Section -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label for="IS_CLICKABLE" class="form-label small text-secondary">Is Clickable</label>
                            <select id="IS_CLICKABLE" name="IS_CLICKABLE" class="form-select form-select-sm">
                                <option value="">Choose</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <p class="text-danger err-lbl mb-0" id="lbl-IS_CLICKABLE"></p>
                        </div>

                        <div class="col-md-3">
                            <label for="REDIRECT_TYPE" class="form-label small text-secondary">Redirect Type</label>
                            <select id="REDIRECT_TYPE" name="REDIRECT_TYPE" class="form-select form-select-sm">
                                <option value="">Choose</option>
                                <option value="_self">Self</option>
                                <option value="_blank">New Window</option>
                                <option value="_modal">Modal</option>
                            </select>
                            <p class="text-danger err-lbl mb-0" id="lbl-REDIRECT_TYPE"></p>
                        </div>

                        <div class="col-md-3">
                            <label for="REDIRECT_TARGET" class="form-label small text-secondary">Redirect Target</label>
                            <input type="text" id="REDIRECT_TARGET" name="REDIRECT_TARGET" class="form-control form-control-sm">
                            <p class="text-danger err-lbl mb-0" id="lbl-REDIRECT_TARGET"></p>
                        </div>
                        <div class="col-md-3">
                            <label for="ON_CLICK_ACTION" class="form-label small text-secondary">On Click Action</label>
                            <textarea id="ON_CLICK_ACTION" name="ON_CLICK_ACTION" class="form-control form-control-sm" rows="1"></textarea>
                            <p class="text-danger err-lbl mb-0" id="lbl-ON_CLICK_ACTION"></p>
                        </div>
                    </div>

                    <!-- UI & Logic -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label for="COMPONENT_NAME" class="form-label small text-secondary">Component Name</label>
                            <input type="text" id="COMPONENT_NAME" name="COMPONENT_NAME" class="form-control form-control-sm">
                            <p class="text-danger err-lbl mb-0" id="lbl-COMPONENT_NAME"></p>
                        </div>

                        <div class="col-md-3">
                            <label for="DISPLAY_ORDER" class="form-label small text-secondary">Display Order</label>
                            <input type="number" id="DISPLAY_ORDER" name="DISPLAY_ORDER" class="form-control form-control-sm">
                            <p class="text-danger err-lbl mb-0" id="lbl-DISPLAY_ORDER"></p>
                        </div>

                        <div class="col-md-3">
                            <label for="ICON_LABEL" class="form-label small text-secondary">Icon Label</label>
                            <input type="text" id="ICON_LABEL" name="ICON_LABEL" class="form-control form-control-sm">
                            <p class="text-danger err-lbl mb-0" id="lbl-ICON_LABEL"></p>
                        </div>

                        <div class="col-md-3">
                            <label for="TOOLTIP_TEXT" class="form-label small text-secondary">Tooltip Text</label>
                            <input type="text" id="TOOLTIP_TEXT" name="TOOLTIP_TEXT" class="form-control form-control-sm">
                            <p class="text-danger err-lbl mb-0" id="lbl-TOOLTIP_TEXT"></p>
                        </div>

                        <div class="col-md-3">
                            <label for="CSS_CLASS" class="form-label small text-secondary">CSS Class</label>
                            <input type="text" id="CSS_CLASS" name="CSS_CLASS" class="form-control form-control-sm">
                            <p class="text-danger err-lbl mb-0" id="lbl-CSS_CLASS"></p>
                        </div>

                        <div class="col-md-3">
                            <label for="VISIBILITY_CONDITION" class="form-label small text-secondary">Visibility Condition</label>
                            <textarea id="VISIBILITY_CONDITION" name="VISIBILITY_CONDITION" class="form-control form-control-sm" rows="1"></textarea>
                            <p class="text-danger err-lbl mb-0" id="lbl-VISIBILITY_CONDITION"></p>
                        </div>

                        <div class="col-md-3">
                            <label for="PARENT_RESOURCE_ID" class="form-label small text-secondary">Parent Resource ID</label>
                            <input type="number" id="PARENT_RESOURCE_ID" name="PARENT_RESOURCE_ID" class="form-control form-control-sm">
                            <p class="text-danger err-lbl mb-0" id="lbl-PARENT_RESOURCE_ID"></p>
                        </div>
                        <div class="col-md-3">
                            <label for="STATUS" class="form-label small text-secondary">Status</label>
                            <select id="STATUS" name="STATUS" class="form-select form-select-sm">
                                <option selected value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Flags -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="form-label small text-secondary">Is Menu Item</label>
                            <select id="IS_MENU_ITEM" name="IS_MENU_ITEM" class="form-select form-select-sm">
                                <option value="">Choose</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-secondary">Is Widget</label>
                            <select id="IS_WIDGET" name="IS_WIDGET" class="form-select form-select-sm">
                                <option value="">Choose</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-secondary">Is Actionable</label>
                            <select id="IS_ACTIONABLE" name="IS_ACTIONABLE" class="form-select form-select-sm">
                                <option value="">Choose</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-12">
                            <label for="TAGS" class="form-label small text-secondary">Tags</label>
                            <input type="text" id="TAGS" name="TAGS" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" type="submit" id="submit-btn">Save Resource</button>
                </div>
            </div>
        </form>
    </div>
</div>