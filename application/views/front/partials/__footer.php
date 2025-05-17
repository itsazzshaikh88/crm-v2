<script src="assets/js/bootstrap/popper.min.js"></script>
<script src="assets/js/bootstrap/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="assets/js/common.js"></script>
<script src="assets/js/constants.js"></script>
<script src="assets/js/helper.js"></script>
<script src="assets/js/app.js"></script>
<script src="assets/js/pagination.js"></script>
<script src="assets/js/skeleton/skeleton-table.js"></script>
<script src="assets/js/skeleton/widget-skeleton.js"></script>
<!--end::Page Custom Javascript-->

<!--begin::Page Custom Javascript(Dynamic Included)-->
<?php
if (isset($scripts) && is_array($scripts)) :
    foreach ($scripts as $script): ?>
        <script src="<?= $script ?>"></script>
<?php endforeach;
endif;
?>
<!--end::Javascript-->
</body>

</html>