const fullPageLoader = document.getElementById("full-page-loader");

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


async function fetchNews(newsID) {
    const apiUrl = `${APIUrl}/news/detail/${newsID}`;
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
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        if (data?.data) {
            renderNews(data?.data);
        }


    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}

function renderNews(news) {
    const container = document.getElementById("news-container");
    const attchmentsContainer = document.getElementById("attachments-container");
    if (container) {
        container.innerHTML = '';
        attchmentsContainer.innerHTML = '';
        if (news) {

            let attachments = [];
            try {
                attachments = news?.ATTACHMENTS ? JSON.parse(news.ATTACHMENTS) : [];
            } catch (error) {
                attachments = [];
            }
            let filePath = `${baseUrl}uploads/news/`
            // Generate the attachment HTML
            const attachmentHTML = attachments.length > 0
                ? `<ul class="list-style-none">
                        ${attachments.map(file => `
                        <li class="mb-2">
                            <a href="${filePath}${file}" target="_blank" class="text-decoration-underline">${file}</a>
                        </li>
                        `).join('')}
                    </ul>`
                : `<p class="text-muted">No attachments found</p>`;

            attchmentsContainer.innerHTML = attachmentHTML;

            container.innerHTML = `<div class="row justify-content-center">
                                        <div class="col-md-12 border-start border-end p-0">
                                            <div class="alert-secondary py-8 px-5 border-start border-5 border-secondary">
                                                <!-- News Heading and details  -->
                                                <h1 class="mb-5 text-primary">${news?.TITLE || ''}</h1>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <p class=""><span class="fw-bold">Priority</span>: <span
                                                            class="badge bg-white border text-${priorityColorCode[news?.PRIORITY]}">${capitalizeWords(news?.PRIORITY)
                || ''}</span></p>
                                                    <p class="badge bg-white border text-${typeColorCode[news?.TYPE]}">${capitalizeWords(news?.TYPE) || ''}
                                                    </p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <p class=""><span class="fw-bold">Date</span>: ${formatAppDate(news?.CREATED_AT)}</p>
                                                    ${news?.AUDIENCE
                    ? `<p class=""><span class="fw-bold">Audience</span>:
                                                        <span class="badge bg-white border text-black">${news.AUDIENCE}</span>
                                                    </p>`
                    : ''
                }
                                                </div>
                                            </div>

                                            <div class="p-5">
                                                <!-- News Content  -->
                                                <div>${news?.DESCRIPTION}</div>
                                            </div>
                                        </div>
                                    </div>`;
        } else {
            // no news details found 
        }
    }
}



document.addEventListener('DOMContentLoaded', function () {
    function getUrlSegments() {
        const path = window.location.pathname;
        const basePathSegments = window.location.pathname.split('/').slice(0, 2); // Detect possible base folder
        const basePath = (window.location.hostname === 'localhost') ? basePathSegments.join('/') + '/' : '/';
        const cleanPath = path.replace(basePath, ''); // Remove project name if local
        return cleanPath.split('/').filter(segment => segment); // Get clean segments
    }

    const segments = getUrlSegments();

    if (segments.length >= 3) { // Check if segment3 exists
        fetchNews(segments[2]); // Call function with segment3
    }
});