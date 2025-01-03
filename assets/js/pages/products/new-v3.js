// Global vars
let selectedFiles = [];
let uploadedFiles = [];

// Version 3 - Add New Product
const productForm = document.getElementById("productForm");
var newProductModal = new bootstrap.Modal(document.getElementById("newProductModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});
function openNewProductModal(action = 'new', productID = null) {
    if (action === 'new') {
        // reset form and then open 
        productForm.reset()
    } else {
        // Fetch product Details
        fetchproduct(productID);
    }
    // Show NEw product modal 
    initializeQuill();
    newProductModal.show()
}
function closeProductModal() {
    productForm.reset()
    selectedFiles = [];
    uploadedFiles = [];
    displayFiles();
    displayUploadedFiles();
    document.getElementById("PRODUCT_ID").value = ''
}

// Quill Editor
let quillInstance;
let quillOptions = {
    theme: 'snow',
    placeholder: 'Write your product description here...',
};
function initializeQuill(editorId = 'productDescription', options = quillOptions, predefinedContent = '') {
    const editorElement = document.getElementById(editorId);
    if (quillInstance) {
        // Destroy the toolbar if it exists
        const toolbar = editorElement.parentElement.querySelector('.ql-toolbar');
        if (toolbar) {
            toolbar.remove();
        }
        // Clear the editor's container and destroy the instance
        quillInstance = null; // Dereference the current instance
        editorElement.innerHTML = ''; // Reset the container's content
    }

    // Set predefined content before initializing
    if (predefinedContent)
        editorElement.innerHTML = predefinedContent;

    // Create a new Quill instance
    quillInstance = new Quill(`#${editorId}`, options);
}

// Handle File Operations
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
    fileList.innerHTML = ''; // Clear current list
    selectedFiles.forEach((file, index) => {
        let content = '';
        content += `<div class="relative d-flex align-items-center justify-content-between border border-secondary gap-8 bg-white rounded px-4 py-2 cursor-pointer position-relative">
                        <p class="mb-0">${file.name}</p>
                        <span class="text-white" onclick="removeFile(${index})"><i class="fa-solid fa-x text-danger"></i></span>
                        <div class="position-absolute top-0 start-0 translate-middle">
                            <div class="bg-primary rounded-circle" style="width: 5px; height: 5px;"></div>
                        </div>
                    </div>`;

        // Append the content as HTML to the fileList element
        fileList.insertAdjacentHTML('beforeend', content);
    });

}
// Display selected files with a remove button
function displayUploadedFiles(productID) {
    const fileList = document.getElementById('file-list-uploaded');
    fileList.innerHTML = ''; // Clear current list
    uploadedFiles.forEach((filename, index) => {
        let content = '';
        content += `<div class="relative d-flex align-items-center justify-content-between border border-secondary gap-8 bg-white rounded px-4 py-2 cursor-pointer position-relative">
                        <p class="mb-0">${filename}</p>
                        <span class="text-white" onclick="deleteFileFromServer('${uploadedFiles}', ${productID})"><i class="fa-solid fa-x text-danger"></i></span>
                    </div>`;

        // Append the content as HTML to the fileList element
        fileList.insertAdjacentHTML('beforeend', content);
    });

}

// Remove a file from the list
function removeFile(index) {
    selectedFiles.splice(index, 1);
    displayFiles();
}
