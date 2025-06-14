<!--begin::PAGE CONTAINER -->
<style>
	input[readonly] {
		background-color: #fbfbfb !important;
	}
</style>
<?php
$username = $loggedInUser['username'] ?? 'Guest';
$usertype = $loggedInUser['userrole'] ?? 'Guest';
$user_id = $loggedInUser['userid'] ?? '';
$email = $loggedInUser['email'] ?? 'user@guest.crm';
?>
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
	<div class="content flex-row-fluid" id="kt_content">
		<form id="form" class="form d-flex flex-column " method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
			<!--begin::PAGE CONTENT GOES FROM HERE-->
			<div class="card mb-2">
				<div class="card-body">
					<div class="row">
						<!-- ============== Complaint DETAILS =========== -->
						<div class="col-md-6 mb-2">
							<div class="row mb-3">
								<div class="col-md-6">
									<h2>Complaint Details</h2>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-3 d-flex align-items-center justify-content-start">
									<label for="COMPLAINT_NUMBER" class="fs-6 fw-bold required">Complaint No.</label>
								</div>
								<div class="col-md-7">
									<input type="text" name="COMPLAINT_NUMBER" id="COMPLAINT_NUMBER" class="form-control" placeholder="Generated Automatically" readonly>
									<span class="text-danger err-lbl" id="lbl-COMPLAINT_NUMBER"></span>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-3 d-flex align-items-center justify-content-start">
									<label for="COMPLAINT_DATE" class="fs-6 fw-bold required">Compalint Date</label>
								</div>
								<div class="col-md-7">
									<input type="date" class="form-control" name="COMPLAINT_DATE" id="COMPLAINT_DATE" value="<?= date('Y-m-d'); ?>" readonly>
									<span class="text-danger err-lbl" id="lbl-COMPLAINT_DATE"></span>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-3 d-flex align-items-center justify-content-start">
									<label for="STATUS" class="fs-6 fw-bold required">Complaint Status</label>
								</div>
								<div class="col-md-7">
									<input type="text" class="form-control" placeholder="Write your billing address" name="STATUS" id="STATUS" value="Draft" readonly>
									<span class="text-danger err-lbl" id="lbl-STATUS"></span>
								</div>
							</div>
						</div>

						<!-- ============== CLIENT DETAILS =========== -->
						<div class="col-md-6 mb-2">
							<div class="row mb-3">
								<div class="col-md-6">
									<h2>Customer Details</h2>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-3 d-flex align-items-center justify-content-start">
									<label for="CUSTOMER_NAME" class="fs-6 fw-bold required">Customer Name</label>
								</div>
								<div class="col-md-8">
									<input type="text" name="CUSTOMER_NAME" id="CUSTOMER_NAME" class="form-control" placeholder="Enter Customer Name" autocomplete="off" value="" readonly>
									<span class="text-danger err-lbl" id="lbl-CUSTOMER_NAME"></span>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-3 d-flex align-items-center justify-content-start">
									<label for="COMPLAINT_RAISED_BY" class="fs-6 fw-bold required">Complaint By</label>
								</div>
								<div class="col-md-8">
									<input type="text" class="form-control" placeholder="Complaint Raised By" name="COMPLAINT_RAISED_BY" id="COMPLAINT_RAISED_BY" autocomplete="off">
									<span class="text-danger err-lbl" id="lbl-COMPLAINT_RAISED_BY"></span>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-3 d-flex align-items-center justify-content-start">
									<label for="MOBILE_NUMBER" class="fs-6 fw-bold required">Contact No.</label>
								</div>
								<div class="col-md-8">
									<input type="text" class="form-control" placeholder="Enter Contact No." name="MOBILE_NUMBER" id="MOBILE_NUMBER" autocomplete="off">
									<span class="text-danger err-lbl" id="lbl-MOBILE_NUMBER"></span>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-md-3 d-flex align-items-center justify-content-start">
									<label for="EMAIL" class="fs-6 fw-bold required">Email Address</label>
								</div>
								<div class="col-md-8">
									<input type="email" class="form-control" placeholder="Enter Email Address" name="EMAIL" id="EMAIL" autocomplete="off" value="<?= $email ?>" readonly>
									<span class="text-danger err-lbl" id="lbl-EMAIL"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card mb-2">
				<div class="card-body">
					<div class="row">

						<div class="col-md-6">
							<h2 class="mb-4">Complaint Product Details</h2>
						</div>
						<div class="col-md-6 text-end"><button class="btn btn-sm btn-success" type="button" onclick="addRow()">
								<i class="las la-plus fs-4 cursor-pointer text-white m-0 p-0"></i>
							</button></div>
					</div>

					<div class="table-responsive">
						<!--begin::Table-->
						<table class="table table-row-bordered align-middle gy-4 gs-9" id="complaint-lines-table">
							<thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
								<tr>
									<td class="min-w-150px">PO Number</td>
									<td class="min-w-150px">Delivery Number</td>
									<td class="min-w-150px">Product Code</td>
									<td class="min-w-250px">Product Description</td>
									<td class="min-w-150px">Delivery Date</td>
									<td class="min-w-150px">Quantity</td>
									<td class="min-w-250px">Issue</td>
									<td class="min-w-250px">Remarks</td>
									<td>

									</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<!-- <select name="PRODUCT_ID[]" id="PRODUCT_ID_1" class="form-control" onclick="chooseProduct(1)">
											<option value="">Choose</option>
										</select> -->
										<select name="PO_NUMBER[]" id="PO_NUMBER_1" class="form-control">
											<option value="">Choose</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
										</select>
									</td>
									<td>
										<input type="text" class="form-control" name="DELIVERY_NUMBER[]" id="DELIVERY_NUMBER_1">
									</td>
									<td>
										<input type="text" class="form-control" name="PRODUCT_CODE[]" id="PRODUCT_CODE_1">
									</td>
									<td>
										<input type="text" class="form-control" name="PRODUCT_DESC[]" id="PRODUCT_DESC_1">
									</td>
									<td>
										<input type="date" class="form-control" name="DELIVERY_DATE[]" id="DELIVERY_DATE_1">
									</td>
									<td>
										<input type="text" class="form-control" name="QTY[]" id="QTY_1">
									</td>
									<td>
										<input type="text" class="form-control" name="ISSUE[]" id="ISSUE_1">
									</td>
									<td>
										<input type="text" class="form-control" name="REMARK[]" id="REMARK_1">
									</td>
									<td>
										<button class="btn btn-sm border border-danger" type="button" onclick="removeRow(this)">
											<i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
										</button>
									</td>
								</tr>
							</tbody>
						</table>
						<!--end::Table-->
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<div class="">
						<div class="row mb-4">
							<h2 class="mb-4">Complaint Details and Attachments</h2>
							<div class="col-md-12 my-4">
								<div class="">
									<!-- Custom styled upload box -->
									<div id="upload-box" class="upload-box d-flex align-items-center btn-outline btn-outline-dashed btn btn-active-light-primary justify-content-center py-8" onclick="document.getElementById('file-input').click();">
										<i class="fas fa-cloud-upload-alt upload-icon fs-2x my-2 text-primary"></i>
										<p class="my-4">Click to upload files</p>
										<input onchange="handleFileSelect(event)" type="file" id="file-input" multiple style="display:none;">
									</div>
									<div class="row mt-4">
										<div class="col-md-6">
											<!-- Uploaded files preview list -->
											<h6 class="fw-normal my-4">New Attached Files</h6>
											<div id="file-list" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
										</div>
										<div class="col-md-6">
											<!-- Uploaded files From Server preview list -->
											<h6 class="fw-normal my-4">Uploaded Files</h6>
											<div id="file-list-uploaded" class="my-4 d-flex align-items-center justify-content-start gap-4 flex-wrap"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row mb-4">
							<div class="col-md-12">
								<label for="COMPLAINT" class="fs-6 fw-bold mb-2">Complaint Details</label>
								<textarea name="COMPLAINT" id="COMPLAINT" class="form-control" placeholder="Please write your complaint in detail (Min. 5 Characters)..." rows="5"></textarea>
								<span class="text-danger err-lbl" id="lbl-COMPLAINT"></span>

							</div>
							<input type="hidden" name="UUID" id="UUID" value="<?= $uuid ?? uuid_v4() ?>">
							<input type="hidden" name="COMPLAINT_ID" id="COMPLAINT_ID" value="">
							<input type="hidden" name="CLIENT_ID" id="CLIENT_ID" value="">
							<input type="hidden" name="" id="USER_ID" value="<?= $user_id ?>">
							<input type="hidden" name="" id="USER_EMAIL" value="<?= $email ?>">
						</div>
					</div>
				</div>
			</div>
			<!--end::PAGE CONTENT GOES FROM HERE-->
			<div class="d-flex justify-content-end mb-10 mt-4">
				<button onclick="cancelFormAndReload()" type="button" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">
					Cancel
				</button>
				<button type="submit" id="submit-btn" class="btn btn-primary">
					<span class="indicator-label">
						Save Changes
					</span>
				</button>
			</div>
		</form>
	</div>
</div>
<!--end::PAGE CONTAINER-->
<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/clients/client-list');
$this->load->view('modals/clients/new-client');
$this->load->view('modals/products/product-list');
?>