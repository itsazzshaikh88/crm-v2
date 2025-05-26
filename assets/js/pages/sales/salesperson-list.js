// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                            <td colspan=${option.colspan} class="text-center text-danger">
                                No data available or try diffrent filter options.
                            </td>
                        </tr > `;

    return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "sales-person-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchSalesPersons(userSearchTerm = null) {

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
        listingSkeleton(tableId, paginate.pageLimit || 0, 'requests');
        const url = `${APIUrl}/sales/salespersons`;
        const filters = filterCriterias();

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
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showSalesPersons(data.salesPersons || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

const salesPersonStatusColors = {
    draft: "#2196F3", // Amber for drafts (attention but not active yet)
    open: "#4CAF50",  // Green for open (active and ongoing)
    closed: "#F44336" // Red for closed (completed or stopped)
};



function showSalesPersons(salesPersons, tbody) {

    let content = '';
    if (salesPersons?.length > 0) {
        // show products
        salesPersons.forEach((salesPerson, index) => {
            content += `<tr data-sales-person-id="${salesPerson?.ID}">
                            <td>${index + 1}</td>
                            <td>${salesPerson?.FIRST_NAME || ''} ${salesPerson?.LAST_NAME || ''}</td>
                            <td>${salesPerson?.EMAIL || ''}</td>
                            <td>${salesPerson?.PHONE || ''}</td>
                            <td>${salesPerson?.DEPARTMENT || ''}</td>
                            <td>${salesPerson?.DESIGNATION || ''}</td>
                            <td>${salesPerson?.DATE_OF_JOINING || ''}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <a href="javascript:void(0)" onclick="openNewSalesPersonForm('edit', ${salesPerson?.ID})">
                                        <small>
                                            <i class="fs-8 fa-solid fa-pen text-primary"></i>
                                        </small>
                                    </a>
                                    <a href="javascript:void(0)" onclick="deleteSalesPerson(${salesPerson?.ID})">
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
    fetchSalesPersons(); // Fetch products for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchSalesPersons();

});


async function deleteSalesPerson(salesPersonID) {
    if (!salesPersonID) {
        throw new Error("Invalid Sales Person ID, Please try Again");
    }

    // Show SweetAlert2 for confirmation
    const confirmed = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
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

        const url = `${APIUrl}/sales/delete_salesperson/${salesPersonID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete request
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete salesPerson details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Sales Person Deleted Successfully' });
            fetchSalesPersons();
        } else {
            throw new Error(data.message || 'Failed to delete salesPerson details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
    }
}


function searchSalesPersonListData(element) {
    const userSearchTerm = element.value.trim();

    fetchSalesPersons(userSearchTerm);
}


// Create a debounced version of the function
const debouncedSearchSalesPersonListData = debounce(searchSalesPersonListData, 300); // 300ms delay


/// export data
function exportsalesPersonData(type = null) {
    const search = document.getElementById("searchInputElement").value.trim();



    // Encode filters and search as query parameters
    const queryParams = new URLSearchParams({
        search: search
    });

    // Trigger download
    window.location.href = `${APIUrl}/sales/sales_person_export_csv?${queryParams.toString()}`;
}

function filterSalesPersonReport() {
    paginate.currentPage = 1;
    fetchSalesPersons(); // Fetch Request for the updated current page
}