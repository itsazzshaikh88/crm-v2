let productFilters = {};
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
const gridListContainer = document.getElementById("grid-style-listing-container");
const listContainer = document.getElementById("list-style-listing");
let fetchedProducts = [];

const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

// For toggle
const gridViewBtn = document.getElementById("gridViewBtn");
const listViewBtn = document.getElementById("listViewBtn");

// Load the saved view from localStorage
const savedView = localStorage.getItem("viewMode") || "grid";


async function fetchProducts() {
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
            gridListContainer.innerHTML = '';
            appendHTMLContentToElement("grid-style-listing-container", generateSkeletonHTML("product-list-grid"), 4);
        }

        const url = `${APIUrl}/products/filterList`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: productFilters
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
        showGridProducts(data.products || [], gridListContainer);

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
        let counter = 0;
        products.forEach(product => {
            let desc = stripHtmlTags(product?.DESCRIPTION || '');
            let img = parseJsonString(product.PRODUCT_IMAGES || '', 0);
            if (img != null)
                img = `${PRODUCT_IMAGES_URL}${img}`;


            content += `<tr data-product-id="${product.PRODUCT_ID}" class="text-gray-800 fs-7">
                                <td>${++counter}</td>
                                <td class="w-300">
                                    <p class="mb-0 line-clamp-1">${product.PRODUCT_NAME}</p>
                                </td>
                                <td class="w-300">
                                    <p class="mb-0 line-clamp-1">
                                        ${(desc == 'null' ? '' : desc)}
                                    </p>
                                </td>
                                <td class="">
                                    <span class="fw-normal"><span class="badge bg-light text-primary">${product.UOM || 'PCS'}</span></span>
                                </td>
                                <td>
                                    <span class="">${product.BASE_PRICE || ''}</span>
                                </td>
                                <td class="">${product.AVL_QTY || ''}</td>
                                <td class="">${product.WEIGHT || ''}</td>
                                <td class="">${product.COLOR || ''}</td>
                                <td class=""><span class="badge bg-light text-gray-800">${product.CATEGORY_CODE || ''}</span></td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a href="products/view/${product.UUID}">
                                            <small>
                                                <i class="fs-8 fa-solid fa-up-right-from-square text-gray-800"></i>
                                            </small>
                                        </a>
                                        `;
            if (isAdmin) {
                content += `
                <a href="javascript:void(0)" onclick="openNewProductModal('edit', ${product.PRODUCT_ID})">
                                            <small>
                                                <i class="fs-8 fa-solid fa-pen text-primary"></i>
                                            </small>
                                        </a>
                <a href="javascript:void(0)" onclick="deleteProduct(${product.PRODUCT_ID})">
                <small>
                    <i class="fs-8 fa-solid fa-trash-can text-danger"></i>
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
                                            <input type="checkbox" value="444" name="type" data-column-name="DIVISION" class="getFilters">
                                            <label class="mb-0 text-filter">Food</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="242" name="type" data-column-name="DIVISION" class="getFilters">
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
                                            <input type="checkbox" data-column-name="VOLUME" value="gm" name="volume" class="getFilters">
                                            <label class="mb-0 text-filter">GM</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="VOLUME" value="kg" name="volume" class="getFilters">
                                            <label class="mb-0 text-filter">KG</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="VOLUME" value="ml" name="volume" class="getFilters">
                                            <label class="mb-0 text-filter">ML</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="VOLUME" value="ltr" name="volume" class="getFilters">
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
                                            <input type="checkbox" data-column-name="SHAPE" value="crate" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Crate</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="cup" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Cup</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="oval" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Oval</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="rectangular" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Rectangular</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="round" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Round</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="square" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Square</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" data-column-name="SHAPE" value="tub" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Tub</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Height Range Slider -->
                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-bold slider-margin-bottom">External Max Height</h6>
                                    <div class="slider-container">
                                        <div id="heightSlider" class="filter-elements"></div>
                                        <input name="heightSliderInput" type="hidden" id="heightSliderInput" value=""/> 
                                    </div>
                                </div>

                                <!-- Width Range Slider -->
                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-bold slider-margin-bottom">External Max Width</h6>
                                    <div class="slider-container">
                                        <div id="widthSlider" class="filter-elements"></div>
                                        <input name="widthSliderInput" type="hidden" id="widthSliderInput" value=""/>
                                    </div>
                                </div>

                                <!-- Length Range Slider -->
                                <div class="col-md-12 mb-3">
                                    <h6 class="fw-bold slider-margin-bottom">External Max Length</h6>
                                    <div class="slider-container">
                                        <div id="lengthSlider" class="filter-elements"></div>
                                        <input name="lengthSliderInput" type="hidden" id="lengthSliderInput" value=""/>
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

        products.forEach(product => {
            let desc = stripHtmlTags(product?.DESCRIPTION || '');
            let img = parseJsonString(product.PRODUCT_IMAGES || '', 0);
            if (img != null)
                img = `${PRODUCT_IMAGES_URL}${img}`;
            content += `<div class="col-md-4 col-lg-3 col-xxl-3 ${products?.length > 3 ? 'mb-5' : ''}">
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
        container.innerHTML = content;



    } else {
        // no data available
        container.innerHTML = renderNoResponseCodeForGrid({ colspan: numberOfHeaders })
    }
}

function filterProductList() {
    productFilters = {}; // Initialize an empty filter object

    // Select all checkboxes with class "getFilters"
    document.querySelectorAll(".getFilters").forEach(checkbox => {
        let column = checkbox.getAttribute("data-column-name");
        let value = checkbox.value;

        // If checkbox is checked, add the value
        if (checkbox.checked) {
            if (productFilters[column]) {
                let valuesArray = productFilters[column].split(",");
                if (!valuesArray.includes(value)) {
                    valuesArray.push(value);
                }
                productFilters[column] = valuesArray.join(",");
            } else {
                productFilters[column] = value;
            }
        } else {
            // If checkbox is unchecked, remove its value
            if (productFilters[column]) {
                let valuesArray = productFilters[column].split(",").filter(v => v !== value);
                productFilters[column] = valuesArray.length > 0 ? valuesArray.join(",") : undefined;
            }
        }
    });

    // Function to get slider values and add to productFilters if not empty
    function addSliderValue(sliderId, columnName) {
        let sliderInput = document.getElementById(sliderId);

        if (sliderInput && sliderInput.value.trim() !== "") {
            productFilters[columnName] = sliderInput.value;
        }
    }

    // Add slider values to filters if available
    addSliderValue("heightSliderInput", "HEIGHT");
    addSliderValue("widthSliderInput", "WIDTH");
    addSliderValue("lengthSliderInput", "LENGTH");

    // Remove empty filters
    Object.keys(productFilters).forEach(key => {
        if (!productFilters[key]) {
            delete productFilters[key];
        }
    });

    fetchProducts(); // Call the function to apply filters
}



function renderNoResponseCodeForGrid() {
    return `
        <div class="col-12 d-flex justify-content-center align-items-center" style="height: 300px;">
            <div class="text-center">
                <img src="assets/images/not-avail.png" alt="No Products" 
                     class="img-fluid" style="max-width: 150px; opacity: 0.7;">
                <h4 class="mt-3 text-secondary fw-bold">No Products Available</h4>
                <p class="text-muted">Please check back later or try a different filters.</p>
                <button type="button" onclick="fetchProducts()" class="btn btn-primary">
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
    fetchProducts(); // Fetch products for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Show grid or list
    if (savedView === 'grid') {
        listContainer.classList.add("d-none");
        gridContainer.classList.remove("d-none");
        listViewBtn.classList.remove("btn-primary");
        gridViewBtn.classList.add("btn-primary");

        // enable sliders
        // Initialize Sliders
        createRangeSlider('heightSlider', 100, 300, 0, 1000);
        createRangeSlider('widthSlider', 50, 200, 30, 300);
        createRangeSlider('lengthSlider', 150, 500, 100, 600);

        // apply on change events
        let filtersCheckBoxes = document.querySelectorAll(".getFilters");
        if (filtersCheckBoxes && filtersCheckBoxes?.length > 0) {
            filtersCheckBoxes.forEach((filterCheckBox) => {
                filterCheckBox.addEventListener("change", filterProductList);
            })
        }
    } else {
        listContainer.classList.remove("d-none");
        gridContainer.classList.add("d-none");
        listViewBtn.classList.add("btn-primary");
        gridViewBtn.classList.remove("btn-primary");
    }
    // Fetch initial product data
    fetchProducts();
    // fetchFilters();



});

function filterProducts() {
    paginate.currentPage = 1;
    fetchProducts();
}

// async function fetchCategoriesForFilter() {
//     const categoryList = document.getElementById("FILTER_CATEGORY_ID");

//     // Disable the select dropdown and show the loading label with animation
//     categoryList.disabled = true;

//     // Retrieve the auth_token from cookies
//     const authToken = getCookie('auth_token');
//     if (!authToken) {
//         toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
//         return;
//     }

//     try {
//         // Fetch categories from the API (replace 'your-api-endpoint' with the actual API URL)
//         const response = await fetch(`${APIUrl}/categories/list`, {
//             method: 'GET', // or POST, depending on the API endpoint
//             headers: {
//                 'Authorization': `Bearer ${authToken}`,
//             },
//         });

//         // Check if the response is okay (status code 200-299)
//         if (!response.ok) {
//             throw new Error('Failed to fetch categories');
//         }

//         // Parse the JSON response
//         const categories = await response.json();

//         // Clear existing options
//         categoryList.innerHTML = '<option value="">Choose Category</option>';

//         // Populate the <select> with category options
//         categories.forEach(category => {
//             const option = document.createElement("option");
//             option.value = category.ID; // Adjust to match the category ID key
//             option.textContent = category.CATEGORY_CODE; // Adjust to match the category name key
//             categoryList.appendChild(option);
//         });
//     } catch (error) {
//         toasterNotification({ type: 'error', message: error });
//     } finally {
//         // Re-enable the select dropdown and hide the loading label
//         categoryList.disabled = false;
//     }
// }

async function fetchFilters() {

    const fullPageLoader = document.getElementById("full-page-loader");

    fullPageLoader.classList.remove("d-none");

    // Retrieve the auth_token from cookies
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
        return;
    }

    try {
        // Fetch categories from the API (replace 'your-api-endpoint' with the actual API URL)
        const response = await fetch(`${APIUrl}/products/filters`, {
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
        const filterData = await response.json();
        console.log(filterData);

    } catch (error) {
        toasterNotification({ type: 'error', message: error });
    } finally {
        fullPageLoader.classList.add("d-none");
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
            fetchProducts();
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
        let elementInput = document.getElementById(`${sliderId}Input`);

        if (elementInput) {
            elementInput.value = values.join("-");
        }

        slider.setAttribute('data-value', values.join('-')); // Store values in data attribute
        triggerOnChange(slider); // Call onchange function
    });

    slider.noUiSlider.on('change', function (values) {
        // Call product filters options
        filterProductList();
    });

}

// Function to trigger onchange event for class filter-elements
function triggerOnChange(element) {
    var event = new Event('change', { bubbles: true });
    element.dispatchEvent(event);

}
