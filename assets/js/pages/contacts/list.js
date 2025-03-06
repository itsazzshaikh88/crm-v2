// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<div class="col-md-12">
                        <div class="d-flex flex-row align-items-center justify-content-center h-full text-center p-10">
                            <!-- Illustration -->
                            <img
                                src="assets/images/add-contact.jpg"
                                alt="Empty Contacts"
                                class="w-300 h-300 mb-6" />
                            <!-- Headline -->
                            <div class="max-w-lg">
                                <h2 class="fs-2x font-semibold text-gray-900">Your Contact List is Empty</h2>
                                <!-- Subtext -->
                                <p class="text-gray-600 mt-2 fs-5 fw-normal">
                                    Your network begins here. Start by adding your first <span class="text-primary">contact</span> and building meaningful connections that matter.
                                </p>
                                <!-- Buttons -->
                                <div class="mt-6">
                                    <a href="contacts/new"
                                        class="btn btn-primary">
                                        Add Contact
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>`;

    return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "contact-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);
const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchContacts() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        listingSkeleton(tableId, paginate.pageLimit || 0, 'contacts');
        const url = `${APIUrl}/contacts/list`;
        const filters = filterCriterias([]);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: filters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch contact data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showContactList(data.contacts || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode();
    }
}

function showContactList(contact, tbody) {
    if (!contact) {
        throw new Error("Contact details not found");
    }
    if (contact && contact.length > 0) {
        let content = ''
        let counter = 0;
        contact.forEach(contact => {
            content += `<tr data-lead-id="${contact?.CONTACT_ID}" class="text-gray-800 fs-7">
                                <td class="text-center">${++counter}</td>
                                <td>
                                    <p class="mb-0">${contact?.FIRST_NAME} ${contact?.LAST_NAME}</p>
                                </td>
                                <td>
                                    <p class="mb-0">${contact?.COMPANY_NAME}</p>
                                </td>
                                <td>${contact?.JOB_TITLE}</td>
                                <td>
                                    <p class="mb-0 text-primary">${contact?.EMAIL}</p>
                                    <p class="mb-0 text-muted"><small>${contact?.PHONE}</small></p>
                                </td>
                                <td>${contact?.ASSIGNED_TO || ''}</td>
                                <td>
                                    <p class="mb-0 badge bg-light text-info"><small>${contact?.CONTACT_SOURCE?.toUpperCase()}</small></p>
                                </td>
                                <td>${capitalizeWords(contact?.PREFERRED_CONTACT_METHOD)}</td>
                                <td><small>${setContactStatus(contact?.STATUS)}</small></td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a href="javascript:void(0)" onclick="viewContactDetails('${contact?.UUID}')">
                                            <small>
                                                <i class="fs-8 fa-solid fa-file-lines text-info"></i>
                                            </small>
                                        </a>
                                        <a href="contacts/new/${contact?.UUID}?action=edit">
                                            <small>
                                                <i class="fs-8 fa-regular fa-pen-to-square text-gray-700"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" onclick="deleteContact(${contact?.CONTACT_ID})">
                                            <small>
                                                <i class="fs-8 fa-solid fa-trash-can text-danger"></i>
                                            </small>
                                        </a>
                                    </div>
                                </td>
                            </tr>`
        });
        tbody.innerHTML = content;
    } else {
        tbody.innerHTML = renderNoResponseCode();
    }

}


function setContactStatus(status) {
    const statusBackgroundColors = {
        new: "#4CAF50", // fresh green for new
        qualified: "#2196F3", // soft blue for qualified
        unqualified: "#FFC107", // amber for unqualified
        engaged: "#FF5722", // warm orange for engaged
        'follow-up-required': "#FF9800", // bright orange for follow-up required
        'no-response': "#9E9E9E", // grey for no response
        'in-active': "#607D8B", // slate blue-gray for inactive
    };
    return `<span class="" style="color: ${statusBackgroundColors[status]}">${capitalizeWords(status)}</span>`
}

// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchContacts(); // Fetch contact for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchContacts();
});

function filterContacts() {
    paginate.currentPage = 1;
    fetchContacts();
}

async function deleteContact(contactID) {



    if (!contactID) {
        throw new Error("Invalid Contact ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete contact? This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel",
            customClass: {
                popup: 'small-swal',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
            },
        });

        if (!confirmation.isConfirmed) return;

        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({
                type: 'error',
                message: "Authorization token is missing. Please login again to make an API request."
            });
            return;
        }

        // Show a non-closable alert box while the activity is being deleted
        Swal.fire({
            title: "Deleting Contact...",
            text: "Please wait while the contact is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/contacts/delete/${contactID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete request
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        // Close the loading alert box
        Swal.close();

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete contact details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Contact Deleted Successfully' });
            // Logic to remove the current row from the table
            const row = document.querySelector(`#contact-list-tbody tr[data-contact-id="${contactID}"]`);
            if (row) {
                row.remove(); // Remove the row from the table
            }
        } else {
            throw new Error(data.message || 'Failed to delete contact details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}


// Modals
// Modal Related Code
var contactDetailModal = new bootstrap.Modal(document.getElementById("new-contact-modal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});
async function viewContactDetails(contactUUID) {
    const contactDetailContainer = document.getElementById("contact-detail-container");
    if (contactDetailContainer)
        contactDetailContainer.innerHTML = `<div class="text-center">
                        <div class="spinner mt-5 mx-auto mb-2"></div>
                        <p><small class="text-slate-500 fw-normal">Fetching Contact Details, Please Wait ....</small></p>
                    </div>`

    contactDetailModal.show()
    // Fetch details

    const apiUrl = `${APIUrl}/contacts/detail`;
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({
            type: 'error',
            message: "Authorization token is missing. Please login again to make an API request."
        });
        return;
    }

    try {
        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ contactUUID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        showContactDetails(data?.data, contactDetailContainer);

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    }
}

function showContactDetails(contactDetail, container) {
    if (contactDetail) {
        const { ADDRESS, ASSIGNED_TO, COMPANY_NAME, CONTACT_SOURCE, CREATED_AT, DEPARTMENT, EMAIL, FIRST_NAME, JOB_TITLE
            , LAST_NAME, NOTES, PHONE, PREFERRED_CONTACT_METHOD, STATUS
        } = contactDetail;
        container.innerHTML = `
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${FIRST_NAME}" readonly placeholder="Enter Value">
                    <label for="floatingInput">First Name</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${LAST_NAME}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Last Name</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${EMAIL}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Email Address</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${PHONE}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Contact Number</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${COMPANY_NAME}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Company Name</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${JOB_TITLE}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Job Title</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${DEPARTMENT}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Department</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${CONTACT_SOURCE}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Contact Source</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${PREFERRED_CONTACT_METHOD}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Prefered Contact Method</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${capitalizeWords(STATUS)}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Status</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${ASSIGNED_TO}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Assigned To</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <input type="text" class="text-gray-800 border border-blue-100 fw-normal form-control bg-white" value="${ADDRESS}" readonly placeholder="Enter Value">
                    <label for="floatingInput">Address</label>
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="form-floating mb-2">
                    <textarea rows="5" class="form-control bg-white" readonly placeholder="Enter Value">${ADDRESS}</textarea>
                    <label for="floatingInput">Notes</label>
                </div>
                <!--end::Input group-->
                `;
    } else {
        container.innerHTML = `<div class="text-center">
                        <p><small class="text-danger fw-normal mt-5">Contact Details Not Found</small></p>
                    </div>`
    }

}