let selectedFiles = [];
let uploadedFiles = [];
let selectedProductElementIndex = null
const fullPageLoader = document.getElementById("full-page-loader")
// Function to add a new row
function addRow() {
    const tableBody = document.querySelector('#request-lines-table tbody');
    const rowCount = tableBody.rows.length + 1;

    // Create a new row
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="PRODUCT_ID[]" id="PRODUCT_ID_${rowCount}" class="form-control" onclick="chooseProduct(${rowCount})">
                <option value="">Choose</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control" name="PRODUCT_DESC[]" id="PRODUCT_DESC_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="QUANTITY[]" id="QUANTITY_${rowCount}">
        </td>
        <td>
            <input type="date" class="form-control" name="REQUIRED_DATE[]" id="REQUIRED_DATE_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="COLOR[]" id="COLOR_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="TRANSPORTATION[]" id="TRANSPORTATION_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="COMMENTS[]" id="COMMENTS_${rowCount}">
        </td>
        <td>
            <button class="btn btn-sm border border-danger" type="button" onclick="removeRow(this)">
                <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
            </button>
        </td>
    `;

    tableBody.appendChild(row);
}

// Function to remove a specific row
function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
}

// Handle file selection
function handleFileSelect(event) {
    const files = Array.from(event.target.files);
    files.forEach(file => {
        // Check if file already selected
        if (!selectedFiles.some(f => f.name === file.name)) {
            selectedFiles.push(file);
            displayFiles();
        }
    });
}

// Display selected files with a remove button
function displayFiles() {
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = ''; // Clear current list
    selectedFiles.forEach((file, index) => {
        let content = '';
        content += `<div class="relative d-flex align-items-center justify-content-between gap-8 bg-light rounded px-4 py-2 cursor-pointer position-relative">
                        <p class="mb-0">${file.name}</p>
                        <span class="text-white" onclick="removeFile(${index})"><i class="fa-solid fa-x text-danger"></i></span>
                        <div class="position-absolute top-0 start-0 translate-middle">
                            <div class="bg-primary rounded-circle" style="width: 5px; height: 5px;"></div>
                        </div>
                    </div>`;

        // Append the content as HTML to the fileList element
        fileList.insertAdjacentHTML('beforeend', content);
    });

}
// Display selected files with a remove button
function displayUploadedFiles(productID) {
    const fileList = document.getElementById('file-list-uploaded');
    fileList.innerHTML = ''; // Clear current list
    uploadedFiles.forEach((filename, index) => {
        let content = '';
        content += `<div class="relative d-flex align-items-center justify-content-between gap-8 bg-light rounded px-4 py-2 cursor-pointer position-relative">
                        <p class="mb-0">${filename}</p>
                        <span class="text-white" onclick="deleteFileFromServer('${uploadedFiles}', ${productID})"><i class="fa-solid fa-x text-danger"></i></span>
                    </div>`;

        // Append the content as HTML to the fileList element
        fileList.insertAdjacentHTML('beforeend', content);
    });

}

// Remove a file from the list
function removeFile(index) {
    selectedFiles.splice(index, 1);
    displayFiles();
}

// Function to submit form
// Function to send a request with Bearer token and display response
async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    // Attach selected files
    selectedFiles.forEach(file => {
        formData.append('files[]', file);
    });

    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Saving Request ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const requestID = document.getElementById("ID").value;
        let url = `${APIUrl}/requests/new`;
        if (requestID)
            url += `/${requestID}`
        // Fetch API with Bearer token in Authorization header
        const response = await fetch(url, {
            method: 'POST', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`
            },
            body: formData
        });
        // Check if the response is OK (status 200-299)
        if (response.ok) {
            const data = await response.json();
            toasterNotification({ type: 'success', message: "Request Saved Successfully!" });
            window.location.reload()
            removeClientName()
            selectedFiles = [];
            document.getElementById('file-list').innerHTML = ''
        } else {
            const errorData = await response.json();
            if (errorData.status === 422) {
                showErrors(errorData.validation_errors ?? []);
            } else {
                toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
            }
        }
    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed:' + error });
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = buttonText;
    }
}

// Product Modal List -------------------------------------------------
// Modal Related Code
var productListModal = new bootstrap.Modal(document.getElementById("product-list-modal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});
// Declare the pagination instance globally
const prodListPaginate = new Pagination('prd-mdl-current-page', 'prd-mdl-total-pages', 'prd-mdl-page-of-pages', 'prd-mdl-range-of-records');
prodListPaginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    prodListPaginate.paginate(action); // Update current page based on the action
    fetchProductsForModalListing(); // Fetch products for the updated current page
}

// Chooose your prodict in lines
function chooseProduct(index) {
    selectedProductElementIndex = index
    const element = document.getElementById(`PRODUCT_ID_${index}`)
    const descElement = document.getElementById(`PRODUCT_DESC_${index}`)
    if (typeof element != undefined) {
        // remove other elements from select
        element.innerHTML = '';
        descElement.value = '';
        productListModal.show()
        fetchProductsForModalListing();
    }

}

function renderNoResponseCode() {
    return `Products Not Available`
}

async function fetchProductsForModalListing(query = null) {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const prodListContainer = document.getElementById("modal-product-list");
        // Set loader to the screen 
        productModalListingSkeleton(prodListContainer, prodListPaginate.pageLimit || 0);

        const url = `${APIUrl}/products/list`;
        const filters = filterCriterias(['CATEGORY_ID']);
        const inputSearchParams = query ?? document.getElementById("searchInput").value.trim()
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: prodListPaginate.pageLimit,
                currentPage: prodListPaginate.currentPage,
                filters: filters,
                search: { "product": inputSearchParams }
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();
        prodListPaginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        prodListPaginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showProducts(data.products || [], prodListContainer);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        prodListContainer.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}
function setProductToRequestLine(productID, productName, productDesc) {

    const productCodeElement = document.getElementById(`PRODUCT_ID_${selectedProductElementIndex}`)
    const productDescElement = document.getElementById(`PRODUCT_DESC_${selectedProductElementIndex}`)

    let option = `<option value="${productID}">${productName}</option>`
    productCodeElement.innerHTML = option;
    productCodeElement.value = productID

    productDescElement.value = productDesc

    // close modal window
    productListModal.hide()
}

function showProducts(products, prodListContainer) {

    let content = '';
    let default_img = "assets/images/default-image.png";
    let counter = 0;
    if (products?.length > 0) {
        // show products
        products.forEach(product => {
            let desc = stripHtmlTags(product?.DESCRIPTION || '');
            desc = desc != 'null' ? desc : ''
            let img = parseJsonString(product.PRODUCT_IMAGES || '', 0);
            if (img != null)
                img = `${PRODUCT_IMAGES_URL}${img}`;
            content += `<!--begin::Radio-->
                        <div class="form-check form-check-custom form-check-solid" onclick="setProductToRequestLine(${product.PRODUCT_ID}, '${escapeSpecialCharacters(product.PRODUCT_NAME)}', '${escapeSpecialCharacters(desc)}')">

                            <!--begin::Label-->
                            <label class="form-check-label d-flex align-items-center justify-content-start" for="kt_modal_update_role_option_${counter}">
                                <div style="height: 40px; width: 40px;">
                                    <img src="${img ?? default_img}" alt="" style="object-fit: cover; height: 40px; width: 40px;">
                                </div>
                                <div class="ms-4">
                                    <div class="fw-bold text-primary line-clamp-1">${product.PRODUCT_NAME}</div>
                                    <div class="text-gray-600 line-clamp-1">${desc}</div>
                                </div>
                            </label>
                            <!--end::Label-->
                        </div>
                        <!--end::Radio-->
                        <div class="separator separator-dashed my-4"></div>`;
        });
        prodListContainer.innerHTML = content;
    } else {
        // no data available
        prodListContainer.innerHTML = renderNoResponseCode()
    }
}

function filterProducts() {
    prodListPaginate.currentPage = 1;
    fetchProductsForModalListing();
}



document.addEventListener('DOMContentLoaded', () => {

    fetchCategories()

    const url = new URL(window.location.href);
    // Get all search parameters
    const searchParams = new URLSearchParams(url.search);
    // Get all URL segments
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const requestUUID = urlSegments[urlSegments.length - 1];
    // Fetch product details if action is edit and id is available
    if (searchParams.get('action') === 'edit') {
        // Your code to fetch product details
        fetchRequest(requestUUID);
    }
});

async function fetchRequest(requestUUID) {
    const apiUrl = `${APIUrl}/requests/detail`;
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({
            type: 'error',
            message: "Authorization token is missing. Please login again to make an API request."
        });
        return;
    }

    try {

        fullPageLoader.classList.toggle("d-none");
        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ requestUUID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        // Display the product information on the page if response is successful
        displayRequestInfo(data.data);

        showClientDetails(data?.data?.header);

        // Show request Number
        document.getElementById("REQUEST_NUMBER").innerHTML = data?.data?.header?.REQUEST_NUMBER || "REQ-00000000"

        // Show uploaded files
        // Show Product Files attached
        if (data?.data?.header?.ATTACHMENTS) {
            uploadedFiles = JSON.parse(data?.data?.header?.ATTACHMENTS) || []
            displayUploadedFiles(data?.data?.header?.ID || 0);
        }

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function displayRequestInfo(data) {
    if (!data) return;
    const { header, lines } = data;

    if (Object.keys(header).length > 0) {
        populateFormFields(header);
    }

    if (lines && Object.keys(lines).length > 0) {
        showRequestLines(lines);
    }
}

function showRequestLines(lines) {
    const tableBody = document.querySelector('#request-lines-table tbody');
    tableBody.innerHTML = ''
    let rowCount = 0;
    lines.forEach((line) => {
        let desc = stripHtmlTags(line?.DESCRIPTION || '');
        // Create a new row
        const row = document.createElement('tr');
        row.innerHTML = `<td>
                                <select name="PRODUCT_ID[]" id="PRODUCT_ID_${++rowCount}" class="form-control" onclick="chooseProduct(${rowCount})">
                                    <option selected value="${line.PRODUCT_ID}">${line.PRODUCT_NAME}</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="PRODUCT_DESC[]" id="PRODUCT_DESC_${rowCount}" value="${escapeSpecialCharacters(desc)}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="QUANTITY[]" id="QUANTITY_${rowCount}" value="${line.QUANTITY}">
                            </td>
                            <td>
                                <input type="date" class="form-control" name="REQUIRED_DATE[]" id="REQUIRED_DATE_${rowCount}" value="${line.REQUIRED_DATE}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="COLOR[]" id="COLOR_${rowCount}" value="${line.COLOR}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="TRANSPORTATION[]" id="TRANSPORTATION_${rowCount}" value="${line.TRANSPORTATION}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="COMMENTS[]" id="COMMENTS_${rowCount}" value="${line.COMMENTS}">
                            </td>
                            <td>
                                <button class="btn btn-sm border border-danger" type="button" onclick="removeRow(this)">
                                    <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
                                </button>
                            </td>
                        `;

        tableBody.appendChild(row);
    })

}

function showClientDetails(header) {
    clientID.value = header?.CLIENT_ID || 0
    clientName.innerHTML = `${header?.FIRST_NAME || ''} ${header?.LAST_NAME || ''}`
    chooseClientBtn.classList.toggle("d-none")
    clientNameBtn.classList.toggle("d-none")
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

function searchProductFromModalList(event) {
    const query = event.target.value.trim(); // Get the input value
    prodListPaginate.currentPage = 1;
    fetchProductsForModalListing(query);
}
const debouncedInput = debounce(searchProductFromModalList, 300);

function clearModalFilterInputs() {
    document.getElementById("searchInput").value = ''
    document.getElementById("CATEGORY_ID").value = ''
}