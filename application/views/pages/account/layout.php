<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start  container-xxl ">

    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">

        <!--begin::Layout - Security-->
        <div class="d-flex flex-column flex-xl-row">
            <!--begin::Sidebar-->
            <?php $this->load->view('pages/account/partials/sidebar'); ?>
            <!--end::Sidebar-->

            <!--begin::Content-->
            <?php
            if (isset($sub_view_path) && file_exists(APPPATH . "views/" . $sub_view_path . ".php"))
                $this->load->view($sub_view_path);
            ?>
            <!--end::Content-->
        </div>
        <!--end::Layout - Security-->
    </div>
    <!--end::Post-->
</div>
<?php $this->load->view('loaders/full-page-loader'); ?>