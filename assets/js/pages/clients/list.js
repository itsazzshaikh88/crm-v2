
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option.colspan}" class="text-center text-danger">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <img src="assets/images/users.png" class="no-data-img-table" alt="">
                                        <h4 class="text-danger">Client details not found. <a href="clients/new">Click to add new client</a> </h4>
                                    </div>
                                </td>
                            </tr>`;

    return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "client-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchClients() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        // Set loader to the screen 
        listingSkeleton(tableId, paginate.pageLimit || 0, 'clients');
        const url = `${APIUrl}/clients/list`;
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
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showClients(data.clients || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}


function showClients(products, tbody) {
    let content = '';
    let default_img = "assets/images/default-image.png";
    if (products?.length > 0) {
        let counter = 0
        // show products
        products.forEach(client => {
            content += `<tr class="fs-7 text-gray-800">
                                <td>${++counter}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex justify-content-start flex-column">
                                            <p class="fw-bold text-hover-primary mb-0">${client.FIRST_NAME} ${client.LAST_NAME}</p>
                                            <span class="text-muted fw-semibold d-block fs-9">${client.USER_ID}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="pe-0 dt-type-numeric">${client.COMPANY_NAME || ''}</td>
                                <td class="pe-0 dt-type-numeric text-primary">${client.EMAIL}</td>
                                <td class="pe-0 dt-type-numeric">${client.PHONE_NUMBER}</td>
                                <td class="pe-0 dt-type-numeric">${client.CREDIT_LIMIT || 0}</td>
                                <td class="pe-0 dt-type-numeric">${client.ORDER_LIMIT || 0}</td>
                                <td class="pe-0 dt-type-numeric">${client.COUNTRY || ''}</td>
                                <td class="pe-0 dt-type-numeric">${client.TAXES || '0'}%</td>
                                <td class="pe-0 dt-type-numeric">${showClientStatus(client.STATUS || '')}</td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a href="clients/view/${client.UUID}">
                                            <small>
                                                <i class="fs-8 fa-solid fa-file-lines text-success"></i>
                                            </small>
                                        </a>
                                        <a href="clients/new/${client.UUID}?action=edit">
                                            <small>
                                                <i class="fs-8 fa-regular fa-pen-to-square text-gray-700"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" onclick="deleteClient(${client.ID})">
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

function showClientStatus(status) {
    let color = 'primary'
    if (status === 'active')
        color = 'success';
    else if (status === 'inactive')
        color = 'danger';
    else if (status === 'suspended')
        color = 'warning';
    else if (status === 'locked')
        color = 'secondary';

    return `<span class='badge bg-${color}'>${capitalizeWords(status)}</span>`;
}


// Global scope
// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchClients(); // Fetch products for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchClients();

});

function filterProducts() {
    paginate.currentPage = 1;
    fetchClients();
}

async function deleteClient(clientID) {
    if (!clientID) {
        throw new Error("Invalid Client ID, Please try Again");
    }
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            throw new Error("Authorization token is missing. Please Login again to make API request.");
        }

        const url = `${APIUrl}/clients/delete/${clientID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete request
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
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Client account Deleted Successfully' });
            fetchClients();
        } else {
            throw new Error(data.message || 'Failed to delete client account details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
    }
}
