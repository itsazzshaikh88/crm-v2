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

        const response = await fetch(`api/activities/logs?page=${pagination.currentPage}&limit=${pagination.itemsPerPage}&source=my-activities`, {
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
                <div class="timeline-icon me-4 ms-3">
                    <i class="${style.icon} fs-5 text-${style.color}">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </div>

                <div class="timeline-content mb-10 mt-n2 pb-4 border-bottom border-light">
                    <div class="overflow-auto pe-3">
                        <div class="fs-6 fw-semibold mb-2 text-gray-800"> <small class="text-primary">${record?.ACTIVITY_TYPE}</small> <br /> ${record?.DESCRIPTION}</div>
                        <div class="d-flex align-items-center mt-1 fs-6">
                            <div class="text-muted me-2 fs-8">${record?.CREATED_AT} by</div>
                            <div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip" data-bs-boundary="window"
                                data-bs-placement="top" aria-label="Alan Nilson" data-bs-original-title="Alan Nilson"
                                data-kt-initialized="1">
                                <img src="assets/images/avatar-user.png" alt="img">
                                <small class="fw-bold text-gray-800 fs-9">${record?.FIRST_NAME} ${record?.LAST_NAME}</small>
                            </div>
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
    const path = getPathWithoutBaseAndQuery();

    if (path.includes("/activities/logs")) {
        //
        console.log('From all activities');

    }
    fetchUserActivities();
});

// Event Listener for "Load More" Button
async function loadMoreData() {
    await pagination.loadMore();
}
