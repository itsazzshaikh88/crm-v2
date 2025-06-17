
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option.colspan}" class="text-center text-danger">
                                    <i class="bi bi-exclamation-circle me-2 text-danger"></i>
                                    No resource found, or try using diffrent filters
                                </td>
                            </tr>`;

    return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "resource-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchResources() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        // Set loader to the screen 
        commonListingSkeleton(tableId, paginate.pageLimit || 0, numberOfHeaders);
        const url = `${APIUrl}/resources/list`;
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
            throw new Error('Failed to fetch resouce data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showResources(data.resources || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}


function showResources(resources, tbody) {
    let content = '';
    if (resources?.length > 0) {
        let counter = 0
        // show resources
        resources.forEach(resource => {
            content += `<tr class="fs-7 text-gray-800">
                                <td>${++counter}</td>
                                <td class="">${resource.RESOURCE_NAME || ''}</td>
                                <td class="">${capitalizeWords(resource.RESOURCE_TYPE || '')}</td>
                                <td class="">${capitalizeWords(resource.MODULE || '')}</td>
                                <td class=""><span class="line-clamp-1">${resource.RESOURCE_DESCRIPTION || ''}</span></td>
                                <td class="">${showResourceStatus(resource.STATUS || '')}</td>
                                <td class="">${formatAppDate(resource.CREATED_DATE || '')}</td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a title="Edit Resource" href="javascript:void(0)" onclick="openResourceModal('edit', ${resource?.ID})">
                                            <small>
                                                <i class="fs-8 fa-regular fa-pen-to-square text-primary"></i>
                                            </small>
                                        </a>
                                        <a title="Delete Resource" href="javascript:void(0)" onclick="deleteResource(${resource?.ID})">
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

function showResourceStatus(status) {
    let color = 'secondary'
    let text = '';
    if (status == 'active') {
        color = 'success';
        text = capitalizeWords(status);
    }
    else if (status == 'inactive') {
        color = 'danger';
        text = capitalizeWords(status);
    }

    return `<span class='badge bg-${color}'>${capitalizeWords(text)}</span>`;
}


// Global scope
// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchResources(); // Fetch resources for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchResources();

});

function filterProducts() {
    paginate.currentPage = 1;
    fetchResources();
}

async function deleteResource(resourceID) {
    if (!resourceID) {
        throw new Error("Invalid Resource ID, Please try Again");
    }
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            throw new Error("Authorization token is missing. Please Login again to make API request.");
        }

        const url = `${APIUrl}/resources/delete/${resourceID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete request
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete resource details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Resource account Deleted Successfully' });
            fetchResources();
        } else {
            throw new Error(data.message || 'Failed to delete resource account details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
    }
}
