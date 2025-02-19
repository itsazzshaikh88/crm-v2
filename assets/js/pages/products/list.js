// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                            <td colspan=${option.colspan} class="text-center text-danger">
                                <div class="container mt-5">
                                    <div class="d-flex justify-content-center align-items-center flex-column">
                                        <div class="card text-center">
                                            <img src="assets/images/no-data.png" class="card-img-top no-data-img-table mx-auto" alt="No Data" />
                                            <div class="card-body">
                                                <h4 class="card-title text-danger">No Data Available</h4>
                                                <p class="card-text">It seems there are no products currently available. You can add a new product to the inventory.</p>
                                                `;
    if (isAdmin)
        noCotent += `<a href="products/new" class="btn btn-primary">Add Product</a>`;
    noCotent += `                           </div >
                                        </div >
                                        </div >
                                        </div >
                                        </td >
                        </tr > `;

    return noCotent;
}

// Global Level Elements
// get table id to store
const tableId = "product-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);

const gridContainer = document.getElementById("grid-style-listing");
const listContainer = document.getElementById("list-style-listing");
let fetchedProducts = [];

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

// For toggle
const gridViewBtn = document.getElementById("gridViewBtn");
const listViewBtn = document.getElementById("listViewBtn");

// Load the saved view from localStorage
const savedView = localStorage.getItem("viewMode") || "grid";


async function fetctProducts() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        if (savedView == 'list') {
            // Set loader to the screen 
            listingSkeleton(tableId, paginate.pageLimit || 0, 'products');
        } else {
            gridContainer.innerHTML = '';
            appendHTMLContentToElement("grid-style-listing", generateSkeletonHTML("product-list-grid"), 4);
        }

        const url = `${APIUrl}/products/filterList`;
        const filters = filterCriterias(['FILTER_CATEGORY_ID']);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: { CATEGORY_ID: filters?.FILTER_CATEGORY_ID }
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;
        fetchedProducts = data.products || [];
        showProducts(data.products || [], tbody);
        showGridProducts(data.products || [], gridContainer);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}


function showProducts(products, tbody) {
    let content = '';
    let default_img = "assets/images/default-image.png";
    if (products?.length > 0) {
        // show products
        products.forEach(product => {
            let desc = stripHtmlTags(product?.DESCRIPTION || '');
            let img = parseJsonString(product.PRODUCT_IMAGES || '', 0);
            if (img != null)
                img = `${PRODUCT_IMAGES_URL}${img}`;


            content += `<tr data-product-id="${product.PRODUCT_ID}">
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="${product.PRODUCT_ID}" />
                                    </div>
                                </td>
                                <td class="w-300">
                                    <div class="d-flex align-items-center">
                                        <!--begin::Thumbnail-->
                                        <a href="javascript:void(0)" class="symbol symbol-50px d-none">
                                            <span
                                                class="symbol-label"
                                                style="background-image: url(${img ?? default_img});">
                                            </span>
                                        </a>
                                        <!--end::Thumbnail-->

                                        <div class="ms-5">
                                            <!--begin::Title-->
                                            <a
                                                href="javascript:void(0)"
                                                class="text-gray-800 text-hover-primary fs-6 fw-bold line-clamp-1"
                                                data-kt-ecommerce-product-filter="product_name">${product.PRODUCT_NAME}</a>
                                            <!--end::Title-->
                                            
                                        </div>
                                    </div>
                                </td>
                                <td class="w-300">
                                    <p class="mb-0 line-clamp-1">
                                        <small>${(desc == 'null' ? '' : desc)}</small>
                                    </p>
                                </td>
                                <td class=" pe-0 dt-type-numeric">
                                    <span class=""><span class="badge bg-light text-primary">${product.UOM || 'PCS'}</span></span>
                                </td>
                                <td class=" pe-0 dt-type-numeric" data-order="18">
                                    <span class="fw-bold ms-3">${product.BASE_PRICE || ''}</span>
                                </td>
                                <td class="pe-0 dt-type-numeric">${product.AVL_QTY || ''}</td>
                                <td class="pe-0 dt-type-numeric">${product.WEIGHT || ''}</td>
                                <td class="pe-0 dt-type-numeric">${product.COLOR || ''}</td>
                                <td class="pe-0 dt-type-numeric"><span class="badge bg-light text-gray-800">${product.CATEGORY_CODE || ''}</span></td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-4">
                                        <a href="products/view/${product.UUID}">
                                            <small>
                                                <i class="fs-5 fa-solid fa-up-right-from-square text-gray-800"></i>
                                            </small>
                                        </a>
                                        `;
            if (isAdmin) {
                content += `
                <a href="javascript:void(0)" onclick="openNewProductModal('edit', ${product.PRODUCT_ID})">
                                            <small>
                                                <i class="fs-5 fa-solid fa-pen text-gray-800"></i>
                                            </small>
                                        </a>
                <a href="javascript:void(0)" onclick="deleteProduct(${product.PRODUCT_ID})">
                <small>
                    <i class="fs-5 fa-solid fa-trash-can text-danger"></i>
                </small>
            </a>`;
            }
            content += `</div>
                                </td>
                            </tr>`;
        });
        tbody.innerHTML = content;
    } else {
        // no data available
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders })
    }
}

function renderFilterOptions() {
    return `<div class="row">
                                <div class="col-md-12 mb-0">
                                    <div class="">
                                        <h6 class="fw-bold mb-0">Type</h6>
                                    </div>
                                    <div class="my-4">
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="food" name="type" class="type">
                                            <label class="mb-0 text-filter">Food</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="industrial" name="type" class="type">
                                            <label class="mb-0 text-filter">Industrial</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-0">
                                    <div class="">
                                        <h6 class="fw-bold mb-0">Volume / Capacity</h6>
                                    </div>
                                    <div class="my-4">
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="gm" name="volume" class="type">
                                            <label class="mb-0 text-filter">GM</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="kg" name="volume" class="type">
                                            <label class="mb-0 text-filter">KG</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="ml" name="volume" class="type">
                                            <label class="mb-0 text-filter">ML</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="ltr" name="volume" class="type">
                                            <label class="mb-0 text-filter">LTR</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-0">
                                    <div class="">
                                        <h6 class="fw-bold mb-0">Shape</h6>
                                    </div>
                                    <div class="my-4">
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="crate" name="shapes" class="type">
                                            <label class="mb-0 text-filter">Crate</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="cup" name="shapes" class="type">
                                            <label class="mb-0 text-filter">Cup</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="oval" name="shapes" class="type">
                                            <label class="mb-0 text-filter">Oval</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="rectangular" name="shapes" class="type">
                                            <label class="mb-0 text-filter">Rectangular</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="round" name="shapes" class="type">
                                            <label class="mb-0 text-filter">Round</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="square" name="shapes" class="type">
                                            <label class="mb-0 text-filter">Square</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="tub" name="shapes" class="type">
                                            <label class="mb-0 text-filter">Tub</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Height Range Slider -->
                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-bold slider-margin-bottom">External Max Height</h6>
                                    <div class="slider-container">
                                        <div id="heightSlider" class="filter-elements"></div>
                                    </div>
                                </div>

                                <!-- Width Range Slider -->
                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-bold slider-margin-bottom">External Max Width</h6>
                                    <div class="slider-container">
                                        <div id="widthSlider" class="filter-elements"></div>
                                    </div>
                                </div>

                                <!-- Length Range Slider -->
                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-bold slider-margin-bottom">External Max Length</h6>
                                    <div class="slider-container">
                                        <div id="lengthSlider" class="filter-elements"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row my-4">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-sm btn-secondary border-danger" type="button" onclick="resetFilterOptions()">Reset</button>
                                </div>
                            </div>`;
}

function showGridProducts(products, container) {
    let content = '';
    let default_img = "assets/images/default-image.png";
    if (products?.length > 0) {
        // show products
        content += `<div class="col-md-3 col-lg-2 py-4">${renderFilterOptions()}</div>
                    <div class="col-md-9 col-lg-10">
                        <div class="row">`;
        products.forEach(product => {
            let desc = stripHtmlTags(product?.DESCRIPTION || '');
            let img = parseJsonString(product.PRODUCT_IMAGES || '', 0);
            if (img != null)
                img = `${PRODUCT_IMAGES_URL}${img}`;
            content += `<div class="col-md-4 col-xxl-3 ${products?.length > 3 ? 'mb-5' : ''}">
                            <div class="card border border-secondary rounded product-card">
                                <div class="card-body p-0 rounded">
                                    <!-- Header -->
                                    <div class="px-4 py-4 bg-header text-center rounded-top">
                                        <a href="products/view/${product.UUID}">
                                            <h5 class="card-title mb-0 text-white fw-normal">${product?.PRODUCT_NAME}</h5>
                                        </a>
                                    </div>
                                    <!-- Image -->
                                    <div class="image text-center">
                                        <img src="${img ?? default_img}" class="img-fluid" alt="">
                                    </div>
                                    <!-- Details -->
                                    <div class="pb-0">
                                        <table class="table table-sm table-borderless table-striped pb-0 mb-0">
                                            <tr>
                                                <th class="px-3 fw-bold text-dark">Dimensions</th>
                                                <td class="px-3">${product?.HEIGHT} X ${product?.WIDTH} X ${product?.LENGTH}</td>
                                            </tr>
                                            <tr>
                                                <th class="px-3 fw-bold text-dark">Volume</th>
                                                <td class="px-3">${product?.VOLUME}</td>
                                            </tr>
                                            <tr>
                                                <th class="px-3 fw-bold text-dark">Weight</th>
                                                <td class="px-3">${product?.WEIGHT}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- Footer Actions -->
                                <div class="card-footer py-4 d-flex align-items-center justify-content-center gap-10">
                                    <a title="View Product" href="products/view/${product.UUID}" class="text-decoration-none">
                                        <small><i class="fs-5 fa-solid fa-up-right-from-square text-gray-800"></i></small>
                                    </a>
                                    ${isAdmin ? `
                                    <a title="Edit Product"  href="javascript:void(0)" onclick="openNewProductModal('edit', ${product.PRODUCT_ID})" class="text-decoration-none">
                                        <small><i class="fs-5 fa-solid fa-pen text-gray-800"></i></small>
                                    </a>
                                    <a title="Delete Product"  href="javascript:void(0)" onclick="deleteProduct(${product.PRODUCT_ID})" class="text-decoration-none">
                                        <small><i class="fs-5 fa-solid fa-trash-can text-danger"></i></small>
                                    </a>` : ''}
                                </div>
                            </div>
                        </div>`;

        });
        content += `</div>
                        </div>`;
        container.innerHTML = content;

        // enable sliders
        // Initialize Sliders
        createRangeSlider('heightSlider', 100, 300, 0, 1000);
        createRangeSlider('widthSlider', 50, 200, 30, 300);
        createRangeSlider('lengthSlider', 150, 500, 100, 600);
    } else {
        // no data available
        container.innerHTML = renderNoResponseCodeForGrid({ colspan: numberOfHeaders })
    }
}

function renderNoResponseCodeForGrid() {
    return `
        <div class="col-12 d-flex justify-content-center align-items-center" style="height: 300px;">
            <div class="text-center">
                <img src="assets/images/not-avail.png" alt="No Products" 
                     class="img-fluid" style="max-width: 150px; opacity: 0.7;">
                <h4 class="mt-3 text-secondary fw-bold">No Products Available</h4>
                <p class="text-muted">Please check back later or try a different category.</p>
                <button type="button" onclick="fetctProducts()" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Refresh
                </button>
            </div>
        </div>
    `;
}



// Global scope
// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginate.pageLimit = savedView == 'grid' ? 15 : 10;

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetctProducts(); // Fetch products for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Show grid or list
    if (savedView === 'grid') {
        listContainer.classList.add("d-none");
        gridContainer.classList.remove("d-none");
        listViewBtn.classList.remove("btn-primary");
        gridViewBtn.classList.add("btn-primary");
    } else {
        listContainer.classList.remove("d-none");
        gridContainer.classList.add("d-none");
        listViewBtn.classList.add("btn-primary");
        gridViewBtn.classList.remove("btn-primary");
    }
    // Fetch initial product data
    fetctProducts();
    // fetchFilters();



});

function filterProducts() {
    paginate.currentPage = 1;
    fetctProducts();
}

async function fetchCategoriesForFilter() {
    const categoryList = document.getElementById("FILTER_CATEGORY_ID");

    // Disable the select dropdown and show the loading label with animation
    categoryList.disabled = true;

    // Retrieve the auth_token from cookies
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
        return;
    }

    try {
        // Fetch categories from the API (replace 'your-api-endpoint' with the actual API URL)
        const response = await fetch(`${APIUrl}/categories/list`, {
            method: 'GET', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        // Check if the response is okay (status code 200-299)
        if (!response.ok) {
            throw new Error('Failed to fetch categories');
        }

        // Parse the JSON response
        const categories = await response.json();

        // Clear existing options
        categoryList.innerHTML = '<option value="">Choose Category</option>';

        // Populate the <select> with category options
        categories.forEach(category => {
            const option = document.createElement("option");
            option.value = category.ID; // Adjust to match the category ID key
            option.textContent = category.CATEGORY_CODE; // Adjust to match the category name key
            categoryList.appendChild(option);
        });
    } catch (error) {
        toasterNotification({ type: 'error', message: error });
    } finally {
        // Re-enable the select dropdown and hide the loading label
        categoryList.disabled = false;
    }
}

async function fetchFilters() {
    const categoryList = document.getElementById("FILTER_CATEGORY_ID");

    // Disable the select dropdown and show the loading label with animation
    categoryList.disabled = true;

    // Retrieve the auth_token from cookies
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
        return;
    }

    try {
        // Fetch categories from the API (replace 'your-api-endpoint' with the actual API URL)
        const response = await fetch(`${APIUrl}/categories/list`, {
            method: 'GET', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        // Check if the response is okay (status code 200-299)
        if (!response.ok) {
            throw new Error('Failed to fetch categories');
        }

        // Parse the JSON response
        const categories = await response.json();

        // Clear existing options
        categoryList.innerHTML = '<option value="">Choose Category</option>';

        // Populate the <select> with category options
        categories.forEach(category => {
            const option = document.createElement("option");
            option.value = category.ID; // Adjust to match the category ID key
            option.textContent = category.CATEGORY_CODE; // Adjust to match the category name key
            categoryList.appendChild(option);
        });
    } catch (error) {
        toasterNotification({ type: 'error', message: error });
    } finally {
        // Re-enable the select dropdown and hide the loading label
        categoryList.disabled = false;
    }
}


async function deleteProduct(productID) {
    if (!productID) {
        throw new Error("Invalid Product ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete product? This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel",
            customClass: {
                popup: 'small-swal',
                confirmButton: 'swal-confirm-btn bg-danger',
                cancelButton: 'swal-cancel-btn',
            },
        });

        if (!confirmation.isConfirmed) return;

        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({
                type: 'error',
                message: "Authorization token is missing. Please login again to make an API request."
            });
            return;
        }

        // Show a non-closable alert box while the activity is being deleted
        Swal.fire({
            title: "Deleting Product...",
            text: "Please wait while the Product is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/products/delete/${productID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete request
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        // Close the loading alert box
        Swal.close();

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete Product details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'Product Deleted Successfully' });
            // Logic to remove the current row from the table
            fetctProducts();
        } else {
            throw new Error(data.message || 'Failed to delete Product details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}


// Toggle between grid and list layout
function toggleListLayout() {

}
function toggleGridLayout() {

}

function toggleReportLayout(action) {
    // set method of listing
    localStorage.setItem("viewMode", action)

    listContainer.classList.toggle("d-none");
    gridContainer.classList.toggle("d-none");
    listViewBtn.classList.toggle("btn-primary");
    gridViewBtn.classList.toggle("btn-primary");

}

// Filter Range Slider options
function createRangeSlider(sliderId, startMin, startMax, min, max) {
    var slider = document.getElementById(sliderId);

    noUiSlider.create(slider, {
        start: [startMin, startMax], // Initial values
        connect: true,  // Show connection between handles
        range: {
            'min': min,
            'max': max
        },
        tooltips: [true, true], // Show tooltips above handles
        format: {
            to: function (value) { return Math.round(value) + ' mm'; },
            from: function (value) { return value.replace(' mm', ''); }
        }
    });

    // Trigger change event dynamically
    slider.noUiSlider.on('update', function (values) {
        slider.setAttribute('data-value', values.join('-')); // Store values in data attribute
        triggerOnChange(slider); // Call onchange function
    });
}

// Function to trigger onchange event for class filter-elements
function triggerOnChange(element) {
    var event = new Event('change', { bubbles: true });
    element.dispatchEvent(event);
}

// Listen for changes on all sliders with class filter-elements
document.querySelectorAll('.filter-elements').forEach(slider => {
    slider.addEventListener('change', function () {
        console.log(`${this.id} changed:`, this.getAttribute('data-value'));
    });
});