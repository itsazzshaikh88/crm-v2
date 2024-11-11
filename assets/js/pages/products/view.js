// Store files
const fullPageLoader = document.getElementById("full-page-loader")
// Function to send a request with Bearer token and display response


// Display selected files 
function showProductImages(images) {
    const gallery = document.getElementById('product-image-gallery');
    const mainImageContainer = document.getElementById('product-image-container');

    // Build the HTML string for the gallery
    let galleryHTML = '';

    // Check if there are any images
    if (images.length > 0) {
        // Set the first image as the main container image
        const firstImageUrl = `${PRODUCT_IMAGES_URL}${images[0]}`;
        mainImageContainer.src = firstImageUrl; // Display the first image

        // Build gallery HTML
        images.forEach(imageUrl => {
            let imgURL = `${PRODUCT_IMAGES_URL}${imageUrl}`;
            galleryHTML += `
                <div class="p-1 border border-secondary cursor-pointer">
                    <img 
                        src="${imgURL}" 
                        alt="Product Image" 
                        class="img-fluid" 
                        style="width: 50px; height: 50px; object-fit: cover;" 
                        onclick="document.getElementById('product-image-container').src='${imgURL}'"
                    >
                </div>
            `;
        });
    } else {
        // If no images are present, you may want to set a default image
        mainImageContainer.src = 'assets/images/default-image.png';
    }

    // Insert the generated HTML into the gallery
    gallery.innerHTML = galleryHTML;
}


async function fetctProduct(productUUID) {
    const apiUrl = `${APIUrl}/products/detail`;
    const authToken = getCookie('auth_token');
    if (!authToken) {
        toasterNotification({
            type: 'error',
            message: "Authorization token is missing. Please login again to make an API request."
        });
        return;
    }
    try {

        fullPageLoader.classList.toggle("d-none");
        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ productUUID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        displayProductInfo(data.data);
        // Show Product Files attached
        if (data?.data?.product?.PRODUCT_IMAGES) {
            console.log(data?.data?.product?.PRODUCT_IMAGES);

            showProductImages(JSON.parse(data?.data?.product?.PRODUCT_IMAGES) || []);
        }

        //  do extra logic here 
        // show status of product
        updateProductStatus(data?.data?.product?.STATUS || '')
        updateDivision(data?.data?.product?.DIVISION || '')
        updateBackOrders(data?.data?.inventory?.ALLOW_BACKORDERS || 0)

        // Generate Edit link and assign it to button
        let editURL = `products/new/${data?.data?.product?.UUID}?action=edit`
        let editLinkElement = document.getElementById("edit-product-link")
        if (isAdmin) {
            editLinkElement.classList.remove("d-none")
            editLinkElement.setAttribute("href", editURL)
        } else {
            editLinkElement.classList.add("d-none")
            editLinkElement.setAttribute("href", "javascript:void(0)")
        }

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function updateProductStatus(status) {
    let element = document.getElementById("lbl-STATUS");
    if (element) {
        element.classList.remove(...["bg-success", "bg-danger", "bg-primary", "bg-warning"]);

        const statusColorMap = {
            active: 'success',
            discontinued: 'warning',
            inactive: 'danger'
        };

        const color = statusColorMap[status] || 'primary'; // Default to 'primary' if status not found
        element.classList.add(`bg-${color}`);
    }
}

function updateDivision(code) {
    let element = document.getElementById("lbl-DIVISION");
    if (element) {
        const divisionMap = {
            _242: 'Non-Food',
            _444: 'Food'
        };
        element.innerHTML = divisionMap[`_${code}`] || '';
    }
}
function updateBackOrders(code) {
    let element = document.getElementById("lbl-ALLOW_BACKORDERS");
    if (element) {
        element.innerHTML = code ? 'Yes' : 'No';
    }
}



function displayProductInfo(data) {

    if (!data || !data.product) return;

    const { inventory, ...productDetails } = data.product;

    if (Object.keys(productDetails).length > 0) {
        showFieldContent(productDetails);
    }

    if (inventory && Object.keys(inventory).length > 0) {
        showFieldContent(inventory);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const productUUID = document.getElementById("UUID").value;
    fetctProduct(productUUID);
});

