// Store files
const fullPageLoader = document.getElementById("full-page-loader")
// Function to send a request with Bearer token and display response

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

function showAttachedFiles(attachedFiles) {
    const fileContainer = document.getElementById("fileContainer");
    if (attachedFiles)
        fileContainer.innerHTML = generateFilesUI(attachedFiles)
    else
        fileContainer.innerHTML = ''

}

function showAdminAttachedFiles(attachedFiles) {
    const adminFileContainer = document.getElementById("adminFileContainer");
    if (attachedFiles)
        adminFileContainer.innerHTML = generateFilesUI(attachedFiles)
    else
        adminFileContainer.innerHTML = ''

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
        data.data.header.ESCALATION_NEEDED = data?.data?.header?.ESCALATION_NEEDED === '1' ? 'Yes' : 'No';
        console.log(data.data);
        const dNoneElements = document.getElementsByClassName('resolve d-none'); // Get all elements with the class 'd-none'

        if (data?.data?.header?.STATUS === 'Closed') {
            // Loop through the elements and remove the 'd-none' class
            Array.from(dNoneElements).forEach((el) => {
                el.classList.remove('d-none');
            });
        }
        displayRequestInfo(data.data);

        // Show Product Files attached
        if (data?.data?.header?.USER_ATTACHMENTS) {
            showAttachedFiles(JSON.parse(data?.data?.header?.USER_ATTACHMENTS) || []);
        }
        if (data?.data?.header?.ADMIN_ATTACHMENTS) {
            showAdminAttachedFiles(JSON.parse(data?.data?.header?.ADMIN_ATTACHMENTS) || []);
        }


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

document.addEventListener('DOMContentLoaded', () => {
    const complaintUUID = document.getElementById("UUID").value;
    fetchRequest(complaintUUID);
});

