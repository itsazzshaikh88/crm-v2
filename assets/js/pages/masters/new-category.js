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
    submitBtn.innerHTML = `Creating New Category ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const categoryID = document.getElementById("ID").value;
        let url = `${APIUrl}/categories/`;
        if (categoryID)
            url += `update/${categoryID}`;
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
            toasterNotification({ type: 'success', message: data?.message || "Category Saved Successfully" });
            form.reset()
            document.getElementById("ID").value;

            // fetch data
            fetchCategories();
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
        console.error(error);

    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = buttonText;
    }
}


async function fetchCategoryDetailsToEdit(categoryID) {
    const apiUrl = `${APIUrl}/categories/detail/${categoryID}`;
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
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }


        displayCategoryDetails(data.data);

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function displayCategoryDetails(data) {
    if (!data) return;

    if (Object.keys(data).length > 0) {
        populateFormFields(data);
    }
}


