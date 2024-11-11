<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::PAGE CONTENT GOES FROM HERE-->
        <div class="card mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 d-flex align-items-center justify-content-between mb-4">
                        <h2 class="mb-4">ASN Details</h2>
                        <h4 class="text-muted fw-normal">ASN Number: <span class="text-black">A-0000-00-00</span> </h4>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="" class="fs-6 fw-bold">Status</label>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="" id="">
                                    <option value="">Select Status</option>
                                    <option value="Draft" selected="">Draft</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-center justify-content-end">
                                <label for="" class="fs-6 fw-bold">Delivery Number</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="" class="fs-6 fw-bold">Carrier Name</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                            <div class="col-md-3 d-flex align-items-center justify-content-end">
                                <label for="" class="fs-6 fw-bold">Transport</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="" class="fs-6 fw-bold">Truck Number</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                            <div class="col-md-3 d-flex align-items-center justify-content-end">
                                <label for="" class="fs-6 fw-bold">LEG DR</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="" class="fs-6 fw-bold">Customer Supplier</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center justify-content-start">
                                <label for="" class="fs-6 fw-bold">Shipping Address</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter request title">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                <h2 class="mb-4">Order Details</h2>
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-row-bordered align-middle gy-4 gs-9">
                        <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                            <tr>
                                <td class="min-w-150px">PO #</td>
                                <td class="min-w-150px">SOC #</td>
                                <td class="min-w-150px">Product</td>
                                <td class="min-w-250px">Product Desc</td>
                                <td class="min-w-150px">Color</td>
                                <td class="min-w-150px">Tranport</td>
                                <td class="min-w-150px">UOM</td>
                                <td class="min-w-150px">Qty</td>
                                <td class="min-w-150px">Packing</td>
                                <td class="min-w-150px">Invoice #</td>
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
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
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
                                    <button class="btn btn-sm border border-danger">
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
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<!--end::PAGE CONTAINER-->