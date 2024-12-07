let currentModal = new bootstrap.Modal(document.getElementById("custom-common-activity-modal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

// Quill Editor Initialization
let quillInstance;
let quillOptions = {
    theme: 'snow',
    placeholder: 'Write your note content here ...',
};

let currentOpenedModal;
let meetingAttendees = []
const meetingAttendeesContainer = document.getElementById("meeting-attendees-container")

function initializeQuill(editorId = 'quill-editor', options = quillOptions, predefinedContent = '') {
    const editorElement = document.getElementById(editorId);
    if (currentOpenedModal === 'custom-activity-modal-meeting')
        quillOptions.placeholder = 'Write your meeting outcomes'
    // Check if Quill is already initialized
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


const activityModals = ['custom-activity-modal-call', 'custom-activity-modal-meeting', 'custom-activity-modal-event', 'custom-activity-modal-task', 'custom-activity-modal-notes'];

const activityTypes = {
    'call': 'custom-activity-modal-call',
    'meeting': 'custom-activity-modal-meeting',
    'notes': 'custom-activity-modal-notes'
}

function openActivityModal(modalID) {
    currentOpenedModal = modalID
    // Assign lead ID to the modal
    document.getElementById(`${modalID}-lbl-LEAD_NUMBER`).value = document.getElementById("LEAD_NUMBER").value || ''
    document.getElementById(`${modalID}-ACTIVITY_UUID`).value = uuid_v4()
    document.getElementById(`${modalID}-ACTIVITY_LEAD_ID`).value = document.getElementById("LEAD_ID").value || ''
    // Show Modal 
    toggleActivityModals(modalID);
    // Initialize Quill editor
    initializeQuill(`${modalID}-editor`)
    currentModal.show()
}

function openActivityModalForEdit(modalID) {
    currentOpenedModal = modalID
    // Assign lead ID to the modal
    // Show Modal 
    toggleActivityModals(modalID);
    currentModal.show()
}


function toggleActivityModals(modalID = null) {
    // hide other contents and only show current content 
    activityModals.forEach((mdl) => {
        const element = document.getElementById(mdl);
        element.classList.add("d-none")
        if (modalID === mdl)
            element.classList.remove("d-none")
    })
}

function resetActivityModal() {
    // hide other contents and only show current content 
    // Assign lead ID to the modal
    document.getElementById(`${currentOpenedModal}-lbl-LEAD_NUMBER`).value = ''
    document.getElementById(`${currentOpenedModal}-ACTIVITY_UUID`).value = ''
    document.getElementById(`${currentOpenedModal}-ACTIVITY_LEAD_ID`).value = ''
    // remove notes content if any
    const qlEditorContentHolder = document.querySelector(".ql-editor");
    const qlEditorTooltipHolder = document.querySelector(".ql-tooltip");
    if (qlEditorContentHolder)
        qlEditorContentHolder.remove()
    if (qlEditorTooltipHolder)
        qlEditorTooltipHolder.remove()
    meetingAttendees = []
    // remove attached attendies s well 
    if (meetingAttendeesContainer)
        meetingAttendeesContainer.innerHTML = ''
    toggleActivityModals()
}


// Add Activity

async function addLeadActivity(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const ACTION = currentOpenedModal.split("-").pop()?.toLowerCase();
    if (ACTION)
        formData.append("ACTION", ACTION)

    if (ACTION === 'meeting') {
        let meetingAttendeesToAppend = ''
        if (meetingAttendees && meetingAttendees.length > 0)
            meetingAttendeesToAppend = meetingAttendees.join(",")
        formData.append("ATTENDEES", meetingAttendeesToAppend);
    }

    // Check if notes available then attach notes
    const qlEditorContentHolder = document.querySelector(`#${currentOpenedModal} .ql-editor`);
    if (qlEditorContentHolder) {
        const notes = (qlEditorContentHolder.innerHTML == '<p><br></p>' ? '' : qlEditorContentHolder.innerHTML) || '';
        formData.append('NOTES', notes);
    }


    // Set Loading Animation on button
    const submitBtn = document.getElementById("btn-add-activities");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Saving ...`;

    // Hide Error
    hideErrors('act-lbl');
    // try {
    // Retrieve the auth_token from cookies
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
        return;
    }
    const activity_id = document.getElementById(`custom-activity-modal-${ACTION}-ACTIVITY_ID`).value;
    let url = `${APIUrl}/activities/`;
    if (activity_id) {
        url += `update/${activity_id}`
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
            setActivitySaved(data?.data, 'new');
            toasterNotification({ type: 'success', message: data?.message });
            // reset form and close modal
            form.reset()
        } else if (data?.type == 'update') {
            // Data is updated
            setActivitySaved(data?.data, 'update');
            toasterNotification({ type: 'success', message: data?.message });
        } else {
            toasterNotification({ type: 'error', message: 'Internal Server Error' });
        }
        currentModal.hide()
    } else {
        const errorData = await response.json();
        if (errorData.status === 422) {
            showErrors(errorData.validation_errors ?? [], 'act-lbl');
        } else {
            toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
        }
    }
    // } catch (error) {
    //     toasterNotification({ type: 'error', message: 'Request failed:' + error });
    // } finally {

    // }
    submitBtn.disabled = false;
    submitBtn.innerHTML = buttonText;
}

function setActivitySaved(data, action) {
    if (!data) return null;
    let activityContent = '';
    if (data?.activity?.ACTIVITY_TYPE?.toLowerCase() === 'call') {
        activityContent = showNewCallLogsActivity(data || {}, action)
    } else if (data?.activity?.ACTIVITY_TYPE?.toLowerCase() === 'meeting') {
        activityContent = showNewMeetingActivity(data || {}, action)
    } else if (data?.activity?.ACTIVITY_TYPE?.toLowerCase() === 'notes') {
        activityContent = showNewNotesActivity(data || {}, action)
    }

    const activityContainer = document.getElementById("activity-container")
    if (activityContainer) {
        if (action === 'new') {
            activityContainer.insertAdjacentHTML("afterbegin", activityContent);
        } else if (action === 'update') {
            const activityID = data?.activity?.ACTIVITY_ID;
            if (activityID) {
                const elementToUpdate = document.getElementById(`activity-inline-container-${activityID}`);
                if (elementToUpdate) {
                    // Update the HTML content of the existing element
                    elementToUpdate.outerHTML = activityContent;
                }
            }
        }
    }

}

function showNewCallLogsActivity(activityData, action) {
    if (!activityData) return '';
    const { activity, details } = activityData;
    let content = `<div class="position-relative ps-6 pe-3 py-2 bg-gray-50s" id="activity-inline-container-${activity?.ACTIVITY_ID}">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-warning"></div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0)" class="mb-1 text-hover-primary fw-bold badge bg-warning text-white"><i class="fa-solid fa-headset text-white"></i> Call Log</a>
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
                                            <p for="" class="mb-0 fw-bold">Call Purpose:<span class="text-gray-800 fw-normal "> ${details?.CALL_PURPOSE}</span></p>
                                            <p for="" class="mb-0 fw-bold">Call Duration:<span class="text-gray-800 fw-normal "> ${details?.CALL_DURATION}</span></p>
                                            <p for="" class="mb-0 fw-bold">Follow-up Date:<span class="text-gray-800 fw-normal "> ${formatAppDate(details?.FOLLOW_UP_DATE)}</span></p>
                                            <p for="" class="mb-0 fw-bold">Note:</p>
                                            <p class="line-clamp-2 text-gray-800 mb-0">${stripHtmlTags(activity?.NOTES)}</p>
                                        </div>
                                    </div>
                                </div>
                                `;
    if (action === 'new')
        content += `<div class="separator separator-dashed mb-4 mt-2"></div>`;
    return content;

}
function showNewNotesActivity(activityData, action) {
    if (!activityData) return '';
    const { activity, details } = activityData;
    let content = `<div class="position-relative ps-6 pe-3 py-2 bg-gray-50s" id="activity-inline-container-${activity?.ACTIVITY_ID}">
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
                                            <p class="line-clamp-2 text-gray-800 mb-0">${stripHtmlTags(details?.NOTE_CONTENT)}</p>
                                        </div>
                                    </div>
                                </div>
                                `;
    if (action === 'new')
        content += `<div class="separator separator-dashed mb-4 mt-2"></div>`;
    return content;
}
function showNewMeetingActivity(activityData, action) {
    if (!activityData) return '';
    const { activity, details } = activityData;
    let content = `<div class="position-relative ps-6 pe-3 py-2 bg-gray-50s" id="activity-inline-container-${activity?.ACTIVITY_ID}">
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
                                            <p for="" class="mb-0 fw-bold">Agenda:<span class="text-gray-800 fw-normal line-clamp-2"> ${details?.AGENDA}</span></p>
                                            <p for="" class="mb-0 fw-bold">Location:<span class="text-gray-800 fw-normal line-clamp-2"> ${details?.LOCATION}</span></p>
                                            <p for="" class="mb-0 fw-bold">Attended By:<span class="text-gray-800 fw-normal "> ${details?.ATTENDEES}</span></p>
                                            <p for="" class="mb-0 fw-bold">Meeting Outcome:</p>
                                            <p class="line-clamp-2 text-gray-800 mb-0">${stripHtmlTags(details?.OUTCOME)}</p>
                                        </div>
                                    </div>
                                </div>
                                `;
    if (action === 'new')
        content += `<div class="separator separator-dashed mb-4 mt-2"></div>`;
    return content;
}



// Meeting attendees

function setMeetingAttendees(event) {
    if (event.key === ',') {
        const attendee = event.target.value.slice(0, -1).trim(); // Remove the trailing ',' and trim spaces
        if (attendee) { // Only add non-empty values
            meetingAttendees.push(attendee);
            updateMeetingAttendees(); // Update the UI
        }
        event.target.value = ''; // Clear the input field
    }
}


function updateMeetingAttendees() {
    let meetingAttendeesContent = '';
    if (meetingAttendees && meetingAttendees.length > 0) {
        meetingAttendees.forEach((atnd, index) => {
            meetingAttendeesContent += `<div class="inline-flex align-items-center justify-content-between badge bg-white border border-blue-100 text-black mb-1 me-2">
                            <span class="fs-8 mb-0 fw-normal me-4 text-info">${atnd}</span>
                            <span onclick="removeMeetingAttendee(${index})"><i class="fa-solid fa-xmark fs-7 text-danger cursor-pointer mb-0"></i></span>
                        </div>`;
        });
    }
    meetingAttendeesContainer.innerHTML = meetingAttendeesContent;
}

function removeMeetingAttendee(index) {
    if (typeof index === "number" && index >= 0 && index < meetingAttendees.length) {
        meetingAttendees.splice(index, 1);
        updateMeetingAttendees();
    }
}

// Edit activities and show details
// Edit Activity Details and edit activity
async function editCurrentActivityDetail(mode = null, activityID = null) {
    if (!mode || !activityID) return;
    // Open Activity Modal and Show Information to Edit

    openActivityModalForEdit(activityTypes[mode]);

    const apiUrl = `${APIUrl}/activities/detail`;
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
        const activityLoader = document.querySelector(`.${mode}-activity-loader-container`);
        activityLoader.classList.remove('d-none')
        const modalID = activityTypes[mode];
        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ activityID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        displayActivityData(data.data || {}, modalID);
        // Toggle modal contents
        toggleActivityModals(modalID);
    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });


    } finally {
        // disable all animation
        const activityLoaders = document.querySelectorAll(".activity-loader-container");
        activityLoaders.forEach((loader) => loader.classList.add('d-none'));
    }
}

function displayActivityData(activityData, modalID) {
    if (!activityData) {
        toasterNotification({ type: 'error', message: 'Activity details not found.' });
        return '';
    }
    const { activity, details } = activityData;
    if (activity?.ACTIVITY_TYPE?.toLowerCase() === 'call') {
        if (Object.keys(details).length > 0) {
            populateFormFields(details);
        }
    } else if (activity?.ACTIVITY_TYPE?.toLowerCase() === 'notes') {

    } else if (activity?.ACTIVITY_TYPE?.toLowerCase() === 'meeting') {
        if (Object.keys(details).length > 0) {
            populateFormFields(details);
        }
        // Set Meeting attendees
        if (details?.ATTENDEES)
            meetingAttendees = details?.ATTENDEES?.split(",");
        updateMeetingAttendees()
    }
    // Show activity ID, Lead Number and Source
    document.getElementById(`${modalID}-lbl-LEAD_NUMBER`).value = document.getElementById("LEAD_NUMBER").value || ''
    document.getElementById(`${modalID}-ACTIVITY_UUID`).value = activity?.UUID;
    document.getElementById(`${modalID}-ACTIVITY_ID`).value = activity?.ACTIVITY_ID;
    document.getElementById(`${modalID}-ACTIVITY_LEAD_ID`).value = document.getElementById("LEAD_ID").value || ''
    // Show Modal 
    toggleActivityModals(modalID);
    // Initialize Quill 
    let notes = '';
    if (activity?.ACTIVITY_TYPE?.toLowerCase() === 'call')
        notes = activity?.NOTES;
    else if (activity?.ACTIVITY_TYPE?.toLowerCase() === 'meeting')
        notes = details?.OUTCOME;
    else if (activity?.ACTIVITY_TYPE?.toLowerCase() === 'notes')
        notes = details?.NOTE_CONTENT;
    // Initialize Quill editor
    initializeQuill(`${modalID}-editor`, quillOptions, notes)
}

async function deleteCurrentActivity(activityID) {
    if (!activityID) {
        toasterNotification({ type: 'error', message: "Activity ID is required." });
        return;
    }

    try {
        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this activity? This action cannot be undone.",
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
            title: "Deleting activity...",
            text: "Please wait while the activity is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        // Make the delete request
        const response = await fetch(`${APIUrl}/activities/delete/${activityID}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
        });

        // Parse the response
        const result = await response.json();

        // Close the loading alert box
        Swal.close();

        // Handle success or error based on the response
        if (response.ok && result.status) {
            // Show a success notification
            toasterNotification({ type: 'success', message: result.message });
            // remove the current activity from the list as well
            const elementToRemove = document.getElementById(`activity-inline-container-${activityID}`);
            if (elementToRemove)
                elementToRemove.remove()
        } else {
            // Show an error notification with the server's error message
            toasterNotification({ type: 'error', message: result.message || 'Failed to delete the activity.' });
        }
    } catch (error) {
        // Close the loading alert box
        Swal.close();
        // Handle unexpected errors
        toasterNotification({ type: 'error', message: `Error deleting activity: ${error.message}` });
    }
}
