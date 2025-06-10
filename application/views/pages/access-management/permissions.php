<div class="flex-lg-row-fluid ms-lg-10">
    <!--begin::Sign-in Method-->
    <div class="card  mb-5 mb-xl-10">
        <!--begin::Content-->
        <div id="kt_account_settings_signin_method" class="collapse show">
            <!--begin::Card body-->
            <div class="card-body p-9">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="fw-bold m-0"><?= $page_heading ?? 'Permissions' ?></h3>
                    <div class="d-flex align-items-center gap-4">
                        <a href="javascript:void(0)" onclick="openPermissionModal()" class="text-decoration-underline">
                            <i class="fa-solid fa-plus text-primary"></i> Assign Permission
                        </a>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-row-bordered gy-3" id="permission-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th>#</th>
                                <th>Role ID</th>
                                <th>Role Name</th>
                                <th>Desctiption</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="permission-list-tbody">

                        </tbody>
                    </table>
                </div>
                <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Sign-in Method-->
</div>


<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('pages/access-management/manage-permission');
?>