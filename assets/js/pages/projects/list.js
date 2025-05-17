// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                                <td colspan="${option.colspan}">
                                    <div class="row justify-content-center">
                                        <div class="col-md-4 d-flex align-items-center flex-column justify-content-center">
                                            <h1 class="fs-2x fw-bolder">No <span class="text-primary">Projects</span> Yet? Let's Get Started!</h1>
                                            <p>
                                                <i>Create Your First Project and Experience Seamless Management</i>
                                            </p>
                                            <p>
                                                It looks like you don’t have any projects created yet. But don't worry—getting started is quick and easy!
                                            </p>
                                            <a href="projects/new" class="btn border border-primary text-primary my-4"> <i class="fa fa-plus text-primary"></i> Add your first project</a>
                                        </div>
                                        <div class="col-md-5">
                                            <img src="assets/images/new-project.png" class="img-fluid" alt="">
                                        </div>
                                    </div>
                                </td>
                            </tr>`;

    return noCotent;
}

const tableId = "projects-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchListedProjects() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        listingSkeleton(tableId, paginate.pageLimit || 0, 'project');
        const url = `${APIUrl}/projects/list`;
        const filters = filterCriterias([]);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: filters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch project data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showProjects(data.projects || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

function setDuration(startDate, endDate) {
    let content = '';
    if (startDate != null && startDate != 'null' && startDate != '0000-00-00')
        content += formatAppDate(startDate);
    if (endDate != null && endDate != 'null' && endDate != '0000-00-00')
        content += ` - ${formatAppDate(endDate)}`;
    return content;
}
const statusColors = {
    NOT_STARTED: '#A9A9A9', // Dark Gray
    IN_PROGRESS: '#1E90FF', // Dodger Blue
    COMPLETED: '#32CD32', // Lime Green
    ON_HOLD: '#FFA500' // Orange
};
const priorityColors = {
    LOW: '#90EE90', // Light Green
    MEDIUM: '#FFD700', // Gold
    HIGH: '#FF4500' // Orange Red
}
function setStatus(status) {
    return `<span class="badge text-white" style="background-color: ${statusColors[status]}">${capitalizeWords(status, true).replace("_", " ")}</span>`;
}

function setPriority(priority) {
    return `<div class="mb-1 fw-bold badge text-white" style="background-color: ${priorityColors[priority]}">${capitalizeWords(priority, true)}</div>`;

}
function getProgressBarColor(progress) {

    if (progress >= 0 && progress <= 25) {
        return '#FF4500'; // Red for 0–25% (Critical)
    } else if (progress > 25 && progress <= 50) {
        return '#FFA500'; // Orange for 26–50% (Warning)
    } else if (progress > 50 && progress <= 75) {
        return '#FFD700'; // Gold for 51–75% (Moderate)
    } else if (progress > 75 && progress <= 100) {
        return '#32CD32'; // Lime Green for 76–100% (Good)
    } else {

        console.log(progress);
        return '#A9A9A9'; // Gray for invalid or out-of-range progress
    }
}

function showProjects(projects, tbody) {
    let content = '';
    let counter = 0;
    if (projects?.length > 0) {
        // show projects
        projects.forEach(project => {
            content += `<tr data-project-id="${project?.PROJECT_ID}" class="">
                                <td class="min-w-300px">
                                    <div class="position-relative ps-6 pe-3 py-2">
                                        <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2" style="background-color: ${statusColors[project?.STATUS]}"></div>
                                        <a href="jabascript:void(0)" class="mb-1 line-clamp-2 text-gray-900 text-hover-primary fw-bold">${project?.PROJECT_NAME}</a>
                                        <div class="fs-8 text-muted fw-bold">${project?.PROJECT_CODE}</div>
                                    </div>
                                </td>
                                <td class="min-w-300px">
                                    <div class="fs-7 text-muted fw-normal line-clamp-3">${project?.DESCRIPTION}</div>
                                </td>
                                <td class="">
                                    <div class="mb-2 fw-normal">${setDuration(project?.START_DATE, project?.END_DATE)}</div>
                                </td>
                                <td>
                                    ${setStatus(project?.STATUS)}
                                </td>
                                <td class="min-w-125px">
                                    <div class="fs-7 fw-bold">${capitalizeWords(project?.PROJECT_TYPE, true)}</div>
                                </td>
                                <td class="">
                                    ${setPriority(project?.PRIORITY)}
                                </td>
                                <td class="">
                                    <div class="d-flex flex-column w-100 me-2 mt-2">
                                        <span class="text-gray-600 me-2 fw-bolder mb-1 fs-7">${project?.PROGRESS}% </span>
                                        <div class="progress bg-gray-100 w-100 h-5px">
                                            <div class="progress-bar" role="progressbar" style="background-color: ${getProgressBarColor(project?.PROGRESS || 0)} !important; width: ${project?.PROGRESS}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a href="projects/new/${project?.UUID}?action=edit">
                                            <small>
                                                <i class="fs-5 fa-regular fa-pen-to-square text-primary"></i>
                                            </small>
                                        </a><a href="javascript:void(0)" onclick="deleteProject(${project?.PROJECT_ID})">
                                            <small>
                                                <i class="fs-5 fa-solid fa-trash-can text-danger"></i>
                                            </small>
                                        </a>
                                    </div>
                                </td>
                            </tr>`;
        });
        tbody.innerHTML = content;
    } else {
        // no data available
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders })
    }
}


// Global scope
// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchListedProjects(); // Fetch projects for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchListedProjects();
});

function filterProjects() {
    paginate.currentPage = 1;
    fetchListedProjects();
}


async function deleteProject(projectID) {
    if (!projectID) {
        throw new Error("Invalid Project ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this Project? This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel",
            customClass: {
                popup: 'small-swal',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
            },
        });

        if (!confirmation.isConfirmed) return;

        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({
                type: 'error',
                message: "Authorization token is missing. Please login again to make an API request."
            });
            return;
        }

        // Show a non-closable alert box while the activity is being deleted
        Swal.fire({
            title: "Deleting Project...",
            text: "Please wait while the project is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/projects/delete/${projectID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete request
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        // Close the loading alert box
        Swal.close();

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete Project details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Project Deleted Successfully' });
            // Logic to remove the current row from the table
            const row = document.querySelector(`#project-list-tbody tr[data-project-id="${projectID}"]`);
            if (row) {
                row.remove(); // Remove the row from the table
            }
        } else {
            throw new Error(data.message || 'Failed to delete project details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}
