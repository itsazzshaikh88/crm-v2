// Store files
const fullPageLoader = document.getElementById("full-page-loader")
// Function to send a request with Bearer token and display response

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

        displayProductInfo(data.data);
        //  do extra logic here 
        // show status of product
        updateStatus(data?.data?.STATUS || '')

        // Generate Edit link and assign it to button
        let editURL = `clients/new/${data?.data?.UUID}?action=edit`
        let editLinkElements = document.querySelectorAll(".edit-link")
        editLinkElements.forEach((link) => link.setAttribute("href", editURL))

        // Shoe username and email in placeholder
        document.getElementById("lbl-USERNAME-PLACEHOLDER").innerHTML = `${data?.data?.FIRST_NAME || ''} ${data?.data?.LAST_NAME || ''}`
        document.getElementById("lbl-EMAIL-PLACEHOLDER").innerHTML = `${data?.data?.EMAIL || ''}`

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function updateStatus(status) {
    let element = document.getElementById("lbl-STATUS");
    if (element) {
        element.classList.remove(...["bg-success", "bg-danger", "bg-secondary", "bg-warning"]);

        const statusColorMap = {
            active: 'success',
            suspended: 'warning',
            inactive: 'danger',
            locked: 'secondary'
        };

        const color = statusColorMap[status] || 'secondary'; // Default to 'secondary' if status not found
        element.classList.add(`bg-${color}`);
    }
}

function updateDivision(code) {
    let element = document.getElementById("lbl-DIVISION");
    if (element) {
        const divisionMap = {
            _242: 'Non-Food',
            _444: 'Food'
        };
        element.innerHTML = divisionMap[`_${code}`] || '';
    }
}
function updateBackOrders(code) {
    let element = document.getElementById("lbl-ALLOW_BACKORDERS");
    if (element) {
        element.innerHTML = code ? 'Yes' : 'No';
    }
}



function displayProductInfo(data) {

    if (!data) return;


    if (Object.keys(data).length > 0) {
        showFieldContent(data);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const clientUUID = document.getElementById("UUID").value;
    fetchClient(clientUUID);
});

