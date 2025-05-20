
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<div class="d-flex justify-content-center align-items-center flex-column">
                        <img src="assets/images/users.png" class="no-data-img-table" alt="">
                        <h4 class="text-danger fw-normal">Sales Person list is empty.</a> </h4>
                    </div>`;

    return noCotent;
}

// Global Level Elements
// get table id to store
const containerID = "modal-sales-person-list";
const listContainer = document.getElementById(containerID);
let fetchedSalesPersons;

async function fetchSalesPersonListFromModal(userSearchTerm = null) {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        // Set loader to the screen 
        clientListModalSkeleton(listContainer, salesPersonModalListPaginate.pageLimit || 0);
        const url = `${APIUrl}/users/listofusers`;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: salesPersonModalListPaginate.pageLimit,
                currentPage: salesPersonModalListPaginate.currentPage,
                filters: { STATUS: 'active', 'USER_TYPE': 'admin' },
                search: userSearchTerm
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();
        salesPersonModalListPaginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        salesPersonModalListPaginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showsalesPersons(data.users || [], listContainer);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        listContainer.innerHTML = renderNoResponseCode();
    }
}


function showsalesPersons(salesPersons, listContainer) {

    if (salesPersons?.length > 0) {
        fetchedSalesPersons = salesPersons
        // show products
        let content = ``
        let counter = 0
        salesPersons.forEach((salesPerson, index) => {

            content += `<!--begin::Radio-->
                        <div class="py-0 cursor-pointer" onclick="setSalesPerson(${index})">
                            <label class="form-check-label" for="kt_modal_update_role_option_0">
                                <span class="fw-bold text-primary cursor-pointer">${salesPerson.FIRST_NAME} ${salesPerson.LAST_NAME}</span>
                                <div class="text-gray-600 cursor-pointer">${salesPerson.EMAIL}</div>
                            </label>
                        </div>
                        <div class="separator separator-dashed my-2"></div>
                        `;
        });
        listContainer.innerHTML = content;
    } else {
        // no data available
        listContainer.innerHTML = renderNoResponseCode()
    }
}


// Global scope
// Declare the pagination instance globally
const salesPersonModalListPaginate = new Pagination('spml-current-page', 'spml-total-pages', 'spml-page-of-pages', 'spml-range-of-records');
salesPersonModalListPaginate.pageLimit = 20; // Set your page limit here

// Function to handle pagination button clicks
function handleSalesPersonListPagination(action) {
    salesPersonModalListPaginate.paginate(action); // Update current page based on the action
    fetchSalesPersonListFromModal(); // Fetch products for the updated current page
}

// Modal Related Code
var salesPersonListModal = new bootstrap.Modal(document.getElementById("sales-person-list-modal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

function opensalesPersonListModal() {
    salesPersonListModal.show();
    fetchSalesPersonListFromModal();
}

function filterSalesPersonsFromModalList() {
    salesPersonModalListPaginate.currentPage = 1;
    fetchSalesPersonListFromModal();
}


function searchSalesPersonListFromModal(element) {
    const userSearchTerm = element.value.trim();
    fetchSalesPersonListFromModal(userSearchTerm);
}


// Create a debounced version of the function
const debouncedSearchSalesPersonListFromModal = debounce(searchSalesPersonListFromModal, 300); // 300ms delay