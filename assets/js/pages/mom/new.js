
const activityContainer = document.getElementById("activity-container")
const activityButtonContainer = document.getElementById("activity-button-container")
const momForm = document.getElementById("momForm");

// Modal Related Code
var newMomModal = new bootstrap.Modal(document.getElementById("newMomModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

function openMomModal(action = 'new', momID = null) {
    if (action === 'new') {
        // reset form and then open 
        momForm.reset()
    } else {
        // Fetch minute Details
        fetchMOM(momID);
    }
    // Show NEw minute modal 
    newMomModal.show()
}
function closeMOMModal() {
    document.getElementById("MOM_ID").value = '';
    momForm.reset()
    const tbody = document.getElementById('attendee-table-tbody');
    tbody.innerHTML = ''
}


// Append and Remove Attendee
function addMeetingAttendee() {
    const tbody = document.getElementById('attendee-table-tbody');

    // Create a new row
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td></td>
        <td>
            <input type="text" class="form-control form-control-sm fs-8 text-gray-700 fw-normal" id="" name="attendee_name[]">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm fs-8 text-gray-700 fw-normal" id="" name="attendee_email[]">
        </td>
        <td class="d-flex align-items-center justify-content-center" style="vertical-align: middle;">
            <div class="d-flex align-items-center justify-content-center text-center">
                <a href="javascript:void(0)" onclick="removeMeetingAttendee(this)" class="d-flex align-items-center justify-content-center text-center py-2 px-2 text-danger"><i class="fa fa-trash p-0 m-0 text-danger"></i></a>
            </div>
        </td>
    `;
    tbody.appendChild(newRow);

    // Reorder the serial numbers
    reorderSerialNumbers();
}

function removeMeetingAttendee(button) {
    const row = button.closest('tr'); // Get the parent row
    row.remove(); // Remove the row

    // Reorder the serial numbers
    reorderSerialNumbers();
}

function reorderSerialNumbers() {
    const rows = document.querySelectorAll('#attendee-table-tbody tr');
    rows.forEach((row, index) => {
        row.children[0].textContent = index + 1; // Set the serial number
    });
}

// Submit  Minutes of Meetings
async function submitMinutes(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Saving Minutes ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const mom_id = document.getElementById("MOM_ID").value;
        let url = `${APIUrl}/mom/`;
        if (mom_id) {
            url += `update/${mom_id}`
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
                showSavedMOM(data?.data);
                startNewMinute();
                toasterNotification({ type: 'success', message: "Minutes of Meeting Saved Successfully" });
            } else if (data?.type == 'update') {
                // Data is updated
                showSavedMOM(data?.data, mom_id);
                toasterNotification({ type: 'success', message: "Minutes of Meeting Updated Successfully" });
            } else {
                toasterNotification({ type: 'error', message: 'Internal Server Error' });
            }
            newMomModal.hide()
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
// function to show data in the list
function showSavedMOM(data = [], rowID = null) {
    if (!data) return '';
    const tableBody = document.querySelector(`#mom-list tbody`);
    let content = `<tr data-minute-id="${data?.MOM_ID}" class="">
        <td>${data?.MEETING_TITLE}</td>
        <td>${formatAppDate(data?.MEETING_DATE)}</td>
        <td>${data?.DURATION}</td>
        <td class=""><small class="badge bg-light text-success fw-normal">${data?.LOCATION_PLATFORM}</small></td>
        <td>${data?.ORGANIZER}</td>
        <td class="line-clamp-1"><small class="d-flex align-items-center justify-content-start gap-1  flex-wrap text-gray-700 line-clamp-1">${showAttendees(data?.ATTENDEES || {})}</small></td>
        <td class="text-end">
            <div class="d-flex align-items-center justify-content-end gap-4">
                <a href="javascript:void(0)" onclick="openMomModal('edit', ${data?.MOM_ID})">
                    <small>
                        <i class="fs-5 fa-regular fa-pen-to-square text-primary"></i>
                    </small>
                </a>
                <a href="javascript:void(0)" onclick="deleteMinute(${data?.MOM_ID})">
                    <small>
                        <i class="fs-5 fa-solid fa-trash-can text-danger"></i>
                    </small>
                </a>
            </div>
        </td>
    </tr>`;
    if (rowID === null) {
        tableBody.insertAdjacentHTML("afterbegin", content);
    } else {
        const row = document.querySelector(`#mom-list-tbody tr[data-minute-id="${rowID}"]`);
        row.outerHTML = content;
    }


}

async function fetchMOM(momID) {
    const apiUrl = `${APIUrl}/mom/detail`;
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

        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ momID })
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

        displayMOMData(data.data);

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
        console.error(error);

    }
}

function textInputElementLoadingAnimation(type = 'set') {
    const formElements = document.querySelectorAll(".mom-form-elements .form-control");
    if (type === 'set') {
        if (formElements && formElements.length > 0) {
            formElements.forEach((formElement) => {
                formElement.value = 'Loading ....'
                formElement.disabled = true;
            })
        }
    } else {
        if (formElements && formElements.length > 0) {
            formElements.forEach((formElement) => {
                formElement.value = ''
                formElement.disabled = false;
            })
        }
    }
}

function displayMOMData(data) {
    if (data) {
        if (Object.keys(data).length > 0) {
            populateFormFields(data);

            // Show Meeting attendees
            showMeetingAttendees(data?.ATTENDEES || {});

            // Set follow up required or not
            let checkBox = document.getElementById("FOLLOW_UP_REQUIRED");
            if (data?.FOLLOW_UP_REQUIRED && data?.FOLLOW_UP_REQUIRED == 1)
                checkBox.checked = true;
            else
                checkBox.checked = false;

        }
    } else {
        toasterNotification({ type: 'error', message: 'Minutes of Meeting Details not found.' });
    }
}

function showMeetingAttendees(data) {
    if (!data) return '';
    let attendees = JSON.parse(data);
    if (attendees && attendees.length > 0) {
        const tbody = document.getElementById('attendee-table-tbody');
        tbody.innerHTML = ''
        attendees.forEach((attendee) => {
            // Create a new row
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td></td>
                <td>
                    <input type="text" class="form-control form-control-sm fs-8 text-gray-700 fw-normal" id="" name="attendee_name[]" value="${attendee?.name}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm fs-8 text-gray-700 fw-normal" id="" name="attendee_email[]" value="${attendee?.email}">
                </td>
                <td class="d-flex align-items-center justify-content-center" style="vertical-align: middle;">
                    <div class="d-flex align-items-center justify-content-center text-center">
                        <a href="javascript:void(0)" onclick="removeMeetingAttendee(this)" class="d-flex align-items-center justify-content-center text-center py-2 px-2 text-danger"><i class="fa fa-trash p-0 m-0 text-danger"></i></a>
                        </div>
                    </td>
                    `;
            tbody.appendChild(newRow);
        })


        // Reorder the serial numbers
        reorderSerialNumbers();
    }


}

function startOver() {
    Swal.fire({
        title: "Are you sure?",
        text: "Starting a new minutes will discard unsaved changes. Do you want to continue?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, start new minute",
        cancelButtonText: "Cancel",
        customClass: {
            popup: 'small-swal',
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Call the function to start a new minute
            startNewMinute();
        }
        // Do nothing if canceled (box automatically closes)
    });

}
function startNewMinute() {
    document.getElementById("MOM_ID").value = '';
    momForm.reset()
    const tbody = document.getElementById('attendee-table-tbody');
    tbody.innerHTML = ''

}