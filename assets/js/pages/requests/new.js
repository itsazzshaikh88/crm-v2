let selectedFiles = [];
let uploadedFiles = [];

// Function to add a new row
function addRow() {
    const tableBody = document.querySelector('#request-lines-table tbody');
    const rowCount = tableBody.rows.length + 1;

    // Create a new row
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="PRODUCT_ID[]" id="PRODUCT_ID_${rowCount}" class="form-control">
                <option value="">Select</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control" name="PRODUCT_DESC[]" id="PRODUCT_DESC_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="QUANTITY[]" id="QUANTITY_${rowCount}">
        </td>
        <td>
            <input type="date" class="form-control" name="REQUIRED_DATE[]" id="REQUIRED_DATE_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="COLOR[]" id="COLOR_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="TRANSPORTATION[]" id="TRANSPORTATION_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="COMMENTS[]" id="COMMENTS_${rowCount}">
        </td>
        <td>
            <button class="btn btn-sm border border-danger" type="button" onclick="removeRow(this)">
                <i class="las la-times fs-4 cursor-pointer text-danger m-0 p-0"></i>
            </button>
        </td>
    `;

    tableBody.appendChild(row);
}

// Function to remove a specific row
function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
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
        content += `<div class="relative d-flex align-items-center justify-content-between gap-8 bg-light rounded px-4 py-2 cursor-pointer position-relative" onclick="removeFile(${index})">
                        <p class="mb-0">${file.name}</p>
                        <span class="text-white"><i class="fa-solid fa-x text-danger"></i></span>
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
        content += `<div class="relative d-flex align-items-center justify-content-between gap-8 bg-light rounded px-4 py-2 cursor-pointer position-relative" onclick="deleteFileFromServer('${uploadedFiles}', ${productID})">
                        <p class="mb-0">${filename}</p>
                        <span class="text-white"><i class="fa-solid fa-x text-danger"></i></span>
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