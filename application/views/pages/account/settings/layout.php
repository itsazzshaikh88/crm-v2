<?php
$setting_navs = [
    [
        'link' => "login-activities",
        'label' => "Login Activities",
        'icon' => null,
    ],
    [
        'link' => "account-security",
        'label' => "Account Management",
        'icon' => null,
    ],
    [
        'link' => "general-preferences",
        'label' => "General Preferences",
        'icon' => null,
    ],
    [
        'link' => "notifications",
        'label' => "Notifications & Alerts",
        'icon' => null,
    ]
]
?>
<div class="flex-lg-row-fluid ms-lg-10">
    <!--begin::Sign-in Method-->
    <div class="card mb-5 mb-xl-10">
        <div class="card-body">
            <ul class="nav nav-tabs mb-5">
                <?php
                $selected_setting_nav = $navlink_view;
                foreach ($setting_navs as $setting_nav):
                ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $selected_setting_nav == $setting_nav['link'] ? 'active' : '' ?>" href="account/settings/<?= $setting_nav['link'] ?>"><?= $setting_nav['label'] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php
            if (isset($navlink_view) && file_exists(APPPATH . "views/pages/account/settings/" . $navlink_view . ".php")):
                $this->load->view("pages/account/settings/" . $navlink_view);
            else :
                $this->load->view("pages/account/settings/home");
            endif;
            ?>
        </div>
    </div>
    <!--end::Sign-in Method-->
</div>