const passwordContainer = document.getElementById("app_signin_password");
const passwordContainerEdit = document.getElementById("app_signin_password_edit");
const resetPasswordButton = document.getElementById("app_signin_password_button");
const enable2FAButton = document.getElementById("enable2FAButton");
const enable2FAContainer = document.getElementById("container-enable-disable-2fa");
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
        console.error(error);
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
        showUserProfileDetails(data || {});

    } catch (error) {
        console.error(error);
        toasterNotification({ type: 'error', message: 'Request failed:' + error });
    } finally {

    }
}

function toggle2FAEnableDisable(action, mfa) {
    let content = get2FAActions(action);
    enable2FAContainer.innerHTML = `${content}`;

    const classesSecondary = ['text-secondary', 'bg-light-secondary', 'btn-secondary', 'border-secondary'];
    const classesPrimary = ['text-primary', 'bg-light-primary', 'btn-primary', 'border-primary'];
    const classesDanger = ['text-danger', 'bg-light-danger', 'btn-danger', 'border-danger'];

    // Combine all classes into a single array
    const classes = [...classesSecondary, ...classesPrimary, ...classesDanger];

    // Remove existing classes
    classes.forEach((className) => enable2FAContainer.classList.remove(className));

    // Add new classes based on the action
    const classesToAppend = action === 'enable' ? classesPrimary : classesDanger;
    classesToAppend.forEach((classToAppend) => enable2FAContainer.classList.add(classToAppend));

    // Show MFA Details (if required, add logic here)
    showMFADetails(mfa, action);
}

function showMFADetails(mfa, action) {
    const mfaDetailContainer = document.getElementById("totp-setup");
    if (action === 'disable') {
        mfaDetailContainer.innerHTML = `<h2 class="text-primary">Set Up Two-Factor Authentication</h2>
                            <p>Follow the steps below to set up two-factor authentication for your account:</p>

                            <ol>
                                <li>
                                    Download a TOTP app (e.g., Google Authenticator, Authy) on your mobile device.
                                </li>
                                <li>
                                    Scan the QR code below using your TOTP app:
                                    <div class="qr-code-container my-4">
                                        <!-- The QR Code image is dynamically rendered here -->
                                        <img src="${mfa?.QR_DATA}" alt="QR Code for TOTP Setup" class="img-fluid w-200 h-200" />
                                    </div>
                                </li>
                                <li>
                                    Alternatively, manually enter the secret key into your TOTP app:
                                    <div class="secret-key-container my-3 p-3 bg-light border rounded">
                                        <strong>Secret Key:</strong>
                                        <code id="authenticator-secret-key" class="fs-4">${mfa?.TOTP_SECRET}</code>
                                    </div>
                                </li>
                                <li>
                                    Enter the 6-digit code generated by your app to verify the setup.
                                </li>
                            </ol>

                            <form id="verify-totp-form" onsubmit="verifyTOTP(event)" class="bg-light rounded border-secondary border border-dashed p-6">
                                <div class="form-group my-3 mb-2">
                                    <label for="totp-code" class="mb-2">Verification Code</label>
                                    <div class="d-flex gap-4">
                                        <input type="text" id="totp-code" name="TOTP_CODE" class="form-control flex-1" placeholder="Enter 6-digit code" />
                                        <button type="submit" id="verify-totp-button" class="btn btn-primary ">Verify</button>
                                    </div>
                                </div>
                            </form>`;
    } else {
        mfaDetailContainer.innerHTML = ''
    }
}


const emailElement = document.getElementById("loggedInUserEmailPlaceholder");
function setLoadingAnimationForUserProfile() {
    if (emailElement)
        emailElement.innerHTML = `<div style="width: 200px; height: 20px"; class="skeleton-box"></div>`
}

function showUserProfileDetails(userDetails) {
    if (!userDetails) return '';
    const { user, mfa } = userDetails;
    if (emailElement)
        emailElement.innerHTML = user?.EMAIL || '';

    // Toggle Enable / Disable 2 FA
    const action = user?.IS_2FA_ENABLED && user?.IS_2FA_ENABLED == 1 ? 'disable' : 'enable';

    toggle2FAEnableDisable(action, mfa);
}

document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchUserProfile();
});

// Enable - Disable Button
async function enable2FA(action) {
    const enable2FAButton = document.getElementById("submit-btn");
    let buttonText = enable2FAButton.innerHTML;
    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: `Do you really want to ${capitalizeWords(action)} Two-Factor Authentication for your account?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: `Yes, ${capitalizeWords(action)} it`,
            cancelButtonText: "Cancel",
            customClass: {
                popup: 'small-swal',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
            },
        });

        if (!confirmation.isConfirmed) return;

        // Show a non-closable alert box while the activity is being deleted
        Swal.fire({
            title: "Processing ...",
            text: `Please wait while the two factor authentication is being ${action}d.`,
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        enable2FAButton.disabled = true;
        enable2FAButton.innerHTML = action === 'enable' ? "Enabling 2FA Account ..." : "Disabling 2FA Account ...";

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
            if (data?.action === 'success') {
                toasterNotification({ type: 'success', message: `Two Factor Authentication ${capitalizeWords(action)}d Successfully.` });

                toggle2FAEnableDisable(action === 'enable' ? 'disable' : 'enable', data?.mfa || {});

            } else {
                toasterNotification({ type: 'error', message: `Failed to ${action} two step authentication.` });
            }
        } else {
            const errorData = await response.json();
            toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
        }
        Swal.close();

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    } finally {
        enable2FAButton.disabled = false;
        enable2FAButton.innerHTML = buttonText;
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
