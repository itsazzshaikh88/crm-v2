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
						<?php
						$link_to_redirect = "javascript:void(0)";
						if (isset($card['link']) && $card['link'] != '')
							$link_to_redirect = $card['link'];

						?>
						<a href="<?= $link_to_redirect ?>" class="card-body d-flex justify-content-between align-items-center flex-row p-4">
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
						<div class="border-bottom mb-2 py-2 d-flex align-items-center justify-content-between bg-light px-2">
							<h6 class="fw-semibold text-gray-800 text-start border-end border-secondary flex-grow-1 me-3 mb-0">Track Orders</h6>
							<div class="d-flex align-items-center justify-content-between gap-2">
								<?= render_org_select("ORG_ID", "ORG_ID", "form-control form-control-sm border border-blue-100 text-gray-700", "Select Division") ?>
								<button type="button" class="btn btn-sm btn-secondary flex-1" onclick="loadOrdersToTrack()"> <i class="fa fa-search"></i></button>
							</div>
						</div>

						<div class="table-responsive">
							<table class="table table-sm table-row-bordered" id="open-orders-list" style="white-space: nowrap;">
								<thead>
									<tr class="fw-bold fs-7 text-gray-900">
										<th>PO #</th>
										<th>Client PO</th>
										<th>Customer</th>
										<th>Product</th>
										<th>Order Qty</th>
										<th>Ship Qty</th>
										<th>Bal Qty</th>
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
								<img src="assets/images/track-order.png" class="theme-light-show w-250px" alt="" id="card-delivery-img">
							</div>
							<!--end::Illustration-->

							<div class="d-none" id="track-processing-container">

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
<style>
	.msm-4 {
		margin-left: -5px !important;
	}
</style>