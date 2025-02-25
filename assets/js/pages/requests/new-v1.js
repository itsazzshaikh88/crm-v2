// Global vars
let selectedFiles = [];
let uploadedFiles = [];
const fullPageLoader = document.getElementById("full-page-loader")
const requestForm = document.getElementById("requestForm");
let selectedProductElementIndex = null
// --------------------- ========================= ------------------------------------
const clientID = document.getElementById("CLIENT_ID");
const clientName = document.getElementById("CLIENT_NAME");
const companyAddress = document.getElementById("COMPANY_ADDRESS");
const billingAddress = document.getElementById("BILLING_ADDRESS");
const shippingAddress = document.getElementById("SHIPPING_ADDRESS");
const contactNumber = document.getElementById("CONTACT_NUMBER");
const emailAddress = document.getElementById("EMAIL_ADDRESS");
// --------------------- ========================= ------------------------------------
// Initialize
var newRequestModal = new bootstrap.Modal(document.getElementById("newRequestModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

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
function displayUploadedFiles(requestID) {
    const fileList = document.getElementById('file-list-uploaded');
    fileList.innerHTML = ''; // Clear current list
    uploadedFiles.forEach((filename, index) => {
        let content = '';
        content += `<div class="relative d-flex align-items-center justify-content-between border border-secondary gap-8 bg-white rounded px-4 py-2 cursor-pointer position-relative">
                        <p class="mb-0">${filename}</p>
                        <span class="text-white" onclick="deleteFileFromServer('${uploadedFiles}', ${requestID})"><i class="fa-solid fa-x text-danger"></i></span>
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


function openNewRequestModal(action = 'new', requestID = null) {
    if (action === 'new') {
        // reset form and then open 
        requestForm.reset()
    } else {
        // Fetch request Details
        fetchRequestToDisplayForEdit(requestID);
    }

    newRequestModal.show()
}

function closeRequestModal() {
    requestForm.reset()
    selectedFiles = [];
    uploadedFiles = [];
    displayFiles();
    displayUploadedFiles();
    document.getElementById("ID").value = '';
    document.getElementById("CLIENT_ID").value = '';
    initializeProductLinesTable();
}

function initializeProductLinesTable() {
    const tbody = document.querySelector('#request-lines-table tbody');
    tbody.innerHTML = `<tr>
                                            <td>
                                                <select name="PRODUCT_ID[]" id="PRODUCT_ID_1" class="form-control form-control-sm border border-blue-100 text-gray-700" onclick="chooseProduct(1)">
                                                    <option value="">Choose</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="PRODUCT_DESC[]" id="PRODUCT_DESC_1">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="QUANTITY[]" id="QUANTITY_1">
                                            </td>
                                            <td>
                                                <input type="date" class="form-control form-control-sm border border-blue-100 text-gray-700" name="REQUIRED_DATE[]" id="REQUIRED_DATE_1">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="COLOR[]" id="COLOR_1">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="TRANSPORTATION[]" id="TRANSPORTATION_1">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700" name="COMMENTS[]" id="COMMENTS_1">
                                            </td>
                                            <td>
                                                <button class="btn btn-sm border border-danger" type="button" onclick="removeRow(this)">
                                                    <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
                                                </button>
                                            </td>
                                        </tr>`;
}

// Submit function
async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    selectedFiles.forEach(file => {
        formData.append('files[]', file);
    });

    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Saving ...`;

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

            if (data?.type === 'insert') {
                closeRequestModal();
                newRequestModal.hide();
                fetchRequests();
            } else if (data?.type === 'update') {

            }


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

async function fetchRequestToDisplayForEdit(requestID) {

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
        // Fetch request data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ searchKey: "ID", searchValue: requestID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        displayRequestInfo(data.data);


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

function startOver() {
    Swal.fire({
        title: "Are you sure?",
        text: "Starting a new request will discard unsaved changes. Do you want to continue?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Add New Request",
        cancelButtonText: "Cancel",
        customClass: {
            popup: 'small-swal',
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Call the function to start a new lead
            closeRequestModal()
        }
    });

}

function clearClientDetails() {
    clientID.value = ''
    clientName.value = ''
    companyAddress.value = ''
    billingAddress.value = ''
    shippingAddress.value = ''
    contactNumber.value = ''
    emailAddress.value = ''
}

function openClientListModalFromRequest() {
    clearClientDetails();
    openClientListModal();
}


// -------- ********************** --------------------------------
function setClient(clientid) {
    const client = fetchedClients[clientid];
    clearClientDetails();
    if (client) {
        clientID.value = client?.ID || 0
        clientName.value = `${client?.FIRST_NAME || ''} ${client?.LAST_NAME || ''}`
        companyAddress.value = `${client?.COMPANY_NAME || ''}`
        billingAddress.value = `${client?.ADDRESS_LINE_1 || ''}`
        shippingAddress.value = `${client?.SHIPPING_ADDRESS || ''}`
        contactNumber.value = `${client?.PHONE_NUMBER || ''}`
        emailAddress.value = `${client?.EMAIL || ''}`
    }
    myModal.hide();
    // Toggle Buttons
    chooseClientBtn.classList.toggle("d-none")
    clientNameBtn.classList.toggle("d-none")
}
// Chooose your prodict in lines
function chooseProduct(index) {
    const element = document.getElementById(`PRODUCT_ID_${index}`);
    const descElement = document.getElementById(`PRODUCT_DESC_${index}`);

    if (!element || !descElement) {
        console.warn(`Elements for index ${index} not found.`);
        return;
    }

    element.value = ''; // Clear input value
    descElement.value = ''; // Clear input value

    selectedProductElementIndex = index;

    if (typeof showProductListingFullScreenModal === 'function') {
        showProductListingFullScreenModal(setProductToRequestLine);
    } else {
        console.warn("showProductListingFullScreenModal function is not defined.");
    }
}




function renderNoResponseCode() {
    return `Products Not Available`
}

function setProductToRequestLine(productID, productName, productDesc) {

    const productCodeElement = document.getElementById(`PRODUCT_ID_${selectedProductElementIndex}`)
    const productDescElement = document.getElementById(`PRODUCT_DESC_${selectedProductElementIndex}`)

    let option = `<option value="${productID || ''}">${productName || ''}</option>`
    productCodeElement.innerHTML = option;
    productCodeElement.value = productID || '';

    productDescElement.value = productDesc != 'null' ? productDesc : '';

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


// ================================= -------------------------------- ===================================
// Add New Row
function addRow() {
    const tableBody = document.querySelector('#request-lines-table tbody');
    const rowCount = tableBody.rows.length + 1;

    // Create a new row
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="PRODUCT_ID[]" id="PRODUCT_ID_${rowCount}" class="form-control form-control-sm border border-blue-100 text-gray-700 " onclick="chooseProduct(${rowCount})">
                <option value="">Choose</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="PRODUCT_DESC[]" id="PRODUCT_DESC_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="QUANTITY[]" id="QUANTITY_${rowCount}">
        </td>
        <td>
            <input type="date" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="REQUIRED_DATE[]" id="REQUIRED_DATE_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="COLOR[]" id="COLOR_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="TRANSPORTATION[]" id="TRANSPORTATION_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm border border-blue-100 text-gray-700 " name="COMMENTS[]" id="COMMENTS_${rowCount}">
        </td>
        <td>
            <button class="btn btn-sm border border-danger" type="button" onclick="removeRow(this)">
                <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
            </button>
        </td>
    `;

    tableBody.appendChild(row);
}

function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
}

// Open Modal
// openNewRequestModal();