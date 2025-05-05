const modalElement = document.getElementById('suggest-sales-forecast-modal');
const suggestiveModal = new bootstrap.Modal(modalElement);
function openSuggestiveSalesForecastModal() {
    suggestiveModal.show();
}

openSuggestiveSalesForecastModal();

function closeSuggestiveSalesForecastModal() {
    const tbody = document.getElementById('suggestive-forcast-table-tbody');
    tbody.innerHTML = '';

    document.getElementById('FILTER_SF_ORG_ID').value = '';
    document.getElementById('FILTER_SF_START_YEAR').value = '';
    document.getElementById('FILTER_SF_MODE').value = '';
    document.getElementById('FILTER_SF_TOTAL_YEARS').value = '';
}

async function fetchSuggestiveForecast() {

    // Get the selected values from the dropdowns
    var orgID = document.getElementById('FILTER_SF_ORG_ID').value;
    var startYear = document.getElementById('FILTER_SF_START_YEAR').value;
    var mode = document.getElementById('FILTER_SF_MODE').value;
    var totalYears = document.getElementById('FILTER_SF_TOTAL_YEARS').value;

    document.getElementById('lbl-filters-sf-ORG_ID').innerText = '';
    document.getElementById('lbl-filters-sf-START_YEAR').innerText = '';
    document.getElementById('lbl-filters-sf-MODE').innerText = '';
    document.getElementById('lbl-filters-sf-TOTAL_YEARS').innerText = '';

    // Initialize a flag to track if all filters are provided
    var isValid = true;



    // Check if any field is empty and display error
    if (!orgID) {
        document.getElementById('lbl-filters-sf-ORG_ID').innerText = 'Please select an organization.';
        isValid = false;
    }
    if (!startYear) {
        document.getElementById('lbl-filters-sf-START_YEAR').innerText = 'Please select a start year.';
        isValid = false;
    }
    if (!mode) {
        document.getElementById('lbl-filters-sf-MODE').innerText = 'Please select a forecast mode.';
        isValid = false;
    }
    if (!totalYears) {
        document.getElementById('lbl-filters-sf-TOTAL_YEARS').innerText = 'Please select total years.';
        isValid = false;
    }

    // If any filter is missing, do not proceed with the API call
    if (!isValid) {
        return; // Exit function without making the API call
    }

    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        fullPageLoader.classList.remove("d-none");

        const url = `${APIUrl}/sales/sugegst_forecast?org-id=${orgID}&start-year=${startYear}&total-years=${totalYears}&mode=${mode}`;


        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        if (!response.ok) throw new Error('Failed to fetch sales forecast data');

        const data = await response.json();

        const suggestive_forecast = data?.suggestive_forecast || [];
        const tbodyID = 'suggestive-forcast-table-tbody';

        showSalesForecastData(suggestive_forecast, tbodyID);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        const tbody = document.getElementById('suggestive-forcast-table-tbody');
        tbody.innerHTML = '<tr><td colspan="32" class="text-center text-danger">Failed to load data</td></tr>';
    } finally {
        fullPageLoader.classList.add("d-none");
    }
}

function showSalesForecastData(data, tbodyID) {
    const tbody = document.getElementById(tbodyID);
    tbody.innerHTML = ''; // Clear previous rows

    if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="32" class="text-center text-muted">No data available</td></tr>';
        return;
    }

    const bgClasses = ['bg-success bg-opacity-25', 'bg-danger bg-opacity-25', 'bg-primary bg-opacity-25'];

    data.forEach((item, index) => {
        const row = document.createElement('tr');

        // Static Fields (First 8 columns)
        const staticCells = [
            index + 1,
            item.DIV || '',
            item.CATE || '',
            item.CUSTOMER_NO || '',
            item.CUSTOMER_NAME || '',
            item.ITEM || '',
            item.DESCRIPTION || '',
            item.UP || ''
        ];

        staticCells.forEach(cellData => {
            const td = document.createElement('td');
            td.textContent = cellData;
            row.appendChild(td);
        });

        // Monthly Qty & Val (Repeat MON_QTY and MON_VAL for 12 months)
        for (let i = 0; i < 12; i++) {
            const colorClass = bgClasses[i % 3];

            const qtyTd = document.createElement('td');
            qtyTd.textContent = item.MON_QTY || '0';
            qtyTd.className = colorClass;

            const valTd = document.createElement('td');
            valTd.textContent = item.MON_VAL || '0';
            valTd.className = colorClass;

            row.appendChild(qtyTd);
            row.appendChild(valTd);
        }

        tbody.appendChild(row);
    });
}

