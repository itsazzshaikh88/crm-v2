const resetPasswordForm = document.getElementById("resetUserPasswordForm")
const resetPasswordDrawer = document.getElementById("app_user_reset_password_drawer")
const passwordFormContainer = document.getElementById("app_user_reset_password_scroll")
const skeletonContainer = document.getElementById("app_skeleton_container")

function clearResetPasswordForm() {
    form.reset();
    document.getElementById("HIDDEN_USER_ID").value = '';
    toggleSkeletonCotainer('hide');
}
function closeResetPasswordDrawer() {
    clearResetPasswordForm();
    resetPasswordDrawer.classList.remove("drawer-on");
}

function toggleSkeletonCotainer(action = 'hide') {
    if (action === 'show') {
        skeletonContainer.classList.remove("d-none")
        passwordFormContainer.classList.add("d-none")
    } else {
        skeletonContainer.classList.add("d-none")
        passwordFormContainer.classList.remove("d-none")
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

        toggleSkeletonCotainer('show');

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
        document.getElementById("HIDDEN_USER_ID").value = data?.data?.ID || 0;
        document.getElementById("lbl-username").innerHTML = `${data?.data?.FIRST_NAME} ${data?.data?.LAST_NAME}`
    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        toggleSkeletonCotainer('hide');
    }
}


function openResetPasswordDrawer(userid) {
    resetPasswordDrawer.classList.add("drawer-on");
    fetchUser(userid);
}

async function resetUserPassword(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    // Attach selected files
    // Set Loading Animation on button
    const submitBtn = document.getElementById("reset-password-submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Updating Password ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const userID = document.getElementById("HIDDEN_USER_ID").value;


        let url = `${APIUrl}/users/reset_password/${userID}`;
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
            toasterNotification({ type: 'success', message: data?.message || "User password reset successfully" });

            if (data?.success) {
                form.reset();
                closeResetPasswordDrawer();
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