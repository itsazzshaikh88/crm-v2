// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option?.colspan}" class="text-center">
                                    No data available
                                </td>
                            </tr>`;

    return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "uom-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchUOM(userSearchTerm = null) {
    if (!userSearchTerm) {
        userSearchTerm = document.getElementById("searchInputElement").value.trim() || null
    }
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        commonListingSkeleton(tableId, paginate.pageLimit || 0, numberOfHeaders);
        const url = `${APIUrl}/uom/list`;
        const rawFilters = filterCriterias(['FILTER_IS_ACTIVE']);

        const filters = Object.fromEntries(
            Object.entries(rawFilters)
                .filter(([_, value]) => value != null && value !== '') // skip empty/null/undefined
                .map(([key, value]) => [key.replace(/^FILTER_/, ''), value])
        );

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters,
                search: userSearchTerm
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch lead data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showUOM(data.uom || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

function showUOM(uom, tbody) {
    let content = '';
    let counter = 0;
    if (uom?.length > 0) {
        // show uom
        uom.forEach(uom => {
            content += `<tr data-uom-id="${uom?.UOM_ID}" class="text-gray-800 fs-7">
                                <td class="text-center">${++counter}</td>
                                <td>${uom?.UOM_CODE}</td>
                                <td>${uom?.UOM_DESCRIPTION}</td>                               
                                <td>${uom?.UOM_TYPE}</td>                               
                                <td>
                                    <span class="badge ${uom?.IS_ACTIVE == 1 ? 'bg-success' : 'bg-danger'}">
                                        ${uom?.IS_ACTIVE == 1 ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                            
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a href="javascript:void(0)" onclick="fetchUOMDetailsToEdit(${uom?.UOM_ID})">
                                            <small>
                                                <i class="fs-8 fa-regular fa-pen-to-square text-gray-700"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" onclick="deleteUOM(${uom?.UOM_ID})">
                                            <small>
                                                <i class="fs-8 fa-solid fa-trash-can text-danger"></i>
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
    fetchUOM(); // Fetch leads for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchUOM();
});




async function deleteUOM(uomID) {
    if (!uomID) {
        throw new Error("Invalid UOM ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete UOM? This action cannot be undone.",
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
            title: "Deleting UOM...",
            text: "Please wait while the UOM is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/uom/delete/${uomID}`;

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
            throw new Error(data.error || 'Failed to delete UOM details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'UOM Deleted Successfully' });
            fetchUOM();
        } else {
            throw new Error(data.message || 'Failed to delete UOM details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}


function searchUOMListData(element) {
    const userSearchTerm = element.value.trim();

    fetchUOM(userSearchTerm);
}


// Create a debounced version of the function
const debouncedSearchUOMListData = debounce(searchUOMListData, 300); // 300ms delay

/// export data
function exportUOMData(type = null) {

    const search = document.getElementById("searchInputElement").value.trim();

    // Encode filters and search as query parameters
    const queryParams = new URLSearchParams({
        search: search
    });

    // Trigger download
    window.location.href = `${APIUrl}/uom/export_csv?${queryParams.toString()}`;
}

function filterUOMReport() {
    paginate.currentPage = 1;
    fetchUOM(); // Fetch Request for the updated current page
}

