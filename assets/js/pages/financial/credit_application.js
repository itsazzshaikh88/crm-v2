
// Separate storage for each field
let selectedFiles = {
    ATTACHMENT: null,
    CERTIFICATE: null,
    OWNER: null
};
let uploadedFiles = [];
const fullPageLoader = document.getElementById("full-page-loader");

// Set the current date as the value of the input field
const currentDate = new Date().toISOString().split('T')[0];  // Format as YYYY-MM-DD
document.getElementById('APPLICATION_DATE').value = currentDate;


function numToWords(element) {
    const value = element.value;
    let words = '';
    if (value != '')
        words = convertCurrencyToWords(value);
    if (words != '')
        document.getElementById("CREDIT_IN_WORDS").value = words.toUpperCase()
    else
        document.getElementById("CREDIT_IN_WORDS").value = ''

}
// Function to convert number to words based on the currency
function convertCurrencyToWords(number, currency = 'SAR') {
    if (isNaN(number) || number === "") return '';

    let [integerPart, decimalPart] = number.split('.');
    let words = '';

    // Handle negative numbers
    if (parseFloat(number) < 0) {
        words += 'minus ';
        integerPart = integerPart.substring(1); // Remove minus sign for conversion
    }

    // Convert integer part to words
    if (integerPart) {
        words += convertNumberToWords(parseInt(integerPart)) + ' ';
    }

    // Add currency text
    if (currency === 'USD') {
        words += 'dollars ';
    } else if (currency === 'INR') {
        words += 'rupees ';
    } else {
        words += 'riyals '; // Default is SAR
    }

    // Convert decimal part (if any)
    if (decimalPart) {
        if (currency === 'USD') {
            words += 'and ' + convertNumberToWords(parseInt(decimalPart)) + ' cents';
        } else if (currency === 'INR') {
            words += 'and ' + convertNumberToWords(parseInt(decimalPart)) + ' paise';
        } else {
            words += 'and ' + convertNumberToWords(parseInt(decimalPart)) + ' halalas'; // SAR
        }
    }

    return words.trim();
}

// Helper function to convert numbers to words (for integer and decimal parts)
function convertNumberToWords(num) {
    const ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    const teens = ['', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
    const tens = ['', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    const thousands = ['', 'thousand', 'million', 'billion'];

    if (num === 0) return 'zero';

    let word = '';
    let thousandCounter = 0;

    while (num > 0) {
        let chunk = num % 1000; // Get the last three digits
        if (chunk > 0) {
            word = convertChunk(chunk) + (thousands[thousandCounter] ? ' ' + thousands[thousandCounter] : '') + ' ' + word;
        }
        num = Math.floor(num / 1000); // Move to the next group of three digits
        thousandCounter++;
    }

    return word.trim();
}

// Helper function to convert a chunk of three digits into words
function convertChunk(num) {
    const ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    const teens = ['', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
    const tens = ['', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

    let str = '';

    if (num > 99) {
        str += ones[Math.floor(num / 100)] + ' hundred ';
        num %= 100;
    }

    if (num > 10 && num < 20) {
        str += teens[num - 10] + ' ';
    } else {
        if (num >= 10) {
            str += tens[Math.floor(num / 10)] + ' ';
            num %= 10;
        }
        if (num > 0) {
            str += ones[num] + ' ';
        }
    }

    return str.trim();
}




// Function to send a request with Bearer token and display response
async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    // Append single files separately
    Object.keys(selectedFiles).forEach(field => {
        if (selectedFiles[field]) {
            formData.append(field, selectedFiles[field]);
        }
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
        const creditID = document.getElementById("HEADER_ID").value;

        let url = `${APIUrl}/Financial/credit_application`;
        if (creditID)
            url += `/${creditID}`
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
            toasterNotification({ type: 'success', message: "Credit Saved Successfully!" });
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

// Handle file selection
function handleFileSelect(event, field) {

    const file = event.target.files[0]; // Allow only one file
    if (!file) return;

    // Replace existing file if any
    selectedFiles[field] = file;
    displayFiles(field);
}

// Display files for a specific field
function displayFiles(field) {
    const fileList = document.getElementById(`file-list-${field}`);
    if (!fileList) {
        console.error(`Element with id "file-list-${field}" not found`);
        return;
    }

    fileList.innerHTML = ''; // Clear the file list for the field

    if (selectedFiles[field]) {
        const file = selectedFiles[field];
        const content = `
            <div class="relative d-flex align-items-center justify-content-between gap-8 bg-light rounded px-4 py-2 cursor-pointer position-relative">
                <p class="mb-0">${file.name}</p>
                <span class="text-white" onclick="removeFile('${field}')"><i class="fa-solid fa-x text-danger"></i></span>
                <div class="position-absolute top-0 start-0 translate-middle">
                    <div class="bg-primary rounded-circle" style="width: 5px; height: 5px;"></div>
                </div>
            </div>`;
        fileList.insertAdjacentHTML('beforeend', content);
    }
}

// // Remove a file from the list
function removeFile(field) {
    selectedFiles[field] = null;
    displayFiles(field);
}



// Display uploaded files with remove button
function displayUploadedFiles(creditData) {
    // File field mappings
    const fileFields = {
        ATTACHMENT: 'CRN_ATTACHMENT',
        CERTIFICATE: 'BANK_CERTIFICATE',
        OWNER: 'OWNER_ID'
    };

    // Iterate through each file field
    for (const [fieldID, dataField] of Object.entries(fileFields)) {
        const fileList = document.getElementById(`file-list-uploaded-${fieldID}`);
        fileList.innerHTML = ''; // Clear the current file list

        // Parse the data for the specific field
        const files = creditData[dataField] ? creditData[dataField].split(',') : [];

        if (files.length > 0) {
            files.forEach((filename) => {
                const content = `
                    <div class="relative d-flex align-items-center justify-content-between gap-8 bg-light rounded px-4 py-2 cursor-pointer position-relative">
                        <p class="mb-0 text-truncate" title="${filename}">${filename}</p>
                        <span class="text-white" onclick="deleteFileFromServer('${filename}', '${fieldID}')">
                            <i class="fa-solid fa-x text-danger"></i>
                        </span>
                    </div>
                `;
                fileList.insertAdjacentHTML('beforeend', content);
            });
        } else {
            const noFileMessage = `<p class="text-muted">No files uploaded.</p>`;
            fileList.insertAdjacentHTML('beforeend', noFileMessage);
        }
    }
}


async function deleteFileFromServer(filename, fieldID) {

    const mainID = document.getElementById("HEADER_ID").value;
    // Confirm before deleting
    if (!confirm(`Are you sure you want to delete ${filename}?`)) {
        return;
    }

    // Prepare the data to send
    const requestData = new FormData();
    requestData.append('filename', filename);
    requestData.append('fieldID', fieldID);
    requestData.append('mainID', mainID);

    try {
        // Send the request to delete the file
        const response = await fetch(`${APIUrl}/financial/delete_file`, {
            method: 'POST',
            body: requestData
        });

        // Check if the response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Parse the response as JSON
        const responseData = await response.json();

        // Handle the response
        if (responseData.success) {
            // File deleted successfully, update UI
            const fileList = document.getElementById(`file-list-uploaded-${fieldID}`);
            const fileItem = Array.from(fileList.children).find(item => item.textContent.trim() === filename);
            if (fileItem) {
                fileItem.remove();
            }
        } else {
            alert(`Failed to delete ${filename}: ${responseData.message}`);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while deleting the file.');
    }
}





async function fetchCredits(creditUUID) {
    const apiUrl = `${APIUrl}/financial/detail`;
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
            body: JSON.stringify({ creditUUID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }


        // Display the product information on the page if response is successful
        displayCreditInfo(data.data);


        // Show business type
        const radioElements1 = document.querySelectorAll(`input[name="BUSINESS_TYPE"]`);
        const radioElements2 = document.querySelectorAll(`input[name="ADDRESS_SPAN"]`);
        const radioElements3 = document.querySelectorAll(`input[name="APPROVED_FINANCE"]`);
        const radioElements4 = document.querySelectorAll(`input[name="APPROVED_MANAGEMENT"]`);

        radioElements1.forEach(radio => {
            if (radio.value === data?.data?.credit?.BUSINESS_TYPE) {
                radio.checked = true;
            }
        });
        radioElements2.forEach(radio => {
            if (radio.value === data?.data?.credit?.ADDRESS_SPAN) {
                radio.checked = true;
            }
        });
        radioElements3.forEach(radio => {
            if (radio.value === data?.data?.credit?.APPROVED_FINANCE) {
                radio.checked = true;
            }
        });
        radioElements4.forEach(radio => {
            if (radio.value === data?.data?.credit?.APPROVED_MANAGEMENT) {
                radio.checked = true;
            }
        });

        // Check and display uploaded files (for admin only)
        if (loginUserType === 'admin' && data?.data?.credit) {
            displayUploadedFiles(data.data.credit);
        }

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });  
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function displayCreditInfo(data) {
    if (!data) return;

    const { credit } = data;


    if (Object.keys(credit).length > 0) {
        populateFormFields(credit);
    }
}


document.addEventListener('DOMContentLoaded', () => {

    const url = new URL(window.location.href);
    // Get all search parameters
    const searchParams = new URLSearchParams(url.search);
    // Get all URL segments
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const creditUUID = urlSegments[urlSegments.length - 1];
    // Fetch product details if action is edit and id is available
    if (searchParams.get('action') === 'edit') {
        // Your code to fetch product details
        fetchCredits(creditUUID);
    } 
});

