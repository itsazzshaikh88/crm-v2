<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= base_url() ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link href="assets/css/common.css" rel="stylesheet">
    <link href="assets/css/pages/login.css" rel="stylesheet">
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



    <div class="container-fluid">
        <div class="d-flex align-items-start justify-content-center">
            <h1 class="text-center fw-bolder">Welcome back, <br> <span class="shadow-green">Sign in</span> to your account. </h1>
        </div>
        <div class="row mt-4 justify-content-center">
            <div class="col-md-3">
                <div class="card shadow rounded">
                    <div class="card-body bg-light rounded">
                        <form id="form" onsubmit="validate(event)" method="post">
                            <div class="form-group mb-2">
                                <label for="email" class="mb-1 fw-normal">Email:</label>
                                <input type="text" class="form-control" placeholder="Enter your registered email address" name="email" id="email">
                                <span class="text-danger err-lbl" id="lbl-email"></span>
                            </div>
                            <div class="form-group mb-2">
                                <label for="email" class="mb-1 fw-normal">Password:</label>
                                <input type="password" class="form-control" placeholder="Enter your registered email address" name="password" id="password">
                                <span class="text-danger err-lbl" id="lbl-password"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <div><input type="checkbox"> <span class="fs-14">Remember Me</span> </div>
                                <div>
                                    <a href="" class="fs-14">Forgot Password</a>
                                </div>
                            </div>
                            <div class="form-group ">
                                <button class="w-100 btn btn-outline-success" id="submit-btn">Sign In</button>
                            </div>

                            <div class="mt-4 mb-2 text-center">
                                <p class="mb-0 text-muted"><small>Choose your account type</small></p>
                            </div>
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <a href="login?account=admin-central" class="py-2 text-white badge bg-primary text-decoration-none fw-normal w-50">Admin</a>
                                <a href="login?account=client-connect" class="py-2 text-white badge bg-info text-decoration-none fw-normal w-50">Client</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Animated Balloons -->
    <div class="circle"></div>

    <!-- Aidelogin images  -->
    <div class="login-side d-none d-md-block">
        <img src="assets/images/login-side.png" alt="">
    </div>


    <script src="assets/js/bootstrap/popper.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="assets/js/common.js"></script>
    <script src="assets/js/pages/login.js"></script>
</body>

</html>