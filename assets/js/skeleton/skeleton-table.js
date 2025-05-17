/**
   * Function to generate a skeleton loader within a table body.
   * @param {string} tableId - The ID of the table where the skeleton will be inserted.
   * @param {number} rows - The number of skeleton rows to generate.
   * @param {number} columns - The number of skeleton columns to generate.
   */
function generateTableSkeletonLoader(tableId, rows, columns) {
    // Locate the table body of the specified table
    const table = document.getElementById(tableId);
    const tbody = table.querySelector("tbody");

    // Clear any existing rows in the tbody (in case the skeleton is being regenerated)
    tbody.innerHTML = "";

    // Generate skeleton rows and cells
    for (let i = 0; i < rows; i++) {
        const row = document.createElement("tr");
        for (let j = 0; j < columns; j++) {
            const cell = document.createElement("td");
            cell.classList.add("skeleton-cell");
            row.appendChild(cell);
        }
        tbody.appendChild(row);
    }
}

function productListSkeleton(tableId, rows) {
    // Locate the table body of the specified table
    const table = document.getElementById(tableId);
    const tbody = table.querySelector("tbody");
    // Clear any existing rows in the tbody (in case the skeleton is being regenerated)
    tbody.innerHTML = "";

    // Generate skeleton rows and cells
    for (let i = 0; i < rows; i++) {
        tbody.insertAdjacentHTML('beforeend', ``);
    }
}

function listingSkeleton(tableId, rows, list_name) {
    // Locate the table body of the specified table
    const table = document.getElementById(tableId);
    const tbody = table.querySelector("tbody");
    // Clear any existing rows in the tbody (in case the skeleton is being regenerated)
    tbody.innerHTML = "";

    // Generate skeleton rows and cells
    for (let i = 0; i < rows; i++) {
        tbody.insertAdjacentHTML('beforeend', getSkeletonStructure(list_name));
    }
}

function commonListingSkeleton(tableId, rows, columns) {
    // Locate the table body of the specified table
    const table = document.getElementById(tableId);
    const tbody = table.querySelector("tbody");

    // Clear any existing rows in the tbody (in case the skeleton is being regenerated)
    tbody.innerHTML = "";

    // Generate skeleton rows
    for (let i = 0; i < rows; i++) {
        const tr = document.createElement("tr");
        tr.classList.add("skeleton-loader");

        // Generate skeleton cells
        for (let j = 0; j < columns; j++) {
            const td = document.createElement("td");
            td.innerHTML = `<div class="skeleton-box" style="width: 100%; height: 20px;"></div>`;
            tr.appendChild(td);
        }

        tbody.appendChild(tr);
    }
}



function getSkeletonStructure(list) {

    if (list === 'contacts-modal')
        return `<div class="w-100 d-flex align-items-start justify-content-center flex-column">
                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <p class="mb-0 line-clamp-2 fw-normal text-primary">
                                    <span class="skeleton-box" style="width: 260px; height: 20px;"></span>
                                </p>
                                <p class="mb-0">
                                    <small class="skeleton-box" style="width: 50px; height: 30px;"></small>
                                </p>
                            </div>
                            <p class="text-gray-700 mb-0">
                                <span class="skeleton-box" style="width: 150px; height: 10px;"></span>
                            </p>
                        </div>`;

    if (list === 'clients')
        return `<tr class="skeleton-loader">
                    <td>
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                        </div>
                    </td>
                    <td class="w-400">
                        <div class="d-flex align-items-center">
                            <!-- Placeholder for the image -->
                            <div class="skeleton-box" style="width: 40px; height: 40px; margin-right: 10px;"></div>
                            <div class="ms-5">
                                <!-- Placeholder for name -->
                                <div class="skeleton-box" style="width: 150px; height: 20px; margin-bottom: 5px;"></div>
                                <!-- Placeholder for subtitle --> <br/>
                                <div class="skeleton-box" style="width: 100px; height: 14px;"></div>
                            </div>
                        </div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 150px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 60px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 50px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 40px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 30px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 50px; height: 20px;"></div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-4">
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                        </div>
                    </td>
                </tr>
                `;

    if (list === 'products')
        return `<tr class="skeleton-loader">
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                    </div>
                                </td>
                                <td class="w-400">
                                    <div class="d-flex align-items-center">
                                        <!-- Thumbnail -->
                                        <div class="skeleton-box" style="width: 50px; height: 50px;"></div>
                                        <div class="ms-5">
                                            <!-- Title -->
                                            <div class="skeleton-box" style="width: 200px; height: 20px; margin-bottom: 5px;"></div>
                                            <div class="skeleton-box" style="width: 150px; height: 14px;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="pe-0 dt-type-numeric">
                                    <div class="skeleton-box" style="width: 40px; height: 20px;"></div>
                                </td>
                                <td class="pe-0 dt-type-numeric">
                                    <div class="skeleton-box" style="width: 50px; height: 20px;"></div>
                                </td>
                                <td class="pe-0 dt-type-numeric">
                                    <div class="skeleton-box" style="width: 40px; height: 20px;"></div>
                                </td>
                                <td class="pe-0 dt-type-numeric">
                                    <div class="skeleton-box" style="width: 40px; height: 20px;"></div>
                                </td>
                                <td class="pe-0 dt-type-numeric">
                                    <div class="skeleton-box" style="width: 30px; height: 20px;"></div>
                                </td>
                                <td class="pe-0 dt-type-numeric">
                                    <div class="skeleton-box" style="width: 30px; height: 20px;"></div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-4">
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                    </div>
                                </td>
                </tr>`;

    if (list === 'requests')
        return `<tr class="skeleton-loader">
                    <td class="text-center">
                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div>
                                <!-- Placeholder for Title -->
                                <div class="skeleton-box" style="width: 100px; height: 20px; margin-bottom: 5px;"></div>
                                <!-- Placeholder for Description -->
                                <div class="skeleton-box" style="width: 150px; height: 16px; margin-bottom: 5px;"></div>
                                <!-- Placeholder for REQ ID -->
                                <div class="skeleton-box" style="width: 120px; height: 14px;"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div>
                                <!-- Placeholder for Title -->
                                <div class="skeleton-box" style="width: 120px; height: 20px; margin-bottom: 5px;"></div>
                                <!-- Placeholder for Description -->
                                <div class="skeleton-box" style="width: 100px; height: 16px; margin-bottom: 5px;"></div>
                                <!-- Placeholder for Email Icon and Address -->
                                <div class="skeleton-box" style="width: 150px; height: 14px;"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                    </td>
                    <td>
                        <div class="skeleton-box" style="width: 150px; height: 20px;"></div>
                    </td>
                    <td>
                        <div class="skeleton-box" style="width: 120px; height: 20px;"></div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-4">
                            <!-- Placeholder for Icons -->
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                        </div>
                    </td>
                </tr>
                `;

    if (list === 'purchase')
        return `<tr class="skeleton-loader">
                                <td class="text-center">
                                    <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div>
                                            <!-- Placeholder for Title -->
                                            <div class="skeleton-box" style="width: 100px; height: 20px; margin-bottom: 5px;"></div>
                                            <!-- Placeholder for Description -->
                                            <div class="skeleton-box" style="width: 150px; height: 16px; margin-bottom: 5px;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <div>
                                            <!-- Placeholder for Title -->
                                            <div class="skeleton-box" style="width: 120px; height: 20px; margin-bottom: 5px;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                                </td>
                                <td>
                                    <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                                </td>
                                <td>
                                    <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                                </td>
                                <td>
                                    <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                                </td>
                                <td>
                                    <div class="skeleton-box" style="width: 150px; height: 20px;"></div>
                                </td>
                                <td>
                                    <div class="skeleton-box" style="width: 120px; height: 20px;"></div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-4">
                                        <!-- Placeholder for Icons -->
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                    </div>
                                </td>
                            </tr>
                            `;

    if (list === 'open-order-list-tracking')
        return ``;
    if (list === 'leads')
        return `<tr class="skeleton-loader">
                    <td class="text-center">
                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                    </td>
                    <td>
                        <div>
                            <!-- Placeholder for Name -->
                            <div class="skeleton-box" style="width: 100px; height: 20px; margin-bottom: 5px;"></div>
                            <!-- Placeholder for ID -->
                            <div class="skeleton-box" style="width: 150px; height: 14px;"></div>
                        </div>
                    </td>
                    <td>
                        <!-- Placeholder for Company -->
                        <div class="skeleton-box" style="width: 150px; height: 20px;"></div>
                    </td>
                    <td>
                        <!-- Placeholder for Job Title -->
                        <div class="skeleton-box" style="width: 150px; height: 20px;"></div>
                    </td>
                    <td>
                        <div>
                            <!-- Placeholder for Email -->
                            <div class="skeleton-box" style="width: 200px; height: 14px; margin-bottom: 5px;"></div>
                            <!-- Placeholder for Phone -->
                            <div class="skeleton-box" style="width: 120px; height: 14px;"></div>
                        </div>
                    </td>
                    <td>
                        <!-- Placeholder for Date -->
                        <div class="skeleton-box" style="width: 100px; height: 20px;"></div>
                    </td>
                    <td>
                        <!-- Placeholder for Source -->
                        <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                    </td>
                    <td>
                        <!-- Placeholder for Status -->
                        <div class="skeleton-box" style="width: 50px; height: 20px;"></div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-4">
                            <!-- Placeholders for Action Icons -->
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                        </div>
                    </td>
                </tr>
                `;
    if (list === 'contacts')
        return `<tr class="skeleton-loader">
                                <td class="text-center">
                                    <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                </td>
                                <td>
                                    <div>
                                        <!-- Placeholder for Name -->
                                        <div class="skeleton-box" style="width: 150px; height: 20px; margin-bottom: 5px;"></div>
                                        <!-- Placeholder for ID -->
                                        <div class="skeleton-box" style="width: 120px; height: 14px;"></div>
                                    </div>
                                </td>
                                <td>
                                    <!-- Placeholder for Company -->
                                    <div class="skeleton-box" style="width: 200px; height: 20px;"></div>
                                </td>
                                <td>
                                    <!-- Placeholder for Job Title -->
                                    <div class="skeleton-box" style="width: 180px; height: 20px;"></div>
                                </td>
                                <td>
                                    <div>
                                        <!-- Placeholder for Email -->
                                        <div class="skeleton-box" style="width: 180px; height: 14px; margin-bottom: 5px;"></div>
                                        <!-- Placeholder for Phone -->
                                        <div class="skeleton-box" style="width: 120px; height: 14px;"></div>
                                    </div>
                                </td>
                                <td>
                                    <!-- Placeholder for Date -->
                                    <div class="skeleton-box" style="width: 150px; height: 20px;"></div>
                                </td>
                                <td>
                                    <!-- Placeholder for Source -->
                                    <div class="skeleton-box" style="width: 120px; height: 20px;"></div>
                                </td>
                                <td>
                                    <!-- Placeholder for Status -->
                                    <div class="skeleton-box" style="width: 100px; height: 20px;"></div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-4">
                                        <!-- Placeholders for Action Icons -->
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                    </div>
                                </td>
                            </tr>`;

    if (list === 'deals')
        return `<tr class="skeleton-loader">
                                <td class="min-w-175px">
                                    <div class="position-relative ps-6 pe-3 py-2">
                                        <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-secondary"></div>
                                        <!-- Placeholder for Deal Name -->
                                        <div class="skeleton-box" style="width: 120px; height: 20px; margin-bottom: 5px;"></div>
                                        <!-- Placeholder for Date -->
                                        <div class="skeleton-box" style="width: 100px; height: 14px;"></div>
                                    </div>
                                </td>
                                <td>
                                    <!-- Placeholder for Age Group -->
                                    <div class="skeleton-box" style="width: 150px; height: 14px;"></div>
                                </td>
                                <td>
                                    <!-- Placeholder for Deal Stage -->
                                    <div class="skeleton-box" style="width: 120px; height: 20px;"></div>
                                </td>
                                <td>
                                    <!-- Placeholder for Deal Type -->
                                    <div class="skeleton-box" style="width: 120px; height: 20px;"></div>
                                </td>
                                <td>
                                    <!-- Placeholder for Team Members -->
                                    <div class="skeleton-box" style="width: 150px; height: 14px;"></div>
                                </td>
                                <td>
                                    <!-- Placeholder for Date Range -->
                                    <div class="skeleton-box" style="width: 160px; height: 20px;"></div>
                                </td>
                                <td>
                                    <!-- Placeholder for Pending Status -->
                                    <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                                </td>
                                <td>
                                    <!-- Placeholder for Active Status -->
                                    <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-4">
                                        <!-- Placeholder for Action Icons -->
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                                    </div>
                                </td>
                            </tr>`

    if (list === 'moms')
        return `<tr class="skeleton-loader">
                    <td>
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                        </div>
                    </td>
                    <td class="w-400">
                        <div class="d-flex align-items-center">
                            <!-- Placeholder for the image -->
                            <div class="skeleton-box" style="width: 40px; height: 40px; margin-right: 10px;"></div>
                            <div class="ms-5">
                                <!-- Placeholder for name -->
                                <div class="skeleton-box" style="width: 150px; height: 20px; margin-bottom: 5px;"></div>
                                <!-- Placeholder for subtitle --> <br/>
                                <div class="skeleton-box" style="width: 100px; height: 14px;"></div>
                            </div>
                        </div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 150px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 60px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 50px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 40px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 30px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 dt-type-numeric">
                        <div class="skeleton-box" style="width: 50px; height: 20px;"></div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-4">
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                        </div>
                    </td>
                </tr>
                `
    if (list === 'project')
        return `<tr class="skeleton-loader">
                                <td class="min-w-175px">
                                    <div class="position-relative ps-6 pe-3 py-2">
                                        <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-light"></div>
                                        <div class="skeleton-box" style="width: 150px; height: 20px; margin-bottom: 5px;"></div> <br />
                                        <div class="skeleton-box" style="width: 100px; height: 14px;"></div>
                                    </div>
                                </td>
                                <td class="min-w-200px">
                                    <div class="fs-7 text-muted fw-normal">
                                        <div class="skeleton-box" style="width: 200px; height: 50px;"></div>
                                    </div>
                                </td>
                                <td class="min-w-150px">
                                    <div class="skeleton-box" style="width: 120px; height: 20px;"></div>
                                </td>
                                <td>
                                    <div class="skeleton-box" style="width: 80px; height: 20px; border-radius: 4px;"></div>
                                </td>
                                <td class="min-w-125px">
                                    <div class="skeleton-box" style="width: 100px; height: 20px;"></div>
                                </td>
                                <td class="min-w-150px">
                                    <div class="skeleton-box" style="width: 70px; height: 20px; border-radius: 4px;"></div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column w-100 me-2 mt-2">
                                        <div class="skeleton-box" style="width: 50px; height: 14px; margin-bottom: 5px;"></div>
                                        <div class="progress bg-light w-100 h-5px">
                                            <div class="skeleton-box" style="width: 100%; height: 5px;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <div class="skeleton-box" style="width: 20px; height: 20px; border-radius: 50%;"></div>
                                        <div class="skeleton-box" style="width: 20px; height: 20px; border-radius: 50%;"></div>
                                        <div class="skeleton-box" style="width: 20px; height: 20px; border-radius: 50%;"></div>
                                    </div>
                                </td>
                </tr>`

    if (list === 'users')
        return `<tr class="skeleton-loader">
                    <td class="text-center">
                        <!-- Placeholder for Serial Number -->
                        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                    </td>
                    <td class="pe-0">
                        <!-- Placeholder for User Name and ID -->
                        <div>
                            <div class="skeleton-box" style="width: 150px; height: 20px; margin-bottom: 5px;"></div>
                            <div class="skeleton-box" style="width: 100px; height: 14px;"></div>
                        </div>
                    </td>
                    <td class="pe-0">
                        <!-- Placeholder for Role -->
                        <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                    </td>
                    <td class="pe-0">
                        <!-- Placeholder for Email -->
                        <div class="skeleton-box" style="width: 150px; height: 20px;"></div>
                    </td>
                    <td class="pe-0">
                        <!-- Placeholder for Phone Number -->
                        <div class="skeleton-box" style="width: 100px; height: 20px;"></div>
                    </td>
                    <td class="pe-0">
                        <!-- Placeholder for Status -->
                        <div class="skeleton-box" style="width: 80px; height: 20px;"></div>
                    </td>
                    <td class="pe-0 text-center">
                        <!-- Placeholder for Verification Status -->
                        <div class="skeleton-box" style="width: 60px; height: 20px;"></div>
                    </td>
                    <td class="text-end">
                        <!-- Placeholder for Action Icons -->
                        <div class="d-flex align-items-center justify-content-end gap-4">
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
                        </div>
                    </td>
                </tr>
                `

    if (list === 'credit')
        return `<tr class="skeleton-loader">
    <td class="text-center">
        <!-- Placeholder for Serial Number -->
        <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
    </td>
    <td>
        <!-- Placeholder for Comment -->
        <div class="d-flex">
            <div>
                <div class="skeleton-box" style="width: 150px; height: 20px; margin-bottom: 5px;"></div>
                <br />
                <div class="skeleton-box" style="width: 120px; height: 14px;"></div>
            </div>
        </div>
    </td>
    <td>
        <!-- Placeholder for Company Details -->
        <div class="d-flex">
            <div>
                <div class="skeleton-box" style="width: 150px; height: 20px; margin-bottom: 5px;"></div>
                <div class="skeleton-box" style="width: 120px; height: 14px; margin-bottom: 5px;"></div>
            </div>
        </div>
    </td>
    <td>
        <!-- Placeholder for Contact Number -->
        <div class="skeleton-box" style="width: 100px; height: 20px;"></div>
    </td>
    <td>
        <!-- Placeholder for Email -->
        <div class="skeleton-box" style="width: 150px; height: 20px;"></div>
    </td>
    <td>
        <!-- Placeholder for Date -->
        <div class="skeleton-box" style="width: 120px; height: 20px;"></div>
    </td>
    <td class="text-end">
        <!-- Placeholder for Action Icons -->
        <div class="d-flex align-items-center justify-content-end gap-4">
            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
            <div class="skeleton-box" style="width: 20px; height: 20px;"></div>
        </div>
    </td>
</tr>
`;
}

function clientListModalSkeleton(container, rows) {
    // Clear any existing data
    container.innerHTML = "";

    let skeleton_skin = `<div class="d-flex fv-row">
                                <div class="form-check form-check-custom form-check-solid">
                                    <div class="skeleton-box" style="width: 20px; height: 20px; margin-right: 10px;"></div>
                                    <label class="form-check-label" for="kt_modal_update_role_option_0">
                                        <div class="skeleton-box" style="width: 200px; height: 20px; margin-bottom: 5px;"></div> <br>
                                        <div class="skeleton-box" style="width: 180px; height: 14px;"></div>
                                        <div class="skeleton-box" style="width: 40px; height: 14px; margin-left: 32px;"></div>
                                    </label>
                                </div>
                            </div>`;
    const divider = `<div class="separator separator-dashed my-2"></div>`;

    // Generate skeleton rows and cells
    for (let i = 0; i < rows; i++) {
        let skeleton = skeleton_skin;
        if (i < (rows - 1))
            skeleton += divider
        container.insertAdjacentHTML('beforeend', skeleton);
    }
}
function productModalListingSkeleton(container, rows) {
    // Clear any existing data
    container.innerHTML = "";

    let skeleton_skin = `<div class="d-flex fv-row">
                                <div class="form-check form-check-custom form-check-solid">
                                    <div class="skeleton-box" style="width: 20px; height: 20px; margin-right: 10px;"></div>
                                    <div class="skeleton-box" style="width: 40px; height: 40px; margin-right: 10px;"></div>
                                    <label class="form-check-label" for="kt_modal_update_role_option_0">
                                        <div class="skeleton-box" style="width: 150px; height: 20px; margin-bottom: 5px;"></div> <br>
                                        <div class="skeleton-box" style="width: 250px; height: 14px;"></div>
                                    </label>
                                </div>
                            </div>`;
    const divider = `<div class="separator separator-dashed my-2"></div>`;

    // Generate skeleton rows and cells
    for (let i = 0; i < rows; i++) {
        let skeleton = skeleton_skin;
        if (i < (rows - 1))
            skeleton += divider
        container.insertAdjacentHTML('beforeend', skeleton);
    }
}

function appendSkeletonContent({
    elementId,
    position = "end",
    skeletonType = "default",
    count = 1
}) {
    const element = document.getElementById(elementId);

    if (!element) {
        console.error(`Element with ID "${elementId}" not found.`);
        return;
    }

    // Generate skeleton HTML for the given count
    let skeletonHTML = "";
    for (let i = 0; i < count; i++) {
        skeletonHTML += generateSkeletonHTML(skeletonType);
    }

    // Append content based on position
    switch (position) {
        case "before":
            element.insertAdjacentHTML("beforebegin", skeletonHTML);
            break;
        case "after":
            element.insertAdjacentHTML("afterend", skeletonHTML);
            break;
        case "start":
            element.insertAdjacentHTML("afterbegin", skeletonHTML);
            break;
        case "end":
        default:
            element.insertAdjacentHTML("beforeend", skeletonHTML);
            break;
    }
}

function generateSkeletonHTML({
    elementId,
    skeletonType = "default"
}) {
    const element = document.getElementById(elementId);

    if (!element) {
        console.error(`Element with ID "${elementId}" not found.`);
        return;
    }
    const skeletonHTML = generateSkeletonHTML(skeletonType);
    element.innerHTML(skeletonHTML);
}

// Helper function to generate skeleton HTML dynamically
function generateSkeletonHTML(type) {
    switch (type) {
        case "product-list-grid":
            return ` <div class="col-md-3">
                            <a class="card border rounded">
                                <div class="card-body px-2 py-2 rounded">
                                    <div class="image text-center p-2">
                                        <div class="skeleton-shimmer" style="width: 100%; height: 150px;"></div>
                                    </div>
                                    <div class="px-4 my-4">
                                        <div class="skeleton-shimmer" style="width: 100%; height: 20px;"></div> <br>
                                        <div class="skeleton-shimmer" style="width: 50%; height: 20px;"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    `;
        case "lead-activities":
            return `
                <div class="position-relative ps-6 pe-3 py-4 bg-gray-50 mb-2">
                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-gray-300"></div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="skeleton-box" style="width: 100px; height: 20px;"></span>
                        <div class="d-flex align-items-center gap-12">
                            <span class="skeleton-box" style="width: 100px; height: 14px;"></span>
                            <div class="d-flex align-items-center gap-4">
                                <span class="skeleton-box" style="width: 20px; height: 20px;"></span>
                                <span class="skeleton-box" style="width: 20px; height: 20px;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="my-4">
                        <div>
                            <span class="skeleton-box" style="width: 350px; height: 14px; margin-bottom: 5px;"></span>
                            <br />
                            <span class="skeleton-box" style="width: 100px; height: 14px;"></span>
                            <span class="skeleton-box" style="width: 120px; height: 14px;"></span>
                        </div>
                    </div>
                </div>
            `;
        case "contacts-list":
            return `<div class="col-md-4 mb-2 border border-dashed border-secondary">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center justify-content-center py-4">
                                <div class="skeleton-box" style="width: 80px; height: 80px;"></div>
                            </div>
                            <div class="col-8 py-4 bg-slate-50 position-relative">
                                <div class="skeleton-box" style="width: 150px; height: 20px;"></div> <br>
                                <div class="skeleton-box" style="width: 10px; height: 10px;"></div> <div class="skeleton-box" style="width: 100px; height: 10px;"></div> <br>
                                <div class="skeleton-box" style="width: 10px; height: 10px;"></div> <div class="skeleton-box" style="width: 130px; height: 10px;"></div> <br>
                                <div class="skeleton-box" style="width: 80px; height: 10px;"></div>
                            </div>
                        </div>
                    </div>`
        case "choose-survey":
            return `<div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                            <!-- Skeleton for Title -->
                            <div class="skeleton-box" style="width: 100%; height: 24px;"></div> <!-- Title -->
                            <br>
                            
                            <!-- Skeleton for Duration -->
                            <div class="skeleton-box" style="width: 60%; height: 16px;"></div> <!-- Duration -->
                            <br>
                            
                            <!-- Separator -->
                            <div class="skeleton-box" style="width: 100%; height: 2px; margin: 20px 0;"></div> <!-- Separator -->
                            
                            <!-- Skeleton for Description -->
                            <div class="skeleton-box" style="width: 100%; height: 12px;"></div>
                            <div class="skeleton-box" style="width: 90%; height: 12px;"></div>
                            <div class="skeleton-box" style="width: 80%; height: 12px;"></div> <!-- Description -->
                            <br>
                            
                            <!-- Skeleton for Button -->
                            <div class="skeleton-box" style="width: 120px; height: 36px;"></div> <!-- Button -->
                            </div>
                        </div>
                        </div>
                        `;
        default:
            return `<div class="skeleton-box" style="width: 100%; height: 20px;"></div>`;
    }
}

function appendHTMLContentToElement(elementId, content, iterations) {
    const parentElement = document.getElementById(elementId);
    if (!parentElement) {
        console.error(`Element with ID "${elementId}" not found.`);
        return;
    }

    if (typeof content !== "string" || iterations <= 0 || !Number.isInteger(iterations)) {
        console.error("Invalid content or iteration count.");
        return;
    }

    parentElement.innerHTML += content.repeat(iterations);
}

