<style>
    .glowing-text {
        color: #00f;
        /* Blue color */
        text-shadow:
            0 0 10px rgba(0, 0, 255, 0.8),
            /* Inner glow */
            0 0 20px rgba(0, 0, 255, 0.6),
            /* Middle glow */
            0 0 30px rgba(0, 0, 255, 0.4),
            /* Outer glow */
            0 0 40px rgba(0, 0, 255, 0.2);
        /* Background shadow glow */

        font-weight: bolder;
        font-size: 5rem;
        /* Customize as needed */
    }
</style>
<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card">
            <div class="card-body text-center">
                <div class="row justify-content-center">
                    <div class="col-md-5 d-flex flex-column align-items-center justify-content-center">
                        <h1 class="fs-5x fw-bolder text-danger glowing-text">404</h1>
                        <h2>Oops! We’ve Lost Track of This Page</h2>
                        <p class="my-2">
                            Looks like this page has wandered off, but don’t worry, we’re experts at tracking things down. While this page might be missing, your customer data never is!
                        </p>

                        <a href="<?= base_url() ?>" class="btn btn-lg my-4 border border-primary text-primary">Go to Dashboard</a>
                    </div>
                    <div class="col-md-7  d-flex align-items-center justify-content-center">
                        <img src="assets/images/not-found.png" style="--float-duration: 4s;" class="img-fluid floating w-50" alt="">
                    </div>
                </div>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->