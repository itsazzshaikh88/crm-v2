let selectedFiles = [];
let uploadedFiles = [];
let selectedProductElementIndex = null
const fullPageLoader = document.getElementById("full-page-loader")
// Function to add a new row
function addRow() {
	const tableBody = document.querySelector('#complaint-lines-table tbody');
	const rowCount = tableBody.rows.length + 1;
	{/* <select name="PO_NUMBER[]" id="PO_NUMBER${rowCount}" class="form-control" onclick="chooseProduct(${rowCount})"></select> */ }
	// Create a new row
	const row = document.createElement('tr');
	row.innerHTML = `
        <td>
            <select name="PO_NUMBER[]" id="PO_NUMBER${rowCount}" class="form-control">
                <option value="">Choose</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control" name="DELIVERY_NUMBER[]" id="DELIVERY_NUMBER${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="PRODUCT_CODE[]" id="PRODUCT_CODE${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="PRODUCT_DESC[]" id="PRODUCT_DESC${rowCount}">
        </td>
        <td>
            <input type="date" class="form-control" name="DELIVERY_DATE[]" id="DELIVERY_DATE${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="QTY[]" id="QTY${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="ISSUE[]" id="ISSUE_${rowCount}">
        </td>
		 <td>
            <input type="text" class="form-control" name="REMARK[]" id="REMARK_${rowCount}">
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

// Function to fetch user details
async function getUserDetail() {
	// Get input values
	let user_id = document.getElementById('USER_ID').value;
	let email = document.getElementById('USER_EMAIL').value;

	let user_name = document.getElementById('CUSTOMER_NAME');
	let complaint_by_name = document.getElementById('COMPLAINT_RAISED_BY');
	let user_phone = document.getElementById('MOBILE_NUMBER');
	let client_id = document.getElementById('CLIENT_ID');


	try {
		// Prepare the API URL
		let url = `${APIUrl}/complaints/getUserDetail`;

		// Prepare the payload to send to the backend
		const payload = {
			user_id: user_id,
			email: email
		};

		// Fetch data from the API
		const response = await fetch(url, {
			method: "POST", // POST method for sending data
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(payload),
		});

		// Check if the response is successful (status code 2xx)
		if (!response.ok) {
			throw new Error(`Error: ${response.status} ${response.statusText}`);
		}

		// Parse the JSON response from the API
		const userDetails = await response.json();

		// Handle the user details (for now, we log them to the console)

		user_name.value = userDetails.FIRST_NAME + ' ' + userDetails.LAST_NAME;
		complaint_by_name.value = userDetails.FIRST_NAME + ' ' + userDetails.LAST_NAME;
		user_phone.value = userDetails.PHONE_NUMBER;
		client_id.value = userDetails.ID;

	} catch (error) {
		// Handle any errors that occurred during the fetch operation
		console.error("Failed to fetch user details:", error);
	}
}



// Call the function to fetch and display user details


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
	submitBtn.innerHTML = `Raising Complaint...`;

	// Hide Error
	hideErrors();
	try {
		// Retrieve the auth_token from cookies
		const authToken = getCookie('auth_token');
		if (!authToken) {
			toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
			return;
		}
		const complaintID = document.getElementById("COMPLAINT_ID").value;
		let url = `${APIUrl}/complaints/new`;
		if (complaintID)
			url += `/${complaintID}`
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
			toasterNotification({ type: 'success', message: "Complaint Raised Successfully!" });
			window.location.reload()
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
	getUserDetail();

	fetchCategories()

	const url = new URL(window.location.href);
	// Get all search parameters
	const searchParams = new URLSearchParams(url.search);
	// Get all URL segments
	const urlSegments = url.pathname.split('/').filter(segment => segment);
	const complaintUUID = urlSegments[urlSegments.length - 1];
	// Fetch product details if action is edit and id is available
	if (searchParams.get('action') === 'edit') {
		// Your code to fetch product details
		fetchComplaint(complaintUUID);
	}
});

async function fetchComplaint(complaintUUID) {
	const apiUrl = `${APIUrl}/complaints/detail`;
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
			body: JSON.stringify({ complaintUUID })
		});

		// Parse the JSON response
		const data = await response.json();

		// Check if the API response contains an error
		if (!response.ok || data.status === 'error') {
			const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
			throw new Error(errorMessage);
		}

		// Display the product information on the page if response is successful
		console.log(data.data); 
		displayRequestInfo(data.data);


		// Show request Number
		document.getElementById("COMPLAINT_NUMBER").innerHTML = data?.data?.header?.COMPLAINT_NUMBER || "COMP-00000000"

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
		showComplaintLines(lines);
	}
}

function showComplaintLines(lines) {
	const tableBody = document.querySelector('#complaint-lines-table tbody');
	tableBody.innerHTML = ''
	let rowCount = 0;
	lines.forEach((line) => {
		let desc = stripHtmlTags(line?.PRODUCT_DESC || '');
		// Create a new row
		console.log(line);
		const row = document.createElement('tr');
		row.innerHTML = `<td>
    						<select name="PO_NUMBER[]" id="PO_NUMBER_${++rowCount}" class="form-control">
       						 <option value="">Choose</option>
        					 <option value="1" ${line.PO_NUMBER == 1 ? 'selected' : ''}>1</option>
        					 <option value="2" ${line.PO_NUMBER == 2 ? 'selected' : ''}>2</option>
        					 <option value="3" ${line.PO_NUMBER == 3 ? 'selected' : ''}>3</option>
        					 <option value="4" ${line.PO_NUMBER == 4 ? 'selected' : ''}>4</option>
    						</select>
						</td>

                            <td>
                                <input type="text" class="form-control" name="DELIVERY_NUMBER[]" id="DELIVERY_NUMBER_${rowCount}" value="${line.DELIVERY_NUMBER}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="PRODUCT_CODE[]" id="PRODUCT_CODE_${rowCount}" value="${line.PRODUCT_CODE}">
                            </td>
							 <td>
                                <input type="text" class="form-control" name="PRODUCT_DESC[]" id="PRODUCT_DESC_${rowCount}" value="${escapeSpecialCharacters(desc)}">
                            </td>
                            <td>
                                <input type="date" class="form-control" name="DELIVERY_DATE[]" id="DELIVERY_DATE_${rowCount}" value="${line.DELIVERY_DATE}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="QTY[]" id="QTY_${rowCount}" value="${line.QTY}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="ISSUE[]" id="ISSUE_${rowCount}" value="${line.ISSUE}">
                            </td>
							 <td>
                                <input type="text" class="form-control" name="REMARK[]" id="REMARK_${rowCount}" value="${line.REMARK}">
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
