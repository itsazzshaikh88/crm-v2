// Store files
const fullPageLoader = document.getElementById("full-page-loader")
// Function to send a request with Bearer token and display response

function generateFilesUI(files) {
    if (!files) return '';
    // Font Awesome icon classes based on file extension
    const iconMap = {
        pdf: { icon: 'fa-file-pdf', color: 'text-danger' },
        doc: { icon: 'fa-file-word', color: 'text-primary' },
        docx: { icon: 'fa-file-word', color: 'text-primary' },
        xls: { icon: 'fa-file-excel', color: 'text-success' },
        xlsx: { icon: 'fa-file-excel', color: 'text-success' },
        ppt: { icon: 'fa-file-powerpoint', color: 'text-warning' },
        pptx: { icon: 'fa-file-powerpoint', color: 'text-warning' },
        txt: { icon: 'fa-file-alt', color: 'text-black' },
        jpg: { icon: 'fa-file-image', color: 'text-info' },
        jpeg: { icon: 'fa-file-image', color: 'text-info' },
        png: { icon: 'fa-file-image', color: 'text-info' },
        gif: { icon: 'fa-file-image', color: 'text-info' },
        zip: { icon: 'fa-file-archive', color: 'text-black' },
        rar: { icon: 'fa-file-archive', color: 'text-black' },
        csv: { icon: 'fa-file-csv', color: 'text-success' },
        mp3: { icon: 'fa-file-audio', color: 'text-warning' },
        mp4: { icon: 'fa-file-video', color: 'text-dark' },
        default: { icon: 'fa-file', color: 'text-black' },
    };

    // Create HTML for each file
    return files.map(fileName => {
        const extension = fileName.split('.').pop().toLowerCase();
        const { icon, color } = iconMap[extension] || iconMap.default;
        let linkOfFile = `${REQUEST_DOCS_URL}${fileName}`;
        return `
                <a target="_blank" href="${linkOfFile}" class="py-1 px-4 border-secondary border border-dashed rounded">
                    <i class="fa ${icon} me-2 ${color}"></i> <span class="${color}">${fileName}</span>
                </a>
    `;
    }).join('');
}

function showAttachedFile(fileName) {
    const fileContainer = document.getElementById("fileContainer");
    if (fileName) {
        const fileHTML = generateFilesUI([fileName]); // Generate UI for the single file
        fileContainer.innerHTML += fileHTML; // Append to the container
    }
}


// Variables for bank details
const bankDetails = {
    BANK_NAME: "************",
    BANK_LOCATION: "************",
    ACCOUNT_NUMBER: "************",
    IBAN_NUMBER: "************",
    SWIFT_CODE: "************",
};

let isHidden = true; // Initially hidden

// Variables for bank detail elements
const bankFields = {
    BANK_NAME: document.getElementById("lbl-BANK_NAME"),
    BANK_LOCATION: document.getElementById("lbl-BANK_LOCATION"),
    ACCOUNT_NUMBER: document.getElementById("lbl-ACCOUNT_NUMBER"),
    IBAN_NUMBER: document.getElementById("lbl-IBAN_NUMBER"),
    SWIFT_CODE: document.getElementById("lbl-SWIFT_CODE")
};

// Function to toggle visibility of specific bank details
function toggleBankDetails(fieldId) {
    const fieldElement = bankFields[fieldId];
    const eyeIcon = document.getElementById(`eye-icon-${fieldId}`);
    const fieldName = fieldId;

    if (isHidden) {
        // Show the actual bank detail
        fieldElement.textContent = bankDetails[fieldName];
        eyeIcon.classList.remove("fa-eye", "text-primary");
        eyeIcon.classList.add("fa-eye-slash", "text-danger"); // Update icon to 'eye-slash'
    } else {
        // Hide the bank detail
        fieldElement.textContent = "************";
        eyeIcon.classList.remove("fa-eye-slash", "text-danger");
        eyeIcon.classList.add("fa-eye", "text-primary"); // Update icon back to 'eye'
    }
    isHidden = !isHidden; // Toggle the state
}



async function fetchCreditsDetail(creditUUID) {
    const apiUrl = `${APIUrl}/financial/detail`;
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

        displayCreditInfo(data.data);

          // Show Credit Files attached for each column
     if (data?.data?.credit?.CRN_ATTACHMENT) {
        showAttachedFile(data.data.credit.CRN_ATTACHMENT);
    }
    if (data?.data?.credit?.BANK_CERTIFICATE) {
        showAttachedFile(data.data.credit.BANK_CERTIFICATE);
    }
    if (data?.data?.credit?.OWNER_ID) {
        showAttachedFile(data.data.credit.OWNER_ID);
    }

    if(data?.data?.credit?.APPLICATION_NUMBER){
        document.getElementById("lbl-APPLICATION_NUMBER").innerHTML = data.data.credit.APPLICATION_NUMBER;
    }

    if (data?.data?.credit?.BANK_NAME) {
        bankDetails.BANK_NAME = data.data.credit.BANK_NAME;
        bankFields.BANK_NAME.textContent = "************"; // Ensure it's hidden
        // Dynamically select the eye icon for BANK_NAME
    const bankIcon = document.getElementById("eye-icon-BANK_NAME");

    // Update icon classes
    bankIcon.classList.add("fa-eye", "text-primary"); // Add blue color
    bankIcon.classList.remove("fa-eye-slash", "text-danger"); // Remove red color
    }


    if (data?.data?.credit?.BANK_LOCATION) {
        bankDetails.BANK_LOCATION = data.data.credit.BANK_LOCATION;
        bankFields.BANK_LOCATION.textContent = "************"; // Ensure it's hidden
        // Dynamically select the eye icon for BANK_LOCATION
    const bankIcon = document.getElementById("eye-icon-BANK_LOCATION");

    // Update icon classes
    bankIcon.classList.add("fa-eye", "text-primary"); // Add blue color
    bankIcon.classList.remove("fa-eye-slash", "text-danger"); // Remove red color
    }


    if (data?.data?.credit?.ACCOUNT_NUMBER) {
        bankDetails.ACCOUNT_NUMBER = data.data.credit.ACCOUNT_NUMBER;
        bankFields.ACCOUNT_NUMBER.textContent = "************"; // Ensure it's hidden
        // Dynamically select the eye icon for ACCOUNT_NUMBER
    const bankIcon = document.getElementById("eye-icon-ACCOUNT_NUMBER");

    // Update icon classes
    bankIcon.classList.add("fa-eye", "text-primary"); // Add blue color
    bankIcon.classList.remove("fa-eye-slash", "text-danger"); // Remove red color
    }


    if (data?.data?.credit?.IBAN_NUMBER) {
        bankDetails.IBAN_NUMBER = data.data.credit.IBAN_NUMBER;
        bankFields.IBAN_NUMBER.textContent = "************"; // Ensure it's hidden
        // Dynamically select the eye icon for IBAN_NUMBER
    const bankIcon = document.getElementById("eye-icon-IBAN_NUMBER");

    // Update icon classes
    bankIcon.classList.add("fa-eye", "text-primary"); // Add blue color
    bankIcon.classList.remove("fa-eye-slash", "text-danger"); // Remove red color
    }


    if (data?.data?.credit?.SWIFT_CODE) {
        bankDetails.SWIFT_CODE = data.data.credit.SWIFT_CODE;
        bankFields.SWIFT_CODE.textContent = "************"; // Ensure it's hidden
        // Dynamically select the eye icon for SWIFT_CODE
    const bankIcon = document.getElementById("eye-icon-SWIFT_CODE");

    // Update icon classes
    bankIcon.classList.add("fa-eye", "text-primary"); // Add blue color
    bankIcon.classList.remove("fa-eye-slash", "text-danger"); // Remove red color
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
        showFieldContent(credit);
    }
}


document.addEventListener('DOMContentLoaded', () => {
    const creditUUID = document.getElementById("UUID").value;
    fetchCreditsDetail(creditUUID);
});