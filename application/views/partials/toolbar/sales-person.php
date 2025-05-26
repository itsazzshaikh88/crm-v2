<!--begin::Action-->
<a href="javascript:void(0)" class="btn btn-sm btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
    <?= date('l d, M Y') ?>
</a>
<!--end::Action-->
<!--begin::Button-->

<!--end::Button-->
<!--begin::Button-->
<?php if (isset($options) && is_array($options)): ?>
    <?php if ($options['action'] === 'list'): ?>
        <a href="javascript:void(0)" onclick="openNewSalesPersonForm('new')" class="btn btn-sm btn-success my-2"> <i class="fa fa-plus"></i> Add Sales Person</a>
    <?php else: ?>
        <a href="sales/salesperson" class="btn btn-sm btn-success my-2"> <i class="fa fa-list"></i> Sales Persons</a>
    <?php endif; ?>
<?php endif; ?>
<!--end::Button-->