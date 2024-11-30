<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body pt-8">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable table-row-bordered " id="lead-list">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th class="text-center">#</th>
                                <th>Name</th>
                                <th>Company Name</th>
                                <th>Role</th>
                                <th>Contact</th>
                                <th>Lead Created</th>
                                <th>Lead Source</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="lead-list-tbody">
                            <tr>
                                <td class="text-center">1</td>
                                <td>
                                    <p class="mb-0 text-primary">Shaikh Ab Azim</p>
                                    <small class="fs-xs text-muted">LD-2024042002</small>
                                </td>
                                <td>
                                    <p class="mb-0 fw-bold">KismatJi Pvt Ltd</p>
                                </td>
                                <td>software Developer</td>
                                <td>
                                    <p class="mb-0">kissu@kis.com</p>
                                    <p class="mb-0"><small>8805629207</small></p>
                                </td>
                                <td>23 Nov,2024</td>
                                <td>
                                    <p class="mb-0 badge bg-light text-info"><small>Facebook</small></p>
                                </td>
                                <td>
                                    <span class="badge bg-success">New</span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-4">
                                        <a href="">
                                            <small>
                                                <i class="fs-5 fa-solid fa-file-lines text-info"></i>
                                            </small>
                                        </a>
                                        <a href="">
                                            <small>
                                                <i class="fs-5 fa-regular fa-pen-to-square text-gray-700"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" onclick="deleteLead()">
                                            <small>
                                                <i class="fs-5 fa-solid fa-trash-can text-danger"></i>
                                            </small>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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

$this->load->view('pages/leads/modals/new-lead');
$this->load->view('modals/activities/activity-modal');
?>