<style>
    .ql-toolbar, .ql-editor, .ql-container {
        border-color: #dbeafe !important;
    }
</style>
<div class="modal fade" tabindex="-1" id="custom-common-activity-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body py-4">
                <div class="row mb-2">
                    <div class="col-md-12 text-end">
                        <!--begin::Close-->
                        <div class="btn btn-sm btn-icon btn-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" onclick="resetActivityModal()">
                            <i class="fa-solid fa-xmark fs-4"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                </div>
                <?php
                $modal_list = ['call', 'event', 'meeting', 'note', 'task'];
                foreach ($modal_list as $mdl_list)
                    $this->load->view('modals/activities/partials/activity-' . $mdl_list);
                ?>
            </div>
        </div>
    </div>
</div>