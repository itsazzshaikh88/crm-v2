// productListSkeleton("invoices-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let l = `<tr>
                    <td colspan="${option?.colspan}" class="text-center text-danger"
                                        <h4 class="text-danger fw-normal">Invoice data not found</h4>
                                </td>
                            </tr>`;

    return l;
}

// Global Level Elements
// get table id to store
const tableId = "invoices-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchInvoices() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        commonListingSkeleton(tableId, paginate.pageLimit || 0, numberOfHeaders);
        const url = `${APIUrl}/invoices/list`;
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
            throw new Error('Failed to fetch request data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showInvoices(data.invoices || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}






function showInvoices(Invoices, tbody) {
    let content = '';
    // Ensure tbody is cleared before updating
    tbody.innerHTML = '';

    if (Invoices?.length > 0) {
        // show Invoices
        let counter = 0;
        Invoices.forEach(invoice => {
            const invoice_link = `http://10.10.2.232/einvoice/invoices_pdf/${invoice?.PDF_PATH}`;
            content += `<tr class="fs-8">
                                <td>${++counter}</td>
                                <td class="text-primary">${invoice?.INVOICE_NUMBER}</td>
                                <td>${invoice?.INVOICE_DATE}</td>
                                <td>${invoice?.CUSTOMER_REGISTRATION_NAME}</td>
                                <td><span class="badge bg-light text-black fw-normal">${invoice?.INVOICE_TYPECODE} - ${invoice?.INVOICE_SUB_TYPECODE}</span></td>
                                <td>${invoice?.TOTAL_TAX_AMOUNT}</td>
                                <td>${invoice?.TAX_INCLUSIVE_AMOUNT}</td>
                                <td>
                                    <a href="${invoice_link}" target="_blank">View PDF</a>
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
paginate.pageLimit = 100; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchInvoices(); // Fetch Request for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchInvoices();
});







