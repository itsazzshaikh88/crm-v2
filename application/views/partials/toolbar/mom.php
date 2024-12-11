<!--begin::Action-->
<a href="javascript:void(0)" class="btn btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
    <?= date('l d, M Y') ?>
</a>
<!--end::Action-->
<!--begin::Button-->
<?php if (isset($options) && is_array($options)): ?>
    <?php if ($options['action'] === 'list'): ?>
        <a href="javascript:void(0)" onclick="openMomModal()" class="btn btn-success my-2">Add Minutes of Meetings</a>
    <?php elseif ($options['action'] === 'form'): ?>
        <a href="mom" class="btn btn-success my-2">Minutes List</a>
    <?php else: ?>
        <a href="mom" class="btn border text-white my-2 mx-2">Minutes List</a>
        <a href="javascript:void(0)" onclick="openMomModal()" class="btn btn-success my-2 mx-2">Add Minutes of Meetings</a>
    <?php endif; ?>
<?php endif; ?>
<!--end::Button-->