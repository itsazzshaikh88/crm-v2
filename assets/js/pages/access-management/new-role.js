var newRoleModal = new bootstrap.Modal(document.getElementById("create-new-role-modal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});
const form = document.getElementById("new-role-form");
const fullPageLoader = document.getElementById("full-page-loader")
function closeNewRoleModal() {
    form.reset()
    // document.getElementById("STATUS").value = 'active';
    document.getElementById("ID").value = '';
}

function openRoleModal(action = 'new', roleID = null) {
    hideErrors();
    if (action === 'new') {
        // reset form and then open 
        form.reset()
        // document.getElementById("STATUS").value = 'active';
        document.getElementById("ID").value = '';
        // Set new lead content

        document.getElementById("IS_ACTIVE").checked = true;
    } else {
        // Fetch Role Details
        fetchRole(roleID);
    }
    // Show NEw Lead modal  
    newRoleModal.show()
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
    submitBtn.innerHTML = `Creating New Role ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const roleID = document.getElementById("ID").value;

        let url = `${APIUrl}/roles/`;
        if (roleID)
            url += `update/${roleID}`;
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
            toasterNotification({ type: 'success', message: data?.message || "Role Saved Successfully" });

            form.reset();
            closeNewRoleModal();
            newRoleModal.hide();

            fetchRoles();
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

async function fetchRole(roleID) {
    const apiUrl = `${APIUrl}/roles/detail/${roleID}`;
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

        // Display the product information on the page if response is successful
        showRoleDetails(data.data);
    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function showRoleDetails(data) {
    if (!data) return;

    if (Object.keys(data).length > 0) {
        populateFormFields(data);

        // Check if the status is active
        document.getElementById("IS_ACTIVE").checked = (data?.IS_ACTIVE) && data?.IS_ACTIVE == '1' ? true : false;;
    }
}