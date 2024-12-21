document.addEventListener('DOMContentLoaded', () => {
    getComplaintDetails();
});

let userId = document.getElementById('USER_ID').value;
let userType = document.getElementById('USER_TYPE').value;
let totalComp = document.getElementById('total_comp');
let activeComp = document.getElementById('active_comp');
let closedComp = document.getElementById('closed_comp');
let draftComp = document.getElementById('draft_comp');

async function getComplaintDetails() {
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
        return;
    }

    const url = `${APIUrl}/complaints/getCardStats`;
    const filters = filterCriterias(['USER_ID', 'USER_TYPE']); // Ensure filterCriterias returns valid filters

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                filters: filters // Send filters to API
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch request data');
        }

        const data = await response.json();
        console.log(data); // Check the response structure

        // Initialize counts for each status and total
        let counts = {
            Active: 0,
            Closed: 0,
            Draft: 0,
            Total: 0
        };

        // Loop through the API data to populate the counts
        data.forEach(item => {
            const { STATUS, STATUS_COUNT } = item; // Destructure the API response fields
            counts[STATUS] = parseInt(STATUS_COUNT, 10); // Assign the count to the corresponding status
            counts.Total += parseInt(STATUS_COUNT, 10); // Add to the total count
        });

        // Update the span elements with the counts
        const totalComp = document.getElementById('total-comp');
        const activeComp = document.getElementById('active-comp');
        const closedComp = document.getElementById('closed-comp');
        const draftComp = document.getElementById('draft-comp');

        if (totalComp) totalComp.innerHTML = counts.Total || 0;
        if (activeComp) activeComp.innerHTML = counts.Active || 0;
        if (closedComp) closedComp.innerHTML = counts.Closed || 0;
        if (draftComp) draftComp.innerHTML = counts.Draft || 0;

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        console.error(error);
    }
}


