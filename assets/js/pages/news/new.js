// Global vars
let selectedFiles = [];
let uploadedFiles = [];
const fullPageLoader = document.getElementById("full-page-loader")
const newsForm = document.getElementById("newsForm");

// quill instance
let quillEditorInstance;

// Initialize modal and open it
var newNewsModal = new bootstrap.Modal(document.getElementById("newNewsModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

// Initialize quill editor again and again

function quillHandler(editorID = 'editor', content = null) {
    const editorElement = document.getElementById(editorID);
    if (!editorElement) return; // Exit if element is not found

    if (!quillEditorInstance) {
        quillEditorInstance = new Quill(`#${editorID}`, {
            theme: 'snow',
            placeholder: 'Write something...',
        });
    }

    quillEditorInstance.root.innerHTML = content || ''; // Set content or clear if empty/null
}


// ---------------- Modal operations -----------------------
function openNewNewsModal(action = 'new', newsID = null) {
    quillHandler();
    if (action === 'new') {
        // reset form and then open 
        resetNewsModal();
    } else {
        // Fetch news Details
        fetchNewsToDisplayForEdit(newsID);
    }
    // Show NEw news modal 
    newNewsModal.show()
}


function resetNewsModal() {
    newsForm.reset()
    selectedFiles = [];
    uploadedFiles = [];
    displayFiles();
    displayUploadedFiles();
    const element = document.getElementById("ID");
    if (element) {
        element.value = '';
    }
    quillHandler();
}


// File handling functions 
// --------------- ========================== ------------------------- ==========================
// Handle file selection
function handleFileSelect(event) {
    const files = Array.from(event.target.files);
    files.forEach(file => {
        // Check if file already selected
        if (!selectedFiles.some(f => f.name === file.name)) {
            selectedFiles.push(file);
            displayFiles();
        }
    });
}

// Display selected files with a remove button
function displayFiles() {
    const fileList = document.getElementById('file-list');
    if (!fileList) return; // Ensure fileList exists before modifying it

    fileList.innerHTML = ''; // Clear current list

    selectedFiles.forEach((file, index) => {
        let content = `
            <div class="relative d-flex align-items-center justify-content-between border border-secondary gap-8 bg-white rounded px-4 py-2 cursor-pointer position-relative">
                <p class="mb-0">${file.name}</p>
                <span class="text-white" onclick="removeFile(${index})"><i class="fa-solid fa-x text-danger"></i></span>
                <div class="position-absolute top-0 start-0 translate-middle">
                    <div class="bg-primary rounded-circle" style="width: 5px; height: 5px;"></div>
                </div>
            </div>`;

        fileList.insertAdjacentHTML('beforeend', content);
    });
}

// Display uploaded files with a remove button
function displayUploadedFiles(newsID) {
    const fileList = document.getElementById('file-list-uploaded');
    if (!fileList) return; // Ensure fileList exists before modifying it

    fileList.innerHTML = ''; // Clear current list

    uploadedFiles.forEach((filename) => {
        let content = `
            <div class="relative d-flex align-items-center justify-content-between border border-secondary gap-8 bg-white rounded px-4 py-2 cursor-pointer position-relative">
                <p class="mb-0">${filename}</p>
                <span class="text-white" onclick="deleteFileFromServer('${filename}', ${newsID})">
                    <i class="fa-solid fa-x text-danger"></i>
                </span>
            </div>`;

        fileList.insertAdjacentHTML('beforeend', content);
    });
}


// Remove a file from the list
function removeFile(index) {
    selectedFiles.splice(index, 1);
    displayFiles();
}

// Action handlers -> data load, data insert
// Submit function
async function submitNewsForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    let newsDescription = (quillEditorInstance?.root?.innerHTML == '<p><br></p>' ? "" : document.querySelector(".ql-editor").innerHTML) || "";
    formData.append('DESCRIPTION', newsDescription);

    selectedFiles.forEach(file => {
        formData.append('files[]', file);
    });

    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Saving ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const newsID = document.getElementById("ID").value;
        let url = `${APIUrl}/news/`;
        if (newsID)
            url += `update/${newsID}`
        else
            url += `new`;
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
            toasterNotification({ type: 'success', message: "News Saved Successfully!" });
            newNewsModal.hide();
            resetNewsModal();
            fetchNews();
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
        console.error(error);

    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = buttonText;
    }
}

async function fetchNewsToDisplayForEdit(newsID) {

    const apiUrl = `${APIUrl}/news/detail/${newsID}`;
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({
            type: 'error',
            message: "Authorization token is missing. Please login again to make an API request."
        });
        return;
    }
    // Show loader


    try {

        fullPageLoader.classList.toggle("d-none");
        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'GET',
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

        // assign the category id to the variable
        // Display the product information on the page if response is successful
        displayNewDetails(data.data);

        // set data to the description box
        if (data?.data?.DESCRIPTION && data?.data?.DESCRIPTION != 'null')
            quillHandler("editor", data?.data?.DESCRIPTION || '');

        // Show Product Files attached
        if (data?.data?.ATTACHMENTS) {
            uploadedFiles = JSON.parse(data?.data?.ATTACHMENTS) || []
            displayUploadedFiles(data?.data?.ID || 0);
        }

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function displayNewDetails(data) {
    if (!data) return;

    if (Object.keys(data).length > 0) {
        populateFormFields(data);
    }
}

function startOver() {
    Swal.fire({
        title: "Are you sure?",
        text: "Starting a new news will discard unsaved changes. Do you want to continue?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Add New news",
        cancelButtonText: "Cancel",
        customClass: {
            popup: 'small-swal',
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Call the function to start a new lead
            resetNewsModal();
        }
    });

}