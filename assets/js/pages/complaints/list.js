// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
	let noCotent = `<tr>
                                <td colspan="${option?.colspan}" class="text-center text-danger">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <img src="assets/images/no-data.png" class="no-data-img-table w-80" alt="">
                                        <h4 class="text-danger fw-normal">Complaint data not found</h4>
                                    </div>
                                </td>
                            </tr>`;

	return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "complaint-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);
const filterType = document.getElementById('STATUS').value;
const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchRequests() {
	try {
		const authToken = getCookie('auth_token');
		if (!authToken) {
			toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
			return;
		}

		// Set loader to the screen 
		listingSkeleton(tableId, paginate.pageLimit || 0, 'complaints');

		const url = `${APIUrl}/complaints/list`;
		// Ensure that filterCriterias returns an object or array of valid filters
		const filters = filterCriterias(['STATUS', 'USER_ID', 'USER_TYPE']);

		const response = await fetch(url, {
			method: 'POST',
			headers: {
				'Authorization': `Bearer ${authToken}`,
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				limit: paginate.pageLimit,
				currentPage: paginate.currentPage,
				filters: filters // Make sure this is an array or object of filter criteria
			})
		});

		if (!response.ok) {
			throw new Error('Failed to fetch request data');
		}

		const data = await response.json();
		paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
		paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

		showRequests(data.complaints || [], tbody);

	} catch (error) {
		toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
		tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
	}
}


function showRequests(complaints, tbody) {
	let content = '';
	let counter = 0;
	let userType = document.getElementById('USER_TYPE').value;
	if (complaints?.length > 0) {
		// Show complaints
		complaints.forEach(complaint => {
			let color = '';
			if (complaint.STATUS == 'Active') {
				color = 'danger';
			} else if (complaint.STATUS == 'Closed') {
				color = 'success';
			} else {
				color = 'primary'
			}
			content += `<tr data-complaint-id="${complaint.COMPLAINT_ID}" class="text-gray-800 fs-7">
							<td class="text-center">${++counter}</td>
							<td>${complaint.COMPLAINT_NUMBER || ''}</td>
							<td>${complaint.COMPLAINT_DATE || ''}</td>
							<td>${complaint.COMPANY_NAME || ''}</td>
							<td>${complaint.CUSTOMER_NAME || ''}</td>
							<td>${complaint.COMPLAINT_RAISED_BY || ''}</td>
							<td>${complaint.EMAIL || ''}</td>
							<td>${complaint.MOBILE_NUMBER || ''}</td>
							<td class=""> <small class="text-white bg-${color} border border-${color} px-2 py-1 rounded">${complaint.STATUS || ''}</small></td>
							<td class="text-end">
								<div class="d-flex align-items-center justify-content-end gap-3">
									${complaint.STATUS === 'Draft' && userType === 'client' || complaint.STATUS === 'Active' && userType === 'client'
					? `
											<a href="complaints/new/${complaint.UUID}?action=edit">
												<small>
													<i class="fs-8 fa-regular fa-pen-to-square text-gray-700"></i>
												</small>
											</a>
											<a href="javascript:void(0)" onclick="deleteRequest(${complaint.COMPLAINT_ID})">
												<small>
													<i class="fs-8 fa-solid fa-trash-can text-danger"></i>
												</small>
											</a>
										`
					: ''
				}
									${complaint.STATUS === 'Draft' && userType === 'admin' || complaint.STATUS === 'Active' && userType === 'admin'
					? `
											<a href="complaints/resolve/${complaint.COMPLAINT_ID}">
												<small>
													<i class="fs-8 fa-regular fa-pen-to-square text-primary"></i>
												</small>
											</a>
										`
					: ''
				}
				<a href="complaints/view/${complaint.UUID}">
										<small>
											<i class="fs-8 fa-solid fa-up-right-from-square text-gray-800"></i>
										</small>
									</a>
								</div>
							</td>
						</tr>`;
		});

		tbody.innerHTML = content;
	}
	else {
		// No data available
		tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
	}
}



// Global scope
// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
	paginate.paginate(action); // Update current page based on the action
	fetchRequests(); // Fetch Request for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
	// Fetch initial product data

	const url = new URL(window.location.href);
	// Get all search parameters
	const searchParams = new URLSearchParams(url.search);
	// Get all URL segments
	const urlSegments = url.pathname.split('/').filter(segment => segment);
	const complaintType = urlSegments[urlSegments.length - 1];
	// Fetch product details if action is edit and id is available
	// Your code to fetch product details
	getComplaintDetails();
	fetchRequests(complaintType);
});

function filterRequest() {
	paginate.currentPage = 1;
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

async function deleteRequest(complaintID) {
	if (!complaintID) {
		throw new Error("Invalid Request ID, Please try Again");
	}
	try {
		const authToken = getCookie('auth_token');
		if (!authToken) {
			throw new Error("Authorization token is missing. Please Login again to make API request.");
		}

		const url = `${APIUrl}/complaints/delete/${complaintID}`;

		const response = await fetch(url, {
			method: 'DELETE', // Change to DELETE for a delete request
			headers: {
				'Authorization': `Bearer ${authToken}`
			}
		});

		const data = await response.json(); // Parse the JSON response

		if (!response.ok) {
			// If the response is not ok, throw an error with the message from the response
			throw new Error(data.error || 'Failed to delete request details');
		}

		if (data.status) {
			// Here, we directly handle the deletion without checking data.status
			toasterNotification({ type: 'success', message: 'Complaint Deleted Successfully' });
			// Logic to remove the current row from the table
			const row = document.querySelector(`#complaint-list-tbody tr[data-complaint-id="${complaintID}"]`);
			if (row) {
				row.remove(); // Remove the row from the table
			}
		} else {
			throw new Error(data.message || 'Failed to delete request details');
		}

	} catch (error) {
		toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
	}
}


let userId = document.getElementById('USER_ID').value;
let userType = document.getElementById('USER_TYPE').value;
let totalComp = document.getElementById('total_comp');
let activeComp = document.getElementById('active_comp');
let closedComp = document.getElementById('closed_comp');
let draftComp = document.getElementById('draft_comp');

async function getComplaintDetails() {
	const authToken = getCookie('auth_token');
	if (!authToken) {
		toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
		return;
	}

	const url = `${APIUrl}/complaints/getCardStats`;
	const filters = filterCriterias(['USER_ID', 'USER_TYPE']); // Ensure filterCriterias returns valid filters

	try {
		const response = await fetch(url, {
			method: 'POST',
			headers: {
				'Authorization': `Bearer ${authToken}`,
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				filters: filters // Send filters to API
			})
		});

		if (!response.ok) {
			throw new Error('Failed to fetch request data');
		}

		const data = await response.json();
		console.log(data); // Check the response structure

		// Initialize counts for each status and total
		let counts = {
			Active: 0,
			Closed: 0,
			Draft: 0,
			Total: 0
		};

		// Loop through the API data to populate the counts
		data.forEach(item => {
			const { STATUS, STATUS_COUNT } = item; // Destructure the API response fields
			counts[STATUS] = parseInt(STATUS_COUNT, 10); // Assign the count to the corresponding status
			counts.Total += parseInt(STATUS_COUNT, 10); // Add to the total count
		});

		// Update the span elements with the counts
		const totalComp = document.getElementById('total-comp');
		const activeComp = document.getElementById('active-comp');
		const closedComp = document.getElementById('closed-comp');
		const draftComp = document.getElementById('draft-comp');

		if (totalComp) totalComp.innerHTML = counts.Total || 0;
		if (activeComp) activeComp.innerHTML = counts.Active || 0;
		if (closedComp) closedComp.innerHTML = counts.Closed || 0;
		if (draftComp) draftComp.innerHTML = counts.Draft || 0;

	} catch (error) {
		toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
		console.error(error);
	}
}
