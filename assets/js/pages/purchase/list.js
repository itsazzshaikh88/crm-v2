// productListSkeleton("product-list", 10, 11);
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
const tableId = "purchase-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetcPOList() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        listingSkeleton(tableId, paginateList.pageLimit || 0, 'purchase');
        const url = `${APIUrl}/purchase/list`;
        const filters = filterCriterias([]);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginateList.pageLimit,
                currentPage: paginateList.currentPage,
                filters: filters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch request data');
        }

        const data = await response.json();
        paginateList.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginateList.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showPODetails(data.po || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

const statusColors = {
    Draft: '#3674B5',   // Golden Yellow (Attractive and stands out)
    Pending: '#FFC107',   // Golden Yellow (Attractive and stands out)
    Approved: '#4CAF50',  // Vibrant Green (Positive and fresh)
    Rejected: '#F44336',  // Bright Red (Warning and attention-grabbing)
};

function showPODetails(po, tbody) {
    let content = '';
    let counter = 0;
    if (po?.length > 0) {
        po.forEach(request => {

            content += `<tr data-request-id="${request.PO_ID}" class="text-gray-800 fs-7">
                                <td class="text-center">${++counter}</td> <!-- # -->
                                 <td>${request?.PO_NUMBER || ''}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="">
                                            <a href="purchase/view/${request.UUID}" class="text-gray-800 text-hover-primary fw-normal mb-1 line-clamp-1" data-kt-ecommerce-category-filter="category_name">${request?.COMPANY_NAME || ''}</a>
                                        </div>
                                    </div>
                                </td> <!-- Company -->
                                <td>${request?.EMAIL_ADDRESS || ''}</td> <!-- Email -->
                                <td ><span class="line-clamp-1">${request?.COMPANY_ADDRESS || ''}</span></td> <!-- Company_address -->
                                <td>${request?.CONTACT_NUMBER || ''}</td> <!-- number -->
                                <td>${request?.PAYMENT_TERM || ''}</td> <!-- Payment -->
                                <td>${request?.TOTAL_AMOUNT || ''}</td> <!-- amount -->
                                <td><span class="badge text-white" style="background-color: ${statusColors[request?.PO_STATUS || '']}">${request?.PO_STATUS || ''}</span></td> <!-- Comments -->
                                <td>${request?.QTY || ''}</td> 
                                <td class="text-end">
                                    <div class="dropdown">
                                        <!-- Button to toggle the dropdown, without the caret (down arrow) -->
                                        <button class="btn btn-link p-0" type="button" id="optionsMenu_${request.PO_ID}" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fs-8 me-2 fa-solid fa-ellipsis-vertical"></i> <!-- Three vertical dots -->
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end px-0 py-2 shadow-lg border"
                                            aria-labelledby="optionsMenu_${request.PO_ID}">
                                            <li class="mb-1 fs-8">
                                                <a class="dropdown-item" href="purchase/view/${request.UUID}" title="View PO">
                                                    <i class="fs-8 me-2 fa-solid fa-file-lines text-success"></i> View PO
                                                </a>
                                            </li>
                                            <li class="mb-1 fs-8">
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    onclick="openNewPurchaseModal('edit', ${request.PO_ID})" title="Edit PO">
                                                    <i class="fs-8 me-2 fa-regular fa-pen-to-square text-gray-700"></i> Edit PO
                                                </a>
                                            </li>
                                            <li class="mb-1 fs-8">
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="deletePO(${request.PO_ID})"
                                                    title="Delete PO">
                                                    <i class="fs-8 me-2 fa-solid fa-trash-can text-danger"></i> Delete PO
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>`;
        });
        tbody.innerHTML = content;
    } else {
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}


// Global scope
// Declare the pagination instance globally
const paginateList = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginateList.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginateList.paginate(action); // Update current page based on the action
    fetcPOList(); // Fetch Request for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetcPOList();
});

function filterRequest() {
    paginateList.currentPage = 1;
    fetcPOList();
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

async function deletePO(poUUID) {
    if (!poUUID) {
        throw new Error("Invalid Request ID, Please try Again");
    }
    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete Purchase Order? This action cannot be undone.",
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
            title: "Deleting Purchase Order ...",
            text: "Please wait while the PO is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/purchase/delete/${poUUID}`;

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
            throw new Error(data.error || 'Failed to delete request details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Purchase Order Deleted Successfully' });
            // Logic to remove the current row from the table
            const row = document.querySelector(`#purchase-list-tbody tr[data-request-id="${poUUID}"]`);
            if (row) {
                row.remove(); // Remove the row from the table
            }
        } else {
            throw new Error(data.message || 'Failed to delete request details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}

