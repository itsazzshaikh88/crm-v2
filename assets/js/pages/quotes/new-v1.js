// Global vars
let selectedFiles = [];
let uploadedFiles = [];

// --------------------- ========================= ------------------------------------
const clientID = document.getElementById("CLIENT_ID");
const clientName = document.getElementById("CLIENT_NAME");
const companyAddress = document.getElementById("COMPANY_ADDRESS");
const billingAddress = document.getElementById("BILLING_ADDRESS");
const shippingAddress = document.getElementById("SHIPPING_ADDRESS");
const contactNumber = document.getElementById("MOBILE_NUMBER");
const emailAddress = document.getElementById("EMAIL_ADDRESS");
const requestNumber = document.getElementById("REQUEST_NUMBER");
// --------------------- ========================= ------------------------------------

var productListModal = new bootstrap.Modal(document.getElementById("product-list-modal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});


const prodListPaginate = new Pagination('prd-mdl-current-page', 'prd-mdl-total-pages', 'prd-mdl-page-of-pages', 'prd-mdl-range-of-records');
prodListPaginate.pageLimit = 10; // Set your page limit here



// Version 3 - Add New Product
const quotesForm = document.getElementById("quotesForm");
var newQuoteModal = new bootstrap.Modal(document.getElementById("newQuoteModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});
function openNewQuoteModal(action = 'new', quoteID = null) {
    if (action === 'new') {
        // reset form and then open 
        quotesForm.reset()
    } else {
        // Fetch product Details
        fetchQuotation(quoteID);
    }
    // Show NEw product modal 
    initializeQuill();
    newQuoteModal.show()
}
function closeQuoteModal() {
    removeClientName();
    quotesForm.reset()
    selectedFiles = [];
    uploadedFiles = [];
    displayFiles();
    displayUploadedFiles();
    document.getElementById("QUOTE_ID").value = ''
}

// Quill Editor
let quillInstance;
let quillOptions = {
    theme: 'snow',
    placeholder: 'Write your product description here...',
};
function initializeQuill(editorId = 'productDescription', options = quillOptions, predefinedContent = '') {
    const editorElement = document.getElementById(editorId);
    if (quillInstance) {
        // Destroy the toolbar if it exists
        const toolbar = editorElement.parentElement.querySelector('.ql-toolbar');
        if (toolbar) {
            toolbar.remove();
        }
        // Clear the editor's container and destroy the instance
        quillInstance = null; // Dereference the current instance
        editorElement.innerHTML = ''; // Reset the container's content
    }

    // Set predefined content before initializing
    if (predefinedContent)
        editorElement.innerHTML = predefinedContent;

    // Create a new Quill instance
    quillInstance = new Quill(`#${editorId}`, options);
}

// Handle File Operations
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
        content += `<div class="relative d-flex align-items-center justify-content-between border border-secondary gap-8 bg-white rounded px-4 py-2 cursor-pointer position-relative">
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
        content += `<div class="relative d-flex align-items-center justify-content-between border border-secondary gap-8 bg-white rounded px-4 py-2 cursor-pointer position-relative">
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
async function fetchQuotation(quoteUUID) {
    const apiUrl = `${APIUrl}/quotes/detail`;
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
            body: JSON.stringify({ searchkey: "QUOTE_ID", searchvalue: quoteUUID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
            console.log(errorMessage);
        }

        // Display the product information on the page if response is successful
        displayQuotesInfo(data.data);

        showClientDetails(data?.data?.header);


        // Show request Number
        document.getElementById("QUOTE_NUMBER").innerHTML = data?.data?.header?.QUOTE_NUMBER || "QUO-00000000"

        // Show uploaded files
        // Show Product Files attached
        if (data?.data?.header?.ATTACHMENTS) {
            uploadedFiles = JSON.parse(data?.data?.header?.ATTACHMENTS) || []
            displayUploadedFiles(data?.data?.header?.ID || 0);
        }

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
        console.log(error);

    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}
async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const productDescription = (document.querySelector(".ql-editor").innerHTML == '<p><br></p>' ? null : document.querySelector(".ql-editor").innerHTML) || null;
    formData.append('COMMENTS', productDescription);
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
        const quoteID = document.getElementById("QUOTE_ID").value;


        let url = `${APIUrl}/quotes/new`;
        if (quoteID)
            url += `/${quoteID}`
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
            toasterNotification({ type: 'success', message: "Quotation Saved Successfully!" });
            if (data?.type == 'insert') {
                newQuoteModal.hide()
                removeClientName();
                fetchRequests();
            } else if (data?.type == 'update') {
                // setTimeout(() => window.location = 'quotes/list', 1500)
                fetchRequests();
                newQuoteModal.hide()
                removeClientName();
            }

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
        console.log(error);
        error

    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = buttonText;
    }
}





function chooseProduct(index) {
    selectedProductElementIndex = index
    const element = document.getElementById(`PRODUCT_ID_${index}`)
    const descElement = document.getElementById(`DESCRIPTION_${index}`)
    if (typeof element != undefined) {
        // remove other elements from select
        element.innerHTML = '';
        descElement.value = '';
        productListModal.show()
        fetchProductsForModalListing();
    }

}

function setProductToRequestLine(productID, productName, productDesc) {

    const productCodeElement = document.getElementById(`PRODUCT_ID_${selectedProductElementIndex}`)
    const productDescElement = document.getElementById(`DESCRIPTION_${selectedProductElementIndex}`)

    let option = `<option value="${productID}">${productName}</option>`
    productCodeElement.innerHTML = option;
    productCodeElement.value = productID

    productDescElement.value = productDesc

    // close modal window
    productListModal.hide()
}


async function fetchProductsForModalListing(query = null) {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        const prodListContainer = document.getElementById("modal-product-list");

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



// document.addEventListener('DOMContentLoaded', () => {

//     // fetchCategories()

//     const url = new URL(window.location.href);
//     // Get all search parameters
//     const searchParams = new URLSearchParams(url.search);
//     // Get all URL segments
//     const urlSegments = url.pathname.split('/').filter(segment => segment);
//     const quoteUUID = urlSegments[urlSegments.length - 1];
//     // Fetch product details if action is edit and id is available
//     if (searchParams.get('action') === 'edit') {
//         // Your code to fetch product details
//         fetchQuotation(quoteUUID);

//     }
// });



async function fetchRequestsDetailForQuote(requestElement) {

    const valueOfElement = requestElement.value;
    const selectedOption = requestElement.options[requestElement.selectedIndex];
    let searchKey;
    if (selectedOption)
        searchKey = selectedOption.getAttribute('data-column');


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
            body: JSON.stringify({ searchKey, searchValue: valueOfElement })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
            console.log(errorMessage);

        }



        // // Display the product information on the page if response is successful
        displayRequestInfo(data.data);

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
        console.error(error);
        
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}



function displayQuotesInfo(data) {
    if (!data) return;
    const { header, lines, requests } = data;

    if (Object.keys(header).length > 0) {
        populateFormFields(header);
    }

    if (lines && Object.keys(lines).length > 0) {
        showQuoteLines(lines);
    }

    if (requests && requests.length > 0) {
        showSelectedClientRequests(requests, header?.REQUEST_ID || 0);
    }
}
function showSelectedClientRequests(requests, selectedRequestID) {

    const requestSelect = document.getElementById("REQUEST_NUMBER");
    requests.forEach(request => {
        let option = `<option value="${request.ID}" data-column="ID">${request.REQUEST_NUMBER}</option>`
        requestSelect.innerHTML += option;
    });
    requestSelect.value = selectedRequestID
}


function displayRequestInfo(data) {
    if (!data) return;
    const { header, lines } = data;

    /// manually show information
    document.getElementById("COMPANY_ADDRESS").value = header?.COMPANY_ADDRESS;

    if (lines && Object.keys(lines).length > 0) {
        showRequestLines(lines);
    }
}


// WHEN WE FETCH REQUSET IN QUOTATION
function showRequestLines(lines) {
    const tableBody = document.querySelector('#quotes-lines-table tbody');
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
                                <input type="number" class="form-control" name="QTY[]" id="QTY_${rowCount}" value="${line.QUANTITY}"   oninput="updateTotal(${rowCount})">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="UNIT_PRICE[]" id="UNIT_PRICE_${rowCount}" value="${line.BASE_PRICE}"  oninput="updateTotal(${rowCount})">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="TOTAL[]" id="TOTAL_${rowCount}" value="">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="COLOR[]" id="COLOR_${rowCount}" value="${line.COLOR}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="TRANSPORTATION[]" id="TRANSPORTATION_${rowCount}" value="${line.TRANSPORTATION}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="LINE_COMMENTS[]" id="LINE_COMMENTS_${rowCount}" value="${line.COMMENTS}">
                            </td>
                            <td>
                                <button class="btn btn-sm border border-danger" type="button" onclick="removeRow(this)">
                                    <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
                                </button>
                            </td>
                        `;

        tableBody.appendChild(row);
        updateTotal(rowCount)
    })

}

function showQuoteLines(lines) {
    const tableBody = document.querySelector('#quotes-lines-table tbody');
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
                                <input type="text" class="form-control" name="DESCRIPTION[]" id="DESCRIPTION_${rowCount}" value="${escapeSpecialCharacters(desc)}">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="QTY[]" id="QTY_${rowCount}" value="${line.QTY}"   oninput="updateTotal(${rowCount})">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="UNIT_PRICE[]" id="UNIT_PRICE_${rowCount}" value="${line.UNIT_PRICE}"  oninput="updateTotal(${rowCount})">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="TOTAL[]" id="TOTAL_${rowCount}" value="${line.TOTAL}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="COLOR[]" id="COLOR_${rowCount}" value="${line.COLOR}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="TRANSPORTATION[]" id="TRANSPORTATION_${rowCount}" value="${line.TRANSPORTATION}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="LINE_COMMENTS[]" id="LINE_COMMENTS_${rowCount}" value="${line.LINE_COMMENTS}">
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

function numberInput(input) {
    // Get the value of the input
    input.value = input.value.replace(/[^0-9]/g, "");
}


function updateTotal(rowCount) {
    const quantityField = document.getElementById(`QTY_${rowCount}`);
    const unitPriceField = document.getElementById(`UNIT_PRICE_${rowCount}`);
    const totalField = document.getElementById(`TOTAL_${rowCount}`);

    if (!quantityField || !unitPriceField || !totalField) {
        console.error("One or more fields are missing for row:", rowCount);
        return;
    }

    // Parse values safely
    const quantity = parseFloat(quantityField.value) || 0;
    const unitPrice = parseFloat(unitPriceField.value) || 0;

    // Calculate the total
    const total = quantity * unitPrice;

    // Update the total field
    totalField.value = total.toFixed(2); // Ensure two decimal places

    // Call any additional functions for total calculation
    calculateBillingTotals(); // Ensure this function exists and works properly
}



function showClientDetails(header) {
    clientID.value = header?.CLIENT_ID || 0
    clientName.innerHTML = `${header?.FIRST_NAME || ''} ${header?.LAST_NAME || ''}`
    
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

// Set client detail after selecting from the modals


function setClient(clientid) {
    const client = fetchedClients[clientid];
    clearClientDetails();
    if (client) {
        clientID.value = client?.ID || 0
        clientName.value = `${client?.FIRST_NAME || ''} ${client?.LAST_NAME || ''}`
        companyAddress.value = `${client?.COMPANY_NAME || ''}`
        contactNumber.value = `${client?.PHONE_NUMBER || ''}`
        emailAddress.value = `${client?.EMAIL || ''}`
    }
    myModal.hide();
    // Toggle Buttons
    

    fetchClientRequests(client?.ID);
}


function removeClientName() {
    clientID && (clientID.value = '');
    requestNumber && (requestNumber.value = '');
    clientName && (clientName.innerHTML = 'Client Name Here ...');
    companyAddress && (companyAddress.value = '');
    billingAddress && (billingAddress.value = '');
    shippingAddress && (shippingAddress.value = '');
    contactNumber && (contactNumber.value = '');
    emailAddress && (emailAddress.value = '');

    document.getElementById("REQUEST_NUMBER").innerHTML = '<option>Select Request Number</option>';

    // Clear product details
    const productTableBody = document.querySelector("#quotes-lines-table tbody"); // Assuming a table structure
    if (productTableBody) {
        productTableBody.innerHTML = ''; // Remove all rows from the product table
    }

    const subtotalInput = document.getElementById("SUB_TOTAL");
    const discountInput = document.getElementById("DISCOUNT_PERCENTAGE");
    const taxInput = document.getElementById("TAX_PERCENTAGE");
    const totalInput = document.getElementById("TOTAL_AMOUNT");

    // Reset billing totals
    subtotalInput && (subtotalInput.value = '');
    discountInput && (discountInput.value = '');
    taxInput && (taxInput.value = '');
    totalInput && (totalInput.value = '');
}

// Fetch clients request
async function fetchClientRequests(ClientID) {
    if (!clientID) {
        toasterNotification({ type: 'error', message: `Invalid Client ID` });
        return;
    }

    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
            return;
        }
        const url = `${APIUrl}/quotes/fetchClientRequests/${ClientID}`;

        // Await the response from fetch
        const response = await fetch(url, {
            method: 'GET', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`
            },
        });
        // Check if the response is OK
        if (!response.ok) {
            throw new Error("Network response was not ok " + response.statusText);
        }
        // Await the parsing of the JSON data
        const data = await response.json();
        const requests = data?.data || [];
        let str = "<option>Select Request</option>";
        if (requests.length > 0) {
            requests.forEach((req) => {
                str += `<option value="${req.ID}" data-column="ID">${req.REQUEST_NUMBER}</option>`;
            });
        }
        document.getElementById("REQUEST_NUMBER").innerHTML = str;
    } catch (error) {
        console.error(error);

        toasterNotification({ type: 'error', message: `There was a problem with the fetch operation: ${error}` });

    }
}

// ------------- ****************** ------------------



let selectedProductElementIndex = null
const fullPageLoader = document.getElementById("full-page-loader")



// Function to add a new row
function addRow() {
    const tableBody = document.querySelector('#quotes-lines-table tbody');
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
                                <input type="text" class="form-control" name=" DESCRIPTION[]" id="DESCRIPTION_${rowCount}" >
                            </td>
                            <td>
                                <input type="number" class="form-control" name="QTY[]" id="QTY_${rowCount}"    oninput="updateTotal(${rowCount})">
                            </td>
                            <td>
                                <input type="number" class="form-control" name=UNIT_PRICE[]" id="UNIT_PRICE_${rowCount}" oninput="updateTotal(${rowCount})">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="TOTAL[]" id="TOTAL_${rowCount}" >
                            </td>
                            <td>
                                <input type="text" class="form-control" name="COLOR[]" id="COLOR_${rowCount}" >
                            </td>
                            <td>
                                <input type="text" class="form-control" name="TRANSPORTATION[]" id="TRANSPORTATION_${rowCount}" ">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="LINE_COMMENTS[]" id="LINE_COMMENTS_${rowCount}" >
                            </td>
                            <td>
            <button class="btn btn-sm border border-danger" type="button" onclick="removeRow(this)">
                <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
            </button>
        </td>
    `;

    tableBody.appendChild(row);

}


function calculateBillingTotals() {
    // Get the number of rows in the table
    let total_lines = document.querySelectorAll("#quotes-lines-table tbody tr").length;

    const subtotal_input = document.getElementById("SUB_TOTAL");
    const discount_input = document.getElementById("DISCOUNT_PERCENTAGE");
    const tax_input = document.getElementById("TAX_PERCENTAGE");
    const total_input = document.getElementById("TOTAL_AMOUNT");

    // Initialize variables
    let subtotal = 0;
    let total_with_discount = 0;
    let total_with_tax = 0;

    discount_input.value = discount_input.value || 0;
    tax_input.value = tax_input.value || 0;
    // Loop through each row to calculate the subtotal
    for (let i = 1; i <= total_lines; i++) {
        let line_total =
            parseFloat(document.getElementById(`TOTAL_${i}`).value) || 0;
        subtotal += line_total;
    }
    subtotal_input.value = subtotal.toFixed(2); // Format to two decimal places

    // Calculate Discount
    let discount = parseFloat(discount_input.value) || 0;
    let discounted_price = 0;
    if (discount > 0) {
        discounted_price = (subtotal * discount) / 100;
    }
    total_with_discount = subtotal - discounted_price;

    // Calculate Tax
    let tax_amount = 0;
    let tax = parseFloat(tax_input.value) || 0;
    if (tax > 0) {
        tax_amount = (total_with_discount * tax) / 100;
    }
    total_with_tax = total_with_discount + tax_amount;

    // Calculate Total
    total_input.value = total_with_tax.toFixed(2);
}

// Function to remove a specific row
function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
}

// Updated Code for Choose Client
function openClientListModalFromQuote() {
    clearClientDetails();
    openClientListModal();
}

function clearClientDetails() {
    clientID.value = ''
    clientName.value = ''
    companyAddress.value = ''
    contactNumber.value = ''
    emailAddress.value = ''
    document.getElementById("REQUEST_NUMBER").innerHTML = '<option value="">Select Requests</option>';
}