// Function to add a new row
function addRow() {
    const tableBody = document.querySelector('#request-lines-table tbody');
    const rowCount = tableBody.rows.length + 1;

    // Create a new row
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="PRODUCT_CODE[]" id="PRODUCT_CODE_${rowCount}" class="form-control">
                <option value="">Select</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control" name="PRODUCT_DESC[]" id="PRODUCT_DESC_${rowCount}">
        </td>
        <td>
            <input type="text" class="form-control" name="QTY[]" id="QTY_${rowCount}">
        </td>
        <td>
            <input type="date" class="form-control" name="REQ_DATE[]" id="REQ_DATE_${rowCount}">
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