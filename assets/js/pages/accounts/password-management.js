const passwordContainer = document.getElementById("app_signin_password");
const passwordContainerEdit = document.getElementById("app_signin_password_edit");
const resetPasswordButton = document.getElementById("app_signin_password_button");
const enable2FAButton = document.getElementById("enable2FAButton");
// Toggle password edit and password container
function togglePasswordEdit(action) {
    if (!action) return;
    passwordContainer.classList.toggle("d-none");
    passwordContainerEdit.classList.toggle("d-none");
    resetPasswordButton.classList.toggle("d-none");
}

// Update Password function
async function updatePassword(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
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
        let url = `${APIUrl}/account/update_password`;
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
            if (data?.type === 'update') {
                toasterNotification({ type: 'success', message: "Password Updated Successfully." });
                // Toggle classes
                togglePasswordEdit('hide');
            } else {
                toasterNotification({ type: 'error', message: 'Failed to update password.' });
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
        form.reset();
        submitBtn.disabled = false;
        submitBtn.innerHTML = buttonText;
    }
}

// Fetch Client Profile
async function fetchUserProfile() {
    // Retrieve the auth_token from cookies
    const authToken = getCookie('auth_token');
    if (!authToken) {
        alert("Authorization token is missing. Please Login again to make API request.");
        return;
    }

    try {

        setLoadingAnimationForUserProfile();

        // Fetch categories from the API (replace 'your-api-endpoint' with the actual API URL)
        const response = await fetch(`${APIUrl}/account/profile`, {
            method: 'GET', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        // Check if the response is okay (status code 200-299)
        if (!response.ok) {
            throw new Error('Failed to fetch user profile');
        }

        // Parse the JSON response
        const data = await response.json();
        showUserProfileDetails(data?.user || {});

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed:' + error });
    } finally {

    }
}
const emailElement = document.getElementById("loggedInUserEmailPlaceholder");
function setLoadingAnimationForUserProfile() {
    if (emailElement)
        emailElement.innerHTML = `<div style="width: 200px; height: 20px"; class="skeleton-box"></div>`
}

function showUserProfileDetails(user) {
    if (!user) return '';

    if (emailElement)
        emailElement.innerHTML = user?.EMAIL || '';
}

document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchUserProfile();
});

// Enable - Disable Button
async function enable2FA(action) {

    // Set Loading Animation on button
    const enable2FAButton = document.getElementById("submit-btn");
    let buttonText = enable2FAButton.innerHTML;
    enable2FAButton.disabled = true;
    enable2FAButton.innerHTML = action === 'enable' ? "Enabling 2FA Account ..." : "Disabling 2FA Account ...";

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        let url = `${APIUrl}/account/multifactor/${action}`;
        // Fetch API with Bearer token in Authorization header
        const response = await fetch(url, {
            method: 'POST', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });


        // Check if the response is OK (status 200-299)
        if (response.ok) {
            const data = await response.json();
            if (data?.type === 'update') {
                toasterNotification({ type: 'success', message: "Password Updated Successfully." });
                // Toggle classes
                togglePasswordEdit('hide');
            } else {
                toasterNotification({ type: 'error', message: 'Failed to update password.' });
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
    }
}

function toggleEnableDisableButton(action) {
    if (action === 'enable') {
        enable2FAButton.innerHTML = 'Disable';
        enable2FAButton.classList.remove("btn-primary");
        enable2FAButton.classList.add("btn-danger");
        enable2FAButton.setAttribute("onclick", "enable2FA('disable')");
    } else {
        enable2FAButton.innerHTML = 'Enable';
        enable2FAButton.classList.remove("btn-danger");
        enable2FAButton.classList.add("btn-primary");
        enable2FAButton.setAttribute("onclick", "enable2FA('enable')");
    }
}
