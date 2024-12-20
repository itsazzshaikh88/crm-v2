

async function printApplication(headerId, creditUUID, formName = '') {
    if (!headerId || !creditUUID) {
        alert('Missing parameters for print.');
        return;
    }

    const url = `${APIUrl}/financial/detail`;
    const authToken = getCookie('auth_token');

    if (!authToken) {
        toasterNotification({
            type: 'error',
            message: "Authorization token is missing. Please login again to make an API request."
        });
        return;
    }


    try {
        // Show loader
        fullPageLoader.classList.toggle("d-none");

        // Fetch credit details from the API
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ creditUUID })
        });

        // Parse JSON response
        const data = await response.json();

        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        // If data is successfully fetched, open the print window
        const applicationUrl = `${baseUrl}print/application/?application_id=${headerId}&application_name=${formName}`;
        const printWindow = window.open(applicationUrl, '_blank', 'width=800,height=600');

        // Wait for the print window to load and then pass the fetched data
        printWindow.onload = function () {
            printWindow.populateCreditForm(data.data.credit); // Pass data to the print view
            printWindow.focus();
            printWindow.print();
        };
    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}
