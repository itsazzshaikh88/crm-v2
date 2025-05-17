let selectedFiles = [];
let uploadedFiles = [];
const fullPageLoader = document.getElementById("full-page-loader")

document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    const url = new URL(window.location.href);
    // Get all search parameters
    const searchParams = new URLSearchParams(url.search);
    // Get all URL segments
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const complaintId = urlSegments[urlSegments.length - 2];
    const resolveUUID = urlSegments[urlSegments.length - 1];
    // Fetch product details if action is edit and id is available
    // Your code to fetch product details
    getComplaintDetails(complaintId)
    if (searchParams.get('action') === 'edit') {
        // Your code to fetch product details
        fetchResolveDetails(resolveUUID);
    }
});

function displayRequestInfo(data) {
    if (!data) return;
    const { header } = data;

    if (Object.keys(header).length > 0) {
        populateFormFields(header);
    }

}

async function fetchResolveDetails(resolveUUID) {
    const apiUrl = `${APIUrl}/complaints/resolveDetail`;
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
            body: JSON.stringify({ resolveUUID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        // Display the product information on the page if response is successful
        displayRequestInfo(data.data);
        // Show business type
        const radioElements1 = document.querySelectorAll(`input[name="ESCALATION_NEEDED"]`);

        radioElements1.forEach(radio => {
            if (radio.value === data?.data?.header?.ESCALATION_NEEDED) {
                radio.checked = true;
            }
        });
        // Show request Number
        document.getElementById("RESOLUTION_NUMBER").innerHTML = data?.data?.header?.RESOLUTION_NUMBER || "RES-00000000"

        // Show uploaded files
        // Show Product Files attached
        if (data?.data?.header?.ATTACHMENTS) {
            uploadedFiles = JSON.parse(data?.data?.header?.ATTACHMENTS) || []
            displayUploadedFiles(data?.data?.header?.ID || 0);
        }

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
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

// Remove a file from the list
function removeFile(index) {
    selectedFiles.splice(index, 1);
    displayFiles();
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

let userId = document.getElementById('USER_ID').value;
async function getComplaintDetails(complaintId) {
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
        return;
    }

    const url = `${APIUrl}/complaints/getComplaintDetails`;
    const compID = complaintId; // Ensure filterCriterias returns valid filters

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                Id: compID // Send filters to API
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch request data');
        }

        const data = await response.json();
        console.log(data); // Check the response structure


        // Update the span elements with the counts
        const complaint_id = document.getElementById('COMPLAINT_ID');
        const complaint_no = document.getElementById('COMPLAINT_NUMBER');
        const complaint_date = document.getElementById('RECEIVED_DATE');
        const complaint_status = document.getElementById('STATUS');

        if (complaint_id) complaint_id.value = data.COMPLAINT_ID || 0;
        if (complaint_no) complaint_no.value = data.COMPLAINT_NUMBER || 0;
        if (complaint_date) complaint_date.value = data.COMPLAINT_DATE || 0;
        if (complaint_status) complaint_status.value = data.STATUS || 0;

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        console.error(error);
    }
    let UUID = document.getElementById('UUID').value;

}

async function submitForm(e) {

    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    // Attach selected files
    selectedFiles.forEach(file => {
        formData.append('files[]', file);
    });
    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Resolving Complaint...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        const resolutionId = document.getElementById("RESOLUTION_ID").value;
        let url = `${APIUrl}/complaints/resolve`;
        if (resolutionId)
            url += `/${resolutionId}`
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
            toasterNotification({ type: 'success', message: "Complaint Resolution Saved Successfully!" });
            let my_baseUrl = `${baseUrl}complaints/list`;
            window.location.href = my_baseUrl; // Redirects to the specified URL

            // selectedFiles = [];
            // document.getElementById('file-list').innerHTML = ''
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

async function fetchRequest(complaintUUID) {
    const apiUrl = `${APIUrl}/complaints/detail`;
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
            body: JSON.stringify({ complaintUUID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }
        displayRequestInfo(data.data);

        // Show Product Files attached
        if (data?.data?.header?.ATTACHMENTS) {
            showAttachedFiles(JSON.parse(data?.data?.header?.ATTACHMENTS) || []);
        }

        // Set Main Label of request title as well
        // Generate Edit link and assign it to button
        // let editURL = `requests/new/${data?.data?.product?.UUID}?action=edit`
        // let editLinkElement = document.getElementById("edit-product-link")
        // if (isAdmin) {
        //     editLinkElement.classList.remove("d-none")
        //     editLinkElement.setAttribute("href", editURL)
        // } else {
        //     editLinkElement.classList.add("d-none")
        //     editLinkElement.setAttribute("href", "javascript:void(0)")
        // }

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function displayRequestInfo(data) {

    if (!data || !data) return;


    const { header, lines } = data;

    if (Object.keys(header).length > 0) {
        showFieldContent(header);
    }

    if (lines?.length > 0) {
        showLinesFields(lines);
    }
}

function showAttachedFiles(attachedFiles) {
    const fileContainer = document.getElementById("fileContainer");
    if (attachedFiles)
        fileContainer.innerHTML = generateFilesUI(attachedFiles)
    else
        fileContainer.innerHTML = ''

}
function generateLines(lines) {
    if (!lines && lines?.length <= 0) return ''
    return lines.map(line => {
        // let desc = stripHtmlTags(line?.DESCRIPTION || '');
        return `<tr id="${line.COMPLAINT_ID}">
                            <td>
                                ${line.PO_NUMBER}
                            </td>
                            <td>
                               ${line.DELIVERY_NUMBER}
                            </td>
                            <td>
                                ${line.PRODUCT_CODE}
                            </td>
                             <td>
                                ${line.PRODUCT_DESC}
                            </td>
                            <td>
                               ${line.DELIVERY_DATE}
                            </td>
                            <td>
                               ${line.QTY}
                            </td>
                            <td>
                               ${line.ISSUE}
                            </td>
                             <td>
                               ${line.REMARK}
                            </td>
                </tr>
                
    `;
    }).join('');
}

function showLinesFields(lines) {
    if (!lines)
        document.getElementById("complaint-lines").innerHTML = ''

    document.getElementById("complaint-lines").innerHTML = generateLines(lines)
}

function generateFilesUI(files) {

    if (!files) return '';
    // Font Awesome icon classes based on file extension
    // Map file extensions to Font Awesome icon classes and Bootstrap color classes
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
        default: { icon: 'fa-file', color: 'text-black' }
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


// function setStatusClose(element) {
//     const statusValue = element.value; // Get the selected value
//     const dNoneElements = document.getElementsByClassName('closed d-none'); // Get all elements with the class 'd-none'

//     if (statusValue === 'Closed') {
//         // Loop through the elements and remove the 'd-none' class
//         if (confirm('Do you want to change the complaint status to closed & proceed to fill further resolvement details?')) {
//             Array.from(dNoneElements).forEach((el) => {
//                 el.classList.remove('d-none');
//             });
//         }
//     }
//     // No else or return is necessary unless you have additional logic.
// }


