<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" id="task-details-card">
                        <div id="task-info"></div>
                        <hr>
                        <div id="task-children"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body" id="task-comments-card">
                        <div id="comments-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('loaders/full-page-loader'); ?>