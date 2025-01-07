// // productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let l = `<tr>
                                <td colspan="${option?.colspan}" class="text-center text-danger">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <img src="assets/images/no-data.png" class="no-data-img-table w-80" alt="">
                                        <h4 class="text-danger fw-normal">No Data Available, Create New Quote</h4>
                                    </div>
                                </td>
                            </tr>`;

    return l;
}

// Global Level Elements
// get table id to store
const tableId = "quote-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchRequests() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        listingSkeleton(tableId, paginateList.pageLimit || 0, 'requests');
        const url = `${APIUrl}/quotes/list`;
        const filters = filterCriterias([]);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginateList.pageLimit,
                currentPage: paginateList.currentPage,
                filters: filters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch request data');
        }

        const data = await response.json();
        paginateList.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginateList.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showQuotes(data.quotes || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}


const quoteStatusColors = {
    draft: "#1F509A",
    Pending: "#F59E0B",
    Approved: "#10B981",
    Rejected: "#EF4444",
};




function showQuotes(Quotes, tbody) {
    let content = '';
    let counter = 0;
    // Ensure tbody is cleared before updating
    tbody.innerHTML = '';

    if (Quotes?.length > 0) {
        // show Quotes
        Quotes.forEach(quote => {

            content += `<tr data-quote-id="${quote.QUOTE_ID}" class="text-gray-800">
                            <td>${++counter}</td>        
                            <td>${quote?.QUOTE_NUMBER || ''}</td>        
                            <td>${quote?.COMPANY_NAME || ''}</td>        
                            <td>${quote?.EMPLOYEE_NAME || ''}</td>
                            <td>${quote?.JOB_TITLE || ''}</td>
                            <td>${quote?.EMAIL_ADDRESS || ''}</td>
                            <td>${quote?.SALES_PERSON || ''}</td>       
                            <td class="text-primary">${quote?.REQUEST_NUMBER || ''}</td>        
                            <td>
                                <span class="badge text-white" style="background-color: ${quoteStatusColors[quote?.QUOTE_STATUS || '']}">${quote?.QUOTE_STATUS || ''}</span>
                            </td>
                            <td>${quote?.TOTAL_AMOUNT || ''}</td>       
                            <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-4">
                                        <a href="quotes/view/${quote.UUID}" title="View Quote"> 
                                            <small>
                                                <i class="fs-5 fa-solid fa-file-lines text-success"></i>
                                            </small>
                                        </a>
                                        <a onclick="openNewQuoteModal('edit',${quote.QUOTE_ID})" title="Edit Quote">
                                            <small>
                                                <i class="fs-5 fa-regular fa-pen-to-square text-gray-700"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" title="Delete Quote" onclick="deleteQuote(${quote.QUOTE_ID})">
                                            <small>
                                                <i class="fs-5 fa-solid fa-trash-can text-danger"></i>
                                            </small>
                                        </a>
                                   <a href="javascript:void(0)" title="Convert to New Quote" onclick="convertToQuotation(${quote.QUOTE_ID})">
                                        <small>
                                            <i class="fa-solid fa-up-right-from-square text-info"></i>
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
const paginateList = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginateList.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginateList.paginate(action); // Update current page based on the action
    fetchRequests(); // Fetch Request for the updated current page
    fetchProductsForModalListing();
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchRequests();
});

function filterRequest() {
    paginateList.currentPage = 1;
    fetchRequests();
}

async function fetchCategories() {
    const categoryList = document.getElementById("CATEGORY_ID");

    // Disable the select dropdown and show the loading label with animation
    categoryList.disabled = true;

    // Retrieve the auth_token from cookies
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
        return;
    }

    try {
        // Fetch categories from the API (replace 'your-api-endpoint' with the actual API URL)
        const response = await fetch(`${APIUrl}/categories/list`, {
            method: 'GET', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        // Check if the response is okay (status code 200-299)
        if (!response.ok) {
            throw new Error('Failed to fetch categories');
        }

        // Parse the JSON response
        const categories = await response.json();

        // Clear existing options
        categoryList.innerHTML = '<option value="">Choose Category</option>';

        // Populate the <select> with category options
        categories.forEach(category => {
            const option = document.createElement("option");
            option.value = category.ID; // Adjust to match the category ID key
            option.textContent = category.CATEGORY_CODE; // Adjust to match the category name key
            categoryList.appendChild(option);
        });
    } catch (error) {
        toasterNotification({ type: 'error', message: error });
    } finally {
        // Re-enable the select dropdown and hide the loading label
        categoryList.disabled = false;
    }
}

async function deleteQuote(requestID) {
    if (!requestID) {
        throw new Error("Invalid Request ID, Please try Again");
    }

    try {
        // SweetAlert2 confirmation dialog
        const confirmResult = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });

        if (!confirmResult.isConfirmed) {
            // User canceled the action
            return;
        }

        // Proceed with deletion
        const authToken = getCookie('auth_token');
        if (!authToken) {
            throw new Error("Authorization token is missing. Please Login again to make API request.");
        }

        const url = `${APIUrl}/quotes/delete/${requestID}`;

        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        if (!response.ok) {
            throw new Error(data.error || 'Failed to delete quote details');
        }

        if (data.status) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Quote Deleted Successfully'
            });
            fetchRequests();

        } else {
            throw new Error(data.message || 'Failed to delete quote details');
        }

    } catch (error) {
        // Show error message
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Quote failed: ${error.message}`
        });
    }
}


async function convertToQuotation(requestID) {
    if (!requestID) {
        throw new Error("Invalid Request ID, Please try Again");
    }
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            throw new Error("Authorization token is missing. Please Login again to make API request.");
        }

        const url = `${APIUrl}/quotes/ConvertNewQuote/${requestID}`;

        const response = await fetch(url, {
            method: 'GET', // Change to DELETE for a delete request
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to Convert new Quotation quote details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Converted to new quote' });
            setTimeout(() => window.location.reload(), 1500);

        } else {
            throw new Error(data.message || 'Failed to delete quote details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'quote failed: ' + error.message });
    }
}

// async function fetchProductsForModalListing(query = null) {
//     try {
//         const authToken = getCookie('auth_token');
//         if (!authToken) {
//             toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
//             return;
//         }
//         const prodListContainer = document.getElementById("modal-product-list");
//         // Set loader to the screen 
//         productModalListingSkeleton(prodListContainer, prodListPaginate.pageLimit || 0);

//         const url = `${APIUrl}/products/list`;
//         const filters = filterCriterias(['CATEGORY_ID']);
//         const inputSearchParams = query ?? document.getElementById("searchInput").value.trim()
//         const response = await fetch(url, {
//             method: 'POST',
//             headers: {
//                 'Authorization': `Bearer ${authToken}`,
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({
//                 limit: prodListPaginate.pageLimit,
//                 currentPage: prodListPaginate.currentPage,
//                 filters: filters,
//                 search: { "product": inputSearchParams }
//             })
//         });

//         if (!response.ok) {
//             throw new Error('Failed to fetch product data');
//         }

//         const data = await response.json();
//         prodListPaginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
//         prodListPaginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

//         showProducts(data.products || [], prodListContainer);

//     } catch (error) {
//         toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
//         prodListContainer.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
//     }
// }