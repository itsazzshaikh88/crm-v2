async function createNewForecast() {
    const tbody = document.getElementById('sales-forecast-list-tbody');
    const existing = document.querySelector('.new-forecast-row');

    if (existing) {
        const result = await Swal.fire({
            title: 'Unsaved Row Exists',
            text: 'A new forecast row is already added but not saved.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Discard & Add New',
            cancelButtonText: 'Cancel',
            width: 400,
            heightAuto: true,
            customClass: {
                popup: 'small-swal',
            }
        });

        if (!result.isConfirmed) return;

        existing.remove(); // Discard old row
    }

    const row = document.createElement('tr');
    row.classList.add('new-forecast-row');

    const inputFields = [
        { name: 'CUSTOMER_NUMBER', type: 'text' },
        { name: 'CUSTOMER_NAME', type: 'text' },
        { name: 'ITEM_C', type: 'text' },
        { name: 'ITEM_DESC', type: 'text' },
        { name: 'PRODUCT_WEIGHT', type: 'number' },
        { name: 'UOM', type: 'text' },
        { name: 'SALES_MAN', type: 'text' },
        { name: 'REGION', type: 'text' },
        { name: 'STATUS', type: 'text' },
    ];

    const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN',
        'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
    const monthFields = [];

    months.forEach(month => {
        monthFields.push({ name: `QTY_${month}`, type: 'number' });
        monthFields.push({ name: `UNIT_${month}`, type: 'text' });
        monthFields.push({ name: `VALUE_${month}`, type: 'number' });
    });

    const actionTd = document.createElement('td');
    actionTd.classList.add('sticky-start', 'bg-white');
    actionTd.style.left = '0';
    actionTd.style.zIndex = '1';
    actionTd.innerHTML = `
        <div class="d-flex gap-1 align-items-center">
            <button class="btn btn-light p-0 px-2" title="Cancel" onclick="cancelForecastRow(this)">
                <i class="fas fa-times text-danger fs-6"></i>
            </button>
            <button class="btn btn-light p-0 px-2" title="Create Record" onclick="createForecastRecord(this)">
                <i class="fas fa-check text-success fs-6"></i>
            </button>
        </div>
    `;
    row.appendChild(actionTd);

    const indexTd = document.createElement('td');
    indexTd.innerHTML = '-';
    indexTd.classList.add("bg-light");
    row.appendChild(indexTd);

    const allFields = [...inputFields, ...monthFields];

    allFields.forEach(field => {
        const td = document.createElement('td');
        td.classList.add("bg-light");
        td.innerHTML = `
            <input type="${field.type}" name="${field.name}" id="${field.name}" 
                   class="form-control form-control-sm py-0 px-2" style="min-width: 100px;" />
            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-${field.name}"></p>
        `;
        row.appendChild(td);
    });

    tbody.insertBefore(row, tbody.firstChild);
}

function cancelForecastRow(btn) {
    Swal.fire({
        title: 'Cancel this row?',
        text: 'Any unsaved data in this row will be lost.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Cancel It',
        cancelButtonText: 'No, Keep It',
        width: 400,
        heightAuto: true,
        customClass: {
            popup: 'small-swal',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const row = btn.closest('tr');
            if (row) row.remove();
        }
    });
}

async function createForecastRecord(btn) {
    const row = btn.closest('tr');
    const inputs = row.querySelectorAll('input[name]');
    const formData = new FormData();

    // Collect data from input fields
    inputs.forEach(input => {
        formData.append(input.name, input.value.trim());
    });

    // Also include ORG_ID, YER, and VER from the select dropdowns
    const orgId = document.getElementById('ORG_ID')?.value;
    const year = document.getElementById('YER')?.value;
    const version = document.getElementById('VER')?.value;

    if (!orgId || !year || !version) {
        toasterNotification({ type: 'error', message: "Division, Year, and Version must be selected." });
        return;
    }

    formData.append('ORG_ID', orgId);
    formData.append('YER', year);
    formData.append('VER', version);

    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({ type: 'error', message: "Authorization token missing." });
        return;
    }

    // Hide any previous errors
    hideErrors();

    try {
        const response = await fetch(`${APIUrl}/sales/add_forecast`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            toasterNotification({ type: 'success', message: "Sales Forecast details saved successfully." });

            if (data?.type === 'insert') {
                row.remove(); // Remove input row
                prependSalesForecastRow(data.data, 'sales-forecast-list-tbody'); // Add new read-only row
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
        toasterNotification({ type: 'error', message: 'Create failed: ' + error.message });
    }
}


function prependSalesForecastRow(forecast, tableId) {
    const tbody = document.getElementById(tableId);
    const row = document.createElement('tr');
    row.classList.add('forecast-row');

    // Action buttons + hidden ID
    const actionTd = document.createElement('td');
    actionTd.classList.add('sticky-start', 'bg-white');
    actionTd.style.left = '0';
    actionTd.style.zIndex = '1';
    actionTd.innerHTML = `
        <div class="d-flex gap-1 align-items-center">
            <button class="btn btn-light p-0 px-2" title="Edit" onclick="editForecastRow(this)">
                <i class="fas fa-pencil-alt text-primary fs-6"></i>
            </button>
            <button class="btn btn-light p-0 px-2" title="Delete" onclick="deleteForecastRow(this)">
                <i class="fas fa-trash text-danger fs-6"></i>
            </button>
            <input type="hidden" name="RECORD_ID" value="${forecast.RECORD_ID}" />
        </div>
    `;
    row.appendChild(actionTd);

    // Index column (this is dynamic, as the new row will be prepended)
    const indexTd = document.createElement('td');
    indexTd.textContent = tbody.rows.length + 1;
    row.appendChild(indexTd);

    // Field keys
    const inputFields = [
        'CUSTOMER_NUMBER', 'CUSTOMER_NAME', 'ITEM_C', 'ITEM_DESC', 'PRODUCT_WEIGHT',
        'UOM', 'SALES_MAN', 'REGION', 'STATUS'
    ];

    const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN',
        'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
    const monthFields = [];

    months.forEach(month => {
        monthFields.push(`QTY_${month}`, `UNIT_${month}`, `VALUE_${month}`);
    });

    const allFields = [...inputFields, ...monthFields];

    // Generate TDs with data-name for mapping
    allFields.forEach(field => {
        const td = document.createElement('td');
        td.setAttribute('data-name', field);
        td.innerHTML = `
            <span class="view-span">${forecast[field] ?? ''}</span>
            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-${field}"></p>
        `;
        row.appendChild(td);
    });

    // Prepend the row to the top of the table body
    tbody.insertBefore(row, tbody.firstChild);

    // Update the sequence (index) of all rows after inserting the new one
    updateRowSequence(tableId);
}

function updateRowSequence(tableId) {
    const tbody = document.getElementById(tableId);
    const rows = tbody.querySelectorAll('.forecast-row');
    rows.forEach((row, index) => {
        const indexTd = row.querySelectorAll('td')[1];  // Select the second <td> (index column)
        if (indexTd) {
            indexTd.textContent = index + 1; // Update sequence number
        }
    });
}




// ✅ NEW FUNCTION: Restart Forecast
async function restartForecast() {
    const existing = document.querySelector('.new-forecast-row');

    const result = await Swal.fire({
        title: 'Restart Forecast Entry?',
        text: existing
            ? 'There is an unsaved forecast row. Do you want to discard it and start fresh?'
            : 'Do you want to start a new forecast entry?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Start New',
        cancelButtonText: 'No, Cancel',
        width: 400,
        heightAuto: true,
        customClass: {
            popup: 'small-swal'
        }
    });

    if (result.isConfirmed) {
        if (existing) existing.remove();
        createNewForecast();
    }
}
