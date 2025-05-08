<div class="flex-lg-row-fluid ms-lg-10">
    <!--begin::Sign-in Method-->
    <div class="card  mb-5 mb-xl-10">

        <!--begin::Content-->
        <div id="kt_account_settings_signin_method" class="collapse show">
            <!--begin::Card body-->
            <div class="card-body p-9">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="fw-bold m-0"><?= $page_heading ?? 'Users' ?></h3>
                    <div class="d-flex align-items-center gap-4 <?= isset($navlink['sub-link']) && $navlink['sub-link'] === 'users' ? '' : 'd-none' ?> ">
                        <a href="javascript:void(0)" onclick="openUserModal()" class="text-primary border border-primary px-4 py-2 rounded">
                            <i class="fa-solid fa-user-plus text-primary"></i> Create New User
                        </a>
                        <a href="users" class="text-gray-700 text-decoration-underline">
                            <i class="fa-solid fa-users text-gray-700"></i> Users List
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-3" id="role-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th>#</th>
                                <th>User</th>
                                <th>User Type</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                                <th>2FA Enabled</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="role-list-tbody">

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
?>