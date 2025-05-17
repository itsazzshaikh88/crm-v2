async function surveyList() {
    try {
        const authToken = getCookie("auth_token");
        if (!authToken) {
            toasterNotification({
                type: "error",
                message:
                    "Authorization token is missing. Please Login again to make API request.",
            });
            return;
        }

        appendSkeletonContent({ elementId: "survey_list", position: "end", skeletonType: "choose-survey", count: 10 });
        const url = `${APIUrl}/survey/survey_list`;
        // const filters = filterCriterias([]);
        const requestBody = {
            filter: {}, // Add valid filters if needed
            pagination: { page: 1, limit: 10 }, // Example pagination details
        };

        const response = await fetch(url, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${authToken}`,
                "Content-Type": "application/json",
            },
            body: JSON.stringify(requestBody),
        });

        if (!response.ok) {
            const errorResponse = await response.json();
            console.error("Server error:", errorResponse);
            throw new Error("Failed to fetch product data");
        }

        const data = await response.json();

        showSurveyList(data.surveys || []);
    } catch (error) {
        toasterNotification({
            type: "error",
            message: "Request failed: " + error.message,
        });
        // tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }
}

function showSurveyList(surveys) {
    const surveyList = document.getElementById("survey_list");
    let content = "";

    if (surveys?.length > 0) {
        // Generate cards for each survey
        surveys.forEach((survey) => {

            content += `<div class="col-md-3">
                    <div class="card">
                      <div class="card-body">
                        <h3 class="">${survey?.SURVEY_NAME}</h3>
                        <h6 class="fw-normal text-muted fs-7">Duration: ${formatAppDate(survey?.START_DATE)} - ${formatAppDate(survey?.END_DATE)} </h6>
                        <div class="separator separator-dashed my-5"></div>
                        <p class="text-gray-600 line-clamp-3">${survey?.SURVEY_DESC}</p>
                        <a href="survey/fill/${survey.SURVEY_ID}" class="btn border border-primary text-primary"><i class="fa-solid fa-arrow-right text-primary"></i> Fill Survey</a>
                      </div>
                    </div>
                  </div>`
        });

        // Insert the generated content into the survey_list div
        surveyList.innerHTML = content;
    } else {
        // Display message if no surveys are available
        surveyList.innerHTML = `
                        <div class="col-md-12">
                            <div class="card ">
                                <div class="card-body pb-0">
                                    <div class="row">
                                        <div class="row justify-content-center">
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="text-right">
                                                            <img src="assets/images/no-survey.png" class="img-fluid"
                                                                alt="" srcset="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="text-center">
                                                            <h1 class="text-danger" style="font-weight:bolder;">No
                                                                Surveys Available</h1>


                                                            <h4 class="sys-main-text text-left mt-19 mb-10">All Surveys
                                                                Are Currently Closed</h4>

                                                            <ul class="text-left p-0" style="list-style-type: none; ">
                                                                <li>
                                                                    <h6 class="font-weight-light">
                                                                        It looks like there are no open surveys at the
                                                                        moment. We appreciate your interest in sharing
                                                                        your feedback and helping us improve.
                                                                    </h6>
                                                                </li>
                                                                <li class="mt-3 ">
                                                                    <h6 class="font-weight-light">
                                                                        Please check back later, as new surveys will be
                                                                        available soon. Your insights are invaluable to
                                                                        us, and we can't wait to hear from you when the
                                                                        next opportunity arises!
                                                                    </h6>
                                                                </li>
                                                                <li class="mt-3">
                                                                    <h6 class="font-weight-light">
                                                                        In the meantime, feel free to explore other
                                                                        areas of our platform or contact us if you have
                                                                        any questions.
                                                                    </h6>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
    }
}

// const paginate = new Pagination('current-page', 'total-pages', 'page-of-pages', 'range-of-records');
// paginate.pageLimit = 10; // Set your page limit here

// Function to handle pagination button clicks
// function handlePagination(action) {
//     paginate.paginate(action); // Update current page based on the action
//     fetctSurvey(); // Fetch products for the updated current page
// }
document.addEventListener("DOMContentLoaded", () => {
    // Fetch initial product data
    surveyList();
});
