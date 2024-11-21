// Store files
let selectedFiles = [];
let uploadedFiles = [];
let selectCategoryID = null;
const fullPageLoader = document.getElementById("full-page-loader")
let quillInstance;
let quillOptions = {
    theme: 'snow',
    placeholder: 'Write your product description here...',
};
function initializeQuill(editorId = 'productDescription', options = quillOptions, predefinedContent = '') {
    document.getElementById(editorId).innerHTML = predefinedContent;
    quillInstance = new Quill(`#${editorId}`, options);
}


// Function to send a request with Bearer token and display response
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
            window.location.reload()
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

async function fetchCategories() {
    const categoryList = document.getElementById("CATEGORY_ID");
    const fetchCategoryLabel = document.getElementById("fetch-category-label");

    // Disable the select dropdown and show the loading label with animation
    categoryList.disabled = true;
    fetchCategoryLabel.classList.remove('d-none');
    fetchCategoryLabel.classList.add('anim-pulse');

    // Retrieve the auth_token from cookies
    const authToken = getCookie('auth_token');
    if (!authToken) {
        alert("Authorization token is missing. Please Login again to make API request.");
        return;
    }

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

        // Clear existing options
        categoryList.innerHTML = '<option value="">Select</option>';

        // Populate the <select> with category options
        categories.forEach(category => {
            const option = document.createElement("option");
            option.value = category.ID; // Adjust to match the category ID key
            option.textContent = category.CATEGORY_CODE; // Adjust to match the category name key
            categoryList.appendChild(option);
        });

        if (selectCategoryID)
            categoryList.value = selectCategoryID

    } catch (error) {
        console.error("Error fetching categories:", error);
        alert("Failed to load categories. Please try again.");
    } finally {
        // Re-enable the select dropdown and hide the loading label
        categoryList.disabled = false;
        fetchCategoryLabel.classList.add('d-none');
        fetchCategoryLabel.classList.remove('anim-pulse');
    }
}
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
        content += `<div class="relative d-flex align-items-center justify-content-between gap-8 bg-light rounded px-4 py-2 cursor-pointer position-relative">
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
        content += `<div class="relative d-flex align-items-center justify-content-between gap-8 bg-light rounded px-4 py-2 cursor-pointer position-relative">
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

async function fetchProduct(productUUID) {
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
            body: JSON.stringify({ productUUID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        // assign the category id to the variable
        selectCategoryID = data?.data?.product?.CATEGORY_ID || '';
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
            console.log(uploadedFiles);

            displayUploadedFiles(data?.data?.product?.PRODUCT_ID || 0);
        }

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

document.addEventListener('DOMContentLoaded', () => {
    fetchCategories();

    const url = new URL(window.location.href);
    // Get all search parameters
    const searchParams = new URLSearchParams(url.search);
    // Get all URL segments
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const productUUID = urlSegments[urlSegments.length - 1];
    // Fetch product details if action is edit and id is available
    if (searchParams.get('action') === 'edit') {
        // Your code to fetch product details
        fetchProduct(productUUID);
    } else {
        initializeQuill();
    }
    // Fetch categories
});

