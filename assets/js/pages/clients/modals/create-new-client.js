async function createClientFromModal(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    // Set Loading Animation on button
    const submitBtn = document.getElementById("btn-new-client-modal");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Creating ...`;

    // Hide Error
    hideErrors('err-lbl-mdl');
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        let url = `${APIUrl}/clients/new`;
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
            // Set New Client to the selected page
            const chooseClientBtn = document.getElementById("choose-client-btn");
            const clientNameBtn = document.getElementById("client-name-btn");
            const clientName = document.getElementById("client-name-element");
            const clientID = document.getElementById("CLIENT_ID");
            const companyAddress = document.getElementById("COMPANY_ADDRESS");
            const billingAddress = document.getElementById("BILLING_ADDRESS");
            const shippingAddress = document.getElementById("SHIPPING_ADDRESS");
            const contactNumber = document.getElementById("CONTACT_NUMBER");
            const emailAddress = document.getElementById("EMAIL_ADDRESS");
            const client = data?.data ?? {}
            clientID.value = client?.ID || 0
            clientName.innerHTML = `${client?.FIRST_NAME || ''} ${client?.LAST_NAME || ''}`
            companyAddress.value = `${client?.COMPANY_NAME || ''}`
            billingAddress.value = `${client?.ADDRESS_LINE_1 || ''}`
            shippingAddress.value = `${client?.SHIPPING_ADDRESS || ''}`
            contactNumber.value = `${client?.PHONE_NUMBER || ''}`
            emailAddress.value = `${client?.EMAIL || ''}`

            myModal.hide();
            newClientModal.hide()
            // Toggle Buttons
            chooseClientBtn.classList.add("d-none")
            clientNameBtn.classList.remove("d-none")


        } else {
            const errorData = await response.json();
            if (errorData.status === 422) {
                showErrors(errorData.validation_errors ?? [], 'lbl-client-modal');
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

function setUsername(input) {
    if (input instanceof HTMLInputElement) {
        const usernamePlaceholder = document.getElementById("USERNAME_PLACEHOLDER");
        if (usernamePlaceholder instanceof HTMLInputElement) {
            usernamePlaceholder.value = input.value;
        }
    }
}