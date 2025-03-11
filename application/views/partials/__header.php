<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= base_url() ?>">
    <title><?= $page_title ?? "Zamil CRM Application" ?></title>

    <meta name="description" content="Enhance your customer relationships with Zamil CRM – a powerful solution designed to streamline sales, automate workflows, and improve customer interactions. Manage leads, track communications, and optimize business operations with an intuitive, feature-rich platform tailored for businesses of all sizes.">

    <meta name="keywords" content="CRM Software, Customer Relationship Management, Sales Tracking, Lead Management, Workflow Automation, Customer Engagement, Business Optimization, User Management, Authentication, Authorization" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />
    <link rel="canonical" href="" />
    <link rel="icon" type="image/svg+xml" href="https://cdn-ilbbkmj.nitrocdn.com/cRYFiDiEDxSTYcPdyCcSPfHxCQdqrdfA/assets/images/optimized/rev-55dae52/zamilplastic.com/favicon.svg">
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Page Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app-core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/common.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/animation.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/skeleton/skeleton-table.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/quill/quill.snow.css" rel="stylesheet">
    <!--end::Global Stylesheets Bundle-->
    <?php
    if (isset($css_files) && is_array($css_files)):
        foreach ($css_files as $css):
    ?>
            <link rel="stylesheet" href="<?= $css ?>">
    <?php endforeach;
    endif; ?>


</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" style="background-image: url(assets/media/patterns/header-bg.png)" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled">
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!-- Include Navbar  -->
                <?php $this->load->view('partials/__navbar') ?>
                <!-- Navbar Ends Here  -->
                <!--begin::Toolbar-->
                <?php

                $this->load->view('partials/__toolbar') ?>
                <!--end::Toolbar-->