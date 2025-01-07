<!--begin::Action-->
<a href="javascript:void(0)" class="btn btn-sm btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
	<?= date('l d, M Y') ?>
</a>
<!--end::Action-->
<!--begin::Button-->

<?php
$usertype = $loggedInUser['usertype'] ?? 'Guest';

if (isset($options) && is_array($options)) {
	if ($usertype == 'client' && $options['action'] === 'form') {
		echo '<a href="complaints/list" class="btn btn-sm btn-danger my-2 mx-2">Complaint List</a> 
		<a href="complaints/new" class="btn btn-sm btn-success my-2">Raise Complaint</a>
		';
	}

	if ($usertype === 'admin' && $options['action'] === 'view' && isset($options['uuid'])) {
		echo '<a href="complaints/list" class="btn btn-sm btn-danger my-2 mx-2">Complaint List</a>
		<button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#complaintModal" onclick="fetchRequest(\'' . htmlspecialchars($options['uuid'], ENT_QUOTES) . '\')">View Complaint</button>';
	} elseif ($usertype === 'admin' && $options['action'] === 'form' && isset($options['uuid']) && isset($options['id'])) {
		// echo '<a href="complaints/resolve/' . htmlspecialchars($options['id'], ENT_QUOTES) . '/' . htmlspecialchars($options['uuid'], ENT_QUOTES) . '" class="btn btn-sm btn-success my-2">Resolve Complaint</a>';
	}
}
?>


<!--end::Button-->