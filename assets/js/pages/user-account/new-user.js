var newUserModal = new bootstrap.Modal(document.getElementById("create-new-user-modal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});
const form = document.getElementById("form");
const fullPageLoader = document.getElementById("full-page-loader")
function closeNewUserModal() {
    form.reset()
    document.getElementById("STATUS").value = 'active';
    document.getElementById("ID").value = '';

    const elementsToHide = document.querySelectorAll(".elements-to-hide");
    toggleElements(elementsToHide, 'show');
}

function openUserModal(action = 'new', userID = null) {
    if (action === 'new') {
        // reset form and then open 
        form.reset()
        document.getElementById("STATUS").value = 'active';
        document.getElementById("ID").value = '';
        // Set new lead content
    } else {
        // Fetch User Details
        fetchUser(userID);
    }
    // Show NEw Lead modal  
    newUserModal.show()
}

async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    // Attach selected files
    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Creating New User ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const userID = document.getElementById("ID").value;


        let url = `${APIUrl}/users/`;
        if (userID)
            url += `update/${userID}`;
        else
            url += 'new';
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
            toasterNotification({ type: 'success', message: data?.message || "User Saved Successfully" });

            if (data?.type === 'insert') {
                form.reset();
                closeNewUserModal();
                newUserModal.hide();
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

async function fetchUser(userID) {
    const apiUrl = `${APIUrl}/users/detail`;
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
            body: JSON.stringify({ userID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        // Display the product information on the page if response is successful
        showUserDetails(data.data);
    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function showUserDetails(data) {
    if (!data) return;

    if (Object.keys(data).length > 0) {
        populateFormFields(data);
    }

    // Disable or remove password and confirm password from the list
    const elementsToHide = document.querySelectorAll(".elements-to-hide");
    toggleElements(elementsToHide, 'hide');
}

function toggleElements(elements, action) {
    if (!elements) return;
    if (elements?.length > 0) {
        elements.forEach(element => {
            action === 'hide' ? element.classList.add("d-none") : element.classList.remove("d-none");
        });
    }
}