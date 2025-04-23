let paginate = {
    pageLimit: 100,
    currentPage: 1,
    totalRecords: 0,
    totalPages: 0,
};

const fullPageLoader = document.getElementById("full-page-loader");

// Fetch forecast data with pagination and filters
async function fetchSalesForecastData(mode = 'replace') {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        if (mode === 'replace') {
            paginate.currentPage = 1;
        }

        fullPageLoader.classList.remove("d-none");

        const url = `${APIUrl}/sales/forecast_list`;
        const filters = filterCriterias(['ORG_ID', 'YER', 'VER']);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: filters,
            })
        });

        if (!response.ok) throw new Error('Failed to fetch sales forecast data');

        const data = await response.json();
        paginate.totalPages = data.pagination.total_pages || 0;
        paginate.totalRecords = data.pagination.total_records || 0;

        const forecasts = data.forecasts || [];
        const tableId = 'sales-forecast-list-tbody';

        if (mode === 'replace') {
            document.getElementById(tableId).innerHTML = '';
        }

        showSalesForecastData(forecasts, tableId);

        // Toggle download button visibility
        const downloadContainer = document.getElementById('download-buttons-container');
        if (forecasts.length > 0) {
            downloadContainer.classList.remove('d-none');
        } else {
            downloadContainer.classList.add('d-none');
        }

        document.getElementById('load-more-btn-container').style.display =
            paginate.currentPage < paginate.totalPages ? 'block' : 'none';

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        document.getElementById('sales-forecast-list-tbody').innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });

        // Hide download buttons on error
        document.getElementById('download-buttons-container').classList.add('d-none');
    } finally {
        fullPageLoader.classList.add("d-none");
    }
}



// Render rows to table
function showSalesForecastData(forecasts, tableId) {
    const tbody = document.getElementById(tableId);

    forecasts.forEach((forecast, index) => {
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

        // Correct index column based on pagination
        const indexTd = document.createElement('td');
        indexTd.textContent = ((paginate.currentPage - 1) * paginate.pageLimit) + index + 1;
        row.appendChild(indexTd);

        // Field keys
        const inputFields = [
            'CUSTOMER_NUMBER', 'CUSTOMER_NAME', 'CATEGORY_CODE', 'SUB_CATEGORY_CODE', 'ITEM_C', 'ITEM_DESC', 'PRODUCT_WEIGHT',
            'UOM', 'SALES_MAN', 'REGION', 'STATUS'
        ];

        const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN',
            'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
        const monthFields = [];

        months.forEach(month => {
            monthFields.push(`QTY_${month}`, `UNIT_${month}`, `VALUE_${month}`);
        });

        const allFields = [...inputFields, ...monthFields];

        allFields.forEach(field => {
            const td = document.createElement('td');
            td.setAttribute('data-name', field);
            td.innerHTML = `
                <span class="view-span">${forecast[field] ?? ''}</span>
                <p class="text-danger err-lbl mb-0 fs-8" id="lbl-${field}"></p>
            `;
            row.appendChild(td);
        });

        tbody.appendChild(row);
    });
}


function editForecastRow(btn) {
    const row = btn.closest('tr');
    const tds = row.querySelectorAll('td');
    const idValue = row.querySelector('input[name="RECORD_ID"]')?.value || '';

    tds.forEach((td, index) => {
        if (index === 0 || index === 1) {
            if (index == 1)
                td.classList.add("bg-light");
            return;
        }

        const value = td.querySelector('.view-span')?.textContent.trim() || '';
        const nameAttr = td.dataset.name || '';
        td.innerHTML = '';

        const input = document.createElement('input');
        input.type = 'text';
        input.value = value;
        input.name = nameAttr;
        input.className = 'form-control form-control-sm py-0 px-2';
        input.style.minWidth = '100px';

        const error = document.createElement('p');
        error.className = 'text-danger err-lbl mb-0 fs-8';
        error.id = `lbl-${nameAttr}`;

        td.appendChild(input);
        td.appendChild(error);

        td.classList.add("bg-light");
    });

    const actionTd = tds[0];
    actionTd.innerHTML = `
        <div class="d-flex gap-1 align-items-center">
            <button class="btn btn-light p-0 px-2" title="Cancel" onclick="cancelEditRow(this)">
                <i class="fas fa-times text-danger fs-6"></i>
            </button>
            <button class="btn btn-light p-0 px-2" title="Update" onclick="updateForecastRow(this)">
                <i class="fas fa-check text-success fs-6"></i>
            </button>
            <input type="hidden" name="RECORD_ID" value="${idValue}" />
        </div>`;
}

function cancelEditRow(btn) {
    const row = btn.closest('tr');
    const tds = row.querySelectorAll('td');
    const idValue = row.querySelector('input[name="RECORD_ID"]')?.value || '';

    tds.forEach((td, index) => {
        if (index === 0 || index === 1) {
            td.classList.remove("bg-light");
            return;
        }
        const input = td.querySelector('input');
        const fieldName = input?.name || td.dataset.name || '';
        const value = input ? input.value : td.textContent.trim();

        td.setAttribute('data-name', fieldName);
        td.innerHTML = `
            <span class="view-span">${value}</span>
            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-${fieldName}"></p>`;

        td.classList.remove("bg-light");
    });

    const actionTd = tds[0];
    actionTd.innerHTML = `
        <div class="d-flex gap-1 align-items-center">
            <button class="btn btn-light p-0 px-2" title="Edit" onclick="editForecastRow(this)">
                <i class="fas fa-pencil-alt text-primary fs-6"></i>
            </button>
            <button class="btn btn-light p-0 px-2" title="Delete" onclick="deleteForecastRow(this)">
                <i class="fas fa-trash text-danger fs-6"></i>
            </button>
            <input type="hidden" name="RECORD_ID" value="${idValue}" />
        </div>`;
}

function updateRowWithData(row, updatedData) {
    const tds = row.querySelectorAll('td');
    tds.forEach((td, index) => {
        if (index === 0 || index === 1) {
            td.classList.remove("bg-light");
            return;
        }
        const fieldName = td.getAttribute('data-name');
        const value = updatedData[fieldName] ?? '';
        td.innerHTML = `
            <span class="view-span">${value}</span>
            <p class="text-danger err-lbl mb-0 fs-8" id="lbl-${fieldName}"></p>`;
        td.classList.remove("bg-light");

    });

    const actionTd = tds[0];
    actionTd.innerHTML = `
        <div class="d-flex gap-1 align-items-center">
            <button class="btn btn-light p-0 px-2" title="Edit" onclick="editForecastRow(this)">
                <i class="fas fa-pencil-alt text-primary fs-6"></i>
            </button>
            <button class="btn btn-light p-0 px-2" title="Delete" onclick="deleteForecastRow(this)">
                <i class="fas fa-trash text-danger fs-6"></i>
            </button>
            <input type="hidden" name="RECORD_ID" value="${updatedData.RECORD_ID}" />
        </div>`;
}

async function updateForecastRow(btn) {
    const row = btn.closest('tr');
    const inputs = row.querySelectorAll('input[name]');
    const formData = new FormData();
    inputs.forEach(input => formData.append(input.name, input.value.trim()));

    const authToken = getCookie('auth_token');
    const id = row.querySelector('input[name="RECORD_ID"]')?.value;

    if (!authToken) {
        toasterNotification({ type: 'error', message: "Authorization token missing." });
        return;
    }

    hideErrors();
    fullPageLoader.classList.remove("d-none");

    try {
        const response = await fetch(`${APIUrl}/sales/update_forecast/${id}`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${authToken}` },
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            toasterNotification({ type: 'success', message: "Sales Forecast updated successfully." });
            updateRowWithData(row, data.data);
        } else {
            const errorData = await response.json();
            if (errorData.status === 422) showErrors(errorData.validation_errors ?? []);
            else toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Update failed: ' + error.message });
    } finally {
        fullPageLoader.classList.add("d-none");
    }
}

function deleteForecastRow(btn) {
    const row = btn.closest('tr');
    const id = row.querySelector('input[name="RECORD_ID"]').value;

    Swal.fire({
        title: 'Are you sure?',
        text: 'This record will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'No, cancel'
    }).then(async (result) => {
        if (result.isConfirmed) {
            const authToken = getCookie('auth_token');
            try {
                fullPageLoader.classList.remove("d-none");
                const response = await fetch(`${APIUrl}/sales/delete_forecast/${id}`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json'
                    }
                });
                if (!response.ok) throw new Error('Failed to delete forecast data');
                row.remove();

                const tbody = document.getElementById("sales-forecast-list-tbody");
                const rows = tbody.querySelectorAll('.forecast-row');
                rows.forEach((row, index) => {
                    const indexTd = row.querySelectorAll('td')[1];  // Select the second <td> (index column)
                    if (indexTd) {
                        indexTd.textContent = index + 1; // Update sequence number
                    }
                });
            } catch (error) {
                toasterNotification({ type: 'error', message: 'Delete failed: ' + error.message });
            } finally {
                fullPageLoader.classList.add("d-none");
            }
        }
    });
}

function loadMoreForecastData() {
    paginate.currentPage++;
    fetchSalesForecastData('append');
}


// document.addEventListener('DOMContentLoaded', () => {
//     fetchSalesForecastData();
// });

function viewForecastRecords() {
    // You can also add any extra validation here if needed
    fetchSalesForecastData('replace');
}

async function fetchForecastVersions() {
    const division = document.getElementById("ORG_ID").value;
    const year = document.getElementById("YER").value;
    const versionSelect = document.getElementById("VER");

    // Clear previous versions
    versionSelect.innerHTML = `<option value="">Select Version</option>`;

    // Both division and year must be selected
    if (!division || !year) return;

    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Missing token. Please login again." });
            return;
        }

        const response = await fetch(`${APIUrl}/sales/forecast_versions?division=${division}&year=${year}`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
            }
        });

        if (!response.ok) throw new Error('Failed to fetch versions');

        const data = await response.json();
        const versions = data.versions;

        if (!versions || versions.length === 0) {
            versionSelect.innerHTML = `<option value="1" selected>Version 1</option>`;
        } else {
            let hasOnlyOne = versions.length === 1;
            versionSelect.innerHTML = `<option value="">Select Version</option>`;
            versions.forEach((item, index) => {
                const ver = item.VER || item.ver || item.version;
                const selected = hasOnlyOne ? 'selected' : '';
                versionSelect.innerHTML += `<option value="${ver}" ${selected}>Version ${ver}</option>`;
            });
        }

    } catch (err) {
        toasterNotification({ type: 'error', message: 'Error fetching versions: ' + err.message });
    }
}


function downloadForecastData(format = 'csv') {
    const orgId = document.getElementById('ORG_ID')?.value;
    const year = document.getElementById('YER')?.value;
    const version = document.getElementById('VER')?.value;

    if (!orgId || !year || !version) {
        toasterNotification({ type: 'error', message: 'Please select Division, Year, and Version.' });
        return;
    }

    const params = new URLSearchParams({ ORG_ID: orgId, YER: year, VER: version, format });

    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({ type: 'error', message: "Authorization token missing." });
        return;
    }

    const downloadUrl = `${APIUrl}/sales/export_forecast?${params.toString()}`;

    const link = document.createElement('a');
    link.href = downloadUrl;
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    link.remove();
}

function toggleFullScreen() {
    const icon = document.getElementById("fullscreen-icon");

    if (!document.fullscreenElement) {
        // Request fullscreen for the whole document
        const docEl = document.documentElement;

        if (docEl.requestFullscreen) {
            docEl.requestFullscreen();
        } else if (docEl.webkitRequestFullscreen) {
            docEl.webkitRequestFullscreen();
        } else if (docEl.msRequestFullscreen) {
            docEl.msRequestFullscreen();
        }

        // Change icon and color
        icon.classList.remove("fa-expand", "text-primary");
        icon.classList.add("fa-compress", "text-danger");

    } else {
        // Exit fullscreen
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }

        // Change icon and color
        icon.classList.remove("fa-compress", "text-danger");
        icon.classList.add("fa-expand", "text-primary");
    }
}

document.addEventListener("fullscreenchange", () => {
    const icon = document.getElementById("fullscreen-icon");
    if (!document.fullscreenElement) {
        icon.classList.remove("fa-compress", "text-danger");
        icon.classList.add("fa-expand", "text-primary");
    }
});
