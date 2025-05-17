// Store files
// let selectedFiles = [];
// let uploadedFiles = [];
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
    // console.log(submitForm);

    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    // get the value of the product description


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
        const SurveyID = document.getElementById("SURVEY_ID").value;

        let url = `${APIUrl}/survey/new`;
        if (SurveyID)
            url += `/${SurveyID}`
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
            toasterNotification({ type: 'success', message: "Survey Saved Successfully!" });
            if (data?.type === 'insert') {
                setTimeout(() => window.location = "survey/new", 1500);
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
        // console.error(error);

        toasterNotification({ type: 'error', message: 'Survey failed:' + error });
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = buttonText;
    }
}

// async function fetchCategories() {
//     const categoryList = document.getElementById("CATEGORY_ID");
//     // const fetchCategoryLabel = document.getElementById("fetch-category-label");

//     // Disable the select dropdown and show the loading label with animation
//     categoryList.disabled = true;
//     fetchCategoryLabel.classList.remove('d-none');
//     fetchCategoryLabel.classList.add('anim-pulse');

//     // Retrieve the auth_token from cookies
//     const authToken = getCookie('auth_token');
//     if (!authToken) {
//         alert("Authorization token is missing. Please Login again to make API request.");
//         return;
//     }

//     try {
//         // Fetch categories from the API (replace 'your-api-endpoint' with the actual API URL)
//         const response = await fetch(`${APIUrl}/categories/all`, {
//             method: 'GET', // or POST, depending on the API endpoint
//             headers: {
//                 'Authorization': `Bearer ${authToken}`,
//             },
//         });

//         // Check if the response is okay (status code 200-299)
//         if (!response.ok) {
//             throw new Error('Failed to fetch categories');
//         }

//         // Parse the JSON response
//         const categories = await response.json();

//         // Clear existing options
//         categoryList.innerHTML = '<option value="">Select</option>';

//         // Populate the <select> with category options
//         categories.forEach(category => {
//             const option = document.createElement("option");
//             option.value = category.ID; // Adjust to match the category ID key
//             option.textContent = category.CATEGORY_CODE; // Adjust to match the category name key
//             categoryList.appendChild(option);
//         });

//         if (selectCategoryID)
//             categoryList.value = selectCategoryID

//     } catch (error) {
//         console.error("Error fetching categories:", error);
//         alert("Failed to load categories. Please try again.");
//     } finally {
//         // Re-enable the select dropdown and hide the loading label
//         categoryList.disabled = false;
//         fetchCategoryLabel.classList.add('d-none');
//         fetchCategoryLabel.classList.remove('anim-pulse');
//     }
// }

// Handle file selection
// function handleFileSelect(event) {
//     const files = Array.from(event.target.files);
//     files.forEach(file => {
//         // Check if file already selected
//         if (!selectedFiles.some(f => f.name === file.name)) {
//             selectedFiles.push(file);
//             displayFiles();
//         }
//     });
// }





async function fetchSurvey(surveyUUID) {
    const apiUrl = `${APIUrl}/survey/detail`;
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
            body: JSON.stringify({ surveyUUID })
        });

        // Parse the JSON response
        const data = await response.json();


        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        // assign the category id to the variable
        // selectCategoryID = data?.data?.survey?.CATEGORY_ID || '';
        // Display the product information on the page if response is successful
        displaySurveyInfo(data.data);

        // set data to the description box
        // if (data?.data?.survey?.DESCRIPTION && data?.data?.survey?.DESCRIPTION != 'null')
        //     initializeQuill('surveyDescription', quillOptions, data?.data?.survey?.DESCRIPTION || '');
        // else
        //     initializeQuill()

        // Show Product Files attached
        // if (data?.data?.product?.PRODUCT_IMAGES) {
        //     uploadedFiles = JSON.parse(data?.data?.product?.PRODUCT_IMAGES) || []
        //     console.log(uploadedFiles);

        //     displayUploadedFiles(data?.data?.product?.PRODUCT_ID || 0);
        // }

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function displaySurveyInfo(data) {
    if (!data) return;


    if (Object.keys(data).length > 0) {
        populateFormFields(data);
    }

}

document.addEventListener('DOMContentLoaded', () => {
    // fetchCategories();

    const url = new URL(window.location.href);
    // Get all search parameters
    const searchParams = new URLSearchParams(url.search);
    // Get all URL segments
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const surveyUUID = urlSegments[urlSegments.length - 1];
    // Fetch product details if action is edit and id is available
    if (searchParams.get('action') === 'edit') {
        // Your code to fetch product details
        fetchSurvey(surveyUUID);
    }
    // Fetch categories
});