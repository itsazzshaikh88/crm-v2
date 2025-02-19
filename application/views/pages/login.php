<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= base_url() ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zamil Plastic Industries CRM</title>
    <link rel="icon" type="image/svg+xml" href="https://cdn-ilbbkmj.nitrocdn.com/cRYFiDiEDxSTYcPdyCcSPfHxCQdqrdfA/assets/images/optimized/rev-55dae52/zamilplastic.com/favicon.svg">
    <link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link href="assets/css/common.css" rel="stylesheet">
    <link href="assets/css/pages/login.css" rel="stylesheet">
    <link href="assets/css/app-core.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="assets/images/zpil-logo.png" class="logo" alt="">
                <span class="badge bg-<?= $account == 'admin-central' ? "primary" : 'info' ?> fw-normal fs-14"><?= application_module($account) ?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-success" aria-current="page" href="#">Zamil Plastic</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">News</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <!-- <div class="container d-none">
        <div class="row mt-4 justify-content-center">
            <div class="col-md-9 border-end"></div>
            <div class="col-md-3 ">
                <div class="card border-0">
                    <div class="card-body border-0  pb-5">
                        <div id="login-container" class="">
                            <h5 class="fw-bold text-slate-600">Welcome Back, </h5>
                            <p class="text-slate-500">Login to your Zamil CRM account</p>
                            <form id="form" onsubmit="validate(event)" method="post">
                                <div class="form-group mb-2">
                                    <label for="email" class="mb-1 fw-normal text-gray-500 fw-bold">Email:</label>
                                    <input type="text" class="form-control form-control-lg rounded" placeholder="Enter your registered email address" name="email" id="email">
                                    <span class="text-danger err-lbl" id="lbl-email"></span>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="email" class="mb-1 fw-normal text-gray-500 fw-bold">Password:</label>
                                    <input type="password" class="form-control form-control-lg rounded" placeholder="Enter your registered email address" name="password" id="password">
                                    <span class="text-danger err-lbl" id="lbl-password"></span>
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <div><input type="checkbox"> <span class="fs-14">Remember Me</span> </div>
                                    <div>
                                        <a href="" class="fs-14">Forgot Password</a>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <button class="w-100 btn btn-success" id="submit-btn">Sign In</button>
                                </div>
                            </form>
                        </div>
                        <div id="otp-container" class="d-none">
                            <h5 class="fw-bold text-slate-600">🔒 Two-Step Verification</h5>
                            <p class="text-slate-500 fs-sm">Two-step verification is <strong>ON</strong>. Please enter the 6-digit code from your authenticator app to proceed.
                            </p>
                            </p>
                            <form id="otp-form" onsubmit="validateOTP(event)" method="post">
                                <div class="form-group mb-2">
                                    <label for="email" class="mb-1 fw-normal text-gray-500 fw-bold">Enter OTP:</label>
                                    <input type="text" class="form-control form-control-lg rounded" placeholder="Enter 6-digit OTP" name="OTP_CODE" id="OTP_CODE">
                                    <span class="text-danger err-lbl" id="lbl-OTP_CODE"></span>
                                    <input type="hidden" class="form-control form-control-lg rounded" name="USER_ID" id="USER_ID">
                                </div>
                                <div class="form-group ">
                                    <button class="w-100 btn btn-success" id="otp-submit-btn">Validate</button>
                                </div>
                                <div class="my-4">
                                    <a href="login" class="py-2"> Go back to login page</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <style>
        .heading {
            font-size: 66px !important;
            font-weight: 700;
        }
    </style>

    <section class=" py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 d-flex flex-column align-items-start justify-content-center">
                    <h1 class="heading">Your All-in-One <span class="text-success">CRM</span> Solution</h1>
                    <h6 class="my-4">Simplify Management. Strengthen Relationships. Boost Growth.</h6>
                    <p class="text-muted">
                        Streamline workflows, enhance productivity, and drive success for everyone involved.
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="row justify-content-end">
                        <div class="col-md-8">
                            <div class="card rounded-0 bg-black border-0">
                                <div class="card-body bg-black border-0">
                                    <div id="login-container" class="">
                                        <div class="text-center">
                                            <h5 class="text-slate-600">Welcome Back, </h5>
                                            <p class="text-slate-500">Login to your Zamil CRM account</p>
                                        </div>
                                        <form id="form" onsubmit="validate(event)" method="post">
                                            <div class="form-group mb-2">
                                                <label for="email" class="mb-1 fw-normal text-gray-500 fw-bold">Email:</label>
                                                <input type="text" class="form-control form-control-lg rounded" placeholder="Enter your registered email address" name="email" id="email">
                                                <span class="text-danger err-lbl" id="lbl-email"></span>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label for="email" class="mb-1 fw-normal text-gray-500 fw-bold">Password:</label>
                                                <input type="password" class="form-control form-control-lg rounded" placeholder="Enter your registered email address" name="password" id="password">
                                                <span class="text-danger err-lbl" id="lbl-password"></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-4">
                                                <div><input type="checkbox"> <span class="fs-14">Remember Me</span> </div>
                                                <div>
                                                    <a href="" class="fs-14">Forgot Password</a>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <button class="w-100 btn btn-success" id="submit-btn">Sign In</button>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <!-- <a href="login?account=admin-central" class="py-2 text-white badge bg-primary text-decoration-none fw-normal w-50">Admin</a>
                                <a href="login?account=client-connect" class="py-2 text-white badge bg-info text-decoration-none fw-normal w-50">Client</a> -->
                                            </div>
                                        </form>
                                    </div>
                                    <div id="otp-container" class="d-none">
                                        <h5 class="fw-bold text-slate-600">🔒 Two-Step Verification</h5>
                                        <p class="text-slate-500 fs-sm">Two-step verification is <strong>ON</strong>. Please enter the 6-digit code from your authenticator app to proceed.
                                        </p>
                                        </p>
                                        <form id="otp-form" onsubmit="validateOTP(event)" method="post">
                                            <div class="form-group mb-2">
                                                <label for="email" class="mb-1 fw-normal text-gray-500 fw-bold">Enter OTP:</label>
                                                <input type="text" class="form-control form-control-lg rounded" placeholder="Enter 6-digit OTP" name="OTP_CODE" id="OTP_CODE">
                                                <span class="text-danger err-lbl" id="lbl-OTP_CODE"></span>
                                                <input type="hidden" class="form-control form-control-lg rounded" name="USER_ID" id="USER_ID">
                                            </div>
                                            <div class="form-group ">
                                                <button class="w-100 btn btn-success" id="otp-submit-btn">Validate</button>
                                            </div>
                                            <div class="my-4">
                                                <a href="login" class="py-2"> Go back to login page</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Animated Balloons -->
    <!-- <div class="circle"></div> -->

    <!-- Aidelogin images  -->
    <!-- <div class="login-side d-none d-md-block">
        <img src="assets/images/lg-bg.png" class="" alt="">
    </div> -->


    <script src="assets/js/bootstrap/popper.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="assets/js/common.js"></script>
    <script src="assets/js/pages/login.js"></script>
</body>

</html>