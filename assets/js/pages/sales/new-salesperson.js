
const salesPersonModalForm = document.getElementById("salesPersonModalForm");
const fullPageLoader = document.getElementById("full-page-loader");

// Modal Related Code
var salesPersonModal = new bootstrap.Modal(document.getElementById("salesPersonModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

function openNewSalesPersonForm(action = 'new', salesPersonID = null) {
    hideErrors();
    if (action === 'new') {
        // reset form and then open 
        clearSalesPersonForm();
    } else {
        // Fetch minute Details
        fetchSalesPersonDetails(salesPersonID);
    }
    // Show NEw minute modal 
    salesPersonModal.show()
}
function clearSalesPersonForm() {
    document.getElementById("ID").value = '';
    salesPersonModalForm.reset()
}


// Submit  Minutes of Meetings
async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

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
        const salesPersonID = document.getElementById("ID").value;
        let url = `${APIUrl}/sales/`;
        if (salesPersonID) {
            url += `update_sales_person/${salesPersonID}`
        }
        else
            url += 'add_sales_person'
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
            toasterNotification({ type: 'success', message: "Minutes of Meeting Saved Successfully" });
            fetchSalesPersons();
            salesPersonModal.hide()
            clearSalesPersonForm();
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
    let content = `<tr data-minute-id="${data?.ID}" class="">
        <td>${data?.MEETING_TITLE}</td>
        <td>${formatAppDate(data?.MEETING_DATE)}</td>
        <td>${data?.DURATION}</td>
        <td class=""><small class="badge bg-light text-success fw-normal">${data?.LOCATION_PLATFORM}</small></td>
        <td>${data?.ORGANIZER}</td>
        <td class="line-clamp-1"><small class="d-flex align-items-center justify-content-start gap-1  flex-wrap text-gray-700 line-clamp-1">${showAttendees(data?.ATTENDEES || {})}</small></td>
        <td class="text-end">
            <div class="d-flex align-items-center justify-content-end gap-4">
                <a href="javascript:void(0)" onclick="openMomModal('edit', ${data?.ID})">
                    <small>
                        <i class="fs-5 fa-regular fa-pen-to-square text-primary"></i>
                    </small>
                </a>
                <a href="javascript:void(0)" onclick="deleteMinute(${data?.ID})">
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

async function fetchSalesPersonDetails(salesPersonID) {
    const apiUrl = `${APIUrl}/sales/sales_person_detail/${salesPersonID}`;
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
        fullPageLoader.classList.remove("d-none");

        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        displaySalesPersonData(data.data);

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
        console.error(error);
    } finally {
        fullPageLoader.classList.add("d-none");
    }
}


function displaySalesPersonData(data) {
    if (data) {
        if (Object.keys(data).length > 0) {
            populateFormFields(data);
        }
    } else {
        toasterNotification({ type: 'error', message: 'Sales person details not found' });
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
            startNewSalesPersonForm();
        }
        // Do nothing if canceled (box automatically closes)
    });

}
function startNewSalesPersonForm() {
    document.getElementById("ID").value = '';
    salesPersonModalForm.reset();
}