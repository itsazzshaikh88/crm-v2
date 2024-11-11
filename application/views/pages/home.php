<?php
include_once 'application/views/data/data-home.php';
?>
<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
	<div class="content flex-row-fluid" id="kt_content">
		<!--begin::PAGE CONTENT GOES FROM HERE-->
		<div class="row mb-4">
			<?php
			foreach ($stats_cards as $card):
			?>
				<div class="col-sm-6 col-md-3">
					<!--begin::Card widget 2-->
					<div class="card h-lg-100">
						<!--begin::Body-->
						<a href="" class="card-body d-flex justify-content-between align-items-center flex-row p-4">
							<!--begin::Icon-->
							<div class="m-0 ps-4">
								<img src="<?= $card['image'] ?>" class="w-80px scale-1-1" alt="">
							</div>
							<!--end::Icon-->
							<!--begin::Section-->
							<div class="d-flex flex-column my-7">
								<!--begin::Number-->
								<span class="fw-bolder fs-3x text-gray-800 lh-1 ls-n2" id="<?= strtolower(str_replace(" ", "-", $card['label'])) ?>">0</span>
								<!--end::Number-->
								<!--begin::Follower-->
								<div class="m-0">
									<span class="fw-semibold fs-4 text-gray-500"><?= $card['label'] ?></span>
								</div>
								<!--end::Follower-->
							</div>
							<!--end::Section-->
						</a>
						<!--end::Body-->
					</div>
					<!--end::Card widget 2-->
				</div>
			<?php endforeach; ?>
		</div>

		<div class="row">
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<h2 class="fw-semibold text-gray-800 text-start">Open Orders</h2>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200 bg-light">
										<th>PO</th>
										<th>Address</th>
										<th>Total</th>
										<th>Status</th>
										<th>Date</th>
										<th>Track</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>PO-0001</td>
										<td>123 Elm St, NY</td>
										<td>$1,200.00</td>
										<td class="text-primary fw-bold">Shipped</td>
										<td>2024-10-01</td>
										<td><a href="#" class="badge bg-light text-gray-900 fw-normal rounded-0">Track</a></td>
									</tr>
									<tr>
										<td>PO-0002</td>
										<td>456 Oak Ave, CA</td>
										<td>$2,500.00</td>
										<td class="fw-bold">Pending</td>
										<td>2024-10-05</td>
										<td><a href="#" class="badge bg-light text-gray-900 fw-normal rounded-0">Track</a></td>
									</tr>
									<tr>
										<td>PO-0003</td>
										<td>789 Maple Dr, TX</td>
										<td>$850.00</td>
										<td class="text-success fw-bold">Delivered</td>
										<td>2024-09-28</td>
										<td><a href="#" class="badge bg-light text-gray-900 fw-normal rounded-0">Track</a></td>
									</tr>
									<tr>
										<td>PO-0004</td>
										<td>101 Pine Rd, FL</td>
										<td>$3,200.00</td>
										<td class="text-danger fw-bold">Canceled</td>
										<td>2024-10-03</td>
										<td><a href="#" class="badge bg-light text-gray-900 fw-normal rounded-0">Track</a></td>
									</tr>
									<tr>
										<td>PO-0005</td>
										<td>222 Cedar Ln, NV</td>
										<td>$950.00</td>
										<td class="text-primary fw-bold">Shipped</td>
										<td>2024-09-30</td>
										<td><a href="#" class="badge bg-light text-gray-900 fw-normal rounded-0">Track</a></td>
									</tr>
									<tr>
										<td>PO-0006</td>
										<td>333 Birch Blvd, IL</td>
										<td>$1,750.00</td>
										<td class="fw-bold">Pending</td>
										<td>2024-10-07</td>
										<td><a href="#" class="badge bg-light text-gray-900 fw-normal rounded-0">Track</a></td>
									</tr>
									<tr>
										<td>PO-0007</td>
										<td>444 Spruce St, WA</td>
										<td>$4,000.00</td>
										<td class="text-success fw-bold">Delivered</td>
										<td>2024-09-25</td>
										<td><a href="#" class="badge bg-light text-gray-900 fw-normal rounded-0">Track</a></td>
									</tr>
									<tr>
										<td>PO-0008</td>
										<td>555 Willow Ave, OR</td>
										<td>$1,100.00</td>
										<td class="text-primary fw-bold">Shipped</td>
										<td>2024-10-08</td>
										<td><a href="#" class="badge bg-light text-gray-900 fw-normal rounded-0">Track</a></td>
									</tr>
									<tr>
										<td>PO-0009</td>
										<td>666 Palm Dr, AZ</td>
										<td>$2,300.00</td>
										<td class="fw-bold">Pending</td>
										<td>2024-10-02</td>
										<td><a href="#" class="badge bg-light text-gray-900 fw-normal rounded-0">Track</a></td>
									</tr>
									<tr>
										<td>PO-0010</td>
										<td>777 Redwood Rd, CO</td>
										<td>$5,500.00</td>
										<td class="text-success fw-bold">Delivered</td>
										<td>2024-09-27</td>
										<td><a href="#" class="badge bg-light text-gray-900 fw-normal rounded-0">Track</a></td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php include_once 'application/views/common/paginate.php' ?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card" dir="ltr">
					<!--begin::Body-->
					<div class="card-body d-flex flex-column align-items-center justify-content-center">
						<!--begin::Heading-->
						<div class="mb-2">
							<!--begin::Title-->
							<h2 class="fw-semibold text-gray-800 text-center">Track Your Order</h2>
							<!--end::Title-->

							<!--begin::Illustration-->
							<div class="py-10 text-center">
								<img src="assets/images/track-order.png" class="theme-light-show w-250px" alt="">
							</div>
							<!--end::Illustration-->
						</div>
						<!--end::Heading-->

						<!--begin::Links-->
						<div class="text-center mb-1">
							<!--begin::Link-->
							<a class="btn btn-sm btn-primary me-2" data-bs-target="#kt_modal_create_app" data-bs-toggle="modal">
								Try now </a>
							<!--end::Link-->

							<!--begin::Link-->
							<a class="btn btn-sm btn-light" href="../pages/user-profile/activity.html">
								Learn more </a>
							<!--end::Link-->
						</div>
						<!--end::Links-->
					</div>
					<!--end::Body-->
				</div>
			</div>
		</div>

		<!--end::PAGE CONTENT GOES FROM HERE-->
	</div>
</div>
<!--end::PAGE CONTAINER-->