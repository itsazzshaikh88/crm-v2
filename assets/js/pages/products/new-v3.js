// Global vars
let selectedFiles = [];
let uploadedFiles = [];
const fullPageLoader = document.getElementById("full-page-loader")
const productForm = document.getElementById("productForm");
// Initialize
var newProductModal = new bootstrap.Modal(document.getElementById("newProductModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

// Quill Editor
let quillInstance;
let quillOptions = {
    theme: 'snow',
    placeholder: 'Write your product description here...',
};

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

function openNewProductModal(action = 'new', productID = null) {
    if (action === 'new') {
        fetchCategories();
        // reset form and then open 
        productForm.reset()
    } else {
        // Fetch product Details
        fetchProductToDisplayForEdit(productID);
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
    document.getElementById("PRODUCT_ID").value = '';
    const editorElement = document.getElementById(`productDescription`);
    editorElement.innerHTML = '';
    initializeQuill();
}

// Submit function
async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    // get the value of the product description
    const productDescription = (document.querySelector(".ql-editor").innerHTML == '<p><br></p>' ? null : document.querySelector(".ql-editor").innerHTML) || null;
    formData.append('DESCRIPTION', productDescription);

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
        const productID = document.getElementById("PRODUCT_ID").value;
        let url = `${APIUrl}/products/new`;
        if (productID)
            url += `/${productID}`
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
            toasterNotification({ type: 'success', message: "Product Saved Successfully!" });

            if (data?.type === 'insert') {
                closeProductModal();
                newProductModal.hide();
                fetchProducts();
            } else if (data?.type === 'update') {
                fetchProducts();
            }


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

async function fetchCategories(selected = '') {
    const categoryList = document.getElementById("CATEGORY_ID");

    // Disable the select dropdown and show the loading label with animation
    categoryList.disabled = true;

    // Retrieve the auth_token from cookies
    const authToken = getCookie('auth_token');
    if (!authToken) {
        alert("Authorization token is missing. Please Login again to make API request.");
        return;
    }
    fullPageLoader.classList.remove("d-none");

    try {
        // Fetch categories from the API (replace 'your-api-endpoint' with the actual API URL)
        const response = await fetch(`${APIUrl}/categories/list`, {
            method: 'GET', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        // Check if the response is okay (status code 200-299)
        if (!response.ok) {
            throw new Error('Failed to fetch categories');
        }

        // Parse the JSON response
        const categories = await response.json();

        showCategoriesOptions(categories || {}, categoryList, selected);



    } catch (error) {
        console.error("Error fetching categories:", error);
    } finally {
        // Re-enable the select dropdown and hide the loading label
        categoryList.disabled = false;
        fullPageLoader.classList.add("d-none");
    }
}
function showCategoriesOptions(categories, categoryList, selectCategory = null) {

    // Clear existing options
    categoryList.innerHTML = '<option value="">Select</option>';
    // Populate the <select> with category options
    categories.forEach(category => {

        const option = document.createElement("option");
        option.value = category.ID; // Adjust to match the category ID key
        option.textContent = category.CATEGORY_CODE; // Adjust to match the category name key
        categoryList.appendChild(option);
    });

    if (selectCategory)
        categoryList.value = selectCategory;
}

async function fetchProductToDisplayForEdit(productID) {

    const apiUrl = `${APIUrl}/products/detail`;
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
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ searchKey: "PRODUCT_ID", searchValue: productID })
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
        displayProductInfo(data.data);

        // set data to the description box
        if (data?.data?.product?.DESCRIPTION && data?.data?.product?.DESCRIPTION != 'null')
            initializeQuill('productDescription', quillOptions, data?.data?.product?.DESCRIPTION || '');
        else
            initializeQuill()

        // Show Product Files attached
        if (data?.data?.product?.PRODUCT_IMAGES) {
            uploadedFiles = JSON.parse(data?.data?.product?.PRODUCT_IMAGES) || []
            displayUploadedFiles(data?.data?.product?.PRODUCT_ID || 0);
        }

        fetchCategories(data?.data?.product?.CATEGORY_ID || '');

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function displayProductInfo(data) {
    if (!data || !data.product) return;

    const { inventory, ...productDetails } = data.product;

    if (Object.keys(productDetails).length > 0) {
        populateFormFields(productDetails);
    }

    if (inventory && Object.keys(inventory).length > 0) {
        populateFormFields(inventory);
    }
}

function startOver() {
    Swal.fire({
        title: "Are you sure?",
        text: "Starting a new product will discard unsaved changes. Do you want to continue?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Add New Product",
        cancelButtonText: "Cancel",
        customClass: {
            popup: 'small-swal',
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Call the function to start a new lead
            closeProductModal()
        }
    });

}