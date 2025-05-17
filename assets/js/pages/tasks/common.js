// Show the modal using Bootstrap 5 modal method with no closing on backdrop or escape key
const modalElement = document.getElementById('taskModal');
const taskModal = new bootstrap.Modal(modalElement, {
    keyboard: false, // Disable closing the modal on ESC key
    backdrop: 'static' // Disable closing the modal when clicking outside
});

async function openNewTaskForm(mode, taskId = null, parentID) {
    // Reset any previous form data
    resetTaskForm();


    taskModal.show();  // Open the modal

    if (mode === 'edit' && taskId !== null) {
        // If in edit mode, fetch task data from the API
        await fetchTaskDetailsToEdit(taskId);
    } else {
        // If in new mode, reset the form and set default title
        document.getElementById('taskModalLabel').innerText = 'Add New Task';
        document.getElementById('taskForm').reset(); // Reset the form
        if (parentID) {
            document.getElementById("PARENT_ID").value = parentID || '';
        }

    }
}

function resetTaskForm() {
    // Reset all form fields to their default state
    ['ID', 'TASK_NAME', 'DESCRIPTION', 'STATUS', 'START_DATE', 'TARGET_DATE', 'END_DATE', 'DURATION', 'COMMENTS', 'PARENT_ID'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.getElementById('STATUS').value = 'Pending';  // Default status
}



const fullPageLoader = document.getElementById("full-page-loader");