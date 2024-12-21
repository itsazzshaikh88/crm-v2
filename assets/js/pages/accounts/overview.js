document.addEventListener('DOMContentLoaded', () => {
    // Fetch User Profile
    fetchUserDetails();
});
const fullPageLoader = document.getElementById("full-page-loader")

async function fetchUserDetails() {
    const apiUrl = `${APIUrl}/users/user`;
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
            method: 'GET',
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
        showUserDetails(data.user);
    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function showUserDetails(data) {
    if (!data) {
        toasterNotification({ type: 'error', message: "User Details not found" });
        return;
    }

    if (Object.keys(data).length > 0) {
        const fullName = `${data?.FIRST_NAME} ${data?.LAST_NAME}`
        const dataToPopulate = { ...data, FULL_NAME: fullName }
        showFieldContent(dataToPopulate);
    }

    // Show 2FA Content
    show2FAStatusOfAccount(data?.IS_2FA_ENABLED);
}

function show2FAStatusOfAccount(action) {
    const container = document.getElementById("container-2fa");

    if (action && action == 1) {
        content = `<div class="notice d-flex bg-light-success rounded border-success border border-dashed  p-6 mt-10" id="container-2fa">
                        <!--begin::Icon-->
                        <i class="fa-solid fa-user-shield fs-2tx text-success me-4"></i>
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack flex-grow-1 ">
                            <!--begin::Content-->
                            <div class=" fw-semibold">
                                <h4 class="text-gray-900 fw-bold">Security Enhanced!</h4>
                                <div class="fs-6 text-gray-700 ">Two-Factor Authentication has been successfully activated on your account. This added layer of <span class="fw-bold">protection</span> ensures only you can access your account. Keep your authentication app or backup codes secure for uninterrupted access.</div>
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                    </div>`;
    } else {
        content = `<div class="notice d-flex bg-light-danger rounded border-danger border border-dashed  p-6 mt-10" id="container-2fa">
                        <!--begin::Icon-->
                        <i class="fa-solid fa-exclamation-circle fs-2tx text-danger me-4"></i>
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack flex-grow-1 ">
                            <!--begin::Content-->
                            <div class=" fw-semibold">
                                <h4 class="text-gray-900 fw-bold">Action Required!</h4>
                                <div class="fs-6 text-gray-700 ">Two-Factor Authentication has been disabled for your account. Without this additional security measure, your account is more vulnerable to <span class="fw-bold">unauthorized access</span>. We highly recommend re-enabling 2FA to maintain optimal security.</div>
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                    </div>`;
    }

    container.outerHTML = content;
}