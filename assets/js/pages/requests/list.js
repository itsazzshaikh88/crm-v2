// RequestListSkeleton("Request-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option?.colspan}" class="text-center text-danger">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <img src="assets/images/no-data.png" class="no-data-img-table w-80" alt="">
                                        <h4 class="text-danger fw-normal">Request data not found</h4>
                                    </div>
                                </td>
                            </tr>`;

    return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "request-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchRequests() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        listingSkeleton(tableId, paginate.pageLimit || 0, 'requests');
        const url = `${APIUrl}/requests/list`;
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
            throw new Error('Failed to fetch request data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showRequests(data.requests || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

function showRequests(requests, tbody) {
    let content = '';
    let counter = 0;
    if (requests?.length > 0) {
        // show requests
        requests.forEach(request => {
            content += `<tr data-request-id="${request.ID}">
                                <td class="text-center">${++counter}</td>
                                <td class="">
                                    <p class="mb-0 text-primary"><small>${request?.REQUEST_NUMBER || ''}</small></p>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="">
                                            <!--begin::Title-->
                                            <a href="requests/view/${request.UUID}" class="text-gray-800 text-hover-primary fs-5 fw-bold line-clamp-1s" data-kt-ecommerce-category-filter="category_name">${request?.REQUEST_TITLE || ''}</a>
                                            <!--end::Title-->
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted fs-7 fw-normal line-clamp-1">${request?.REQUEST_DETAILS || ''}</div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="">
                                            <!--begin::Title-->
                                            <a href="" class="text-gray-800 text-hover-primary fs-5 fw-bold mb-1" data-kt-ecommerce-category-filter="category_name">${request?.COMPANY_NAME || ''}</a>
                                            <!--end::Title-->
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <!--begin::Description-->
                                        <div class="text-muted fs-7 fw-normal line-clamp-1">${request?.FIRST_NAME || ''} ${request?.LAST_NAME || ''}
                                        </div>
                                    <!--end::Description--></td>
                                <td>${request?.CONTACT_NUMBER || ''}</td>
                                <td>
                                    ${request?.EMAIL_ADDRESS || ''}
                                </td>
                                <td>${formatAppDate(request?.CREATED_AT || '') ?? ''}</td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-4">
                                        <a href="requests/view/${request.UUID}">
                                            <small>
                                                <i class="fs-5 fa-solid fa-file-lines text-success"></i>
                                            </small>
                                        </a>
                                        <a onclick="openNewRequestModal('edit', ${request.ID})" href="javascript:void(0)">
                                            <small>
                                                <i class="fs-5 fa-regular fa-pen-to-square text-gray-700"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" onclick="deleteRequest(${request.ID})">
                                            <small>
                                                <i class="fs-5 fa-solid fa-trash-can text-danger"></i>
                                            </small>
                                        </a>
                                    </div>
                                </td>
                            </tr>`;
        });
        tbody.innerHTML = content;
    } else {
        // no data available
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders })
    }
}


// Global scope
// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchRequests(); // Fetch Request for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial Request data
    fetchRequests();
});

function filterRequest() {
    paginate.currentPage = 1;
    fetchRequests();
}

async function fetchCategories() {
    const categoryList = document.getElementById("CATEGORY_ID");

    // Disable the select dropdown and show the loading label with animation
    categoryList.disabled = true;

    // Retrieve the auth_token from cookies
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
        return;
    }

    try {
        // Fetch categories from the API (replace 'your-api-endpoint' with the actual API URL)
        const response = await fetch(`${APIUrl}/categories/list`, {
            method: 'GET', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        // Check if the response is okay (status code 200-299)
        if (!response.ok) {
            throw new Error('Failed to fetch categories');
        }

        // Parse the JSON response
        const categories = await response.json();

        // Clear existing options
        categoryList.innerHTML = '<option value="">Choose Category</option>';

        // Populate the <select> with category options
        categories.forEach(category => {
            const option = document.createElement("option");
            option.value = category.ID; // Adjust to match the category ID key
            option.textContent = category.CATEGORY_CODE; // Adjust to match the category name key
            categoryList.appendChild(option);
        });
    } catch (error) {
        toasterNotification({ type: 'error', message: error });
    } finally {
        // Re-enable the select dropdown and hide the loading label
        categoryList.disabled = false;
    }
}

async function deleteRequest(requestID) {
    if (!requestID) {
        throw new Error("Invalid Request ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete Request? This action cannot be undone.",
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
            title: "Deleting Request...",
            text: "Please wait while the Request is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/requests/delete/${requestID}`;

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
            throw new Error(data.error || 'Failed to delete Request details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Request Deleted Successfully' });
            // Logic to remove the current row from the table
            fetchRequests();
        } else {
            throw new Error(data.message || 'Failed to delete Request details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}
