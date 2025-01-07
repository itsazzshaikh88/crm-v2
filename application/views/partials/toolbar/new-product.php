<!--begin::Action-->
<a href="javascript:void(0)" class="btn btn-sm btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
    <?= date('l d, M Y') ?>
</a>
<!--end::Action-->
<!--begin::Button-->
<?php if (isset($options) && is_array($options)):
?>
    <?php if ($options['action'] === 'list'):
    ?>
        <button type="button" onclick="openNewProductModal()" class="btn btn-sm btn-success my-2">Create New Product</button>
    <?php elseif ($options['action'] === 'form'): ?>
        <a href="products/list" class="btn btn-sm btn-success my-2">Product List</a>
    <?php else: ?>
        <a href="products/list" class="btn btn-sm border text-white my-2 mx-2">Product List</a>
        <button type="button" onclick="openNewProductModal()" class="btn btn-sm btn-success my-2">Create New Product</button>
    <?php endif; ?>
<?php endif; ?>
<!--end::Button-->