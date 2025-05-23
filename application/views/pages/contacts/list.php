<style>
  .placeholder {
    background-color: #e0e0e0;
    border-radius: 4px;
    display: inline-block;
    height: 1em;
    vertical-align: middle;
    animation: placeholder-glow 1.5s infinite;
  }

  @keyframes placeholder-glow {
    0% {
      background-color: #eee;
    }

    50% {
      background-color: #ddd;
    }

    100% {
      background-color: #eee;
    }
  }

  .contact-card {
    border: 1px solid #f1f1f1;
    border-radius: 0.5rem;
    background-color: #fff;
    padding: 1rem;
    transition: background-color 0.2s ease-in-out;
  }

  .contact-card:hover {
    background-color: #fdfdfd;
  }

  .profile-img {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #ccc;
  }

  .contact-meta small {
    color: #6c757d;
  }

  .info-line {
    display: flex;
    align-items: center;
    font-size: 13px;
    margin-bottom: 4px;
  }

  .info-line i {
    color: #6c757d;
    margin-right: 6px;
    font-size: 13px;
    width: 16px;
    text-align: center;
  }



  .action-buttons button {
    padding: 0.25rem 0.5rem;
    font-size: 12px;
  }
</style>
<!--begin::PAGE CONTAINER -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
  <div class="content flex-row-fluid" id="kt_content">
    <!--begin::PAGE CONTENT GOES FROM HERE-->
    <div class="card">
      <div class="card-body pt-8">
        <div class="row align-items-end justify-content-between mb-4">
          <div class="col-md-6 d-flex gap-2">
            <div>
              <label for="STATUS" class="mb-1">
                <small class="text-muted">Status</small>
              </label>
              <select class="form-select form-select-sm" id="STATUS" name="STATUS">
                <option value="">All</option>
                <option value="new">New</option>
                <option value="qualified">Qualified</option>
                <option value="unqualified">Unqualified</option>
                <option value="engaged">Engaged</option>
                <option value="follow-up-required">Follow-up Required</option>
                <option value="no-response">No Response</option>
                <option value="in-active">In-Active</option>
                <option value="active">Active</option>
              </select>
            </div>

            <div>
              <label for="PREFERRED_CONTACT_METHOD" class="mb-1">
                <small class="text-muted">Preferred Contact Method</small>
              </label>
              <select class="form-select form-select-sm" id="PREFERRED_CONTACT_METHOD" name="PREFERRED_CONTACT_METHOD">
                <option value="">All</option>
                <option value="email">Email</option>
                <option value="phone-call">Phone Call</option>
                <option value="text-message">Text Message (SMS)</option>
                <option value="whatsapp">WhatsApp</option>
                <option value="video-call">Video Call</option>
                <option value="in-person-meeting">In-Person Meeting</option>
                <option value="social-media">Social Media (e.g., LinkedIn)</option>
                <option value="no-preference">No Preference</option>

              </select>
            </div>
            <div>
              <label for="searchInputElement" class="mb-1">
                <small class="text-muted">
                  <i class="fa-solid fa-circle-info fs-9"></i> Search by Column Data
                </small>
              </label>
              <input type="text" oninput="debouncedSearchContactListData(this)" class="form-control form-control-sm" id="searchInputElement" placeholder="Search by column ..">
            </div>
            <div class="align-self-end">
              <button class="btn btn-sm btn-light border border-secondary" onclick="filterContactReport()">Filter</button>
            </div>
          </div>

          <!-- Right side: Export -->
          <div class="col-md-3 text-end">
            <button id="exportCsvBtn" onclick="exportcontactData('csv')" class="btn btn-primary btn-sm me-2"> <i class="fas fa-file-csv"></i>
            </button>
          </div>
        </div>
        <!-- <div class="table-responsive">
                    <table class="table align-middle table-row-bordered fs-7 gy-3 table-row-bordered " id="contact-list">
                        <thead>
                            <tr class="fw-bold fs-7 text-gray-900">
                                <th class="text-center">#</th>
                                <th>Contact</th>
                                <th>Company Name</th>
                                <th>Job Title</th>
                                <th>Email</th>
                                <th>Contact #</th>
                                <th>Assigned To</th>
                                <th>Source</th>
                                <th>Pref. Method</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="contact-list-tbody"></tbody>
                    </table>
                </div> -->
        <div class="row" id="contact-list">
          <!-- Contact Cards will be injected here -->
        </div>

        <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?>
      </div>
    </div>
    <!--end::PAGE CONTENT GOES FROM HERE-->
  </div>
</div>
<!--end::PAGE CONTAINER-->

<!-- Include modals to add new lead  -->
<?php
$this->load->view('loaders/full-page-loader');
$this->load->view('modals/contact/new-contact');
?>