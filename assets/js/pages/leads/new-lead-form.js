// Set Initial Variables
const newLeadCreatedContent = `<div class="row">
                                    <div class="col-md-12 text-center">
                                        <img src="assets/images/add-lead.png" class="w-250" alt="Add Lead">
                                        <div class="max-w-3xl mx-auto">
                                            <h5 class="fw-normal">Stay on top of your <b>lead's</b> journey by logging every interaction. <span class="text-warning">Add</span> notes, emails, meeting minutes, or important follow-ups right here. This space will grow with your <span class="text-primary">actions.</span></h5>
                                        </div>
                                    </div>
                                </div>`;

const newLeadContent = `<div class="d-flex align-items-center jusity-content-start flex-column max-w-xl mx-auto text-center">
                                    <h1 class="mt-8 fs-2x text-gray-600">Add a New <span class="text-primary">Lead</span> – Build Your Next Big Connection!</h1>

                                    <img src="assets/images/add-leads.png" class="w-300" alt="">
                                    <p>
                                        Every great relationship starts with a single step. Let’s capture your <span class="text-warning">lead’s</span> details and pave the way for success!
                                    </p>
                                </div>`;

const activityContainer = document.getElementById("activity-container");
const activityButtonContainer = document.getElementById(
	"activity-button-container"
);
const leadForm = document.getElementById("leadForm");

// Modal Related Code
var newLeadModal = new bootstrap.Modal(
	document.getElementById("newLeadModal"),
	{
		keyboard: false, // Disable closing on escape key
		backdrop: "static", // Disable closing when clicking outside the modal
	}
);

function openLeadModal(action = "new", leadID = null) {
	hideErrors();
	if (action === "new") {
		// reset form and then open
		leadForm.reset();
		// Set UUID to the UUID input field
		document.getElementById("STATUS").value = "new";
		// Set new lead content
		activityContainer.innerHTML = newLeadContent;
		activityButtonContainer.classList.add("d-none");
	} else {
		// Fetch Lead Details
		fetchLead(leadID);
	}
	newLeadModal.show();
}

function closeLeadModal() {
	document.getElementById("LEAD_ID").value = "";
	activityContainer.innerHTML = "";
	activityButtonContainer.classList.add("d-none");
	leadForm.reset();
	document.getElementById("STATUS").value = "new";
}

async function submitLead(e) {
	e.preventDefault();
	const form = e.target;
	const formData = new FormData(form);

	// Set Loading Animation on button
	const submitBtn = document.getElementById("submit-btn");
	let buttonText = submitBtn.innerHTML;
	submitBtn.disabled = true;
	submitBtn.innerHTML = `Creating Lead ...`;

	// Hide Error
	hideErrors();
	try {
		// Retrieve the auth_token from cookies
		const authToken = getCookie("auth_token");
		if (!authToken) {
			toasterNotification({
				type: "error",
				message:
					"Authorization token is missing. Please Login again to make API request.",
			});
			return;
		}
		const lead_id = document.getElementById("LEAD_ID").value;
		let url = `${APIUrl}/leads/`;
		if (lead_id) {
			url += `update_lead/${lead_id}`;
		} else url += "new_lead";
		// Fetch API with Bearer token in Authorization header
		const response = await fetch(url, {
			method: "POST", // or POST, depending on the API endpoint
			headers: {
				Authorization: `Bearer ${authToken}`,
			},
			body: formData,
		});

		// Check if the response is OK (status 200-299)
		if (response.ok) {
			const data = await response.json();
			if (data?.type == "insert") {
				// Data is inserted
				setLeadCreated(data?.data);
				toasterNotification({
					type: "success",
					message: "Lead Created Successfully",
				});
			} else if (data?.type == "update") {
				// Data is updated
				toasterNotification({
					type: "success",
					message: "Lead Updated Successfully",
				});
			} else {
				toasterNotification({
					type: "error",
					message: "Internal Server Error",
				});
			}

			// if (data?.data?.STATUS === "qualified") {
			// 	 convertLeadToContact(data?.data?.LEAD_ID);
			// }
			fetchLeads();
		} else {
			const errorData = await response.json();
			if (errorData.status === 422) {
				openAccordionsForErrors(errorData.validation_errors ?? []);
				showErrors(errorData.validation_errors ?? []);
			} else {
				toasterNotification({
					type: "error",
					message: errorData.message ?? "Internal Server Error",
				});
			}
		}
	} catch (error) {
		toasterNotification({ type: "error", message: "Request failed:" + error });
	} finally {
		submitBtn.disabled = false;
		submitBtn.innerHTML = buttonText;
	}
}

function setLeadCreated(data) {
	if (!data) return null;
	document.getElementById("LEAD_NUMBER").value = data?.LEAD_NUMBER || "";
	document.getElementById("LEAD_ID").value = data?.LEAD_ID || "";
	activityContainer.innerHTML = newLeadCreatedContent;
	activityButtonContainer.classList.remove("d-none");
}
function setLeadUpdated(data) {
	if (!data) return null;
}

async function fetchLead(leadID) {
	const apiUrl = `${APIUrl}/leads/lead_detail/${leadID}`;
	const authToken = getCookie("auth_token");
	if (!authToken) {
		toasterNotification({
			type: "error",
			message:
				"Authorization token is missing. Please login again to make an API request.",
		});
		return;
	}

	try {
		// Set loading animation
		textInputElementLoadingAnimation("set");

		// set animation to activities fetch
		appendSkeletonContent({
			elementId: "activity-container",
			position: "end",
			skeletonType: "lead-activities",
			count: 5,
		});

		// Fetch product data from the API
		const response = await fetch(apiUrl, {
			method: "POST",
			headers: {
				Authorization: `Bearer ${authToken}`,
			},
		});

		// Parse the JSON response
		const data = await response.json();

		// Check if the API response contains an error
		if (!response.ok || data.status === "error") {
			const errorMessage =
				data.message || `Error: ${response.status} ${response.statusText}`;
			throw new Error(errorMessage);
		}

		// Set loading animation
		textInputElementLoadingAnimation("remove");
		displayLeadData(data.data);
	} catch (error) {
		// Show error notification
		toasterNotification({ type: "error", message: "Error: " + error.message });
	} finally {
	}
}

function displayLeadData(data) {
	if (data) {
		const { lead, activities } = data;

		if (Object.keys(lead).length > 0) {
			populateFormFields(lead);
		}
		showLeadActivities(activities);
	} else {
		toasterNotification({
			type: "error",
			message: "Lead Details and Lead Activity details not found.",
		});
	}
}

function showLeadActivities(activities) {
	activityButtonContainer.classList.remove("d-none");
	if (activities?.data.length == 0) {
		activityContainer.innerHTML = newLeadCreatedContent;
		return;
	}
	// Show Lead Activities
	const { data } = activities;
	let activitiesContent = "";
	if (data && data.length > 0) {
		data.forEach((activity) => {
			const activityType = activity?.ACTIVITY_TYPE?.toLowerCase();
			if (activityType === "call")
				activitiesContent += showCallLogsActivity(activity);
			else if (activityType === "notes")
				activitiesContent += showNotesActivity(activity);
			else if (activityType === "meeting")
				activitiesContent += showMeetingActivity(activity);
		});
	}
	activityContainer.innerHTML = activitiesContent;
}

function showCallLogsActivity(activity) {
	if (!activity) return "";
	return `<div class="position-relative ps-6 pe-3 py-2 bg-gray-50s" id="activity-inline-container-${
		activity?.ACTIVITY_ID
	}">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-warning"></div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0)" class="mb-1 text-hover-primary fw-bold badge bg-warning text-white"> <i class="fa-solid fa-headset text-white"></i> Call Log</a>
                                        <div class="d-flex align-items-center gap-12">
                                            <div class="fs-8 text-warning fw-normal">Created on ${formatAppDate(
																							activity?.ACTIVITY_DATE
																						)}</div>
                                            <div class="d-flex align-items-center gap-4">
                                                <a href="javascript:void(0)" title="Edit Activity" onclick="editCurrentActivityDetail('${activity?.ACTIVITY_TYPE?.toLowerCase()}',${
		activity?.ACTIVITY_ID
	})"><i class="fa-solid fa-file-pen cursor-pointer text-success fs-4"></i></a>
                                                <a href="javascript:void(0)" title="Delete Activity" onclick="deleteCurrentActivity(${
																									activity?.ACTIVITY_ID
																								})"><i class="fa-solid fa-trash cursor-pointer text-danger fs-4"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <p for="" class="mb-0 fw-bold">Call Purpose:<span class="text-gray-800 fw-normal "> ${
																							activity?.CALL_PURPOSE
																						}</span></p>
                                            <p for="" class="mb-0 fw-bold">Call Duration:<span class="text-gray-800 fw-normal "> ${
																							activity?.CALL_DURATION
																						}</span></p>
                                            <p for="" class="mb-0 fw-bold">Follow-up Date:<span class="text-gray-800 fw-normal "> ${formatAppDate(
																							activity?.FOLLOW_UP_DATE
																						)}</span></p>
                                            <p for="" class="mb-0 fw-bold">Note:</p>
                                            <p class="line-clamp-2 text-gray-800 mb-0">${stripHtmlTags(
																							activity?.NOTES
																						)}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed mb-4 mt-2"></div>`;
}
function showNotesActivity(activity) {
	if (!activity) return "";
	return `<div class="position-relative ps-6 pe-3 py-2 bg-gray-50s" id="activity-inline-container-${
		activity?.ACTIVITY_ID
	}">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-primary"></div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0)" class="mb-1 text-hover-primary fw-bold badge bg-primary text-white"><i class="fa-solid fa-notes-medical text-white"></i> Note</a>
                                        <div class="d-flex align-items-center gap-12">
                                            <div class="fs-8 text-primary fw-normal">Created on ${formatAppDate(
																							activity?.ACTIVITY_DATE
																						)}</div>
                                            <div class="d-flex align-items-center gap-4">
                                                <a href="javascript:void(0)" title="Edit Activity" onclick="editCurrentActivityDetail('${activity?.ACTIVITY_TYPE?.toLowerCase()}',${
		activity?.ACTIVITY_ID
	})"><i class="fa-solid fa-file-pen cursor-pointer text-success fs-4"></i></a>
                                                <a href="javascript:void(0)" title="Delete Activity" onclick="deleteCurrentActivity(${
																									activity?.ACTIVITY_ID
																								})"><i class="fa-solid fa-trash cursor-pointer text-danger fs-4"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <p for="" class="mb-0 fw-bold">Note:</p>
                                            <p class="line-clamp-2 text-gray-800 mb-0">${stripHtmlTags(
																							activity?.NOTES
																						)}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed mb-4 mt-2"></div>`;
}
function showMeetingActivity(activity) {
	if (!activity) return "";
	return `<div class="position-relative ps-6 pe-3 py-2 bg-gray-50s" id="activity-inline-container-${
		activity?.ACTIVITY_ID
	}">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-info"></div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0)" class="mb-1 text-hover-primary fw-bold badge bg-info text-white"> <i class="fa-solid fa-chalkboard-user text-white"></i> Meeting Details</a>
                                        <div class="d-flex align-items-center gap-12">
                                            <div class="fs-8 text-info fw-normal">Created on ${formatAppDate(
																							activity?.ACTIVITY_DATE
																						)}</div>
                                            <div class="d-flex align-items-center gap-4">
                                                <a href="javascript:void(0)" title="Edit Activity" onclick="editCurrentActivityDetail('${activity?.ACTIVITY_TYPE?.toLowerCase()}',${
		activity?.ACTIVITY_ID
	})"><i class="fa-solid fa-file-pen cursor-pointer text-success fs-4"></i></a>
                                                <a href="javascript:void(0)" title="Delete Activity" onclick="deleteCurrentActivity(${
																									activity?.ACTIVITY_ID
																								})"><i class="fa-solid fa-trash cursor-pointer text-danger fs-4"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div>
                                            <p for="" class="mb-0 fw-bold">Agenda:<span class="text-gray-800 fw-normal"> ${
																							activity?.AGENDA
																						}</span></p>
                                            <p for="" class="mb-0 fw-bold">Location:<span class="text-gray-800 fw-normal"> ${
																							activity?.LOCATION
																						}</span></p>
                                            <p for="" class="mb-0 fw-bold">Attended By:<span class="text-gray-800 fw-normal "> ${
																							activity?.ATTENDEES
																						}</span></p>
                                            <p for="" class="mb-0 fw-bold">Meeting Outcome:</p>
                                            <p class="line-clamp-2 text-gray-800 mb-0">${stripHtmlTags(
																							activity?.NOTES
																						)}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed mb-4 mt-2"></div>`;
}

function textInputElementLoadingAnimation(type = "set") {
	const formElements = document.querySelectorAll(
		".lead-form-elements .form-control"
	);
	if (type === "set") {
		if (formElements && formElements.length > 0) {
			formElements.forEach((formElement) => {
				formElement.value = "Loading ....";
				formElement.style.fontSize = "10px";
				formElement.disabled = true;
			});
		}
	} else {
		if (formElements && formElements.length > 0) {
			formElements.forEach((formElement) => {
				formElement.value = "";
				formElement.style.fontSize = "1.1rem";
				formElement.disabled = false;
			});
		}
	}
}

function startOver() {
	Swal.fire({
		title: "Are you sure?",
		text: "Starting a new lead will discard unsaved changes. Do you want to continue?",
		icon: "warning",
		showCancelButton: true,
		confirmButtonText: "Yes, start new lead",
		cancelButtonText: "Cancel",
		customClass: {
			popup: "small-swal",
			confirmButton: "swal-confirm-btn",
			cancelButton: "swal-cancel-btn",
		},
	}).then((result) => {
		if (result.isConfirmed) {
			// Call the function to start a new lead
			startNewLead();
		}
		// Do nothing if canceled (box automatically closes)
	});
}

function startNewLead() {
	document.getElementById("LEAD_ID").value = "";
	activityContainer.innerHTML = newLeadContent;
	activityButtonContainer.classList.add("d-none");
	leadForm.reset();
	document.getElementById("STATUS").value = "new";
}

async function convertLeadToContact(leadID) {
	if (!leadID) {
		throw new Error("Invalid Lead ID, Please try Again");
	}

	try {
		const authToken = getCookie("auth_token");
		if (!authToken) {
			toasterNotification({
				type: "error",
				message:
					"Authorization token is missing. Please login again to make an API request.",
			});
			return;
		}

		// Show a non-closable alert box while the activity is being deleted
		Swal.fire({
			title: "Lead Conversion ...",
			text: "Please wait while the lead is being converted to contact.",
			icon: "info",
			showConfirmButton: false,
			allowOutsideClick: false,
			customClass: {
				popup: "small-swal",
			},
		});

		const url = `${APIUrl}/leads/convert/${leadID}`;

		const response = await fetch(url, {
			method: "DELETE", // Change to DELETE for a delete request
			headers: {
				Authorization: `Bearer ${authToken}`,
			},
		});

		const data = await response.json(); // Parse the JSON response

		// Close the loading alert box
		Swal.close();

		if (!response.ok) {
			// If the response is not ok, throw an error with the message from the response
			throw new Error(data.error || "Failed to convert lead to contact");
		}

		if (data.status) {
			// Here, we directly handle the deletion without checking data.status
			toasterNotification({
				type: "success",
				message: "Lead Converted to Contact Successfully",
			});
		} else {
			throw new Error(data.message || "Failed to convert lead to contact");
		}
	} catch (error) {
		toasterNotification({ type: "error", message: "Error: " + error.message });
		Swal.close();
	}
}

// New: Set consultant data and show clear icon
function setSalesPerson(index) {
	const person = fetchedSalesPersons?.[index];
	if (!person) return;

	const name = `${person.FIRST_NAME || ""} ${person.LAST_NAME || ""}`.trim();
	document.getElementById("ASSIGNED_TO").value = name;
	document.getElementById("ASSIGNED_TO_ID").value = person.ID || "";

	salesPersonListModal?.hide?.();
	toggleClearIcon("ASSIGNED_TO", "clearSalesPerson");
}

function clearSalesPersonDetails() {
	document.getElementById("ASSIGNED_TO").value = "";
	document.getElementById("ASSIGNED_TO_ID").value = "";
	toggleClearIcon("ASSIGNED_TO", "clearSalesPerson");
}

// Utility to toggle clear icon visibility based on input value
function toggleClearIcon(inputId, clearIconId) {
	const inputVal = document.getElementById(inputId).value;
	const icon = document.getElementById(clearIconId);
	if (inputVal && inputVal.trim() !== "") {
		icon.style.display = "inline";
	} else {
		icon.style.display = "none";
	}
}

document.addEventListener("DOMContentLoaded", function () {
	const params = new URLSearchParams(window.location.search);

	const source = params.get("u_source");
	const mode = params.get("mode");
	const leadId = params.get("lead-id");

	if (source === "email" && mode === "lead-assigned" && leadId) {
		openLeadModal("edit", leadId);
	}
});

const leadFields = [
	"LEAD_ID",
	"LEAD_NUMBER",
	"ORG_ID",
	"LEAD_SOURCE",
	"LEAD_EVENT",
	"STATUS",
	"ASSIGNED_TO",
	"ASSIGNED_TO_ID",
];

const contactFields = [
	"FIRST_NAME",
	"LAST_NAME",
	"JOB_TITLE",
	"COMPANY_NAME",
	"EMAIL",
	"PHONE",
];

const dealFields = [
	"DEAL_NUMBER",
	"DEAL_STATUS",
	"DEAL_STAGE",
	"DEAL_TYPE",
	"DEAL_VALUE",
	"DEAL_PRIORITY",
	"EXPECTED_CLOSE_DATE",
	"ACTUAL_CLOSE_DATE",
	"PROBABILITY",
	"DEAL_SOURCE",
	"DEAL_DESCRIPTION",
	"CLOSE_REASON",
];

function openAccordionsForErrors(errors) {
	const errorKeys = Object.keys(errors);

	const hasLeadError = errorKeys.some((key) => leadFields.includes(key));
	const hasContactError = errorKeys.some((key) => contactFields.includes(key));
	const hasDealError = errorKeys.some((key) => dealFields.includes(key));

	if (hasLeadError) toggleAccordion("leadDetails", "show");
	if (hasContactError) toggleAccordion("contactDetails", "show");
	if (hasDealError) toggleAccordion("dealDetails", "show");
}

function toggleAccordion(id) {
	const el = document.getElementById(id);
	if (!el) return;

	// Prevent re-initializing or closing others
	if (!el.classList.contains("show")) {
		new bootstrap.Collapse(el, {
			toggle: true,
		});
	}
}
