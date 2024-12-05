<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="row" id="contact-container">
                    <!-- <div class="col-md-4 m-2 border border-dashed border-secondary">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <img src="assets/images/avatar-user-placeholder.png" class="img-fluid contact-image" alt="">
                            </div>
                            <div class="col-8 py-4 bg-slate-50 position-relative">
                                <h3 class="line-clamp-1 fw-bold text-slate-800">Shaikh Ab Azim</h3>
                                <p class="mb-0 line-clamp-1 text-primary"><i class="fa-solid fa-envelope-open-text text-primary me-2"></i> shaikh.azim@gmail.com</p>
                                <p class="mb-0 line-clamp-1"><i class="fa-solid fa-phone-volume text-gray-700 me-2"></i> 8805629207</p>
                                <p class="mt-4 mb-0">
                                    <span class="badge bg-primary">Active</span>
                                </p>
                            </div>
                        </div>
                    </div> -->
                    
                </div>
                <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->

<!-- Include modals to add new lead  -->
<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/contact/new-contact');
?>