const fullPageLoader = document.getElementById("full-page-loader");

document.addEventListener('DOMContentLoaded', () => {
    const taskId = window.location.pathname.split('/').pop();
    fetchTaskDetails(taskId);
    fetchTaskComments(taskId);
});

async function fetchTaskDetails(taskId) {
    try {
        const response = await fetch(`${APIUrl}/tasks/task_detail/${taskId}`, {
            headers: { 'Authorization': `Bearer ${getCookie('auth_token')}` }
        });
        const data = await response.json();
        if (data.status === 'success') {
            renderTaskInfo(data.data.task);
            renderTaskChildren(data.data.children);
        }
    } catch (err) {
        console.error(err);
    }
}

const projectStatusTextColors = {
    'Pending': 'text-warning',
    'In Progress': 'text-primary',
    'Completed': 'text-success',
    'On Hold': 'text-secondary',
    'Cancelled': 'text-danger'
};

function renderTaskInfo(task) {
    const safe = val => val || '';
    const html = `
        <table class="table table-sm table-borderless">
            <tr><td><strong>Task ID:</strong></td><td>${safe(task.TASK_ID)}</td></tr>
            <tr><td><strong>Task Name:</strong></td><td>${safe(task.TASK_NAME)}</td></tr>
            <tr><td><strong>Description:</strong></td><td>${safe(task.DESCRIPTION)}</td></tr>
            <tr><td><strong>Status:</strong></td><td class="${projectStatusTextColors[safe(task.STATUS)]}">${safe(task.STATUS)}</td></tr>
            <tr><td><strong>Consultant:</strong></td><td>${safe(task.CONSULTANT)}</td></tr>
            <tr><td><strong>Start Date:</strong></td><td>${safe(task.START_DATE)}</td></tr>
            <tr><td><strong>Target Date:</strong></td><td>${safe(task.TARGET_DATE)}</td></tr>
            <tr><td><strong>End Date:</strong></td><td>${safe(task.END_DATE)}</td></tr>
            <tr><td><strong>Duration:</strong></td><td>${safe(task.DURATION)}</td></tr>
        </table>
    `;
    document.getElementById('task-info').innerHTML = html;
}

function renderTaskChildren(children) {
    let html = `<h6>Children Tasks</h6><ul class="list-group">`;
    const recursiveRender = (nodes, prefix = '') => {
        nodes.forEach((node, index) => {
            const idx = prefix ? `${prefix}.${index + 1}` : `${index + 1}`;
            html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                <span>${idx}. <a href="javascript:void(0)" onclick="loadChildComments(${node.ID})">${node.TASK_NAME || ''}</a></span>
                <button class="btn btn-sm btn-light" onclick="window.open('tasks/details/${node.ID}', '_blank')">Open</button>
            </li>`;
            if (node.children?.length) recursiveRender(node.children, idx);
        });
    };
    recursiveRender(children);
    html += `</ul>`;
    document.getElementById('task-children').innerHTML = html;
}

async function fetchTaskComments(taskId) {
    try {
        const response = await fetch(`${APIUrl}/tasks/task_comments/${taskId}`, {
            headers: { 'Authorization': `Bearer ${getCookie('auth_token')}` }
        });
        const data = await response.json();
        if (data.status === 'success') {
            renderCommentsThread(data.data, taskId);
        } else {
            renderCommentsThread([], taskId);
        }
    } catch (err) {
        console.error(err);
        renderCommentsThread([], taskId);
    }
}

function loadChildComments(childId) {
    fetchTaskComments(childId);
}

function renderCommentForm(taskID, commentID = '', parentCommentID = '') {
    return `<div class="mb-3">
                <div class="d-flex align-items-center justify-content-end mb-2">
                    <button id="commentTogglerBtn" class="btn btn-sm text-primary bg-light" onclick="toggleCommentBox(this)">Add New Comment</button>
                </div>
                <form id="comment-form" method="POST" enctype="multipart/form-data" class="d-none" onsubmit="submitComment(event)">
                    <textarea rows="5" class="form-control" id="COMMENT_TEXT" name="COMMENT_TEXT" placeholder="Write a comment..."></textarea>
                    <input type="hidden" name="TASK_ID" id="TASK_ID" value="${taskID}" />
                    <input type="hidden" name="PARENT_ID" id="PARENT_ID" value="${parentCommentID}" />
                    <input type="hidden" name="ID" id="ID" value="${commentID}" />
                    <button class="btn btn-sm btn-primary mt-2" id="submit-comment-btn">Save Comment</button>
                </form>
            </div>`;
}



function toggleCommentBox(btnElement, action = 'show') {

    const formElement = document.getElementById("comment-form");

    if (action === "show") {
        formElement.classList.remove("d-none");
        btnElement.innerText = "Hide Comment Box";
        btnElement.classList.remove("text-primary");
        btnElement.classList.add("text-danger");
        btnElement.setAttribute("onclick", "toggleCommentBox(this, 'hide')");
    } else if (action === "hide") {
        formElement.classList.add("d-none");
        btnElement.innerText = "Add New Comment";
        btnElement.classList.remove("text-danger");
        btnElement.classList.add("text-primary");
        btnElement.setAttribute("onclick", "toggleCommentBox(this, 'show')");
    }
}

function editComment(commentID) {
    const commentTogglerBtn = document.getElementById('commentTogglerBtn');
    toggleCommentBox(commentTogglerBtn, 'show');

    // load comment data and show it in the comment box
    fetchCommentDetailsByID(commentID);

    // set focus to the comment area
    let commentBoxElement = document.getElementById("COMMENT_TEXT");
    if (commentBoxElement)
        commentBoxElement.focus();
}

async function deleteComment(commentID, taskID) {
    if (!commentID) {
        throw new Error("Invalid Comment ID, Please try Again");
    }

    try {
        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this comment? This will also delete all its sub-comments. This action cannot be undone.",
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
            title: "Deleting Comments ...",
            text: "Please wait while the comment is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/tasks/delete_comment/${commentID}`;

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
            toasterNotification({ type: 'success', message: 'Comment(s) Deleted Successfully' });
            fetchTaskComments(taskID);
        } else {
            throw new Error(data.message || 'Failed to delete Task details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Task failed: ' + error.message });
        Swal.close();
    }
}

async function fetchCommentDetailsByID(commentID) {
    const apiUrl = `${APIUrl}/tasks/comment_detail/${commentID}`;
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
            }
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        displayCommentInfoInCommentBox(data.data);

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.add("d-none");
    }
}

function displayCommentInfoInCommentBox(data) {
    if (!data) return;

    if (Object.keys(data).length > 0) {
        populateFormFields(data);
    }
}

function renderCommentsThread(comments, taskId) {
    const container = document.getElementById('comments-container');
    container.innerHTML = renderCommentForm(taskId); // Form for top-level comment (PARENT_ID = 0)

    if (!comments || comments.length === 0) {
        container.innerHTML += `<p class="text-danger text-center my-4">No comments found.</p>`;
        return;
    }

    const buildThread = (list, indent = 0) => {
        return list.map(comment => `<div style="margin-left:${indent * 10}px;" class="border border-secondary mb-2 bg-light p-4 rounded">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <strong>${comment.FULL_NAME || 'Unknown'}</strong>
            <small class="text-muted">(${comment.CREATED_AT || ''})</small>
        </div>
        <div class="d-flex gap-2">
            <!-- Edit Icon -->
            <button class="btn btn-sm btn-link text-secondary p-0 edit-comment-btn" data-id="${comment.ID}" title="Edit"
                onclick="editComment(${comment.ID}, this)">
                <i class="fas fa-pen fa-xs text-info"></i>
            </button>

            <!-- Delete Icon -->
            <button class="btn btn-sm btn-link p-0 delete-comment-btn" data-id="${comment.ID}"
                title="Delete" onclick="deleteComment(${comment.ID}, ${taskId})">
                <i class="fas fa-trash fa-xs text-danger"></i>
            </button>
        </div>
    </div>

    <p class="my-2">${comment.COMMENT_TEXT || ''}</p>

    <button id="reply-btn-${comment.ID}" class="btn btn-sm btn-link text-primary p-0 mb-4"
        onclick="showReplyBox(${comment.ID}, ${taskId}, this)">
        Reply
    </button>

    <div id="reply-box-${comment.ID}"></div>

    ${comment.replies?.length ? buildThread(comment.replies, indent + 1) : ''}
</div>

            `).join('');
    };

    container.innerHTML += buildThread(comments);
}


function showReplyBox(commentId, taskId, btn) {
    const box = document.getElementById(`reply-box-${commentId}`);

    if (box.innerHTML.trim()) {
        // Hide reply box
        box.innerHTML = '';
        btn.innerText = 'Reply';
        btn.classList.remove('text-danger');
        btn.classList.add('text-primary');
    } else {
        // Show reply form
        box.innerHTML = `
            <textarea class="form-control form-control-sm mb-1" id="reply-text-${commentId}" placeholder="Write a reply..."></textarea>
            <button 
                class="btn btn-sm btn-success" 
                onclick="submitCommentReply(${taskId}, ${commentId})"
            >Post Reply</button>
        `;
        btn.innerText = 'Cancel';
        btn.classList.remove('text-primary');
        btn.classList.add('text-danger');
    }
}


async function submitCommentReply(taskID, parentID = 0) {
    const commentText = document.getElementById(`reply-text-${parentID}`)?.value.trim();

    if (!commentText) {
        toasterNotification({
            type: 'error',
            message: "<strong>Oops!</strong> You forgot to write a comment. Please enter your comment to continue."
        });
        return;
    }

    if (!taskID) {
        toasterNotification({
            type: 'error',
            message: "<strong>Oops!</strong> Task ID is missing. We need a Task ID to submit your comment."
        });
        return;
    }

    if (!parentID || parentID == 0) {
        toasterNotification({
            type: 'error',
            message: "<strong>Oops!</strong> Parent comment ID is missing. We need a valid Parent ID to submit a reply."
        });
        return;
    }

    const formData = new FormData();
    formData.append('TASK_ID', taskID);
    formData.append('COMMENT_TEXT', commentText);
    formData.append('PARENT_ID', parentID);

    // Reply Button Loading State
    const submitBtn = document.querySelector(`#reply-box-${parentID} button`);
    let originalText = submitBtn?.innerHTML;
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `Saving Reply ...`;
    }

    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please login again." });
            return;
        }

        const response = await fetch(`${APIUrl}/tasks/new_comment`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            toasterNotification({ type: 'success', message: "Reply posted successfully!" });

            // Reset reply box
            const replyBox = document.getElementById(`reply-box-${parentID}`);
            if (replyBox) replyBox.innerHTML = '';

            // Re-fetch comments
            fetchTaskComments(taskID);
        } else {
            toasterNotification({ type: 'error', message: data.message ?? 'Failed to post reply.' });
        }
    } catch (error) {
        toasterNotification({ type: 'error', message: 'Something went wrong. Please try again.' });
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText || 'Reply';
        }
    }
}



function clearCommentForm() {
    // Clear text area and hidden inputs
    document.getElementById('COMMENT_TEXT').value = '';  // Clears the comment text area
    document.getElementById('PARENT_ID').value = '';     // Clears PARENT_ID field
    document.getElementById('ID').value = '';            // Clears ID field

    // Optionally, reset the form to hidden state if you want to hide the form
    document.getElementById('comment-form').classList.add('d-none');

    // Reset the button text and onclick handler
    const commentTogglerBtn = document.getElementById('commentTogglerBtn');
    commentTogglerBtn.innerText = "Add New Comment"; // Reset the button text
    commentTogglerBtn.setAttribute("onclick", "toggleCommentBox(this, 'show')"); // Set onclick to 'show' action
}



// Comments adding section
async function submitComment(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const commentText = document.getElementById("COMMENT_TEXT").value;
    const taskID = document.getElementById("TASK_ID").value;

    if (!commentText) {
        toasterNotification({
            type: 'error',
            message: "<strong>Oops!</strong> You forgot to write a comment. Please enter your comment to continue."
        });
        return;
    }

    if (!taskID) {
        toasterNotification({
            type: 'error',
            message: "<strong>Oops!</strong> Task ID is missing. We need a Task ID to submit your comment."
        });
        return;
    }

    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-comment-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Saving Comment ...`;
    try {


        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const commentID = document.getElementById("ID").value;
        let url = `${APIUrl}/tasks/`;
        if (commentID)
            url += `update_comment/${commentID}`;
        else
            url += 'new_comment'
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
            toasterNotification({ type: 'success', message: "Comment Saved succefully." });
            // Comment clear and load comments on save actions
            clearCommentForm();
            fetchTaskComments(taskID);
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