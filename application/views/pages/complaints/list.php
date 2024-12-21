<!--begin::PAGE CONTAINER -->
<?php
$firstCard = 'Total';
$secondCard = 'Active';
$thirdCard = 'Closed/Resolved';
$fourthCard = 'Draft';
$usertype = $loggedInUser['usertype'] ?? 'Guest';
$user_id = $loggedInUser['userid'];
?>
<input type="hidden" id="USER_TYPE" value="<?= $usertype ?>">
<input type="hidden" id="USER_ID" value="<?= $user_id ?>">
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
	<div class="content flex-row-fluid" id="kt_content">
		<!--begin::PAGE CONTENT GOES FROM HERE-->
		<div class="card">
			<div class="card-body">
				<div class="row">
					<!--begin::Col-->
					<div class="col">
						<a href="<?= base_url('complaints/list/total') ?>">
							<div class="card card-dashed min-w-175px my-3 p-6 d-flex align-items-center flex-row ">
								<img class="me-5" style="width: 100px;" src="<?= base_url('assets/images/customer-feedback.png') ?>" alt="Feedback">
								<div class="ms-5 text-center mx-auto">
									<span style="font-size:x-large;font-weight:500" class="text-info d-flex flex-column text-center">Total</span>
									<span class="text-info">
										<span style="font-size:xx-large;font-weight:bolder" id="total-comp">0</span>
									</span>
								</div>
							</div>
						</a>
					</div>
					<!--begin::Col-->
					<div class="col">
						<a href="<?= base_url('complaints/list/active') ?>">
							<div class="card card-dashed min-w-175px my-3 p-6 d-flex align-items-center flex-row ">
								<img class="me-5" style="width: 100px;" src="<?= base_url('assets/images/complaint.png') ?>" alt="Active">
								<div class="ms-5 text-center mx-auto">
									<span style="font-size:x-large;font-weight:500" class="text-danger d-flex flex-column text-center">Active</span>
									<span class="text-danger">
										<span style="font-size:xx-large;font-weight:bolder" id="active-comp">0</span>
									</span>
								</div>
							</div>
						</a>
					</div>
					<!--end::Col-->
					<div class="col">
						<a href="<?= base_url('complaints/list/closed') ?>">
							<div class="card card-dashed min-w-175px my-3 p-6 d-flex align-items-center flex-row ">
								<img class="me-5" style="width: 100px;" src="<?= base_url('assets/images/check.png') ?>" alt="Closed/Resolved">
								<div class="ms-5 text-center mx-auto">
									<span style="font-size:x-large;font-weight:500" class="text-success d-flex flex-column text-center">Closed</span>
									<span class="text-success">
										<span class="fs-lg-2tx fw-bold d-flex justify-content-center">
											<span style="font-size:xx-large;font-weight:bolder" class="counted text-success" id="closed-comp">0</span>
										</span>
									</span>
								</div>
							</div>
						</a>
					</div>
					<!--begin::Col-->
					<div class="col">
						<a href="<?= base_url('complaints/list/draft') ?>">
							<div class="card card-dashed min-w-175px my-3 p-6 d-flex align-items-center flex-row ">
								<img class="me-5" style="width: 100px;" src="<?= base_url('assets/images/to-do-list.png') ?>" alt="Closed/Resolved">
								<div class="ms-5 text-center mx-auto">
									<span style="font-size:x-large;font-weight:500" class="text-primary d-flex flex-column text-center">Draft</span>
									<span class="text-primary">
										<span class="fs-lg-2tx fw-bold d-flex justify-content-center">
											<span style="font-size:xx-large;font-weight:bolder" class="counted text-primary" id="draft-comp">0</span>
										</span>
									</span>
								</div>
							</div>
						</a>
					</div>
				</div>
				<input type="hidden" id="STATUS" name="" value="<?= $type ?>">
				<div class="table-responsive">
					<table class="table table-row-dashed gy-7" id="complaint-list">
						<thead>
							<tr class="fw-bold fs-6 text-gray-800">
								<th>#</th>
								<th class="w-350">Complaint No.</th>
								<th class="w-250">Date</th>
								<th class="w-350">Company Name</th>
								<th class="w-350">Customer Name</th>
								<th class="w-350">Raised By</th>
								<th class="w-350">Email</th>
								<th class="w-150">Mobile</th>
								<th class="w-150">Status</th>
								<th class="w-250 text-center">Action</th>
							</tr>
						</thead>
						<tbody id="complaint-list-tbody">
						</tbody>
					</table>
				</div>
				<?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
			</div>
		</div>
		<!--end::PAGE CONTENT GOES FROM HERE-->
	</div>
</div>
<!--end::PAGE CONTAINER-->