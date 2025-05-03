let quill = null; // Define globally so you can manage the instance

// Modal Related Code
var emailActivityModal = new bootstrap.Modal(document.getElementById("emailActivityModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

function openEmailActivityModal() {
    hideErrors();

    // Destroy previous instance if exists
    if (quill) {
        quill = null;
        document.getElementById('email-body-content').innerHTML = '';
        document.querySelector('.ql-toolbar')?.remove();
    }

    // Reinitialize the editor
    quill = new Quill('#email-body-content', {
        theme: 'snow',
        placeholder: 'Write your email here...',
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                ['link', 'blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }]
            ]
        }
    });
    emailActivityModal.show()
}

function closeEmailActivityModal(action = 'close') {

    if (action === 'cancel') {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to cancel this email?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'No, keep writing'
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, close the modal
                resetEmailActivityForm();
                emailActivityModal.hide();
            }
        });
    } else {
        resetEmailActivityForm();
        emailActivityModal.hide();
    }
}

function resetEmailActivityForm() {
    // Reset text inputs
    document.getElementById('recepient_email').value = '';
    document.getElementById('email_subject').value = '';

    // Clear Quill editor
    if (quill) {
        quill.setContents([]); // Clears editor content
    }
    hideErrors();
}

// Function to send a request with Bearer token and display response
async function submitEmailActivityMail(e) {
    e.preventDefault();

    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    const originalButtonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Sending Email ...`;

    // Hide Error
    hideErrors();

    try {
        // Retrieve the auth_token from cookies
        const authToken = getCookie('auth_token');
        if (!authToken) {
            toasterNotification({ type: 'error', message: "Authorization token is missing. Please login again to make API request." });
            return;
        }

        // Extract form values
        const recepientEmail = document.getElementById('recepient_email').value.trim();
        const emailSubject = document.getElementById('email_subject').value.trim();
        const emailBody = quill.root.innerHTML;
        const isEmpty = emailBody.trim() === '<p><br></p>';
        const sanitizedBody = isEmpty ? '' : emailBody;


        // Construct the JSON payload
        const payload = {
            RECEIPIENT: recepientEmail,
            SUBJECT: emailSubject,
            MESSAGE: sanitizedBody
        };

        const url = `${APIUrl}/email/send`;

        // Send the JSON payload with appropriate headers
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        // Handle the response
        if (response.ok) {
            const data = await response.json();
            toasterNotification({ type: 'success', message: "Email Sent Successfully!" });
            closeEmailActivityModal(); // Close the modal
        } else {
            const errorData = await response.json();
            if (errorData.status === 422) {
                showErrors(errorData.validation_errors ?? [], "act-email-lbl");
            } else {
                toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
            }
        }
    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error });
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalButtonText;
    }
}
