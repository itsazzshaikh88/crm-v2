const taskTableId = "task-list";
const taskTableBody = document.querySelector(`#${taskTableId} tbody`);
const numberOfTaskHeaders = document.querySelectorAll(`#${taskTableId} thead th`).length || 0;
let taskDataTree = [];

let paginate = {
    pageLimit: 100,
    currentPage: 1,
    totalRecords: 0,
    totalPages: 0,
};

async function fetchTasks() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again." });
            return;
        }

        const response = await fetch(`${APIUrl}/tasks/list`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: []
            })
        });

        if (!response.ok) throw new Error('Failed to fetch task data');

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        taskDataTree = buildTaskTree(data.tasks || []);
        renderTaskTree(taskDataTree, taskTableBody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Task failed: ' + error.message });
        taskTableBody.innerHTML = renderNoResponseCode();
    }
}

function buildTaskTree(tasks) {
    const taskMap = {};
    const tree = [];

    tasks.forEach(task => {
        task.children = [];
        taskMap[task.ID] = task;
    });

    tasks.forEach(task => {
        if (task.PARENT_ID && taskMap[task.PARENT_ID]) {
            taskMap[task.PARENT_ID].children.push(task);
        } else {
            tree.push(task);
        }
    });

    return tree;
}

function renderTaskTree(tasks, container, depth = 0, prefix = '') {
    container.innerHTML = '';
    tasks.forEach((task, index) => {
        const newPrefix = prefix ? `${prefix}.${index + 1}` : `${index + 1}`;
        renderTaskRow(task, container, depth, `${task.ID}`, newPrefix);
    });
}

const projectStatusStyles = {
    'Pending': 'bg-warning text-dark',       // Yellow-ish
    'In Progress': 'bg-primary text-white',  // Blue
    'Completed': 'bg-success text-white',    // Green
    'On Hold': 'bg-secondary text-white',    // Grey
    'Cancelled': 'bg-danger text-white'      // Red
};

const projectStatusTextColors = {
    'Pending': 'text-warning',
    'In Progress': 'text-primary',
    'Completed': 'text-success',
    'On Hold': 'text-secondary',
    'Cancelled': 'text-danger'
};



function renderTaskRow(task, container, depth, uniqueId, serial) {
    const indent = '&nbsp;'.repeat(depth * 6);
    const hasChildren = task.children && task.children.length > 0;

    const isMainTask = task.PARENT_ID === 0 || task.PARENT_ID === "0" || !task.PARENT_ID || task.PARENT_ID === '';
    const taskClass = isMainTask ? 'text-primary' : '';

    const tr = document.createElement('tr');
    tr.setAttribute('data-id', task.ID);
    tr.setAttribute('data-parent-id', task.PARENT_ID || '');
    tr.setAttribute('data-unique-id', uniqueId);
    tr.className = `task-row depth-${depth}`;

    // First cell: Action buttons
    const actionsCell = `
        <td class="text-center align-middle sticky-col-start bg-light px-2">
            <div class="d-flex align-items-center gap-4">
                <div class="action-icon rounded-circle bg-light text-primary cursor-pointer" onclick="openNewTaskForm('edit','${task.ID}')" title="Edit">
                    <i class="fa fa-edit fs-12 text-primary"></i>
                </div>
                <div class="action-icon rounded-circle bg-light text-danger cursor-pointer" onclick="deleteTask('${task.ID}')" title="Delete">
                    <i class="fa fa-trash fs-12 text-danger"></i>
                </div>
                <div class="action-icon rounded-circle bg-light text-success cursor-pointer" onclick="openNewTaskForm('new', '', '${task.ID}')" title="Add Subtask">
                    <i class="fa fa-plus fs-12 text-success"></i>
                </div>
            </div>
        </td>
    `;

    // Second cell: Serial number
    const serialCell = `
        <td class="text-center align-middle fw-bold sticky-col-second bg-white">
            ${serial}
        </td>
    `;

    // Remaining cells
    const remainingCells = `
        <td>${indent}${hasChildren ? `<a href="javascript:void(0);" onclick="toggleTaskVisibility('${uniqueId}')">
            <i class="fa fa-minus toggle-icon"></i></a>` : ''} 
            <span class="${taskClass}">${task.TASK_ID || ''}</span>
        </td>
        <td>
            <a href="tasks/details/${task.ID}" class="text-decoration-none text-primary fw-semibold">
                ${task.TASK_NAME || ''}
            </a>
        </td>
        <td><span class="${projectStatusTextColors[task.STATUS] || 'text-muted'} fw-semibold">${capitalizeWords(task.STATUS) || ''}</span></td>
        <td>${task.DEPARTMENT || ''}</td>
        <td>${task.CONSULTANT || ''}</td>
        <td>${formatDate(task.START_DATE) || ''}</td>
        <td>${formatDate(task.TARGET_DATE) || ''}</td>
        <td>${formatDate(task.END_DATE) || ''}</td>
        <td>${task.DURATION || ''}</td>
        <td>${task.CREATED_BY || ''}</td>
        <td>${formatDateTime(task.CREATED_AT) || ''}</td>
    `;

    tr.innerHTML = actionsCell + serialCell + remainingCells;
    container.appendChild(tr);

    // Recursively render children
    task.children.forEach((child, index) => {
        const childPrefix = `${serial}.${index + 1}`;
        renderTaskRow(child, container, depth + 1, `${uniqueId}-${child.ID}`, childPrefix);
    });
}



function toggleTaskVisibility(uniqueId) {
    const rows = document.querySelectorAll(`[data-parent-id="${uniqueId}"], [data-unique-id^="${uniqueId}-"]`);
    const isCurrentlyHidden = rows[0]?.style.display === 'none';

    rows.forEach(row => {
        row.style.display = isCurrentlyHidden ? '' : 'none';
    });

    const icon = document.querySelector(`[data-unique-id="${uniqueId}"] .toggle-icon`);
    if (icon) {
        icon.classList.toggle('fa-minus', isCurrentlyHidden);
        icon.classList.toggle('fa-plus', !isCurrentlyHidden);
    }

    const childRows = document.querySelectorAll(`[data-parent-id="${uniqueId}"]`);
    childRows.forEach(childRow => {
        const childId = childRow.getAttribute('data-unique-id');
        const childIcon = document.querySelector(`[data-unique-id="${childId}"] .toggle-icon`);

        if (childIcon) {
            const childChildren = document.querySelectorAll(`[data-parent-id="${childId}"], [data-unique-id^="${childId}-"]`);
            const isChildHidden = childChildren[0]?.style.display === 'none';

            childIcon.classList.toggle('fa-minus', !isChildHidden);
            childIcon.classList.toggle('fa-plus', isChildHidden);
        }
    });
}


function formatDate(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return '';
    return `${d.getDate().toString().padStart(2, '0')}-${(d.getMonth() + 1).toString().padStart(2, '0')}-${d.getFullYear()}`;
}

function formatDateTime(dateTimeStr) {
    if (!dateTimeStr) return '';
    const d = new Date(dateTimeStr);
    if (isNaN(d.getTime())) return '';
    return `${formatDate(dateTimeStr)} ${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`;
}

fetchTasks();

async function deleteTask(taskID) {
    if (!taskID) {
        throw new Error("Invalid Task ID, Please try Again");
    }

    try {
        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this task? This will also delete all its sub-tasks. This action cannot be undone.",
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
                message: "Authorization token is missing. Please login again to make an API Task."
            });
            return;
        }

        // Show a non-closable alert box while the activity is being deleted
        Swal.fire({
            title: "Deleting Task ...",
            text: "Please wait while the Task  is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/tasks/delete/${taskID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete Task
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        // Close the loading alert box
        Swal.close();

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete Task details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Task Deleted Successfully' });
            // Logic to remove the current row from the table
            fetchTasks();
        } else {
            throw new Error(data.message || 'Failed to delete Task details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Task failed: ' + error.message });
        Swal.close();
    }
}


// Fullscreen change event
function toggleFullScreen() {
    const icon = document.getElementById("fullscreen-icon");

    if (!document.fullscreenElement) {
        // Request fullscreen for the whole document
        const docEl = document.documentElement;

        if (docEl.requestFullscreen) {
            docEl.requestFullscreen();
        } else if (docEl.webkitRequestFullscreen) {
            docEl.webkitRequestFullscreen();
        } else if (docEl.msRequestFullscreen) {
            docEl.msRequestFullscreen();
        }

        // Change icon and color
        icon.classList.remove("fa-expand", "text-primary");
        icon.classList.add("fa-compress", "text-danger");

    } else {
        // Exit fullscreen
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }

        // Change icon and color
        icon.classList.remove("fa-compress", "text-danger");
        icon.classList.add("fa-expand", "text-primary");
    }
}

document.addEventListener("fullscreenchange", () => {
    const icon = document.getElementById("fullscreen-icon");
    if (!document.fullscreenElement) {
        icon.classList.remove("fa-compress", "text-danger");
        icon.classList.add("fa-expand", "text-primary");
    }
});

// Refresh task details
async function reloadTaskData() {
    try {
        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to reload task details.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, reload it",
            cancelButtonText: "Cancel",
            customClass: {
                popup: 'small-swal',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
            },
        });

        if (!confirmation.isConfirmed) return;
        fetchTasks();

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Task failed: ' + error.message });
        Swal.close();
    }
}