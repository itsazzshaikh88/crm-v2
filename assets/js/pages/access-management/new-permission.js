var newPermissionModal = new bootstrap.Modal(document.getElementById("create-new-permission-modal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});
const form = document.getElementById("new-permission-form");
const fullPageLoader = document.getElementById("full-page-loader");

const permissionLoader = document.getElementById("permission-loader");
const permissionContainer = document.getElementById("permission-container");

function closeNewPermissionModal() {
    form.reset()
    document.getElementById("STATUS").value = 'active';
    document.getElementById("ID").value = '';
    document.getElementById("TOTAL_RESOURCES").value = 0;
    document.getElementById("ROLE_ID").value = '';
    document.getElementById("STATUS").value = 'active';
    permissionLoader.classList.add("d-none");
    permissionContainer.innerHTML = '';
    document.getElementById("ROLE_ID").disabled = false;
}

function resetPermissions() {
    document.getElementById("ROLE_ID").value = '';
    document.getElementById("STATUS").value = 'active';
    permissionLoader.classList.add("d-none");
    permissionContainer.innerHTML = '';
    document.getElementById("ROLE_ID").disabled = false;
}
function openPermissionModal(action = 'new', permissionID = null) {
    hideErrors();
    if (action === 'new') {
        // reset form and then open 
        form.reset()
        document.getElementById("STATUS").value = 'active';
        document.getElementById("ID").value = '';
        document.getElementById("TOTAL_RESOURCES").value = 0;

    } else {
        // Fetch Permission Details
        fetchPermission(permissionID);
    }
    // Show NEw Lead modal  
    newPermissionModal.show()
}


async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    // Attach selected files
    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Creating New Permission ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const permissionID = document.getElementById("ID").value;

        // Enable select role 
        document.getElementById("ROLE_ID").disabled = false;

        let url = `${APIUrl}/permissions/new`;
        if (permissionID)
            url += `/${permissionID}`;
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
            toasterNotification({ type: 'success', message: data?.message || "Permission Saved Successfully" });

            form.reset();
            closeNewPermissionModal();
            newPermissionModal.hide();
            fetchPermissions();
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

async function fetchPermission(permissionID) {
    const apiUrl = `${APIUrl}/permissions/detail/${permissionID}`;
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

        if (!data.data || data.data.length === 0) {
            container.innerHTML = "<div class='text-muted'>No resources found.</div>";
            return;
        }

        renderPermissionsTable(data.data);

        // Show Role selected
        document.getElementById("ROLE_ID").value = data?.data?.[0]?.ASSIGNED_ROLE_ID || '';
        document.getElementById("ID").value = data?.data?.[0]?.PERMISSION_HEADER_ID || '';
    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}


// Fetch Roles from the list
async function fetchRolesForRolesMenu() {
    const roleLoadingSpinner = document.getElementById("role-loading-spinner");
    const roleSelectMenu = document.getElementById("ROLE_ID");
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        // Set Loader and disable role list
        roleLoadingSpinner.classList.remove("d-none");
        roleSelectMenu.disabled = true;
        const url = `${APIUrl}/roles/list`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: 9999999999,
                currentPage: 1,
                filters: { IS_ACTIVE: '1' }
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();

        showRolesInParameter(data.roles || [], roleSelectMenu);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        console.error(error);
    }
    finally {
        // Set Loader and disable role list
        roleLoadingSpinner.classList.add("d-none");
        roleSelectMenu.disabled = false;
    }
}

function showRolesInParameter(roles, roleElement) {
    if (!roles) return;

    let content = '<option value="">Chooose</option>';
    if (roles?.length > 0) {
        roles.forEach(role => {
            content += `<option value="${role?.ID}">${role?.ROLE_NAME}</option>`;
        });
    }
    roleElement.innerHTML = content;

}

// Load Resources
document.addEventListener("DOMContentLoaded", function () {
    fetchRolesForRolesMenu();
});

// Permissions
async function fetchUserPermissions() {
    const container = document.getElementById("permission-container");
    const roleId = document.getElementById("ROLE_ID").value;
    const authToken = getCookie('auth_token');

    if (!authToken) {
        toasterNotification({ type: 'error', message: 'Auth token missing!' });
        return;
    }

    if (!roleId) {
        toasterNotification({ type: 'error', message: 'Role is required!' });
        return;
    }

    permissionLoader.classList.remove("d-none");

    try {
        const response = await fetch(`${APIUrl}/permissions/assigned`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                filters: {
                    ROLE_ID: roleId,
                    STATUS: '1'
                }
            })
        });

        const result = await response.json();

        if (!result.permissions || result.permissions.length === 0) {
            container.innerHTML = "<div class='text-muted'>No resources found.</div>";
            return;
        }

        renderPermissionsTable(result.permissions);
    } catch (error) {
        container.innerHTML = "<div class='text-danger'>Error loading permissions</div>";
        console.error(error);
    }
    finally {
        permissionLoader.classList.add("d-none");
    }
}

function renderPermissionsTable(data) {
    const container = document.getElementById("permission-container");
    document.getElementById("TOTAL_RESOURCES").value = data?.length || 0;
    let permissionHeaderID = null;
    let html = `
        <table class="table table-sm table-bordered table-striped my-4">
            <thead class="thead-dark">
                <tr class="bg-secondary text-dark fw-bold">
                    <th class="ps-2">Resource</th>
                    <th class="ps-2">Type</th>
                    <th class="ps-2">View</th>
                    <th class="ps-2">Create</th>
                    <th class="ps-2">Edit</th>
                    <th class="ps-2">Delete</th>
                    <th class="ps-2">Export</th>
                    <th class="ps-2">Print</th>
                    <th class="ps-2">Assign</th>
                    <th class="ps-2">Share</th>
                </tr>
            </thead>
            <tbody>
    `;

    data.forEach((item, index) => {
        if (index == 0) {
            permissionHeaderID = item?.PERMISSION_HEADER_ID || null;
        }
        html += `
                    <tr>
                        <td class="ps-2">
                            ${item.RESOURCE_NAME || ''}
                            <input type="hidden" name="RESOURCE_ID_${index}" value="${item.RESOURCE_ID}">
                        </td>
                        <td class="ps-2">
                            ${item.RESOURCE_TYPE?.toUpperCase() || ''}
                        </td>
                        <td class="ps-2">
                            <input type="checkbox" name="CAN_VIEW_${index}" value="1" ${item.CAN_VIEW == 1 ? 'checked' : ''}>
                        </td>
                        <td class="ps-2">
                            <input type="checkbox" name="CAN_CREATE_${index}" value="1" ${item.CAN_CREATE == 1 ? 'checked' : ''}>
                        </td>
                        <td class="ps-2">
                            <input type="checkbox" name="CAN_EDIT_${index}" value="1" ${item.CAN_EDIT == 1 ? 'checked' : ''}>
                        </td>
                        <td class="ps-2">
                            <input type="checkbox" name="CAN_DELETE_${index}" value="1" ${item.CAN_DELETE == 1 ? 'checked' : ''}>
                        </td>
                        <td class="ps-2">
                            <input type="checkbox" name="CAN_EXPORT_${index}" value="1" ${item.CAN_EXPORT == 1 ? 'checked' : ''}>
                        </td>
                        <td class="ps-2">
                            <input type="checkbox" name="CAN_PRINT_${index}" value="1" ${item.CAN_PRINT == 1 ? 'checked' : ''}>
                        </td>
                        <td class="ps-2">
                            <input type="checkbox" name="CAN_ASSIGN_${index}" value="1" ${item.CAN_ASSIGN == 1 ? 'checked' : ''}>
                        </td>
                        <td class="ps-2">
                            <input type="checkbox" name="CAN_SHARE_${index}" value="1" ${item.CAN_SHARE == 1 ? 'checked' : ''}>
                        </td>
                    </tr>
                `;
    });
    html += `</tbody></table>`;
    container.innerHTML = html;

    document.getElementById("ID").value = permissionHeaderID;
}