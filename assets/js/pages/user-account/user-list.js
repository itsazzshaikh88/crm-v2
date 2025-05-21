
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option.colspan}" class="text-center">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <img src="assets/images/users.png" class="no-data-img-table" alt="">
                                        <h4 class="fw-normal text-danger">
                                            Oops! No users found.
                                        </h4>
                                    </div>
                                </td>
                            </tr>`;

    return noCotent;
}

// get table id to store
const tableId = "user-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;


// Parse the URL
const parsedUrl = new URL(window.location);
const pathName = parsedUrl.pathname;

function setListActions(actionID) {
    if (pathName.includes("users/reset_password")) {
        return `<a href="javascript:void(0);" onclick="openResetPasswordDrawer('${actionID}')">
                    <span class="fw-normal text-gray-700 text-decoration-underline">
                        Change Password
                    </span>
                </a>`;
    }
    else {
        return `<div class="d-flex align-items-center justify-content-end gap-3">
                                       
                                        <a href="javascript:void(0);" onclick="openUserModal('edit','${actionID}')" title="Edit User">
                                            <small>
                                                <i class="fs-8 fa-regular fa-pen-to-square text-gray-700"></i>
                                            </small>
                                        </a>

                                        <a href="javascript:void(0)" onclick="deleteUser(${actionID})" title="Delete User">
                                            <small>
                                                <i class="fs-8 fa-solid fa-trash-can text-danger"></i>
                                            </small>
                                        </a>
                                    </div>`;
    }
}


async function fetchUsers(userSearchTerm = null) {
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
        listingSkeleton(tableId, paginate.pageLimit || 0, 'users');
        const url = `${APIUrl}/users/list`;
        const rawFilters = filterCriterias(['FILTER_USER_TYPE', 'FILTER_STATUS']);

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
                filters: filters,
                search: userSearchTerm
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showUsers(data.users || [], tbody);

    } catch (error) {
        console.error(error);

        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

const userAccountStatusColors = {
    active: "#28a745", // Vibrant green to signify active and operational
    inactive: "#dc3545", // Muted gray to represent inactivity
    suspended: "#ffc107", // Bright yellow to caution suspension
    locked: "#F26B0F", // Bold red to indicate locked status
};

function show2FAStatus(status) {
    let enabled = status == '1' ? 'Yes' : 'No';
    return `<span class='fw-normal fw-bold badge bg-light' style="color: ${enabled == 'Yes' ? "#28a745" : "#dc3545"}">${enabled}</span>`
}

function showUsers(products, tbody) {
    let content = '';
    let counter = 0;

    // let default_img = "assets/images/default-image.png";
    if (products?.length > 0) {
        // show products
        products.forEach(user => {
            content += `<tr data-user-id="${user?.ID}" class="fs-7 text-gray-800">
                                <td class="text-center">${++counter}</td>
                                <td class="">
                                    <div>
                                        <p class="mb-0 fw-bold">${user?.FIRST_NAME || ''} ${user?.LAST_NAME || ''}</p>
                                        <small class="text-gray-600">${user?.USER_ID || ''}</small>
                                    </div>
                                </td>
                                <td class=" "><span class="badge bg-light fw-normal text-gray-700">${capitalizeWords(user?.USER_TYPE)}</span></td>
                                <td class=" text-primary">${user?.EMAIL || 0}</td>
                                <td class="">${user?.PHONE_NUMBER || 0}</td>
                                <td class=""><span class='fw-normal badge text-white' style="background-color: ${userAccountStatusColors[user?.STATUS || '']}">${capitalizeWords(user?.STATUS || '')}</span></td>
                                <td class=" text-center">${show2FAStatus(user?.IS_2FA_ENABLED || '')}</td>
                                <td class="text-end">${setListActions(user?.ID || 0)}</td>
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
    fetchUsers(); // Fetch products for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchUsers();

});

function filterProducts() {
    paginate.currentPage = 1;
    fetchUsers();
}

async function deleteUser(userID) {
    if (!userID) {
        throw new Error("Invalid User ID, Please try Again");
    }

    // Show SweetAlert2 for confirmation
    const confirmed = await Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete User? This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    });

    if (!confirmed.isConfirmed) {
        return; // Exit if not confirmed
    }

    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            throw new Error("Authorization token is missing. Please Login again to make API request.");
        }

        const url = `${APIUrl}/users/delete/${userID}`;

        const response = await fetch(url, {
            method: 'DELETE', // DELETE method for deleting the resource
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete client details');
        }

        if (data.status) {
            // Successfully deleted from the backend
            toasterNotification({ type: 'success', message: 'User account Deleted Successfully' });

            fetchUsers();
        } else {
            throw new Error(data.message || 'Failed to delete user account details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
    }
}


function searchUsersListData(element) {
    const userSearchTerm = element.value.trim();

    fetchUsers(userSearchTerm);
}


// Create a debounced version of the function
const debouncedSearchUsersListData = debounce(searchUsersListData, 300); // 300ms delay


/// export data
function exportUserData(type = null) {

    const search = document.getElementById("searchInputElement").value.trim();



    // Encode filters and search as query parameters
    const queryParams = new URLSearchParams({
        search: search
    });

    // Trigger download
    window.location.href = `${APIUrl}/users/export_csv?${queryParams.toString()}`;
}

function filterUserReport() {
    paginate.currentPage = 1;
    fetchUsers(); // Fetch Request for the updated current page
}