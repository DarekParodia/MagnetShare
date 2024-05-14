var form, loginInput, passwordInput, submitButton, warnbox;

document.addEventListener("DOMContentLoaded", function () {
    form = document.getElementById("login-form");
    loginInput = document.getElementById("login");
    passwordInput = document.getElementById("password");
    submitButton = document.getElementById("submit-button");
    warnbox = document.getElementById("warnbox");

    console.log("Login form loaded");
    console.log(form);

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        validateForm();
    });
});

function validateForm() {
    let login = loginInput.value;
    let password = passwordInput.value;

    warnbox.innerHTML = "";

    if (login === "" || password === "") {
        warnbox.innerHTML = "<p class='error'>All fields must be filled</p>";
    } else {
        form.submit();
    }

    console.log("Form validated");
}
