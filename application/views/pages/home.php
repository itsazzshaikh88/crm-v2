<?php
include_once 'application/views/data/data-home.php';
$usertype = $loggedInUser['usertype'] ?? 'guest';
$stats_cards = $usertype === 'admin' ? $admin_stats_cards : $client_stats_cards;
?>
<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
	<div class="content flex-row-fluid" id="kt_content">
		<!--begin::PAGE CONTENT GOES FROM HERE-->
		<div class="row mb-4">
			<?php
			foreach ($stats_cards as $card):
			?>
				<div class="col-sm-6 col-md-3 <?= count($stats_cards) > 4 ? "mb-2" : "" ?>">
					<!--begin::Card widget 2-->
					<div class="card">
						<!--begin::Body-->
						<a href="" class="card-body d-flex justify-content-between align-items-center flex-row p-4">
							<!--begin::Icon-->
							<div class="m-0 ps-4">
								<img src="<?= $card['image'] ?>" class="w-60px scale-1-1" alt="">
							</div>
							<!--end::Icon-->
							<!--begin::Section-->
							<div class="d-flex flex-column my-4 text-end">
								<!--begin::Number-->
								<span class="fw-bolder fs-3x text-gray-800 lh-1 ls-n2 card-stats-label-preview" id="<?= strtolower(str_replace(" ", "_", $card['label'])) ?>">
									0
								</span>
								<!--end::Number-->
								<!--begin::Follower-->
								<div class="m-0">
									<span class="fw-bold fs-4 text-gray-500"><?= $card['label'] ?></span>
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
							<table class="table table-sm table-row-bordered" id="open-orders-list">
								<thead>
									<tr class="fw-bold fs-7 text-gray-900">
										<th>PO</th>
										<th>Address</th>
										<th>Total</th>
										<th>Status</th>
										<th>Date</th>
										<th>Track</th>
									</tr>
								</thead>
								<tbody id="open-orders-list-tbody">

								</tbody>
							</table>
						</div>
						<?= renderPaginate('oo-current-page', 'oo-total-pages', 'oo-page-of-pages', 'oo-range-of-records') ?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card" dir="ltr">
					<!--begin::Body-->
					<div class="card-body">
						<!--begin::Heading-->
						<div class="mb-2">
							<!--begin::Title-->
							<h2 class="fw-semibold text-gray-800 text-center">Track Your Order</h2>
							<!--end::Title-->
							<!--begin::Illustration-->
							<div class="py-10 text-center" id="track-idle-container">
								<img src="assets/images/track-order.png" class="theme-light-show w-250px" alt="">
							</div>
							<!--end::Illustration-->

							<div class="d-none" id="track-processing-container">
								<div class="timeline timeline-border-dashed">
									<!--begin::Timeline item-->
									<div class="timeline-item pb-5">
										<!--begin::Timeline line-->
										<div class="timeline-line"></div>
										<!--end::Timeline line-->

										<!--begin::Timeline icon-->
										<div class="timeline-icon">
											<i class="ki-duotone ki-cd fs-2 text-success"><span class="path1"></span><span class="path2"></span></i>
										</div>
										<!--end::Timeline icon-->

										<!--begin::Timeline content-->
										<div class="timeline-content m-0">
											<!--begin::Label-->
											<span class="fs-8 fw-bolder text-success text-uppercase">Sender</span>
											<!--begin::Label-->

											<!--begin::Title-->
											<a href="#" class="fs-6 text-gray-800 fw-bold d-block text-hover-primary">Albert Flores</a>
											<!--end::Title-->

											<!--begin::Title-->
											<span class="fw-semibold text-gray-500">3517 W. Gray St. Utica, Pennsylvania 57867</span>
											<!--end::Title-->
										</div>
										<!--end::Timeline content-->
									</div>
									<!--end::Timeline item-->

									<!--begin::Timeline item-->
									<div class="timeline-item">
										<!--begin::Timeline line-->
										<div class="timeline-line"></div>
										<!--end::Timeline line-->

										<!--begin::Timeline icon-->
										<div class="timeline-icon">
											<i class="ki-duotone ki-geolocation fs-2 text-info"><span class="path1"></span><span class="path2"></span></i>
										</div>
										<!--end::Timeline icon-->

										<!--begin::Timeline content-->
										<div class="timeline-content m-0">
											<!--begin::Label-->
											<span class="fs-8 fw-bolder text-info text-uppercase">Receiver</span>
											<!--begin::Label-->

											<!--begin::Title-->
											<a href="#" class="fs-6 text-gray-800 fw-bold d-block text-hover-primary">Jessie Clarcson</a>
											<!--end::Title-->

											<!--begin::Title-->
											<span class="fw-semibold text-gray-500">Total 2,356 Items in the Stock</span>
											<!--end::Title-->
										</div>
										<!--end::Timeline content-->
									</div>
									<!--end::Timeline item-->
								</div>
							</div>
						</div>
						<!--end::Heading-->
					</div>
					<!--end::Body-->
				</div>
			</div>
		</div>

		<!--end::PAGE CONTENT GOES FROM HERE-->
	</div>
</div>
<!--end::PAGE CONTAINER-->