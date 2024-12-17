<!--begin::Action-->
<a href="javascript:void(0)" class="btn btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
    <?= date('l d, M Y') ?>
</a>
<!--end::Action-->
<!--begin::Button-->
<?php if (isset($options) && is_array($options)): ?>
    <?php if ($options['action'] === 'list'): ?>
        <a href="quotes/new" class="btn btn-success my-2">Create New Quotation</a>
    <?php elseif ($options['action'] === 'form'): ?>
        <a href="quotes/list" class="btn btn-success my-2">Quotation List</a>
    <?php else: ?>
        <a href="quotes/list" class="btn border text-white my-2 mx-2">Quotation List</a>
        <a href="quotes/new" class="btn btn-success my-2 mx-2">Create New Quotation</a>
    <?php endif; ?>
<?php endif; ?>
<!--end::Button-->