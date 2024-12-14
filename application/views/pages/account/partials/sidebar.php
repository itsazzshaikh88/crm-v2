<?php
function setActiveLink($selected, $current)
{
    if ($selected === $current)
        return "active";
    return '';
}
?>
<div class="flex-column flex-lg-row-auto w-100 w-xl-300px mb-10">
    <!--begin::Card-->
    <div class="card card-flush" data-kt-sticky="true" data-kt-sticky-name="account-navbar" data-kt-sticky-offset="{default: false, xl: '80px'}" data-kt-sticky-height-offset="50" data-kt-sticky-width="{lg: '250px', xl: '300px'}" data-kt-sticky-animation="false" data-kt-sticky-left="auto" data-kt-sticky-top="90px" data-kt-sticky-zindex="95">


        <!--begin::Card body-->
        <div class="card-body pt-10 p-10">
            <!--begin::Summary-->
            <div class="d-flex flex-center flex-column mb-10 rounded border-secondary border border-dashed card-rounded p-6">
                <!--begin::Avatar-->
                <div class="symbol  mb-3 symbol-100px symbol-circle "><img alt="Pic" src="assets/images/avatar-user.png"></div><!--end::Avatar-->
                <!--begin::Name-->
                <a href="javascript:void(0)" class="fs-2 text-gray-800 text-hover-primary fw-bold mb-1">
                    <?= $loggedInUser['username'] ?? 'Guest' ?>
                </a>
                <!--end::Name-->

                <!--begin::Position-->
                <div class="fs-6 fw-semibold text-gray-500 mb-2">
                    <?= $loggedInUser['email'] ?? 'guest@email.live' ?> </div>
                <!--end::Position-->

                <!--begin::Actions-->
                <?php
                $usertype = $loggedInUser['usertype'] ?? 'guest';
                ?>
                <div class="d-flex flex-center">
                    <a href="javascript:void(0)" class="btn btn-sm btn-light-<?= $usertype === 'admin' ? "primary" : "info" ?> py-2 px-4 fw-bold me-2" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_chat"><?= ucfirst($usertype) ?></a>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Summary-->

            <!--begin::Menu-->
            <ul class="menu menu-column menu-pill menu-title-gray-700 menu-bullet-gray-300 menu-state-bg menu-state-bullet-primary fw-bold fs-5 mb-10">
                <!--begin::Menu item-->
                <li class="menu-item mb-1">
                    <!--begin::Menu link-->
                    <a class="menu-link px-6 py-4 <?= setActiveLink($navlink['sub-link'] ?? '', "overview"); ?>" href="accounts">
                        <span class="menu-bullet"><span class="bullet"></span></span>
                        <span class="menu-title">
                            Overview </span>
                    </a>
                    <!--end::Menu link-->
                </li>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <li class="menu-item mb-1">
                    <!--begin::Menu link-->
                    <a class="menu-link px-6 py-4 <?= setActiveLink($navlink['sub-link'] ?? '', "settings"); ?>" href="account/settings">
                        <span class="menu-bullet"><span class="bullet"></span></span>
                        <span class="menu-title">
                            Settings </span>
                    </a>
                    <!--end::Menu link-->
                </li>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <li class="menu-item mb-1">
                    <!--begin::Menu link-->
                    <a class="menu-link px-6 py-4 <?= setActiveLink($navlink['sub-link'] ?? '', "security"); ?>" href="account/security">
                        <span class="menu-bullet"><span class="bullet"></span></span>
                        <span class="menu-title">
                            Security </span>
                    </a>
                    <!--end::Menu link-->
                </li>
                <!--end::Menu item-->
            </ul>
            <!--end::Menu-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>