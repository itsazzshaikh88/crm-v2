// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option?.colspan || 10}">
                                    <div class="row w-100 align-items-center justify-content-center">
                                        <div class="col-md-4">
                                            <img src="assets/images/deals.png" class="img-fluid float-animation" alt="">
                                        </div>
                                        <div class="col-md-5 text-center ink-effect">
                                            <h2 class="fs-2x text-slate-500 mb-6">Welcome to Your <span class="text-success text-decoration-underline">Deals</span> Dashboard!</h2>
                                            <h5 class="my-2 fw-bold text-slate-500">Stay Organized, Close Deals Faster</h5>
                                            <p class="my-4 text-muted">
                                                This is where all your deals will live! You can manage your sales pipeline, track progress, and focus on what matters most: growing your business.
                                            </p>
                                            <a href="javascript:void(0)" onclick="openDealModal()" class="btn btn-primary"> <i class="fa fa-plus"></i> Add Your First Deal</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;

    return noCotent;
}

const dealStatusColors = {
    new: '#1E88E5',          // Slightly Darker Blue
    contacted: '#FFC107',    // Rich Amber Yellow
    qualified: '#4CAF50',    // Balanced Green
    'proposal-sent': '#2196F3', // Moderate Sky Blue
    negotiation: '#FB8C00',  // Vibrant Orange
    'closed-won': '#388E3C', // Strong Green (Success)
    'closed-lost': '#E64A19' // Warm Coral (Failure)
};

const dealTypeColors = {
    'new-business': '#00ACC1', // Rich Cyan
    renewal: '#FBC02D',        // Deeper Yellow
    upsell: '#7CB342',         // Stronger Green
    'cross-sell': '#9575CD'    // Muted Purple
};

const dealStageColors = {
    'lead-generation': '#039BE5', // Moderate Blue
    qualification: '#FDD835',     // Bright Yellow
    'proposal-quote': '#0288D1',  // Deeper Sky Blue
    negotiation: '#F57C00',       // Rich Orange
    'closed-won': '#2E7D32',      // Deep Green (Success)
    'closed-lost': '#D84315'      // Dark Coral Orange (Lost)
};

const priorityColors = {
    high: '#E53935',      // Vibrant Red for High Priority
    medium: '#FB8C00',    // Bright Orange for Medium Priority
    low: '#43A047',       // Soothing Green for Low Priority
    none: '#9E9E9E'       // Neutral Gray for No Priority
};


// Global Level Elements
// get table id to store
const tableId = "deal-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchDeals(userSearchTerm = null) {


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
        listingSkeleton(tableId, paginate.pageLimit || 0, 'deals');
        const url = `${APIUrl}/deals/list`;
        const rawFilters = filterCriterias(['FILTER_DEAL_STATUS']);

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
                search: userSearchTerm,
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch deal data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showDeals(data.deals || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

function showDeals(deals, tbody) {
    const dealStatusColors = {
        new: "#6610f2",               // Purple - Represents new beginnings
        contacted: "#0078d7",         // Blue - Contact made, waiting for response
        qualified: "#28a745",         // Green - Qualified and ready to move forward
        "proposal-sent": "#ffc107",   // Yellow - Proposal has been sent
        negotiation: "#17a2b8",       // Teal - Negotiation in progress
        "closed-won": "#198754",      // Dark Green - Deal successfully closed
        "closed-lost": "#dc3545"      // Red - Deal lost
    };


    let content = '';
    let counter = 0;
    if (deals?.length > 0) {
        // show deals
        deals.forEach(deal => {
            content += `<tr data-deal-id="${deal?.DEAL_ID}" class="text-gray-800 fs-7">
                                <td class="min-w-175px">${deal?.DEAL_NAME}</td>
                                <td>${deal?.ASSOCIATED_CONTACT_ID}</td>
                                <td>
                                    <span class="" style="color: ${dealStageColors[deal?.DEAL_STAGE]}">${capitalizeWords(deal?.DEAL_STAGE?.replace("-", " "), true)}</span>
                                </td>
                                <td>
                                    <span class="" style="color: ${dealTypeColors[deal?.DEAL_TYPE]}">${capitalizeWords(deal?.DEAL_TYPE?.replace("-", " "), true)}</span>
                                </td>
                                <td class="">${deal?.DEAL_VALUE}</td>
                                <td class="">${formatAppDate(deal?.EXPECTED_CLOSE_DATE)}</td>
                                <td>
                                    <span class="badge text-white" style="background-color: ${priorityColors[deal?.DEAL_PRIORITY]}">
                                        ${capitalizeWords(deal?.DEAL_PRIORITY)}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light" style="color: ${dealStatusColors[deal?.DEAL_STATUS]}">
                                        ${capitalizeWords(deal?.DEAL_STATUS?.replaceAll("-", " "), true)}
                                        </span>
                                </td>
                                <td>${formatAppDate(deal.CREATED_AT)}</td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a title="Edit Deal" href="javascript:void(0)" onclick="openDealModal('edit', ${deal?.DEAL_ID})">
                                            <small>
                                                <i class="fs-8 fa-regular fa-pen-to-square text-primary"></i>
                                            </small>
                                        </a>
                                        <a title="Delete Deal" href="javascript:void(0)" onclick="deleteDeal(${deal?.DEAL_ID})">
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
    fetchDeals(); // Fetch deals for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchDeals();
});

function filterdeals() {
    paginate.currentPage = 1;
    fetchDeals();
}


async function deleteDeal(dealID) {
    if (!dealID) {
        throw new Error("Invalid deal ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete deal? This action cannot be undone.",
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
            title: "Deleting deal...",
            text: "Please wait while the deal is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/deals/delete/${dealID}`;

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
            throw new Error(data.error || 'Failed to delete deal details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Deal Deleted Successfully' });
            // Logic to remove the current row from the table
            const row = document.querySelector(`#deal-list-tbody tr[data-deal-id="${dealID}"]`);
            if (row) {
                row.remove(); // Remove the row from the table
            }
        } else {
            throw new Error(data.message || 'Failed to delete deal details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}


function searchDealsListData(element) {
    const userSearchTerm = element.value.trim();

    fetchDeals(userSearchTerm);
}

// Create a debounced version of the function
const debouncedSearchDealsListData = debounce(searchDealsListData, 300); // 300ms delay

/// export data
function exportdealsData(type = null) {
    const search = document.getElementById("searchInputElement").value.trim();

    // Encode filters and search as query parameters
    const queryParams = new URLSearchParams({
        search: search
    });

    // Trigger download
    window.location.href = `${APIUrl}/deals/export_csv?${queryParams.toString()}`;
}

function filterDealsReport() {
    paginate.currentPage = 1;
    fetchDeals(); // Fetch Request for the updated current page
}

