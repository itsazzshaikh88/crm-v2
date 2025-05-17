var notyf = new Notyf({
    duration: 3000,
    position: {
        x: 'center',
        y: 'top'
    }
});
// notyf.error("Invalid Username or Password")

const loginContainer = document.getElementById("login-container");
const otpContainer = document.getElementById("otp-container");

async function validate(event) {
    event.preventDefault();
    let submitBtn = document.getElementById("submit-btn");
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class='animate-spin'><i class="bi bi-arrow-repeat"></i></span> Signing In...`;

    hideErrors();
    const form = event.target;
    const formData = new FormData(form);

    try {
        const response = await fetch("api/auth/login", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();
        if (result.status) {
            if (result?.two_step_enabled) {
                loginContainer.classList.add("d-none");
                otpContainer.classList.remove("d-none");
                form.reset();
                // Set user of 
                document.getElementById("USER_ID").value = result?.user || 0
            } else
                window.location = result?.url;
        } else {
            if (result?.type === 'validation') {
                showErrors(result.errors);
            } else {
                notyf.error(result?.message)
            }
        }
    } catch (error) {
        notyf.error(error)
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `Sign In`;
    }
}

async function validateOTP(event) {
    event.preventDefault();
    let submitBtn = document.getElementById("otp-submit-btn");
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class='animate-spin'><i class="bi bi-arrow-repeat"></i></span> Validating OTP ...`;

    hideErrors();
    const form = event.target;
    const formData = new FormData(form);

    try {
        const response = await fetch("api/auth/otp", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();
        if (result.status) {
            window.location = result?.url;
        } else {
            if (result?.type === 'validation') {
                showErrors(result.errors);
            } else {
                form.reset();
                notyf.error(result?.message);
            }
        }
    } catch (error) {
        notyf.error(error)
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `Validate`;
    }
}