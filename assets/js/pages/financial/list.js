
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option?.colspan}" class="text-center text-danger">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <img src="assets/images/no-data.png" class="no-data-img-table w-80" alt="">
                                        <h4 class="text-danger fw-normal">credit data not found</h4>
                                    </div>
                                </td>
                            </tr>`;

    return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "credit-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);
let fullPageLoader = document.getElementById("full-page-loader");
const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchCredits() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API credit." });
            return;
        }
        const userId = "<?= $user_id ?>"; // Pass from PHP
        const userType = "<?= $usertype ?>"; // Pass from PHP

        // Set loader to the screen 
        listingSkeleton(tableId, paginate.pageLimit || 0, 'credit');
        const url = `${APIUrl}/financial/credit_application_list`;
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
            throw new Error('Failed to fetch credit data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showCredits(data.credits || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'credit failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

function showCredits(credits, tbody) {
    let content = '';
    let counter = 0;
    if (credits?.length > 0) {
        // show credits
        credits.forEach(credit => {
            content += `<tr data-credit-id="${credit.HEADER_ID}" class="fs-7 text-gray-800">
                                <td class="text-center">${++counter}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="">
                                            <p class="fw-bold mb-0 line-clamp-1">${credit?.APPLICANT_COMMENT || ''}</p>
                                            <small class="text-primary">${credit?.APPLICATION_NUMBER || ''}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div class="">
                                            <!--begin::Title-->
                                            <p class="mb-0">${credit?.COMPANY_NAME || ''}</p> 
                                            <p class="text-muted fw-normal line-clamp-1">${credit?.FIRST_NAME || ''} ${credit?.LAST_NAME || ''}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>${credit?.PHONE || ''}</td>
                                <td>
                                    ${credit?.EMAIL || ''}
                                </td>
                                <td>${formatAppDate(credit?.CREATED_AT || '') ?? ''}</td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                      <a href="financial/view/${credit.UUID}">
                                            <small>
                                                <i class="fs-8 fa-solid fa-file-lines text-success"></i>
                                            </small>
                                        </a>
                                        <a href="financial/credit_application/${credit.UUID}?action=edit">
                                            <small>
                                                <i class="fs-8 fa-regular fa-pen-to-square text-gray-700"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" onclick="deletecredit(${credit.HEADER_ID})">
                                            <small>
                                                <i class="fs-8 fa-solid fa-trash-can text-danger"></i>
                                            </small>
                                        </a>
                                           <a href="javascript:void(0)" onclick="printApplication(${credit.HEADER_ID}, '${credit.UUID}', 'credit_application')">
                                            <small>
                                                <i class="fs-8 fa-solid fa-print text-primary"></i>
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
    fetchCredits(); // Fetch credit for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchCredits();
});

function filterRequest() {
    paginate.currentPage = 1;
    fetchCredits();
}


async function deletecredit(creditID) {
    if (!creditID) {
        throw new Error("Invalid credit ID, Please try Again");
    }
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            throw new Error("Authorization token is missing. Please Login again to make API credit.");
        }

        const url = `${APIUrl}/financial/delete/${creditID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete credit
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete credit details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'credit Deleted Successfully' });
            // Logic to remove the current row from the table
            const row = document.querySelector(`#credit-list-tbody tr[data-credit-id="${creditID}"]`);
            if (row) {
                row.remove(); // Remove the row from the table
            }
        } else {
            throw new Error(data.message || 'Failed to delete credit details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'credit failed: ' + error.message });
    }
}



