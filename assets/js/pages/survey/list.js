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
                                                <p class="card-text">It seems there are no surveys currently available. You can add a new Survey.</p>
                                                `;
    if (isAdmin)
        noCotent += `<a href="survey/new" class="btn btn-primary">Add Survey</a>`;
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
const tableId = "survey-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetctSurvey(userSearchTerm = null) {

    if (!userSearchTerm) {
        userSearchTerm = document.getElementById("searchInputElement").value.trim() || null
    }
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        // Set loader to the screen 
        listingSkeleton(tableId, paginate.pageLimit || 0, 'requests');
        const url = `${APIUrl}/survey/list`;
        const filters = filterCriterias(['STATUS']);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: filters,
                search: userSearchTerm,
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showSurvey(data.surveys || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

const surveyStatusColors = {
    draft: "#2196F3", // Amber for drafts (attention but not active yet)
    open: "#4CAF50",  // Green for open (active and ongoing)
    closed: "#F44336" // Red for closed (completed or stopped)
};



function showSurvey(surveys, tbody) {

    let content = '';
    let default_img = "assets/images/default-image.png";
    if (surveys?.length > 0) {

        // show products
        surveys.forEach((survey, index) => {

            let desc = stripHtmlTags(survey?.DESCRIPTION || '');
            let img = parseJsonString(survey.PRODUCT_IMAGES || '', 0);
            if (img != null)
                img = `${PRODUCT_IMAGES_URL}${img}`;


            content += `<tr data-survey-id="${survey.SURVEY_ID}">
                            <td>${index + 1}</td>
                            <td class=" ">${survey.SURVEY_NAME}</td>
                            <td class=" ">${survey.SURVEY_DESC || ''}</td>
                            <td class="">${formatAppDate(survey.START_DATE || '')} - ${formatAppDate(survey.END_DATE || '')}</td>
                            <td class="">${survey.CONDUCTED_BY || ''}</td>
                            <td class="">
                                <span class="text-white badge" style="background-color: ${surveyStatusColors[survey.STATUS || '']}">
                                ${capitalizeWords(survey.STATUS || '')}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-3">

                                    <a href="survey/new/${survey.UUID}?action=edit">
                                        <small>
                                            <i class="fs-8 fa-regular fa-pen-to-square text-gray-700"></i>
                                        </small>
                                    </a>`;
            if (isAdmin) {
                content += `<a href="javascript:void(0)" onclick="deleteSurvey(${survey.SURVEY_ID})">
                                        <small>
                                            <i class="fs-8 fa-solid fa-trash-can text-danger"></i>
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
    fetctSurvey(); // Fetch products for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetctSurvey();
    // fetchCategories()

});


async function deleteSurvey(surveyID) {
    if (!surveyID) {
        throw new Error("Invalid Survey ID, Please try Again");
    }

    // Show SweetAlert2 for confirmation
    const confirmed = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    });

    if (!confirmed.isConfirmed) {
        return; // Exit if not confirmed
    }

    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            throw new Error("Authorization token is missing. Please Login again to make API request.");
        }

        const url = `${APIUrl}/survey/delete/${surveyID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete request
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete survey details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Survey Deleted Successfully' });
            fetctSurvey();
        } else {
            throw new Error(data.message || 'Failed to delete survey details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
    }
}


function searchSurveyListData(element) {
    const userSearchTerm = element.value.trim();

    fetctSurvey(userSearchTerm);
}


// Create a debounced version of the function
const debouncedSearchSurveyListData = debounce(searchSurveyListData, 300); // 300ms delay


/// export data
function exportsurveyData(type = null) {
    const search = document.getElementById("searchInputElement").value.trim();



    // Encode filters and search as query parameters
    const queryParams = new URLSearchParams({
        search: search
    });

    // Trigger download
    window.location.href = `${APIUrl}/survey/export_csv?${queryParams.toString()}`;
}

function filterSurveyReport() {
    paginate.currentPage = 1;
    fetctSurvey(); // Fetch Request for the updated current page
}