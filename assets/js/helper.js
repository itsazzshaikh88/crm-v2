/**
 * Strips HTML tags and converts HTML entities to plain text.
 * @param {string} inputString - The input string containing HTML.
 * @returns {string} - The cleaned plain text string.
 */
function stripHtmlTags(inputString) {
    // Check if the input is a string
    if (typeof inputString !== 'string') {
        throw new TypeError('Expected a string as input.');
    }

    // Remove HTML tags and decode HTML entities
    const cleanedString = inputString
        .replace(/<[^>]*>/g, '') // Remove HTML tags
        .replace(/&nbsp;/g, ' ') // Replace non-breaking spaces
        .replace(/&lt;/g, '<')   // Decode less than
        .replace(/&gt;/g, '>')   // Decode greater than
        .replace(/&amp;/g, '&')   // Decode ampersand
        .replace(/&quot;/g, '"')  // Decode double quotes
        .replace(/&apos;/g, "'")   // Decode single quotes
        .trim(); // Remove leading and trailing whitespace

    return cleanedString || '';
}


/**
 * Parses a JSON string and returns an object or an element at a specified index.
 *
 * @param {string|null} jsonString - The JSON string to parse.
 * @param {number|null} index - The index of the element to return (if applicable).
 * @returns {object|null} - Returns the parsed object, the element at the specified index, or null.
 */
function parseJsonString(jsonString, index = null) {
    // Validate input: check for null or empty string
    if (jsonString === null || jsonString.trim() === '') {
        return null;
    }

    let parsedObject;

    try {
        // Attempt to parse the JSON string
        parsedObject = JSON.parse(jsonString);
    } catch (error) {
        // If parsing fails, log the error (optional) and return null
        console.error('Failed to parse JSON string:', error);
        return null;
    }

    // If no index is provided, return the entire parsed object
    if (index === null) {
        return parsedObject;
    }

    // Check if the parsed object is an array
    if (Array.isArray(parsedObject)) {
        // Validate the index and return the corresponding element
        return index >= 0 && index < parsedObject.length ? parsedObject[index] : null;
    }

    // If parsedObject is not an array, return null
    return null;
}


function populateFormFields(data) {
    // Loop through each key in the object
    for (const [key, value] of Object.entries(data)) {
        // Find the element by ID that matches the key
        const element = document.getElementById(key);

        if (!element) continue; // Skip if element not found

        // Check the type of form element and set value accordingly
        if (element.tagName === 'INPUT') {
            switch (element.type) {
                case 'text':
                case 'email':
                case 'password':
                case 'hidden':
                    element.value = value;
                    break;
                case 'number':
                    element.value = parseFloat(value)
                    break;

                case 'radio':
                    const radioElements = document.querySelectorAll(`input[name="${key}"]`);
                    radioElements.forEach(radio => {
                        if (radio.value === value) {
                            radio.checked = true;
                        }
                    });
                    break;

                case 'checkbox':
                    element.checked = Array.isArray(value) ? value.includes(element.value) : Boolean(value);
                    break;
            }
        } else if (element.tagName === 'SELECT') {
            element.value = value;
        } else if (element.tagName === 'TEXTAREA') {
            element.value = value;
        }
    }
}


function showFieldContent(data) {
    // Check if the data is a valid object
    if (typeof data === 'object' && data !== null) {
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                // Select the HTML element by ID
                const element = document.getElementById(`lbl-${key}`);
                // If the element exists, update its innerHTML
                if (element) {
                    if (['null', '', ' ', "", "\"\""].includes(data[key]))
                        element.innerHTML = '';
                    else
                        element.innerHTML = capitalizeWords(data[key], true);
                }
            }
        }
    }
}

function capitalizeWords(str, capitalizeAll = false) {
    // Validate input
    if (typeof str !== 'string' || str.trim() === '') {
        return ''; // Return an empty string if input is not a valid string
    }

    if (capitalizeAll) {
        // Capitalize the first letter of each word
        return str.replace(/\b\w/g, char => char.toUpperCase());
    } else {
        // Capitalize only the first letter of the first word in the string
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }
}
