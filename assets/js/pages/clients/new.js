
const fullPageLoader = document.getElementById("full-page-loader")

// Function to send a request with Bearer token and display response
async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Creating ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const client_id = document.getElementById("ID").value;
        let url = `${APIUrl}/clients/new`;
        if (client_id)
            url += `/${client_id}`
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
            toasterNotification({ type: 'success', message: "Client Details Saved Successfully!" });
            if (!client_id)
                form.reset();
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

async function fetchClients() {
    const categoryList = document.getElementById("CATEGORY_ID");
    const fetchCategoryLabel = document.getElementById("fetch-category-label");

    // Disable the select dropdown and show the loading label with animation
    categoryList.disabled = true;
    fetchCategoryLabel.classList.remove('d-none');
    fetchCategoryLabel.classList.add('anim-pulse');

    // Retrieve the auth_token from cookies
    const authToken = getCookie('auth_token');
    if (!authToken) {
        alert("Authorization token is missing. Please Login again to make API request.");
        return;
    }

    try {
        // Fetch categories from the API (replace 'your-api-endpoint' with the actual API URL)
        const response = await fetch(`${APIUrl}/categories/list`, {
            method: 'GET', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        // Check if the response is okay (status code 200-299)
        if (!response.ok) {
            throw new Error('Failed to fetch categories');
        }

        // Parse the JSON response
        const categories = await response.json();

        // Clear existing options
        categoryList.innerHTML = '<option value="">Select</option>';

        // Populate the <select> with category options
        categories.forEach(category => {
            const option = document.createElement("option");
            option.value = category.ID; // Adjust to match the category ID key
            option.textContent = category.CATEGORY_CODE; // Adjust to match the category name key
            categoryList.appendChild(option);
        });

        if (selectCategoryID)
            categoryList.value = selectCategoryID

    } catch (error) {
        console.error("Error fetching categories:", error);
        alert("Failed to load categories. Please try again.");
    } finally {
        // Re-enable the select dropdown and hide the loading label
        categoryList.disabled = false;
        fetchCategoryLabel.classList.add('d-none');
        fetchCategoryLabel.classList.remove('anim-pulse');
    }
}


async function fetchClient(clientUUID) {
    const apiUrl = `${APIUrl}/clients/detail`;
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({
            type: 'error',
            message: "Authorization token is missing. Please login again to make an API request."
        });
        return;
    }
    // Show loader


    try {

        fullPageLoader.classList.toggle("d-none");
        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ clientUUID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        displayClientInfo(data.data);

        // Hide login details contaier
        document.getElementById("login-details-container").classList.add("d-none")
        // Disable email field
        document.getElementById("EMAIL").setAttribute("readonly", true)
        document.getElementById("EMAIL").classList.add("bg-light")

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function displayClientInfo(data) {
    if (!data) return;

    if (Object.keys(data).length > 0) {
        populateFormFields(data);
    }
}

function setUsername(input) {
    if (input instanceof HTMLInputElement) {
        const usernamePlaceholder = document.getElementById("USERNAME_PLACEHOLDER");
        if (usernamePlaceholder instanceof HTMLInputElement) {
            usernamePlaceholder.value = input.value;
        }
    }
}

function setShippingAddress(input) {
    if (input instanceof HTMLInputElement && input.type === 'checkbox' && input.checked) {
        const billingAddressInput = document.getElementById("BILLING_ADDRESS");
        const shippingAddressInput = document.getElementById("SHIPPING_ADDRESS");

        if (billingAddressInput instanceof HTMLInputElement && shippingAddressInput instanceof HTMLInputElement) {
            // Check if billing address value is not empty
            if (billingAddressInput.value.trim() !== "") {
                shippingAddressInput.value = billingAddressInput.value;
            }
        }
    }
}




document.addEventListener('DOMContentLoaded', () => {
    const url = new URL(window.location.href);
    // Get all search parameters
    const searchParams = new URLSearchParams(url.search);
    // Get all URL segments
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const clientUUID = urlSegments[urlSegments.length - 1];
    // Fetch product details if action is edit and id is available
    if (searchParams.get('action') === 'edit') {
        // Your code to fetch product details
        fetchClient(clientUUID);
    }
});

