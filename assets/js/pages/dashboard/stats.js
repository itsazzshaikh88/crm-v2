
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

// get table id to store
const tableId = "open-orders-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead tr th`).length || 0;
const openOrderTrackingPaginate = new Pagination('oo-current-page', 'oo-total-pages', 'oo-page-of-pages', 'oo-range-of-records');
openOrderTrackingPaginate.pageLimit = 10; // Set your page limit here

async function fetcOpenOrders() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        listingSkeleton(tableId, openOrderTrackingPaginate.pageLimit || 0, 'open-order-list-tracking');
        return;
        const url = `${APIUrl}/requests/list`;
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

        showRequests(data.requests || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchCardStats();
    // fetcOpenOrders();
});