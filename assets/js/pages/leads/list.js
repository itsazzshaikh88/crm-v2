// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option?.colspan}">
                                    <div class="d-flex align-items-center justify-content-center gap-5 mx-auto ">
                                        <div>
                                            <img src="assets/images/empty-folder.png" class="w-350" alt="">
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center gap-5 flex-column">
                                            <h1 class="fs-2x fw-bolder text-danger">No Leads Found!</h1>
                                            <p class="fs-4">Oops! Looks like you don't have any leads yet. <br> Start building your pipeline by creating a <span class="text-primary">new lead</span>!</p>
                                            <div class="d-flex align-items-center justify-content-center gap-5 ">
                                                <button  onclick="openLeadModal()" type="button" class="btn btn-success"><i class="fa-solid fa-wand-magic-sparkles"></i> Create New Lead</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;

    return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "lead-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchLeads() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        listingSkeleton(tableId, paginate.pageLimit || 0, 'leads');
        const url = `${APIUrl}/leads/list`;
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
            throw new Error('Failed to fetch lead data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showLeads(data.leads || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

function showLeads(leads, tbody) {
    const leadStatusColors = {
        new: "#6610f2",           // Blue - Represents new beginnings
        contacted: "#0078d7",     // Gray - Neutral, awaiting response
        engaged: "#17a2b8",       // Teal - Active engagement
        qualified: "#28a745",     // Green - Qualified and ready to move forward
        disqualified: "#dc3545",  // Red - Disqualified or not suitable
    };

    let content = '';
    let counter = 0;
    if (leads?.length > 0) {
        // show leads
        leads.forEach(lead => {
            content += `<tr data-lead-id="${lead?.LEAD_ID}">
                                <td class="text-center">${++counter}</td>
                                <td>
                                    <p class="mb-0 text-primary">${lead?.FIRST_NAME} ${lead?.LAST_NAME}</p>
                                    <small class="fs-xs text-muted">${lead?.LEAD_NUMBER}</small>
                                </td>
                                <td>
                                    <p class="mb-0 fw-bold">${lead?.COMPANY_NAME}</p>
                                </td>
                                <td>${lead?.JOB_TITLE}</td>
                                <td>
                                    <p class="mb-0">${lead?.EMAIL}</p>
                                    <p class="mb-0"><small>${lead?.PHONE}</small></p>
                                </td>
                                <td>${formatAppDate(lead?.CREATED_AT)}</td>
                                <td>
                                    <p class="mb-0 badge bg-light text-info"><small>${lead?.LEAD_SOURCE}</small></p>
                                </td>
                                <td>
                                    <span class="badge text-white" style="background-color: ${leadStatusColors[lead?.STATUS]}">${capitalizeWords(lead?.STATUS)}</span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-4">
                                        <a href="javascript:void(0)" onclick="openLeadModal('view', ${lead?.LEAD_ID})">
                                            <small>
                                                <i class="fs-5 fa-solid fa-file-lines text-info"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" onclick="openLeadModal('edit', ${lead?.LEAD_ID})">
                                            <small>
                                                <i class="fs-5 fa-regular fa-pen-to-square text-gray-700"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" onclick="deleteLead(${lead?.LEAD_ID})">
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
    fetchLeads(); // Fetch leads for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchLeads();
});

function filterLeads() {
    paginate.currentPage = 1;
    fetchLeads();
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

async function deleteLead(leadID) {
    if (!leadID) {
        throw new Error("Invalid Lead ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete lead? This action cannot be undone.",
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
            title: "Deleting Lead...",
            text: "Please wait while the lead is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/leads/delete/${leadID}`;

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
            throw new Error(data.error || 'Failed to delete lead details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Lead Deleted Successfully' });
            // Logic to remove the current row from the table
            const row = document.querySelector(`#lead-list-tbody tr[data-lead-id="${leadID}"]`);
            if (row) {
                row.remove(); // Remove the row from the table
            }
        } else {
            throw new Error(data.message || 'Failed to delete lead details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}
