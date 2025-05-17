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
        font-size: 0.75rem;
        font-weight: 400;
        color: rgb(66, 66, 66);
    }

    .text-secondary {
        color: #777777 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .err-lbl {
        font-size: 0.75rem;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content rounded-3 shadow-sm">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="taskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <div class="row g-2">
                        <!-- Hidden ID input -->
                        <input type="hidden" id="task_id" name="task_id" value="">

                        <!-- Task Name -->
                        <div class="col-12">
                            <label for="task_name" class="form-label fw-bold text-secondary small">Task Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="task_name" name="task_name" placeholder="Enter task name" required>
                            <p class="text-danger err-lbl mb-0" id="lbl-task_name"></p>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-bold text-secondary small">Description</label>
                            <textarea class="form-control form-control-sm" id="description" name="description" rows="3" placeholder="Describe the task..."></textarea>
                            <p class="text-danger err-lbl mb-0" id="lbl-description"></p>
                        </div>

                        <!-- Status -->
                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label fw-bold text-secondary small">Status <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="status" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="On Hold">On Hold</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <p class="text-danger err-lbl mb-0" id="lbl-status"></p>
                        </div>

                        <!-- Start Date -->
                        <div class="col-12 col-md-6">
                            <label for="start_date" class="form-label fw-bold text-secondary small">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="start_date" name="start_date" required>
                            <p class="text-danger err-lbl mb-0" id="lbl-start_date"></p>
                        </div>

                        <!-- Target Date -->
                        <div class="col-12 col-md-6">
                            <label for="target_date" class="form-label fw-bold text-secondary small">Target Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="target_date" name="target_date" required>
                            <p class="text-danger err-lbl mb-0" id="lbl-target_date"></p>
                        </div>

                        <!-- End Date -->
                        <div class="col-12 col-md-6">
                            <label for="end_date" class="form-label fw-bold text-secondary small">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="end_date" name="end_date" required>
                            <p class="text-danger err-lbl mb-0" id="lbl-end_date"></p>
                        </div>

                        <!-- Duration -->
                        <div class="col-12 col-md-6">
                            <label for="duration" class="form-label fw-bold text-secondary small">Duration (in days) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="duration" name="duration" min="1" placeholder="e.g., 10" required>
                            <p class="text-danger err-lbl mb-0" id="lbl-duration"></p>
                        </div>

                        <!-- Comments -->
                        <div class="col-12">
                            <label for="comments" class="form-label fw-bold text-secondary small">Comments</label>
                            <textarea class="form-control form-control-sm" id="comments" name="comments" rows="2" placeholder="Any additional details"></textarea>
                            <p class="text-danger err-lbl mb-0" id="lbl-comments"></p>
                        </div>

                        <!-- Parent Task (Optional) -->
                        <div class="col-12">
                            <label for="parent_id" class="form-label fw-bold text-secondary small">Parent Task</label>
                            <input type="number" class="form-control form-control-sm" id="parent_id" name="parent_id" placeholder="Enter parent task ID (if any)">
                            <p class="text-danger err-lbl mb-0" id="lbl-parent_id"></p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-sm text-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="saveTask()">Save Task</button>
            </div>
        </div>
    </div>
</div>