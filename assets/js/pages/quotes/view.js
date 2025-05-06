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

async function fetchQuotation(quoteUUID) {
    const apiUrl = `${APIUrl}/quotes/detail`;
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
            body: JSON.stringify({ searchkey: "UUID", searchvalue: quoteUUID })
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
        if (data?.data?.header?.EMPLOYEE_NAME)
            document.getElementById("main-lbl-EMPLOYEE_NAME").innerHTML = data?.data?.header?.EMPLOYEE_NAME


    } catch (error) {
        console.error(error);

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
        console.log(line.LINE_COMMENTS);

        let desc = stripHtmlTags(line?.DESCRIPTION || '');
        return `<tr id="${line.LINE_ID}">
                    <td>
                        <span class="line-clamp-1s">${line.PRODUCT_NAME}</span>
                    </td>
                    <td>
                        <span class="line-clamp-1s">${(desc == 'null' ? '' : desc)}</span>
                    </td>
                    <td>${line.QTY || ''}</td>
                    <td>${line.UNIT_PRICE || ''}</td>
                    <td>${line.COLOR || ''}</td>
                    <td>${line.TRANSPORTATION || ''}</td>
                    <td>
                        <span class="line-clamp-1s mb-0">${line.LINE_COMMENTS || ''}</span>
                    </td>
                </tr>
                
    `;
    }).join('');
}

function showLinesFields(lines) {
    if (!lines)
        document.getElementById("quote-lines").innerHTML = ''

    document.getElementById("quote-lines").innerHTML = generateLines(lines)
}

document.addEventListener('DOMContentLoaded', () => {
    const quoteUUID = document.getElementById("UUID").value;
    fetchQuotation(quoteUUID);
});

