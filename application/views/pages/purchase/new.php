<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 d-flex align-items-center justify-content-between mb-4">
                        <h2 class="mb-4">General Details</h2>
                        <h4 class="text-muted fw-normal">PO Number: <span class="text-black">PO-0000-00-00</span> </h4>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="" class="fs-6 fw-bold">Quotation Number</label>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="" id="">
                                    <option value="">Select Quotation</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-center justify-content-end">
                                <label for="" class="fs-6 fw-bold">Request Number</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" readonly placeholder="Enter request title">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="" class="fs-6 fw-bold">Company Name</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                            <div class="col-md-3 d-flex align-items-center justify-content-end">
                                <label for="" class="fs-6 fw-bold">Company Address</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="" class="fs-6 fw-bold">Email Address</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                            <div class="col-md-3 d-flex align-items-center justify-content-end">
                                <label for="" class="fs-6 fw-bold">Mobile Number</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                <h2 class="mb-4">Product Details</h2>
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                            <tr>
                                <td class="min-w-150px">Product</td>
                                <td class="min-w-250px">Product Desc</td>
                                <td class="min-w-150px">Qty</td>
                                <td class="min-w-150px">Unit Price</td>
                                <td class="min-w-150px">Total</td>
                                <td class="min-w-150px">Color</td>
                                <td class="min-w-150px">Tranport</td>
                                <td class="min-w-150px">SOC #</td>
                                <td class="min-w-150px">Rec Qty</td>
                                <td class="min-w-150px">Bal Qty</td>
                                <td>
                                    <button class="btn btn-sm btn-success">
                                        <i class="las la-plus fs-4 cursor-pointer text-white m-0 p-0"></i>
                                    </button>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="" id="" class="form-control">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <button class="btn btn-sm border border-danger">
                                        <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>

                <div class="my-10">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label for="" class="fs-6 fw-bold mb-2">Currency</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="fs-6 fw-bold mb-2">Payment Term</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="fs-6 fw-bold mb-2">Status</label>
                                    <select name="STATUS" class="form-control" id="STATUS">
                                        <option value="">Select Status</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="Pending" selected="">Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                    <label for="" class="fs-6 fw-bold">Sub Total</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                    <label for="" class="fs-6 fw-bold">Discount in %</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                    <label for="" class="fs-6 fw-bold">Tax in %</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                    <label for="" class="fs-6 fw-bold">Total</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <label for="" class="fs-6 fw-bold mb-2">Comments</label>
                        <textarea name="" id="" class="form-control" placeholder="Write your comments here ..." rows="5"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="" class="fs-6 fw-bold mb-2">Attachments</label>
                        <input type="file" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->