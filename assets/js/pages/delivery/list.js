// productListSkeleton("deliveries-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let l = `<tr>
                    <td colspan="${option?.colspan}" class="text-center text-danger"
                                        <h4 class="text-danger fw-normal">Delivery data not found</h4>
                                </td>
                            </tr>`;

    return l;
}

// Global Level Elements
// get table id to store
const tableId = "delivery-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchDeliveries() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        commonListingSkeleton(tableId, paginate.pageLimit || 0, numberOfHeaders);
        const url = `${APIUrl}/deliveries/list`;
        const filters = filterCriterias(['ORG', 'FROM_DATE', 'TO_DATE']);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch request data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showDeliveries(data.deliveries || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}






function showDeliveries(deliveries, tbody) {
    let content = '';
    // Ensure tbody is cleared before updating
    tbody.innerHTML = '';

    if (deliveries?.length > 0) {
        // show deliveries
        let counter = 0;
        deliveries.forEach(invoice => {
            content += `<tr class="fs-8">
                                <td class="text-center">${++counter}</td>
                                <td class="text-primary">${invoice?.DELIVERY_NO || ''}</td>
                                <td>${invoice?.DELIVERY_LINE_ID || ''}</td>
                                <td>${invoice?.SOURCE_NAME || ''}</td>
                                <td>${invoice?.SOC || ''}</td>
                                <td>${invoice?.LINE_NO || ''}</td>
                                <td>${invoice?.ITEM || ''}</td>
                                <td class="truncate-cell" 
                                    tabindex="0" 
                                    onclick="toggleCellExpand(this)" 
                                    onblur="collapseCell(this)">
                                        ${invoice?.ITEM_DESCRIPTION || ''}
                                </td>
                                <td>${invoice?.CUSTOMER_ID || ''}</td>
                                <td>${invoice?.REQUESTED_QUANTITY || ''}</td>
                                <td>${invoice?.SHIPPED_QUANTITY || ''}</td>
                                <td>${invoice?.CUST_PO_NUMBER || ''}</td>
                                <td>${invoice?.PACKING_DETAILS || ''}</td>
                                <td>${invoice?.NUMBER_PACKING || ''}</td>
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
paginate.pageLimit = 100; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchDeliveries(); // Fetch Request for the updated current page
}
function filterDeliveryReport() {
    paginate.currentPage = 1;
    fetchDeliveries(); // Fetch Request for the updated current page
}

document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchDeliveries();
});

function toggleCellExpand(cell) {
    cell.classList.toggle('expanded');
}
function collapseCell(cell) {
    cell.classList.remove('expanded');
}
function expandCell(cell) {
    cell.classList.add('expanded');
    cell.focus(); // Ensure blur can be triggered later
}



