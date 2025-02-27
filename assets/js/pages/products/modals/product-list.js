let productFilters = {};
let productListFullScreenModal = null;
let modalElement = document.getElementById("productListFullScreenModal");
let callbackFunc = null;

if (modalElement) {
    productListFullScreenModal = new bootstrap.Modal(modalElement, {
        keyboard: false,        // Disable closing on escape key
        backdrop: 'static'      // Disable closing when clicking outside the modal
    });
}

function showProductListingFullScreenModal(userDefinedFunc) {
    if (productListFullScreenModal) {
        productListFullScreenModal.show();
        callbackFunc = userDefinedFunc;

        fetchProductListForModal();
    }
}

function hideProductListingFullScreenModal() {
    if (productListFullScreenModal)
        productListFullScreenModal.hide();

}

function filterProductListForModal() {
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

    fetchProductListForModal(); // Call the function to apply filters
}

// Variable declarations

const gridContainer = document.getElementById("prod-listing-modal-grid");
const gridListContainer = document.getElementById("prod-listing-modal-grid-container");
let fetchedProducts = [];



async function fetchProductListForModal() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        gridListContainer.innerHTML = '';
        appendHTMLContentToElement("prod-listing-modal-grid-container", generateSkeletonHTML("product-list-grid"), 4);

        const url = `${APIUrl}/products/filterList`;
        const filters = [];

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: prodModalListpaginate.pageLimit,
                currentPage: prodModalListpaginate.currentPage,
                filters: productFilters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();
        prodModalListpaginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        prodModalListpaginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;
        fetchedProducts = data.products || [];

        showGridProductsForModalListing(data.products || [], gridListContainer);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        // show no response code
        gridListContainer.innerHTML = renderNoResponseCodeForGridProdModalList({ colspan: numberOfHeaders })
    }
}

function renderFilterOptionsForProductModalList() {
    return `<div class="row">
                                <div class="col-md-12 mb-0">
                                    <div class="">
                                        <h6 class="fw-bold mb-0">Type</h6>
                                    </div>
                                    <div class="my-4">
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="444" name="type" data-column-name="division" class="getFilters">
                                            <label class="mb-0 text-filter">Food</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="242" name="type" data-column-name="division" class="getFilters">
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
                                            <input type="checkbox" value="gm" name="volume" class="getFilters">
                                            <label class="mb-0 text-filter">GM</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="kg" name="volume" class="getFilters">
                                            <label class="mb-0 text-filter">KG</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="ml" name="volume" class="getFilters">
                                            <label class="mb-0 text-filter">ML</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="ltr" name="volume" class="getFilters">
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
                                            <input type="checkbox" value="crate" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Crate</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="cup" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Cup</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="oval" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Oval</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="rectangular" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Rectangular</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="round" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Round</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="square" name="shapes" class="getFilters">
                                            <label class="mb-0 text-filter">Square</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-2">
                                            <input type="checkbox" value="tub" name="shapes" class="getFilters">
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

function showGridProductsForModalListing(products, container) {
    let content = '';
    let default_img = "assets/images/default-image.png";
    if (products?.length > 0) {

        products.forEach(product => {
            let desc = stripHtmlTags(product?.DESCRIPTION || '');
            let img = parseJsonString(product.PRODUCT_IMAGES || '', 0);
            if (img != null)
                img = `${PRODUCT_IMAGES_URL}${img}`;
            content += `<div class="col-md-4 col-lg-3 col-xxl-3 cursor-pointer ${products?.length > 3 ? 'mb-5' : ''}" onclick="selectProduct(this)" data-product-id="${product?.PRODUCT_ID}" 
    data-product-name="${encodeURIComponent(product?.PRODUCT_NAME || '')}" 
    data-product-desc="${encodeURIComponent(product?.DESCRIPTION || '')}">
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
                            </div>
                        </div>`;

        });
        container.innerHTML = content;

    } else {
        // no data available
        container.innerHTML = renderNoResponseCodeForGridProdModalList({ colspan: numberOfHeaders })
    }
}

function renderNoResponseCodeForGridProdModalList() {
    return `
        <div class="col-12 d-flex justify-content-center align-items-center" style="height: 300px;">
            <div class="text-center">
                <img src="assets/images/not-avail.png" alt="No Products" 
                     class="img-fluid" style="max-width: 150px; opacity: 0.7;">
                <h4 class="mt-3 text-secondary fw-bold">No Products Available</h4>
                <p class="text-muted">Please check back later or try a different category.</p>
                <button type="button" onclick="fetchProductListForModal()" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Refresh
                </button>
            </div>
        </div>
    `;
}



// Global scope
// Declare the pagination instance globally
const prodModalListpaginate = new Pagination('prod-modal-current-page', 'prod-modal-total-pages', 'prod-modal-page-of-pages', 'prod-modal-range-of-records');
prodModalListpaginate.pageLimit = 15;

// Function to handle pagination button clicks
function handlePagination(action) {
    prodModalListpaginate.paginate(action); // Update current page based on the action
    fetchProductListForModal(); // Fetch products for the updated current page
}

function filterProducts() {
    prodModalListpaginate.currentPage = 1;
    fetchProductListForModal();
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
            fetchProductListForModal();
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


// function to call after click
function selectProduct(selectedProductCard) {
    const productID = selectedProductCard.getAttribute('data-product-id');
    const productName = decodeURIComponent(selectedProductCard.getAttribute('data-product-name') || '');
    const productDesc = decodeURIComponent(selectedProductCard.getAttribute('data-product-desc') || '');
    callbackFunc(productID, productName, productDesc);

    // close modal
    hideProductListingFullScreenModal();
}


// Function calls

document.addEventListener('DOMContentLoaded', () => {
    // enable sliders
    // Initialize Sliders
    createRangeSlider('heightSlider', 100, 300, 0, 1000);
    createRangeSlider('widthSlider', 50, 200, 30, 300);
    createRangeSlider('lengthSlider', 150, 500, 100, 600);

    // apply on change events
    let filtersCheckBoxes = document.querySelectorAll(".getFilters");
    if (filtersCheckBoxes && filtersCheckBoxes?.length > 0) {
        filtersCheckBoxes.forEach((filterCheckBox) => {
            filterCheckBox.addEventListener("change", filterProductListForModal);
        })
    }
});

