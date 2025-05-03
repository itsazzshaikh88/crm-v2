const pagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 0,

    setTotalItems(total) {
        this.totalItems = total;
        this.totalPages = Math.ceil(total / this.itemsPerPage);
    },

    canLoadMore() {
        return this.currentPage < this.totalPages;
    },

    async loadMore() {
        if (this.canLoadMore()) {
            this.currentPage++;
            await fetchUserActivities();
        }
    },

    reset() {
        this.currentPage = 1;
        this.totalItems = 0;
        this.totalPages = 0;
    }
};

const activityContainer = document.getElementById("activity-container");
const loadingContainer = document.getElementById("loadingContainer");
const loadMoreBtnContainer = document.getElementById("loadMoreBtnContainer");
// Function to Fetch Data from API
async function fetchUserActivities() {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({
                type: 'error',
                message: "Authorization token is missing. Please login again to make an API request."
            });
            return;
        }

        // set loading state
        loadingContainer.classList.toggle("d-none");
        loadMoreBtnContainer.classList.add("d-none");

        const response = await fetch(`api/activities/logs?page=${pagination.currentPage}&limit=${pagination.itemsPerPage}&source=my-activities&type=LOGGED IN`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });
        const data = await response.json();

        // Append new data to UI (Modify this part based on your HTML structure)
        renderData(data.activity_logs || []);

        pagination.setTotalItems(data?.pagination?.total_records || 0);

        // Toggle Load More button visibility
        if (pagination.canLoadMore())
            loadMoreBtnContainer.classList.remove("d-none");
        else
            loadMoreBtnContainer.classList.add("d-none");


    } catch (error) {
        console.error("Error fetching data:", error);
    } finally {
        loadingContainer.classList.toggle("d-none");
    }
}

function getActionStyle(action = "") {
    const lowerAction = action.toLowerCase();

    if (/(saved|created)/.test(lowerAction)) {
        return { icon: "fa-solid fa-pencil", color: "success" };
    }
    if (/(updated|changed)/.test(lowerAction)) {
        return { icon: "fa-solid fa-pen-to-square", color: "primary" };
    }
    if (/deleted/.test(lowerAction)) {
        return { icon: "fa-solid fa-trash-can", color: "danger" };
    }
    if (/logged in/.test(lowerAction)) {
        return { icon: "fa-solid fa-right-to-bracket", color: "info" };
    }

    return { icon: "fa-solid fa-layer-group", color: "black" }; // Default case
}



// Function to Render Data (Modify this part based on your UI structure)
function renderData(records) {
    records.forEach(record => {
        const style = getActionStyle(record?.ACTIVITY_TYPE);
        const timelineItem = `
            <div class="timeline-item">
                <div class="timeline-line"></div>

                <div class="timeline-content mb-10 mt-n2 pb-4 border-bottom border-light">
                    <div class="overflow-auto ps-5">
                        <div class="fs-6 fw-semibold mb-2 text-gray-800"> 
                            <small class="text-primary">${record?.ACTIVITY_TYPE}</small>
                            <p class="mb-0">Browser used: ${record?.BROWSER}</p>
                            <p class="mb-0 fs-8">User Agent: ${record?.USER_AGENT}</p>
                        </div>
                        <div class="d-flex align-items-center fs-6">
                            <div class="text-muted me-2 fs-8">${record?.CREATED_AT} by</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        activityContainer.insertAdjacentHTML("beforeend", timelineItem);
    });
}

const getPathWithoutBaseAndQuery = () => {
    const url = new URL(window.location.href);
    return url.pathname; // Gives "/page/subpage"
};





// Load initial data on page load
document.addEventListener("DOMContentLoaded", () => {
    fetchUserActivities();
});

// Event Listener for "Load More" Button
async function loadMoreData() {
    await pagination.loadMore();
}
