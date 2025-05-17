
async function fetchCardStats() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        const statsLabels = document.querySelectorAll(".card-stats-label-preview");
        if (statsLabels && statsLabels.length > 0) {
            statsLabels.forEach((lbl) => lbl.innerHTML = dashboardStatsSkeleton())
        }

        const url = `${APIUrl}/stats/dashboard`;

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch data');
        }

        const data = await response.json();

        showDashboardStats(data.stats || []);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        showDashboardStats();
    }
}

function showDashboardStats(stats = {}) {
    const statsElements = document.querySelectorAll(".card-stats-label-preview");
    statsElements.forEach((element) => {
        const id = element.getAttribute("id");
        element.innerHTML = stats?.[id] || 0;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchCardStats();
});