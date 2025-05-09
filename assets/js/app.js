async function logoutAction() {

    const authToken = getCookie("auth_token");
    if (!authToken) return window.location = `${baseUrl}login`;

    // Show the SweetAlert confirmation dialog
    const result = await Swal.fire({
        title: "Are you sure?",
        text: "Do you want to log out?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, log me out",
        cancelButtonText: "No, stay logged in",
        reverseButtons: true,
        buttonsStyling: false,
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-secondary"
        }
    });

    // If user confirms the logout
    if (result.isConfirmed) {
        // Show loading animation
        Swal.fire({
            title: "Signing out...",
            html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            allowOutsideClick: false,
            showConfirmButton: false
        });

        try {
            // Send a logout request to the backend with the auth token
            const response = await fetch("api/auth/logout", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${authToken}` // Include the auth token
                },
                body: JSON.stringify({ action: "logout" })
            });

            const result = await response.json();

            // If the session is successfully destroyed
            if (response.ok && result.success) {
                // Redirect to login page
                window.location.href = `${baseUrl}login`;
            } else {
                // Handle failure (optional)
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong! Could not log you out.",
                    confirmButtonText: "OK"
                });
            }
        } catch (error) {
            // Handle error if the request fails
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "There was an issue connecting to the server.",
                confirmButtonText: "OK"
            });
        }
    }
    // If user cancels the logout, no need to do anything; the alert box will close.
}

// Load org list on page load if not already in localStorage
// Load org list on page load if not already in localStorage and populate ORG_ID if exists
async function loadOrgsList() {
    let shouldFetch = false;

    try {
        const data = JSON.parse(localStorage.getItem('orgsList'));
        if (!Array.isArray(data) || data.length === 0) {
            shouldFetch = true;
        }
    } catch {
        shouldFetch = true;
    }

    if (shouldFetch) {
        await fetchOrgsList();
    }

    // After ensuring data is in localStorage, populate the select if it exists
    if (document.getElementById('ORG_ID')) {
        populateSelectWithOrgs('ORG_ID');
    }
}

// Populate a <select> element by ID
function populateSelectWithOrgs(selectId) {
    const selectElement = document.getElementById(selectId);
    if (!selectElement) {
        console.warn(`Select element with ID "${selectId}" not found`);
        return;
    }

    selectElement.innerHTML = getOrgOptions();
}


// Fetch org list from API and store in localStorage
async function fetchOrgsList() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({
                type: 'error',
                message: "Authorization token is missing. Please login again to make API request."
            });
            return;
        }

        const url = `${APIUrl}/organization/lov`;
        const filters = filterCriterias([]);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ filters })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch organization list');
        }

        const data = await response.json();

        localStorage.setItem('orgsList', JSON.stringify(data.data || []));


    } catch (error) {
        console.error('Error fetching organizations:', error);
    }
}

// Returns <option> tag string from localStorage data
function getOrgOptions() {
    try {
        const data = JSON.parse(localStorage.getItem('orgsList'));
        if (!Array.isArray(data)) return '';

        return data.map(org => `<option value="${org.ORG_ID}">${org.ORG_CODE}</option>`).join('');
    } catch {
        return '';
    }
}

// Populates a <select> element by its ID
function populateOrgSelect(selectId) {
    const selectElement = document.getElementById(selectId);
    if (!selectElement) {
        console.warn(`Select element with ID "${selectId}" not found`);
        return;
    }

    selectElement.innerHTML = getOrgOptions();
}

// Run on page load
window.addEventListener('DOMContentLoaded', () => {
    loadOrgsList();
});
