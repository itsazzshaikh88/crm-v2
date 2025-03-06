
async function fetchCardStats() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        const statsLabels = document.querySelectorAll(".card-stats-label-preview");
        if (statsLabels && statsLabels.length > 0) {
            statsLabels.forEach((lbl) => lbl.innerHTML = dashboardStatsSkeleton())
        }

        const url = `${APIUrl}/stats/dashboard`;

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch data');
        }

        const data = await response.json();

        showDashboardStats(data.stats || []);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        showDashboardStats();
    }
}

function showDashboardStats(stats = {}) {
    const statsElements = document.querySelectorAll(".card-stats-label-preview");
    statsElements.forEach((element) => {
        const id = element.getAttribute("id");
        element.innerHTML = stats?.[id] || 0;
    });
}

function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option?.colspan}" class="text-center">
                                    <p class="mt-4 mb-0">No Open Purchase Orders Found</p>
                                </td>
                            </tr>`;

    return noCotent;
}

// get table id to store
const tableId = "open-orders-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead tr th`).length || 0;
const openOrderTrackingPaginate = new Pagination('oo-current-page', 'oo-total-pages', 'oo-page-of-pages', 'oo-range-of-records');
openOrderTrackingPaginate.pageLimit = 10; // Set your page limit here

async function fetchOpenOrders() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        listingSkeleton(tableId, openOrderTrackingPaginate.pageLimit || 0, 'open-order-list-tracking');

        const url = `${APIUrl}/purchase/open_orders`;
        const filters = filterCriterias([]);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: openOrderTrackingPaginate.pageLimit,
                currentPage: openOrderTrackingPaginate.currentPage,
                filters: filters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch request data');
        }

        const data = await response.json();
        openOrderTrackingPaginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        openOrderTrackingPaginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        renderOpenOrders(data.open_pos || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

function renderOpenOrders(pos, tbody) {
    if (!pos) {
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
        return;
    } else {
        let content = '';
        if (pos.length > 0) {
            pos.forEach((po) => {
                content += `<tr class="text-gray-800 fs-7">
								<td>${po?.PO_NUMBER}</td>
								<td>${po?.COMPANY_ADDRESS}</td>
								<td>${po?.TOTAL_AMOUNT}</td>
								<td><small class="badge bg-light text-black fw-normal border">${po?.PO_STATUS}</small></td>
								<td>${formatAppDate(po?.CREATED_AT)}</td>
                                <td>
                                    <button class="btn btn-sm btn-secondary p-0 px-4 py-1"> <i class="fa-solid fa-location-arrow"></i> Track</button>
                                </td>
							</tr>`;
            });
            tbody.innerHTML = content;
        } else {
            tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
            return;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchCardStats();
    fetchOpenOrders();
});