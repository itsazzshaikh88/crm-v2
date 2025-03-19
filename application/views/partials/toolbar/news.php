<!--begin::Action-->
<a href="javascript:void(0)" class="btn btn-sm btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
    <?= date('l d, M Y') ?>
</a>
<!--end::Action-->
<!--begin::Button-->
<?php
$currentUser = $loggedInUser['username'] ?? 'Guest';
$currentUserType = $loggedInUser['usertype'] ?? 'guest';
if (isset($options) && is_array($options)):
?>
    <?php if ($options['action'] === 'list'):
    ?>
        <?php if ($currentUserType === 'admin'): ?>
            <button type="button" onclick="openNewNewsModal()" class="btn btn-sm btn-success my-2">Upload News</button>
        <?php endif; ?>
    <?php elseif ($options['action'] === 'form'): ?>
        <a href="news" class="btn btn-sm border text-white border-white me-2 my-2">News List</a>
    <?php else: ?>
        <a href="news" class="btn btn-sm border text-white border-white me-2 ">News List</a>
    <?php endif; ?>
<?php endif; ?>
<!--end::Button-->