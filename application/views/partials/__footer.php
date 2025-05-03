<?php
$start_year = "2024";
$current_year = date('Y');
$cpyright_years = ($start_year == $current_year) ? $current_year : "$start_year-$current_year";

?>
<!--begin::Footer-->
<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
    <!--begin::Container-->
    <div class="container-xxl d-flex flex-column flex-md-row align-items-center justify-content-between">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-bold me-1">©<?= $cpyright_years ?></span>
            <a href="https://zamilplastics.com" target="_blank" class="text-gray-800 text-hover-primary">Zamil Plastic Industries Co.</a>
        </div>
        <!--end::Copyright-->
        <!--begin::Menu-->
        <ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
            <li class="menu-item">
                <a href="https://zamilplastic.com/en/about-us/" target="_blank" class="menu-link px-2">About</a>
            </li>
        </ul>
        <!--end::Menu-->
    </div>
    <!--end::Container-->
</div>
<!--end::Footer-->
</div>
<!--end::Wrapper-->
</div>
<!--end::Page-->
</div>
<!--end::Root-->
<!--begin::Drawers-->
<!--begin::Activities drawer-->
<?php $this->load->view('drawers/activity-drawer') ?>
<!--end::Activities drawer-->

<!--end::Drawers-->
<!--begin::Modals-->
<!--begin::Modal - Invite Friends-->
<?php $this->load->view('modals/invite-friends') ?>
<!--end::Modal - Invite Friend-->
<!--begin::Modal - Create App-->
<?php $this->load->view('modals/create-app') ?>
<!--end::Modal - Create App-->
<!--begin::Modal - New Target-->
<?php $this->load->view('modals/new-target') ?>
<!--end::Modal - New Target-->
<!--end::Modals-->
<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
    <span class="svg-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
            <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
        </svg>
    </span>
    <!--end::Svg Icon-->
</div>
<!--end::Scrolltop-->
<!--end::Main-->
<script>
    var baseUrl = "<?= base_url() ?>";
    var APIUrl = "<?= base_url() ?>api";
    var hostUrl = "assets/";
    const PRODUCT_IMAGES_URL = `${baseUrl}uploads/products/`;
    const REQUEST_DOCS_URL = `${baseUrl}uploads/requests/`;

    const loginUserType = "<?= $loggedInUser['usertype'] ?? 'Guest' ?>";
    const loginUserID = "<?= $loggedInUser['userid'] ?? '0' ?>";
    const loggedInUserFullDetails = <?= json_encode($loggedInUserFullDetails, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

    if (loginUserType) {
        localStorage.setItem("usertype", btoa(loginUserType))
    }
    let isAdmin = loginUserType == 'admin' ? true : false
</script>
<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(used by all pages)-->
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Page Vendors Javascript(used by this page)-->
<script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<script src="assets/js/custom/apps/ecommerce/catalog/save-product.js"></script>
<script src="assets/js/widgets.bundle.js"></script>
<script src="assets/js/custom/widgets.js"></script>
<script src="assets/js/custom/apps/chat/chat.js"></script>
<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="assets/js/custom/utilities/modals/create-campaign.js"></script>
<script src="assets/js/custom/utilities/modals/users-search.js"></script>
<script src="assets/js/quill/quill.js"></script>
<!--end::Page Vendors Javascript-->
<!--begin::Page Custom Javascript(used by this page)-->
<script src="assets/js/custom/widgets.js"></script>
<script src="assets/js/constants.js"></script>
<script src="assets/js/common.js"></script>
<script src="assets/js/helper.js"></script>
<script src="assets/js/app.js"></script>
<script src="assets/js/pagination.js"></script>
<script src="assets/js/skeleton/skeleton-table.js"></script>
<script src="assets/js/skeleton/widget-skeleton.js"></script>

<script>
    function cancelFormAndReload() {
        // SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to cancel this form and lose all changes?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, reload the page
                location.reload();
            } else {
                // If user cancels, do nothing
                console.log('Form cancelation was aborted.');
            }
        });
    }
</script>
<!--end::Page Custom Javascript-->

<!--begin::Page Custom Javascript(Dynamic Included)-->
<?php
if (isset($scripts) && is_array($scripts)) :
    foreach ($scripts as $script): ?>
        <script src="<?= $script ?>"></script>
<?php endforeach;
endif;
?>
<!--end::Javascript-->
</body>
<!--end::Body-->

</html>