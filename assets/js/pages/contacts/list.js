// productListSkeleton("product-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<div class="col-md-12">
                        <div class="d-flex flex-row align-items-center justify-content-center h-full text-center p-10">
                            <!-- Illustration -->
                            <img
                                src="assets/images/add-contact.jpg"
                                alt="Empty Contacts"
                                class="w-300 h-300 mb-6" />
                            <!-- Headline -->
                            <div class="max-w-lg">
                                <h2 class="fs-2x font-semibold text-gray-900">Your Contact List is Empty</h2>
                                <!-- Subtext -->
                                <p class="text-gray-600 mt-2 fs-5 fw-normal">
                                    Your network begins here. Start by adding your first <span class="text-primary">contact</span> and building meaningful connections that matter.
                                </p>
                                <!-- Buttons -->
                                <div class="mt-6">
                                    <a href="contacts/new"
                                        class="btn btn-primary">
                                        Add Contact
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>`;

    return noCotent;
}

// Global Level Elements
// get table id to store
const contactContainer = document.getElementById("contact-container");

async function fetchContacts() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }
        // Set loader to the screen 
        appendSkeletonContent({
            elementId: "contact-container",
            position: "end",
            skeletonType: "contacts-list",
            count: paginate.pageLimit
        });
        const url = `${APIUrl}/contacts/list`;
        const filters = filterCriterias([]);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: filters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch contact data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showContactList(data.contacts || [], contactContainer);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        contactContainer.innerHTML = renderNoResponseCode();
    }
}

function showContactList(data, container) {
    if (!data) {
        throw new Error("Contact details not found");
    }
    if (data && data.length > 0) {

    } else {
        contactContainer.innerHTML = renderNoResponseCode();
    }

}

// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginate.pageLimit = 12; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchContacts(); // Fetch contact for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchContacts();
});

function filterContacts() {
    paginate.currentPage = 1;
    fetchContacts();
}

