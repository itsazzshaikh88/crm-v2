<nav class="navbar navbar-expand-lg navbar-light shadow-sm">
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
                    <a class="nav-link text-success" aria-current="page" target="_blank" href="https://zamilplastic.com/en/">Zamil Plastic</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="updates/news-and-announcements">News</a>
                </li>
            </ul>
        </div>
    </div>
</nav>