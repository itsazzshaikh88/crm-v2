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
                <!-- Search / Filter / Export Row -->
                <div class="d-flex flex-wrap gap-2 my-4 align-items-end">
                    <div>
                        <label for="FILTER_USER_TYPE" class="mb-1">
                            <small class="text-muted">User Type</small>
                        </label>
                        <select name="FILTER_USER_TYPE" id="FILTER_USER_TYPE" class="form-control form-control-sm">
                            <option value="">Choose</option>
                            <option value="admin">Admin</option>
                            <option value="client">Client</option>
                            <option value="employee">Employee</option>
                            <option value="vendor">Vendor</option>
                            <option value="subadmin">Subadmin</option>
                            <option value="co-admin">Co-Admin</option>
                            <option value="salesperson">Salesperson</option>
                            <option value="guest">Guest</option>
                            <option value="super-admin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label for="FILTER_STATUS" class="mb-1">
                            <small class="text-muted">Status</small>
                        </label>
                        <select name="FILTER_STATUS" id="FILTER_STATUS" class="form-control form-control-sm">
                            <option value="">Choose</option>
                            <option selected value="active">Active</option>
                            <option value="inactive">In-Active</option>
                            <option value="suspended">Suspended</option>
                            <option value="locked">Locked</option>
                        </select>
                    </div>
                    <div>
                        <label for="searchInputElement" class="mb-1"><small class="text-muted"><i class="fa-solid fa-circle-info fs-9"></i> Search by Column Data</small></label>
                        <input type="text" oninput="debouncedSearchUsersListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search...">
                    </div>

                    <div>
                        <button class="btn btn-sm btn-light border border-secondary mt-2" onclick="filterUserReport()">Filter</button>
                    </div>

                    <div class="ms-auto d-flex gap-2 mt-2">
                        <button id="exportCsvBtn" onclick="exportUserData('csv')" class="btn btn-primary btn-sm">
                            <i class="fas fa-file-csv"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-3" id="user-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th class="bg-light">#</th>
                                <th class="bg-light">User</th>
                                <th class="bg-light">User Type</th>
                                <th class="bg-light">Email</th>
                                <th class="bg-light">Phone Number</th>
                                <th class="bg-light">Status</th>
                                <th class="bg-light">2FA Enabled</th>
                                <th class="bg-light text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="user-list-tbody">

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
$this->load->view('pages/user-account/modals/new-user');
$this->load->view('loaders/full-page-loader');
$this->load->view('drawers/admin/reset-user-password');
?>