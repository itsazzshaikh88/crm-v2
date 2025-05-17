// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td class="" colspan="${option.colspan}">
                                    <div class="d-flex max-w-6xl align-items-center justify-content-center mx-auto">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <img src="assets/images/minutes.png" class="img-fluid" alt="">
                                            </div>
                                            <div class="col-md-7 text-center mt-4 d-flex flex-column align-items-center mt-5">
                                                <h1>Welcome to the <span class="text-primary">Moments</span> that Matter!</h1>
                                                <h5 class="text-gray-700 fw-normal mb-4"><i>Capture, Collaborate, and Conquer</i></h5>
                                                <div class="text-center">
                                                    <p class="mt-4 text-gray-800">
                                                        Effective meetings start with clear documentation.
                                                        Here, your decisions, actions, and next steps are organized in one place.
                                                    </p>
                                                    <button type="button" onclick="openMomModal()" class="btn text-success border border-success fw-normal"> <i class="fa fa-plus text-success"></i> Add Minutes of Meeting</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;

    return noCotent;
}

const tableId = "mom-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchMOMS() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        listingSkeleton(tableId, paginate.pageLimit || 0, 'moms');
        const url = `${APIUrl}/mom/list`;
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
            throw new Error('Failed to fetch MOM data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showMinutes(data.moms || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

function showMinutes(minutes, tbody) {
    let content = '';
    let counter = 0;
    if (minutes?.length > 0) {
        let counter = 0;
        // show minutes
        minutes.forEach(minute => {
            content += `<tr data-minute-id="${minute?.MOM_ID}" class="text-gray-800 fs-7">
                                <td>${++counter}</td>
                                <td>${minute?.MEETING_TITLE}</td>
                                <td>${formatAppDate(minute?.MEETING_DATE)}</td>
                                <td>${minute?.DURATION}</td>
                                <td class=""><small class="badge bg-light-danger text-danger fw-normal">${minute?.LOCATION_PLATFORM}</small></td>
                                <td>${minute?.ORGANIZER}</td>
                                <td class=""><small class="d-flex align-items-center justify-content-start gap-1  flex-wrap text-gray-700 ">${showAttendees(minute?.ATTENDEES || {})}</small></td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a href="javascript:void(0)" onclick="openMomModal('edit', ${minute?.MOM_ID})">
                                            <small>
                                                <i class="fs-8 fa-regular fa-pen-to-square text-primary"></i>
                                            </small>
                                        </a>
                                        <a href="javascript:void(0)" onclick="deleteMinute(${minute?.MOM_ID})">
                                            <small>
                                                <i class="fs-8 fa-solid fa-trash-can text-danger"></i>
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

function showAttendees(attendees) {
    if (!attendees) return '';
    let attendeesList = JSON.parse(attendees);
    let content = '';
    let counter = 0;
    if (attendeesList && attendeesList.length > 0) {
        attendeesList.forEach((list) => {
            content += `<span class="mb-0">${list?.name}</span>`;
            if (counter < (attendeesList.length) - 1)
                content += ',';
            counter++;
        })
    }
    return content;
}

// Global scope
// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchMOMS(); // Fetch moms for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchMOMS();
});

function filterMOMS() {
    paginate.currentPage = 1;
    fetchMOMS();
}


async function deleteMinute(momID) {
    if (!momID) {
        throw new Error("Invalid Minutes ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this Minutes of Meeting? This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel",
            customClass: {
                popup: 'small-swal',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
            },
        });

        if (!confirmation.isConfirmed) return;

        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({
                type: 'error',
                message: "Authorization token is missing. Please login again to make an API request."
            });
            return;
        }

        // Show a non-closable alert box while the activity is being deleted
        Swal.fire({
            title: "Deleting Minutes ...",
            text: "Please wait while the minutes is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/mom/delete/${momID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete request
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        // Close the loading alert box
        Swal.close();

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete MOM details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Minutes Deleted Successfully' });
            fetchMOMS();
        } else {
            throw new Error(data.message || 'Failed to delete mom details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}
