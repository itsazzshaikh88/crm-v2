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
const tableId = "category-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchCategories(userSearchTerm = null) {

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
        const url = `${APIUrl}/categories/list`;
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

        showCategories(data.categories || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

function showCategories(categories, tbody) {
    let content = '';
    let counter = 0;
    if (categories?.length > 0) {
        // show categories
        categories.forEach(category => {
            content += `<tr data-category-id="${category?.ID}" class="text-gray-800 fs-7">
                                <td class="text-center">${++counter}</td>
                                <td>${category?.CATEGORY_CODE}</td>
                                <td>${category?.CATEGORY_NAME}</td>
                                <td><p class="line-clamp-1">${category?.DESCRIPTION || ''}</p></td>                                
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a href="javascript:void(0)" title="Edit Record" onclick="fetchCategoryDetailsToEdit(${category?.ID})">
                                            <small>
                                                <i class="fs-8 fa-regular fa-pen-to-square text-gray-700"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" title="Delete Record" onclick="deleteCategory(${category?.ID})">
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
    fetchCategories(); // Fetch leads for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchCategories();
});




async function deleteCategory(categoryID) {
    if (!categoryID) {
        throw new Error("Invalid Category ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete Category? This action cannot be undone.",
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
            title: "Deleting Category...",
            text: "Please wait while the Category is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/categories/delete/${categoryID}`;

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
            throw new Error(data.error || 'Failed to delete Category details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Category Deleted Successfully' });
            fetchCategories();
        } else {
            throw new Error(data.message || 'Failed to delete Category details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}


function searchCategoryListData(element) {
    const userSearchTerm = element.value.trim();

    fetchCategories(userSearchTerm);
}


// Create a debounced version of the function
const debouncedSearchCategoryListData = debounce(searchCategoryListData, 300); // 300ms delay


/// export data
function exportCategoryData(type = null) {

    const search = document.getElementById("searchInputElement").value.trim();


    // Encode filters and search as query parameters
    const queryParams = new URLSearchParams({
        search: search
    });

    // Trigger download
    window.location.href = `${APIUrl}/categories/export_csv?${queryParams.toString()}`;
}

function filterCategoryReport() {
    paginate.currentPage = 1;
    fetchCategories(); // Fetch Request for the updated current page
}
