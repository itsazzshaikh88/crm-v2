<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start  container-xxl ">

    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="card-title text-gray-800">Filter Activities</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="timeline timeline-border-dashed" id="activity-container"></div>
                        <div class="my-5 text-center d-none" id="loadingContainer">
                            <p>Loading ...</p>
                        </div>
                        <div class="my-5 text-center d-none" id="loadMoreBtnContainer">
                            <button type="button" id="loadMoreBtn" onclick="loadMoreData()" class="btn btn-sm btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary">Load More</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Post-->
</div>