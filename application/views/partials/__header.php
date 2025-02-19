<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= base_url() ?>">
    <title><?= $page_title ?? "Olivesofts - Fixed Assets Tracking Application" ?></title>

    <meta name="description" content="Manage your organization's fixed assets effortlessly with our Fixed Assets Management and Asset Tracking application. This comprehensive tool allows you to categorize assets, generate QR codes for easy tracking, and monitor physical asset locations in real time. Enhance efficiency, reduce losses, and streamline your asset management process with user-friendly features tailored for small to large-scale companies.">

    <meta name="keywords" content="Fixed Assets Management, Asset Tracking, QR Code Generation, Asset Categorization, Asset Reports, Physical Asset Tracking, User Management, Authentication, Authorization, Asset Image Uploads" />
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