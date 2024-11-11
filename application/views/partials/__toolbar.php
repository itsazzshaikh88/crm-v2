<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5"><?= $page_heading ?? 'Page Heading' ?></h3>
        <!--begin::Title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center flex-wrap py-2">
            <?php
            if (isset($toolbar) && is_array($toolbar))
                $this->load->view('partials/toolbar/' . $toolbar['name'], ['options' => $toolbar]);
            else
                $this->load->view('partials/toolbar/default');
            ?>
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->