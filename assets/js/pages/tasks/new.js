// Function to send a request with Bearer token and display response
async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Saving Task ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const taskID = document.getElementById("ID").value;
        let url = `${APIUrl}/tasks/`;
        if (taskID)
            url += `update/${taskID}`;
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
            toasterNotification({ type: 'success', message: "Task Saved Successfully!" });
            if (data?.type === 'insert') {
            }
            resetTaskForm();
            taskModal.hide();
            fetchTasks();
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

async function fetchTaskDetailsToEdit(taskId) {
    const apiUrl = `${APIUrl}/tasks/detail/${taskId}`;
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({
            type: 'error',
            message: "Authorization token is missing. Please login again to make an API request."
        });
        return;
    }
    // Show loader

    fullPageLoader.classList.remove("d-none");
    try {

        fullPageLoader.classList.toggle("d-none");
        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
            }
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        displayTaskInfo(data.data);

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.add("d-none");
    }
}

function displayTaskInfo(data) {
    if (!data) return;

    if (Object.keys(data).length > 0) {
        populateFormFields(data);
    }
}

// New: Set consultant data and show clear icon
function setSalesPerson(index) {
    const person = fetchedSalesPersons?.[index];
    if (!person) return;

    const name = `${person.FIRST_NAME || ''} ${person.LAST_NAME || ''}`.trim();
    document.getElementById("CONSULTANT").value = name;
    document.getElementById("CONSULTANT_ID").value = person.ID || '';

    salesPersonListModal?.hide?.();
    toggleClearIcon('CONSULTANT', 'clearConsultant');
}

function clearConsultantDetails() {
    document.getElementById("CONSULTANT").value = '';
    document.getElementById("CONSULTANT_ID").value = '';
    toggleClearIcon('CONSULTANT', 'clearConsultant');
}

// Utility to toggle clear icon visibility based on input value
function toggleClearIcon(inputId, clearIconId) {
    const inputVal = document.getElementById(inputId).value;
    const icon = document.getElementById(clearIconId);
    if (inputVal && inputVal.trim() !== '') {
        icon.style.display = 'inline';
    } else {
        icon.style.display = 'none';
    }
}