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


function getSkeletonStructure(list) {
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

    if (list === 'open-order-list-tracking')
        return ``;
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