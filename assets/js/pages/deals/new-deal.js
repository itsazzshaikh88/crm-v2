// Set Initial Variables
const newDealCreatedContent = `<div class="row">
                                <div class="col-md-12 text-center">
                                    <img src="assets/images/add-leads.png" class="w-250" alt="Add Deal">
                                    <div class="max-w-3xl mx-auto">
                                        <h5 class="fw-normal">Keep your <b>deals</b> pipeline organized and track every milestone. <span class="text-warning">Log</span> updates, negotiations, proposals, or important deadlines right here. Watch this space evolve as your <span class="text-success">deals</span> move closer to success.</h5>
                                    </div>
                                </div>
                            </div>
                            `;

const newDealContent = `<div class="d-flex align-items-center justify-content-start flex-column max-w-xl mx-auto text-center">
            <h1 class="mt-8 fs-2x text-gray-600">Unlock Opportunities – <span class="text-success">Add Your First Deal</span> Now!</h1>

            <img src="assets/images/add-leads.png" class="w-300" alt="Add Deals Illustration">
            
            <p class="mt-4 text-muted">
                Every successful sale starts with a great <span class="text-warning">Deal!</span> Organize your pipeline, track progress, and move closer to your business goals. Ready to make your next big win? Let’s get started!
            </p>
        </div>
        `;

const activityContainer = document.getElementById("activity-container")
const activityButtonContainer = document.getElementById("activity-button-container")
const dealForm = document.getElementById("dealForm");

// Modal Related Code
var newDealModal = new bootstrap.Modal(document.getElementById("newDealModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

function openDealModal(action = 'new', dealID = null) {
    if (action === 'new') {
        // reset form and then open 
        dealForm.reset()
        // Set UUID to the UUID input field
        document.getElementById("UUID").value = uuid_v4();
        document.getElementById("DEAL_STATUS").value = 'new';
        // Set new deal content
        activityContainer.innerHTML = newDealContent
        activityButtonContainer.classList.add("d-none")
    } else {
        // Fetch Deal Details
        fetchDeal(dealID);
    }
    // Show NEw Deal modal 
    newDealModal.show()
}
function closeDealModal() {
    document.getElementById("UUID").value = '';
    document.getElementById("DEAL_ID").value = '';
    activityContainer.innerHTML = '';
    activityButtonContainer.classList.add("d-none")
    dealForm.reset()
    document.getElementById("DEAL_STATUS").value = 'new'
}

async function submitDeal(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Generating Deal ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const deal_id = document.getElementById("DEAL_ID").value;
        let url = `${APIUrl}/deals/`;
        if (deal_id) {
            url += `update/${deal_id}`
        }
        else
            url += 'new'
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
            if (data?.type == 'insert') {
                // Data is inserted
                setDealCreated(data?.data);
                toasterNotification({ type: 'success', message: "Deal Created Successfully" });
            } else if (data?.type == 'update') {
                // Data is updated
                toasterNotification({ type: 'success', message: "Deal Updated Successfully" });
            } else {
                toasterNotification({ type: 'error', message: 'Internal Server Error' });
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

function setDealCreated(data) {
    if (!data) return null;
    document.getElementById("DEAL_NUMBER").value = data?.DEAL_NUMBER || ''
    document.getElementById("DEAL_ID").value = data?.DEAL_ID || ''
    activityContainer.innerHTML = newDealCreatedContent;
    activityButtonContainer.classList.remove("d-none")
}
function setLeadUpdated(data) {
    if (!data) return null;

}

async function fetchDeal(dealID) {
    const apiUrl = `${APIUrl}/deals/detail`;
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({
            type: 'error',
            message: "Authorization token is missing. Please login again to make an API request."
        });
        return;
    }

    try {
        // Set loading animation
        textInputElementLoadingAnimation('set');

        // set animation to activities fetch
        appendSkeletonContent({ elementId: "activity-container", position: "end", skeletonType: "lead-activities", count: 5 })

        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ dealID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        // Set loading animation
        textInputElementLoadingAnimation('remove');
        displayDealData(data.data);

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
        console.error(error);

    } finally {

    }
}

function displayDealData(data) {
    if (data) {
        const { deal, activities } = data;

        if (Object.keys(deal).length > 0) {
            populateFormFields(deal);
        }
        showDealActivities(activities);
    } else {
        toasterNotification({ type: 'error', message: 'Lead Details and Lead Activity details not found.' });
    }
}

function showDealActivities(activities) {
    activityButtonContainer.classList.remove("d-none");
    if (activities?.data.length == 0) {
        activityContainer.innerHTML = newDealCreatedContent;
        return;
    }
    // Show Lead Activities
    const { data } = activities;
    let activitiesContent = ''
    if (data && data.length > 0) {
        data.forEach((activity) => {
            const activityType = activity?.ACTIVITY_TYPE?.toLowerCase();
            if (activityType === 'call')
                activitiesContent += showCallLogsActivity(activity)
            else if (activityType === 'notes')
                activitiesContent += showNotesActivity(activity)
            else if (activityType === 'meeting')
                activitiesContent += showMeetingActivity(activity)
            else if (activityType === 'task')
                activitiesContent += showTaskActivity(activity)


        })
    }
    activityContainer.innerHTML = activitiesContent
}

function showCallLogsActivity(activity) {
    if (!activity) return '';
    return `<div class="position-relative ps-6 pe-3 py-2 bg-gray-50s" id="activity-inline-container-${activity?.ACTIVITY_ID}">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-warning"></div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0)" class="mb-1 text-hover-primary fw-bold badge bg-warning text-white"> <i class="fa-solid fa-headset text-white"></i> Call Log</a>
                                        <div class="d-flex align-items-center gap-12">
                                            <div class="fs-8 text-warning fw-normal">Created on ${formatAppDate(activity?.ACTIVITY_DATE)}</div>
                                            <div class="d-flex align-items-center gap-4">
                                                <a href="javascript:void(0)" title="Edit Activity" onclick="editCurrentActivityDetail('${activity?.ACTIVITY_TYPE?.toLowerCase()}',${activity?.ACTIVITY_ID})"><i class="fa-solid fa-file-pen cursor-pointer text-success fs-4"></i></a>
                                                <a href="javascript:void(0)" title="Delete Activity" onclick="deleteCurrentActivity(${activity?.ACTIVITY_ID})"><i class="fa-solid fa-trash cursor-pointer text-danger fs-4"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <p for="" class="mb-0 fw-bold">Call Purpose:<span class="text-gray-800 fw-normal "> ${activity?.CALL_PURPOSE}</span></p>
                                            <p for="" class="mb-0 fw-bold">Call Duration:<span class="text-gray-800 fw-normal "> ${activity?.CALL_DURATION}</span></p>
                                            <p for="" class="mb-0 fw-bold">Follow-up Date:<span class="text-gray-800 fw-normal "> ${formatAppDate(activity?.FOLLOW_UP_DATE)}</span></p>
                                            <p for="" class="mb-0 fw-bold">Note:</p>
                                            <p class="line-clamp-2 text-gray-800 mb-0">${stripHtmlTags(activity?.NOTES)}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed mb-4 mt-2"></div>`;
}
function showNotesActivity(activity) {
    if (!activity) return '';
    return `<div class="position-relative ps-6 pe-3 py-2 bg-gray-50s" id="activity-inline-container-${activity?.ACTIVITY_ID}">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-primary"></div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0)" class="mb-1 text-hover-primary fw-bold badge bg-primary text-white"><i class="fa-solid fa-notes-medical text-white"></i> Note</a>
                                        <div class="d-flex align-items-center gap-12">
                                            <div class="fs-8 text-primary fw-normal">Created on ${formatAppDate(activity?.ACTIVITY_DATE)}</div>
                                            <div class="d-flex align-items-center gap-4">
                                                <a href="javascript:void(0)" title="Edit Activity" onclick="editCurrentActivityDetail('${activity?.ACTIVITY_TYPE?.toLowerCase()}',${activity?.ACTIVITY_ID})"><i class="fa-solid fa-file-pen cursor-pointer text-success fs-4"></i></a>
                                                <a href="javascript:void(0)" title="Delete Activity" onclick="deleteCurrentActivity(${activity?.ACTIVITY_ID})"><i class="fa-solid fa-trash cursor-pointer text-danger fs-4"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <p for="" class="mb-0 fw-bold">Note:</p>
                                            <p class="line-clamp-2 text-gray-800 mb-0">${stripHtmlTags(activity?.NOTES)}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed mb-4 mt-2"></div>`
}
function showMeetingActivity(activity) {
    if (!activity) return '';
    return `<div class="position-relative ps-6 pe-3 py-2 bg-gray-50s" id="activity-inline-container-${activity?.ACTIVITY_ID}">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-info"></div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0)" class="mb-1 text-hover-primary fw-bold badge bg-info text-white"> <i class="fa-solid fa-chalkboard-user text-white"></i> Meeting Details</a>
                                        <div class="d-flex align-items-center gap-12">
                                            <div class="fs-8 text-info fw-normal">Created on ${formatAppDate(activity?.ACTIVITY_DATE)}</div>
                                            <div class="d-flex align-items-center gap-4">
                                                <a href="javascript:void(0)" title="Edit Activity" onclick="editCurrentActivityDetail('${activity?.ACTIVITY_TYPE?.toLowerCase()}',${activity?.ACTIVITY_ID})"><i class="fa-solid fa-file-pen cursor-pointer text-success fs-4"></i></a>
                                                <a href="javascript:void(0)" title="Delete Activity" onclick="deleteCurrentActivity(${activity?.ACTIVITY_ID})"><i class="fa-solid fa-trash cursor-pointer text-danger fs-4"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <p for="" class="mb-0 fw-bold">Agenda:<span class="text-gray-800 fw-normal"> ${activity?.AGENDA}</span></p>
                                            <p for="" class="mb-0 fw-bold">Location:<span class="text-gray-800 fw-normal"> ${activity?.LOCATION}</span></p>
                                            <p for="" class="mb-0 fw-bold">Attended By:<span class="text-gray-800 fw-normal "> ${activity?.ATTENDEES}</span></p>
                                            <p for="" class="mb-0 fw-bold">Meeting Outcome:</p>
                                            <p class="line-clamp-2 text-gray-800 mb-0">${stripHtmlTags(activity?.NOTES)}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed mb-4 mt-2"></div>`
}

function showTaskActivity(activity) {
    if (!activity) return '';
    return `<div class="position-relative ps-6 pe-3 py-2 bg-gray-50s" id="activity-inline-container-${activity?.ACTIVITY_ID}">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-danger"></div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0)" class="mb-1 text-hover-primary fw-bold badge bg-danger text-white"> <i class="fa-solid fa-list-check text-white"></i> Task Details</a>
                                        <div class="d-flex align-items-center gap-12">
                                            <div class="fs-8 text-danger fw-normal">Created on ${formatAppDate(activity?.ACTIVITY_DATE)}</div>
                                            <div class="d-flex align-items-center gap-4">
                                                <a href="javascript:void(0)" title="Edit Activity" onclick="editCurrentActivityDetail('${activity?.ACTIVITY_TYPE?.toLowerCase()}',${activity?.ACTIVITY_ID})"><i class="fa-solid fa-file-pen cursor-pointer text-success fs-4"></i></a>
                                                <a href="javascript:void(0)" title="Delete Activity" onclick="deleteCurrentActivity(${activity?.ACTIVITY_ID})"><i class="fa-solid fa-trash cursor-pointer text-danger fs-4"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <p for="" class="mb-0 fw-bold">Due Date:<span class="text-gray-800 fw-normal"> ${formatAppDate(activity?.DUE_DATE)}</span></p>
                                            <p for="" class="mb-0 fw-bold">Priority:<span class="text-gray-800 fw-normal"> ${activity?.PRIORITY}</span></p>
                                            <p for="" class="mb-0 fw-bold">Task Description:</p>
                                            <p class="line-clamp-2 text-gray-800 mb-0">${stripHtmlTags(activity?.NOTES)}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed mb-4 mt-2"></div>`
}


function textInputElementLoadingAnimation(type = 'set') {
    const formElements = document.querySelectorAll(".deal-form-elements .form-control");
    if (type === 'set') {
        if (formElements && formElements.length > 0) {
            formElements.forEach((formElement) => {
                formElement.value = 'Loading ....'
                formElement.style.fontSize = '10px'
                formElement.disabled = true;
            })
        }
    } else {
        if (formElements && formElements.length > 0) {
            formElements.forEach((formElement) => {
                formElement.value = ''
                formElement.style.fontSize = '1.1rem'
                formElement.disabled = false;
            })
        }
    }
}

function startOver() {
    Swal.fire({
        title: "Are you sure?",
        text: "Starting a new deal will discard unsaved changes. Do you want to continue?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, start new deal",
        cancelButtonText: "Cancel",
        customClass: {
            popup: 'small-swal',
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Call the function to start a new DEAL
            startNewDeal();
        }
        // Do nothing if canceled (box automatically closes)
    });

}

function startNewDeal() {
    document.getElementById("UUID").value = uuid_v4();
    document.getElementById("DEAL_ID").value = '';
    activityContainer.innerHTML = newDealContent
    activityButtonContainer.classList.add("d-none")
    dealForm.reset()
    document.getElementById("DEAL_STATUS").value = 'new'
}
