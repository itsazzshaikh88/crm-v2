async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    // Set Loading Animation on button
    const submitBtn = document.getElementById("survey-submit-btn");
    // let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Submitting Survey ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        let url = `${APIUrl}/survey/fill_new_survey`;
        // Fetch API with Bearer token in Authorization header
        const response = await fetch(url, {
            method: 'POST', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`
            },
            body: formData
        });
        // Check if the response is OK (status 200-299)
        if (response.ok) {
            const data = await response.json();
            toasterNotification({ type: 'success', message: "Survey Saved Successfully!" });
            setTimeout(() => window.location = "survey/choose", 1500);
        } else {
            const errorData = await response.json();
            if (errorData.status === 422) {
                showErrors(errorData.validation_errors ?? []);
            } else {
                toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
            }
        }
    } catch (error) {
        toasterNotification({ type: 'error', message: 'Survey failed:' + error });
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = buttonText;
    }
}
const fullPageLoader = document.getElementById("full-page-loader");
async function fetctSurveyDetails(survey_id) {
    try {
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please Login again to make API request." });
            return;
        }

        // Set loader to the screen 
        fullPageLoader?.classList.remove("d-none");
        const url = `${APIUrl}/query/fetch`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                tableName: "XX_CRM_SURVEY",
                columns: "SURVEY_NAME,SURVEY_NUMBER",
                returnType: "row",
                conditions: {
                    SURVEY_ID: survey_id
                }
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch survey data');
        }

        const data = await response.json();
        const placeholder = document.getElementById("PLACEHOLDER_SURVEY_NAME");
        placeholder.innerHTML = `${data?.data?.SURVEY_NAME} (${data?.data?.SURVEY_NUMBER || ''})` || '';


    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
    }
    finally {
        fullPageLoader?.classList.add("d-none");
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const survey_id = document.getElementById("SURVEY_ID")?.value || 0;
    fetctSurveyDetails(survey_id);
});