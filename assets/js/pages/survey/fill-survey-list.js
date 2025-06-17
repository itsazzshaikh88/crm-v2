// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
	let noCotent = `<tr>
                            <td colspan=${option.colspan} class="text-center text-danger">
                                <div class="container mt-5">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <div class="card text-center">
                                            <img src="assets/images/no-data.png" class="card-img-top no-data-img-table mx-auto" alt="No Data" />
                                            <div class="card-body">
                                                <h4 class="card-title text-danger">No Data Available</h4>
                                                <p class="card-text"> It seems there are no Survey currently available. You can add a new Survey to the inventory. </p>
                                                `;
	if (isAdmin)
		noCotent += `<a href="products/new" class="btn btn-primary">Add Survey</a>`;
	noCotent += `                           </div >
                                        </div >
                                    </div >
                                </div >
                            </td >
                        </tr > `;

	return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "fill-survey-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders =
	document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetctFillSurvey() {
	try {
		const authToken = getCookie("auth_token");
		if (!authToken) {
			toasterNotification({
				type: "error",
				message:
					"Authorization token is missing. Please Login again to make API request.",
			});
			return;
		}

		// Set loader to the screen
		listingSkeleton(tableId, paginate.pageLimit || 0, "requests");
		const url = `${APIUrl}/survey/fill_surveylist`;
		const filters = filterCriterias([]);

		const response = await fetch(url, {
			method: "POST",
			headers: {
				Authorization: `Bearer ${authToken}`,
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				limit: paginate.pageLimit,
				currentPage: paginate.currentPage,
				filters: filters,
			}),
		});

		if (!response.ok) {
			throw new Error("Failed to fetch product data");
		}

		const data = await response.json();
		paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
		paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

		showfillSurvey(data.surveys || [], tbody);
	} catch (error) {
		toasterNotification({
			type: "error",
			message: "Request failed: " + error.message,
		});
		tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
	}
}

function showfillSurvey(surveys, tbody) {
	let content = "";
	if (surveys?.length > 0) {
		// show products
		surveys.forEach((survey, index) => {
			content += `<tr data-survey-id="${
				survey?.HEADER_ID
			}" class="fs-7 text-gray-800">
                            <td class="">${index + 1}</td>
                            <td class=""> ${survey?.SURVEY_NUMBER || ""}</td>
                            <td class="">
                            <p class="mb-0 line-clamp-1">${
															survey?.SURVEY_NAME ||
															'<span style="color: red;">Survey Name Not Available</span>'
														}</p>
                            </td>

                            <td class="">${
															formatAppDate(survey?.FILLED_DATE) || ""
														}</td>

                            <td class="">${survey?.SURVEY_USERNAME || ""}</td>
                            <td class="">${survey?.SURVEY_YEAR || ""}</td>

                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-start gap-4">
                                    <a title="View Survey Response" href="survey/responses/${
																			survey?.HEADER_ID
																		}/${survey?.UUID}">
                                        <small>
                                            <i class="fs-5 fa-solid fa fa-arrow-right text-success"></i>
                                        </small>
                                    </a>
                                    `;
			if (isAdmin) {
				content += `<a title="Delete Survey" href="javascript:void(0)" onclick="deletefillSurvey(${survey?.HEADER_ID})">
                                        <small>
                                            <i class="fs-5 fa-solid fa-trash-can text-danger"></i>
                                        </small>
                                    </a>`;
			}
			content += `</div>
                            </td>
                        </tr>`;
		});

		tbody.innerHTML = content;
	} else {
		// no data available
		tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
	}
}

// Global scope
// Declare the pagination instance globally
const paginate = new Pagination(
	"current-page",
	"total-pages",
	"page-of-pages",
	"range-of-records"
);
paginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
	paginate.paginate(action); // Update current page based on the action
	fetctFillSurvey(); // Fetch products for the updated current page
}
document.addEventListener("DOMContentLoaded", () => {
	// Fetch initial product data
	fetctFillSurvey();
	// fetchCategories()
});

async function deletefillSurvey(surveyID) {
	if (!surveyID) {
		throw new Error("Invalid Survey ID, Please try Again");
	}

	// Show SweetAlert2 for confirmation
	const confirmed = await Swal.fire({
		title: "Are you sure?",
		text: "You won't be able to revert this!",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#d33",
		cancelButtonColor: "#3085d6",
		confirmButtonText: "Yes, delete it!",
	});

	if (!confirmed.isConfirmed) {
		return; // Exit if not confirmed
	}

	try {
		const authToken = getCookie("auth_token");
		if (!authToken) {
			throw new Error(
				"Authorization token is missing. Please Login again to make API request."
			);
		}

		const url = `${APIUrl}/survey/surveyfill_delete/${surveyID}`;

		const response = await fetch(url, {
			method: "DELETE", // Change to DELETE for a delete request
			headers: {
				Authorization: `Bearer ${authToken}`,
			},
		});

		const data = await response.json(); // Parse the JSON response

		if (!response.ok) {
			// If the response is not ok, throw an error with the message from the response
			throw new Error(data.error || "Failed to delete survey details");
		}

		if (data.status) {
			// Here, we directly handle the deletion without checking data.status
			toasterNotification({
				type: "success",
				message: "Survey Deleted Successfully",
			});
			// Logic to remove the current row from the table
			const row = document.querySelector(
				`#fill-survey-list-tbody tr[data-survey-id="${surveyID}"]`
			);
			if (row) {
				row.remove(); // Remove the row from the table
			}
		} else {
			throw new Error(data.message || "Failed to delete survey details");
		}
	} catch (error) {
		toasterNotification({
			type: "error",
			message: "Request failed: " + error.message,
		});
	}
}
