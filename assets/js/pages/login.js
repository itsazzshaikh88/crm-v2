var notyf = new Notyf({
    duration: 3000,
    position: {
        x: 'center',
        y: 'top'
    }
});
// notyf.error("Invalid Username or Password")

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
            window.location = result?.url;
        } else {
            if (result?.type === 'validation') {
                showErrors(result.errors);
            } else {
                notyf.error(result?.message)
            }
        }


    } catch (error) {
        alert(error);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `Sign In`;
    }


}