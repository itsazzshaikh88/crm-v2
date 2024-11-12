
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<div class="d-flex justify-content-center align-items-center flex-column">
                        <img src="assets/images/users.png" class="no-data-img-table" alt="">
                        <h4 class="text-danger fw-normal">Client list is empty, Create new client.</a> </h4>
                    </div>`;

    return noCotent;
}

// Global Level Elements
// get table id to store
const containerID = "modal-client-list";
const listContainer = document.getElementById(containerID);
let fetchedClients;

async function fetchClients() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        // Set loader to the screen 
        clientListModalSkeleton(listContainer, paginate.pageLimit || 0);
        const url = `${APIUrl}/clients/list`;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: { STATUS: 'active' }
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showClients(data.clients || [], listContainer);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        listContainer.innerHTML = renderNoResponseCode();
    }
}


function showClients(clients, listContainer) {

    if (clients?.length > 0) {
        fetchedClients = clients
        // show products
        let content = ``
        let counter = 0
        clients.forEach((client, index) => {

            content += `<!--begin::Radio-->
                        <div class="form-check form-check-custom form-check-solid" onclick="setClient(${index})">
                            <!--begin::Input-->
                            <input class="form-check-input me-3" name="user_rol${++counter}" type="radio" value="${counter}" id="kt_modal_update_role_option_${counter}">
                            <!--end::Input-->
                            <!--begin::Label-->
                            <label class="form-check-label" for="kt_modal_update_role_option_0">
                                <div class="fw-bold text-primary">${client.FIRST_NAME} ${client.LAST_NAME}</div>
                                <div class="text-gray-600">${client.EMAIL} | ${client.COUNTRY}</div>
                            </label>
                            <!--end::Label-->
                        </div>
                        <!--end::Radio-->
                        <div class="separator separator-dashed my-4"></div>
                        `;
        });
        listContainer.innerHTML = content;
    } else {
        // no data available
        listContainer.innerHTML = renderNoResponseCode()
    }
}


// Global scope
// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
paginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchClients(); // Fetch products for the updated current page
}

// Modal Related Code
var myModal = new bootstrap.Modal(document.getElementById("client-list-modal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

// Modal Related Code
var newClientModal = new bootstrap.Modal(document.getElementById("new-client-modal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});
function openClientListModal() {
    myModal.show();
    fetchClients();
}

function openNewClientModal() {
    // Set new UUID to modal input
    const uuidForClientModal = uuid_v4()
    const modalUUIDInput = document.getElementById("lbl-modal-UUID")
    if (modalUUIDInput)
        modalUUIDInput.value = uuidForClientModal

    newClientModal.show()
}

function filterProducts() {
    paginate.currentPage = 1;
    fetchClients();
}

const chooseClientBtn = document.getElementById("choose-client-btn");
const clientNameBtn = document.getElementById("client-name-btn");
const clientName = document.getElementById("client-name-element");
const clientID = document.getElementById("CLIENT_ID");
const companyAddress = document.getElementById("COMPANY_ADDRESS");
const billingAddress = document.getElementById("BILLING_ADDRESS");
const shippingAddress = document.getElementById("SHIPPING_ADDRESS");
const contactNumber = document.getElementById("CONTACT_NUMBER");
const emailAddress = document.getElementById("EMAIL_ADDRESS");

// Set and Toggle clients
function setClient(clientid) {
    const client = fetchedClients[clientid];
    clientID.value = ''
    clientName.innerHTML = 'Client Name Here ...'
    companyAddress.value = ''
    billingAddress.value = ''
    shippingAddress.value = ''
    contactNumber.value = ''
    emailAddress.value = ''
    if (client) {
        clientID.value = client?.ID || 0
        clientName.innerHTML = `${client?.FIRST_NAME || ''} ${client?.LAST_NAME || ''}`
        companyAddress.value = `${client?.COMPANY_NAME || ''}`
        billingAddress.value = `${client?.ADDRESS_LINE_1 || ''}`
        shippingAddress.value = `${client?.SHIPPING_ADDRESS || ''}`
        contactNumber.value = `${client?.PHONE_NUMBER || ''}`
        emailAddress.value = `${client?.EMAIL || ''}`
    }
    myModal.hide();
    // Toggle Buttons
    chooseClientBtn.classList.toggle("d-none")
    clientNameBtn.classList.toggle("d-none")
}

function removeClientName() {
    clientID.value = ''
    clientName.innerHTML = 'Client Name Here ...'
    companyAddress.value = ''
    billingAddress.value = ''
    shippingAddress.value = ''
    contactNumber.value = ''
    emailAddress.value = ''
    chooseClientBtn.classList.toggle("d-none")
    clientNameBtn.classList.toggle("d-none")
}

// reset new client form
function resetNewClientForm() {
    const form = document.getElementById("new_client_form");
    form.reset()
}

