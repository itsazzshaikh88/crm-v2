

function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option?.colspan}" class="text-center">
                                    <p class="mt-4 mb-0">No Purchase Orders Found</p>
                                </td>
                            </tr>`;

    return noCotent;
}

// get table id to store
const tableId = "open-orders-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

// Teracker container
const trackIdleContainer = document.getElementById("track-idle-container");
const trackProcessingContainer = document.getElementById("track-processing-container");
const deliveryCardImage = document.getElementById("card-delivery-img");

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
        const filters = filterCriterias(['ORG_ID']);

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
								<td>${po?.CLIENT_PO}</td>
								<td>${po?.CUSTOMER}</td>
								<td>${po?.PRODUCT}</td>
								<td>${po?.ORD_QTY}</td>
								<td>${po?.SHIP_QTY}</td>
								<td>${po?.BAL_QTY}</td>
                                <td>
                                    <button class="btn btn-sm btn-secondary p-0 px-4 py-1" type="button" onclick="trackPODetails(this, '${po?.CLIENT_PO}','${po?.PRODUCT}')"> <i class="fa-solid fa-location-arrow"></i> Track</button>
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

function loadOrdersToTrack() {
    openOrderTrackingPaginate.currentPage = 1;
    fetchOpenOrders();
}


document.addEventListener('DOMContentLoaded', () => {
    fetchOpenOrders();
});

function handlePagination(action) {
    openOrderTrackingPaginate.paginate(action); // Update current page based on the action
    fetchOpenOrders(); // Fetch products for the updated current page
}

// Tracker 

async function trackPODetails(btnElement, po, product) {

    const apiUrl = `${APIUrl}/purchase/po_track_detail?po=${po}&product=${product}`;
    const authToken = getCookie('auth_token');

    trackIdleContainer.classList.remove("d-none");
    trackProcessingContainer.classList.add("d-none");

    if (!authToken) {
        toasterNotification({
            type: 'error',
            message: "Authorization token is missing. Please login again to make an API request."
        });
        return;
    }
    const defaultDelImage = "assets/images/track-order.png";
    const delLoadingImage = "assets/images/del-loading.gif";
    const btnDefaultText = '<i class="fa-solid fa-location-arrow"></i> Track';
    try {

        // Set loading animation
        // disable button and show loading animaion to card 
        btnElement.disabled = true;
        btnElement.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Wait`;
        deliveryCardImage.src = delLoadingImage;

        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`
            },
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        // show tracker records
        showTracker(data?.data || {});

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
        console.error(error);

    } finally {
        btnElement.disabled = false;
        deliveryCardImage.src = defaultDelImage;
        btnElement.innerHTML = btnDefaultText;
    }
}


function showTracker(details) {
    trackIdleContainer.classList.add("d-none");
    trackProcessingContainer.classList.remove("d-none");
    if (details) {
        // Show details of tracker
        trackProcessingContainer.innerHTML = `
            <div class="table-responsive my-3 border-bottom">
                <table class="table table-striped table-sm table-borderless small mb-0">
                    <tbody>
                        <tr>
                            <th scope="row" class="px-2 px-1 mb-0">PO Number:</th>
                            <td class="px-2 px-1 mb-0">${details?.CLIENT_PO || ''}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="px-2 px-1 mb-0">Product:</th>
                            <td class="px-2 px-1 mb-0">${details?.PRODUCT || ''}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="px-2 px-1 mb-0">Client:</th>
                            <td class="px-2 px-1 mb-0">${details?.CUSTOMER || ''}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="timeline timeline-border-dashed mt-5">
                <div class="timeline-item pb-5 mb-2">
                    <div class="timeline-line"></div>
                    <div class="timeline-icon">
                        <i class="msm-4 fas fa-file-alt fs-6 text-primary"><span class="path1"></span><span
                                class="path2"></span></i>
                    </div>
                    <div class="timeline-content m-0">
                        <span class="fs-8 fw-bolder text-primary text-uppercase badge bg-light mb-2">${details?.CRM_PO_STATUS || ""}</span>
                        <a href="#" class="fs-7 text-gray-800 fw-normal d-block text-hover-primary">Purchase Order</a>
                    </div>
                </div>
                <div class="timeline-item pb-5 mb-2">
                    <div class="timeline-line"></div>
                    <div class="timeline-icon">
                        <i class="msm-4 fas fa-file-signature fs-6 text-danger"><span class="path1"></span><span
                                class="path2"></span></i>
                    </div>
                    <div class="timeline-content m-0">
                        <span class="fs-8 fw-bolder text-danger text-uppercase badge bg-light mb-2">${details?.SOC_STATUS || ""}</span>
                        <a href="#" class="fs-7 text-gray-800 fw-normal d-block text-hover-primary">Sales Order</a>
                    </div>
                </div>
                <div class="timeline-item pb-5 mb-2">
                    <div class="timeline-line"></div>
                    <div class="timeline-icon">
                        <i class="msm-4 fas fa-truck fs-6 text-info"><span class="path1"></span><span
                                class="path2"></span></i>
                    </div>
                    <div class="timeline-content m-0">
                        <span class="fs-8 fw-bolder text-info text-uppercase badge bg-light mb-2">${details?.DEL || ""}</span>
                        <a href="#" class="fs-7 text-gray-800 fw-normal d-block text-hover-primary">Delivery</a>
                    </div>
                </div>
            </div>
        `;
    } else {
        // Show no details are here
    }

}