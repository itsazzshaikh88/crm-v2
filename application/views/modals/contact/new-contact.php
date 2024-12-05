<div class="modal fade" tabindex="-1" id="new-contact-modal">
    <div class="modal-dialog modal-dialog-scrollable max-w-lg">
        <div class="modal-content p-0">
            <div class="modal-header d-flex flex-column align-items-center justify-content-between p-4 ">
                <div class="w-100 d-flex align-items-center justify-content-between p-0">
                    <h3 class="modal-title text-primary fw-normal" id="contact-modal-title"></h3>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <!--end::Close-->
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="mt-4 px-6">
                    <h5 class="fw-bold text-gray-700">✨ Let's Take It to the <span class="text-success">Next</span> Level!</h5>
                    <p class="fs-8">
                        You’ve captured a <span class="text-warning">lead</span>—now let’s turn potential into progress. Share a bit more about this contact to pave the way for success.
                    </p>
                </div>
                <div class="separator separator-dashed"></div>
                <form action="" class="px-6 bg-gray-50 py-4">
                    <?php
                    $fields = [
                        [
                            'name' => '',
                            'required' => true,
                            'element' => 'input',
                            'type' => 'text'
                        ]
                    ]

                    ?>
                    <!--begin::Input group-->
                    <div class="form-floating mb-7">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email address</label>
                    </div>
                    <!--end::Input group-->
                </form>
            </div>
        </div>
    </div>
</div>