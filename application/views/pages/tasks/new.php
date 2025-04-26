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
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form id="taskForm" method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <div class="modal-content rounded-3 shadow-sm">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="taskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <!-- Hidden ID input -->
                        <input type="hidden" name="ID" id="ID" value="">

                        <!-- TASK_NAME -->
                        <div class="col-12">
                            <label for="TASK_NAME" class="form-label fw-bold text-secondary small required-field">Task Name</label>
                            <input type="text" class="form-control form-control-sm" id="TASK_NAME" name="TASK_NAME" placeholder="Enter task name">
                            <p class="text-danger err-lbl mb-0" id="lbl-TASK_NAME"></p>
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="col-12">
                            <label for="DESCRIPTION" class="form-label fw-bold text-secondary small">Description</label>
                            <textarea class="form-control form-control-sm" id="DESCRIPTION" name="DESCRIPTION" rows="3" placeholder="Describe the task..."></textarea>
                            <p class="text-danger err-lbl mb-0" id="lbl-DESCRIPTION"></p>
                        </div>

                        <!-- STATUS -->
                        <div class="col-12 col-md-6">
                            <label for="STATUS" class="form-label fw-bold text-secondary small required-field">Status</label>
                            <select class="form-select form-select-sm" id="STATUS" name="STATUS">
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="On Hold">On Hold</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <p class="text-danger err-lbl mb-0" id="lbl-STATUS"></p>
                        </div>

                        <!-- START_DATE -->
                        <div class="col-12 col-md-6">
                            <label for="START_DATE" class="form-label fw-bold text-secondary small required-field">Start Date</label>
                            <input type="date" class="form-control form-control-sm" id="START_DATE" name="START_DATE">
                            <p class="text-danger err-lbl mb-0" id="lbl-START_DATE"></p>
                        </div>

                        <!-- TARGET_DATE -->
                        <div class="col-12 col-md-6">
                            <label for="TARGET_DATE" class="form-label fw-bold text-secondary small">Target Date</label>
                            <input type="date" class="form-control form-control-sm" id="TARGET_DATE" name="TARGET_DATE">
                            <p class="text-danger err-lbl mb-0" id="lbl-TARGET_DATE"></p>
                        </div>

                        <!-- END_DATE -->
                        <div class="col-12 col-md-6">
                            <label for="END_DATE" class="form-label fw-bold text-secondary small">End Date</label>
                            <input type="date" class="form-control form-control-sm" id="END_DATE" name="END_DATE">
                            <p class="text-danger err-lbl mb-0" id="lbl-END_DATE"></p>
                        </div>

                        <!-- DURATION -->
                        <div class="col-12 col-md-6">
                            <label for="DURATION" class="form-label fw-bold text-secondary small">Duration (in days)</label>
                            <input type="number" class="form-control form-control-sm" id="DURATION" name="DURATION" min="0" step="any" placeholder="e.g., 10">
                            <p class="text-danger err-lbl mb-0" id="lbl-DURATION"></p>
                        </div>

                        <!-- COMMENTS -->
                        <div class="col-12">
                            <label for="COMMENTS" class="form-label fw-bold text-secondary small">Comments</label>
                            <textarea class="form-control form-control-sm" id="COMMENTS" name="COMMENTS" rows="2" placeholder="Any additional details"></textarea>
                            <p class="text-danger err-lbl mb-0" id="lbl-COMMENTS"></p>
                        </div>

                        <!-- PARENT_ID (Optional) -->
                        <div class="col-12">
                            <label for="PARENT_ID" class="form-label fw-bold text-secondary small">Parent Task</label>
                            <input readonly type="number" class="form-control form-control-sm" id="PARENT_ID" name="PARENT_ID" placeholder="Enter parent task ID (if any)">
                            <p class="text-danger err-lbl mb-0" id="lbl-PARENT_ID"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" id="submit-btn">Save Task</button>
                </div>
            </div>
        </form>
    </div>
</div>