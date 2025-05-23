let newsFilters = {};
// newsListSkeleton("news-list", 10, 11);
function renderNoResponseCode(option, isAdmin = false) {
    let noCotent = `<tr>
                        <td colspan=${option.colspan} class="text-center text-danger">
                            No data available
                        </td>
                    </tr> `;

    return noCotent;
}

// get table id to store
const tableId = "news-list";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);


const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

async function fetchNews(userSearchTerm = null) {

    if (!userSearchTerm) {
        userSearchTerm = document.getElementById("searchInputElement").value.trim() || null
    }
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API news." });
            return;
        }

        commonListingSkeleton(tableId, paginate.pageLimit || 0, numberOfHeaders);

        const url = `${APIUrl}/news/list`;
        const rawFilters = filterCriterias(['FILTER_STATUS', 'FILTER_TYPE']);

        const filters = Object.fromEntries(
            Object.entries(rawFilters)
                .filter(([_, value]) => value != null && value !== '') // skip empty/null/undefined
                .map(([key, value]) => [key.replace(/^FILTER_/, ''), value])
        );

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: filters,
                search: userSearchTerm
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch news data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        showNews(data.news || [], tbody);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'news failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
        console.error(error);
    }
}

const typeColorCode = {
    news: "primary",        // Blue
    announcement: "info",   // Light Blue
};

const priorityColorCode = {
    low: "success",         // Green
    medium: "warning",      // Yellow
    high: "danger",         // Red
    urgent: "dark",         // Black/Dark
};

const visibilityColorCode = {
    public: "primary",      // Blue
    internal: "black",  // Gray
};

const statusColorCode = {
    draft: "primary",     // Gray
    published: "success",   // Green
    disabled: "danger",     // Red
};

function showNews(news, tbody) {
    let content = '';
    let default_img = "assets/images/default-image.png";
    if (news?.length > 0) {
        // show news
        let counter = 0;
        news.forEach(news => {
            let desc = stripHtmlTags(news?.DESCRIPTION || '');
            let img = parseJsonString(news.PRODUCT_IMAGES || '', 0);
            if (img != null)
                img = `${PRODUCT_IMAGES_URL}${img}`;


            content += `<tr data-news-id="${news.ID}" class="text-gray-800 fs-7">
                                <td class="text-center">${++counter}</td>
                                <td class="">${news?.TITLE || ''}</td>
                                <td class=""><span class="badge bg-light text-${typeColorCode[news?.TYPE]}">${capitalizeWords(news?.TYPE) || ''}</span></td>
                                <td class="">${news?.CATEGORY || ''}</td>
                                <td class=""><span class="badge bg-light text-${priorityColorCode[news?.PRIORITY]}">${capitalizeWords(news?.PRIORITY) || ''}</span></td>
                                <td class="">${news?.AUDIENCE || ''}</td>
                                <td class=""><span class="badge bg-light text-${statusColorCode[news?.STATUS]}">${capitalizeWords(news?.STATUS) || ''}</span></td>
                                
                                <td class="text-end">
                                    <div class="dropdown">
                                        <!-- Button to toggle the dropdown, without the caret (down arrow) -->
                                        <button class="btn btn-link p-0" type="button" id="optionsMenu" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fs-8 me-2 fa-solid fa-ellipsis-vertical"></i> <!-- Three vertical dots -->
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end px-0 py-2 shadow-lg border" aria-labelledby="optionsMenu">
                                            <li class="mb-1 fs-8">
                                                <a class="dropdown-item" href="news/view/${news.ID}/${news.UUID}" title="View News">
                                                    <i class="fs-8 me-2 fa-solid fa-file-lines text-success"></i> View News
                                                </a>
                                            </li>
                                            <li class="mb-1 fs-8">
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    onclick="openNewNewsModal('edit', ${news.ID})" title="Edit News">
                                                    <i class="fs-8 me-2 fa-regular fa-pen-to-square text-gray-700"></i> Edit News
                                                </a>
                                            </li>
                                            <li class="mb-1 fs-8">
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="deleteNews(${news.ID})"
                                                    title="Delete News">
                                                    <i class="fs-8 me-2 fa-solid fa-trash-can text-danger"></i> Delete news
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>`;
        });
        tbody.innerHTML = content;
    } else {
        // no data available
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders })
    }
}

// Declare the pagination instance globally
const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');

// Function to handle pagination button clicks
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchNews(); // Fetch news for the updated current page
}
document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial news data
    fetchNews();
});

function filternews() {
    paginate.currentPage = 1;
    fetchNews();
}



async function deleteNews(newsID) {
    if (!newsID) {
        throw new Error("Invalid News ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete news? This action cannot be undone.",
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
                message: "Authorization token is missing. Please login again to make an API news."
            });
            return;
        }

        // Show a non-closable alert box while the activity is being deleted
        Swal.fire({
            title: "Deleting News...",
            text: "Please wait while the News is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${APIUrl}/news/delete/${newsID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete news
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        // Close the loading alert box
        Swal.close();

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete News details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: 'News Deleted Successfully' });
            // Logic to remove the current row from the table
            fetchNews();
        } else {
            throw new Error(data.message || 'Failed to delete News details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'news failed: ' + error.message });
        Swal.close();
    }
}


function searchNewsListData(element) {
    const userSearchTerm = element.value.trim();

    fetchNews(userSearchTerm);
}


// Create a debounced version of the function
const debouncedSearchNewsListData = debounce(searchNewsListData, 300); // 300ms delay


/// export data
function exportNewsData(type = null) {

    const search = document.getElementById("searchInputElement").value.trim();



    // Encode filters and search as query parameters
    const queryParams = new URLSearchParams({
        search: search
    });

    // Trigger download
    window.location.href = `${APIUrl}/news/export_csv?${queryParams.toString()}`;
}

function filterNewsReport() {
    paginate.currentPage = 1;
    fetchNews(); // Fetch Request for the updated current page
}