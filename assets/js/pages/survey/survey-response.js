async function fetchSurveyFeedback(feedbackId) {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        // Set loader to the screen 
        const url = `${APIUrl}/survey/fetchSurveyFeedback/${feedbackId}`;

        // const response = await fetch(url);
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ feedbackId })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product data');
        }

        const data = await response.json();
        renderSurveyTable(data);
    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        // tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}


// Example data (you can replace this with your actual data sources)
const ratings = [
    { color: 'danger', value: 'strongly-disagree', icon: 'bi bi-emoji-angry' },
    { color: 'warning', value: 'disagree', icon: 'bi bi-emoji-frown' },
    { color: 'secondary', value: 'neutral', icon: 'bi bi-emoji-neutral' },
    { color: 'info', value: 'agree', icon: 'bi bi-emoji-smile' },
    { color: 'success', value: 'strongly-agree', icon: 'bi bi-emoji-heart-eyes' }
];

const questions = [
    {
        title: "A - Products & Technology",
        questions: [
            {
                title: "Product Quality",
                questions: "How would you rate the quality of our products?"
            },
            {
                title: "Packaging And Labelling Quality",
                questions: "How would you rate the suitability of our outer packaging, palletization and labelling?"
            },
            {
                title: "Innovation & Development",
                questions: "How would you rate our ability to innovate and develop products?"
            },
            {
                title: "Technological Capacity",
                questions: "How would you rate our company's use of high-technology in production?"
            }
        ]
    },
    {
        title: "B - Delivery And Logistics",
        questions: [
            {
                title: "On-Time Delivery Performance",
                questions: "How would you rate our on-time delivery performance?"
            },
            {
                title: "Order Confirmation Time",
                questions: ""
            },
            {
                title: "Approach To Urgent Orders",
                questions: "How would you rate our ability to innovate and develop products?"
            },
            {
                title: "Accuracy",
                questions: "How would you rate our compliance with your specified delivery quantities?"
            },
            {
                title: "Means Of Transport",
                questions: "How would you rate our network and the transport alternatives we offer?"
            },
            {
                title: "Logistics Follow Up Services",
                questions: "How would you rate the information flow for transport information and services of our logistics department?"
            }
        ]
    },
    {
        title: "C - Customer Service",
        questions: [
            {
                title: "Communication Quality",
                questions: "How would you rate the quality of communication with our sales representative?"
            },
            {
                title: "Easy Access To Sales Team",
                questions: "How would you rate your access to our sales team? (exp. By phone, mail etc.)"
            },
            {
                title: "Professionalism",
                questions: "How would you rate our Professionalism in dealing with you?"
            },
            {
                title: "After Sales Service",
                questions: "How supportive do you find our customer service following the purchase of a product?"
            },
            {
                title: "Responsiveness To Documentation Requirements",
                questions: "How would you rate our responses to documentation requirements?"
            }
        ]
    },
    {
        title: "D - Technical Service And Development",
        questions: [
            {
                title: "Technical Support",
                questions: "How would you rate the technical competence of our engineers and their response time?"
            },
            {
                title: "Trial Performance",
                questions: "How do you find our lead times and performance quality for trials?"
            },
            {
                title: "Complaint Handling",
                questions: "How do you rate the response time and content of our replies to your complaints?"
            }
        ]
    },
    {
        title: "E - Company Reputation",
        questions: [
            {
                title: "Our Position Within The Industry",
                questions: "How would you rate Zamil Plastic amongst the major Packaging companies?"
            },
            {
                title: "Competitiveness",
                questions: "How do you rate the competitiveness of our products?"
            },
            {
                title: "Approach",
                questions: "How would we rank in terms of building trust as a business partner?"
            },
            {
                title: "Website",
                questions: "How would you rate our website? (in terms of informative, user-friendly, easy-to-access etc.)"
            }
        ]
    }
];



// Function to show rating emojis (adapted from PHP version)
function showRatingEmoji(selected, current) {

    if (current === selected) {
        const selectedIcon = ratings.find(icon => icon.value === selected);
        return selectedIcon ? `<span><i class=" fs-3x ${selectedIcon.icon} text-${selectedIcon.color}"></i></span>` : '';
    }

    return ''; // Return an empty string if no selection
}

// Function to render the survey table
function renderSurveyTable(data) {
    const tbody = document.getElementById('survey-body');
    let content = '';

    const { surveys: { line: { OPTIONS } } } = data;
    const { surveys: { header } } = data;
    const selectedOptions = JSON.parse(OPTIONS || "[]");

    console.log(header);


    // Survey header row
    content += `
        <tr class="bg-white head-row">
            <td class="vertical-middle">
                <h6 class="font-weight-bold mb-0">Customer Evaluation</h6>
                <p class="survey-text mb-0">Please tick the box that best indicates your degree of satisfaction.</p>
            </td>`;

    // Loop through ratings to create rating columns
    ratings.forEach(rating => {
        content += `
            <td class="text-center bg-light-${rating.color} vertical-middle w-10">
            <i class="${rating.icon} text-${rating.color} "></i>
                <p class="mt-2 d-flex justify-content-center fw-bold align-items-center text-${rating.color}">${capitalizeWords(rating?.value, true)?.replace("-", " ")}</p>    
            </td>
        `;
    });

    content += '</tr>';

    // Loop through questions
    let counter = 1;
    let selectedOptionIndex = 0;
    questions.forEach(question => {
        content += `
            <tr class="survey-title-bg">
                <td colspan="6" class="text-white">${question.title}</td>
            </tr>
        `;

        question.questions.forEach((que, innerIndex) => {
            content += `
                <tr>
                    <td>
                        <span class="mb-0 survey-text survey-question-title">
                            ${innerIndex + 1}. ${que.title}
                        </span>
                        <p class="mb-0 survey-question-text">${que.questions}</p>
                    </td>
            `;

            // Loop through rating criteria and show emojis
            ratings.forEach(criteria => {

                content += `
                    <td class="text-center vertical-middle bg-light-${criteria.color} text-icon">
                        ${selectedOptions[selectedOptionIndex]['rating'] === criteria.value ? showRatingEmoji(criteria.value, selectedOptions[selectedOptionIndex]['rating']) : ''} 
                    </td>
                `;
            });

            content += '</tr>';
            counter++;
            selectedOptionIndex++;
        });
    });

    // Further comments section
    content += `
        <tr class="survey-title-bg">
            <td colspan="6" class="text-white">F - Further Comments</td>
        </tr>
        <tr>
            <td colspan="5" class="vertical-middle">
                <h6 class="mb-0 survey-text survey-question-title fw-normal">
                    Would you recommend/have you ever recommended Zamil Plastic to any other company?
                </h6>
            </td>
            <td class="text-center">
                <h6 class='rounded-pill text-white pt-1 pb-1 bg-${header.RECOMMENDATION === 'yes' ? 'success' : 'danger'}'>
                    ${header.RECOMMENDATION === 'yes' ? 'Yes' : 'No'}
                </h6>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <h6 class="mb-0 survey-text survey-question-title fw-normal">
                    Do you have any comments or suggestion that would help us improve our quality of customer service?
                </h6>
                <div class="bg-white pt-2 pb-2 pr-3 pl-3 mt-2">
                    ${header.COMMENTS}
                </div>
            </td>
        </tr>
    `;

    // Inject the generated content into the tbody
    tbody.innerHTML = content;
}

// Function to retrieve the feedback rating for a specific question (based on seq)


// Call the function to render the table with example data

document.addEventListener('DOMContentLoaded', () => {


    const url = new URL(window.location.href);
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const feedbackId = urlSegments[urlSegments.length - 1];
    fetchSurveyFeedback(feedbackId);

});