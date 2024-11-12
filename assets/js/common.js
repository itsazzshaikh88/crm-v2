// Save Form Data
function resetForm() {
    if (confirm("Do you want to reset form?")) {
        window.location.reload();
    }
}
function showErrors(errors, id_lbl = "lbl") {
    // Loop through each error field in the errors object
    for (const fieldName in errors) {
        if (errors.hasOwnProperty(fieldName)) {
            const errorMessage = errors[fieldName];
            const errorElement = document.getElementById(`${id_lbl}-${fieldName}`);
            if (errorElement) {
                // Update the span element with the error message
                errorElement.innerHTML = errorMessage;
                // Optionally, add a CSS class to highlight the error, e.g., errorElement.classList.add('text-danger');
            }
        }
    }
}

function hideErrors(class_name = "err-lbl") {
    const errorLabels = document.querySelectorAll(`.${class_name}`); // Select all elements with class 'err-lbl'

    errorLabels.forEach((label) => {
        label.innerHTML = ""; // Clear innerHTML of each error label
    });
}

function validateNumberInput(input) {
    // Allow only digits and decimal points
    input.value = input.value.replace(/[^\d.]/g, '');
}


function getCookie(name) {
    const decodedCookie = decodeURIComponent(document.cookie);
    const cookieArray = decodedCookie.split(';');
    const cookieName = name + "=";

    for (let cookie of cookieArray) {
        let c = cookie.trim();
        if (c.indexOf(cookieName) === 0) {
            return c.substring(cookieName.length, c.length);
        }
    }
    return null; // Return null if the cookie is not found
}

function toasterNotification(option) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right", // Corrected the class name
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    if (option.type === 'success') {
        toastr.success(option.message);
    }
    else if (option.type === 'error') {
        toastr.error(option.message);
    } else {
        toastr.warning(option.message)
    }
}

function filterCriterias(filters = []) {

    if (filters != []) {
        // Create an object to hold the values
        let filteredObject = {};
        // Loop through the filters to get values from DOM elements
        filters.forEach(filter => {
            let element = document.getElementById(filter); // Get the element by ID
            if (element && element.value) { // Check if element exists and has a non-empty value
                filteredObject[filter] = element.value; // Use the filter name as the key
            }
        });
        // Return the object as a JSON string
        return filteredObject;
    }
    return {};
}

function uuid_v4() {
    // Generate 16 random bytes (128 bits)
    const data = crypto.getRandomValues(new Uint8Array(16));

    // Set the version to 4 (UUID v4)
    data[6] = (data[6] & 0x0f) | 0x40;

    // Set the variant to RFC 4122
    data[8] = (data[8] & 0x3f) | 0x80;

    // Format as UUID (xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx)
    return [...data]
        .map((b, i) =>
            [4, 6, 8, 10].includes(i) ? `-${b.toString(16).padStart(2, '0')}` : b.toString(16).padStart(2, '0')
        )
        .join('');
}
