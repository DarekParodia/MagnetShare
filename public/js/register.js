var form, loginInput, emailInput, passwordInput, passwordConfirmInput, submitButton, warnbox;

document.addEventListener("DOMContentLoaded", function () {
    form = document.getElementById("register-form");
    loginInput = document.getElementById("login");
    emailInput = document.getElementById("email");
    passwordInput = document.getElementById("password");
    passwordConfirmInput = document.getElementById("password2");
    submitButton = document.getElementById("submit-button");
    warnbox = document.getElementById("warnbox");

    console.log("Register form loaded");
    console.log(form);

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        validateForm();
    });
});

function validateForm() {
    let login = loginInput.value;
    let email = emailInput.value;
    let password = passwordInput.value;
    let passwordConfirm = passwordConfirmInput.value;

    warnbox.innerHTML = "";

    if (login === "" || email === "" || password === "" || passwordConfirm === "") {
        warnbox.innerHTML = "<p class='error'>All fields must be filled</p>";
    } else if (!validateLogin(login)) {
        warnbox.innerHTML = "<p class='error'>Login must contain only letters and digits, and be 3 to 20 characters long</p>";
    } else if (!validatePassword(password, passwordConfirm)) {
        warnbox.innerHTML = "<p class='error'>Password must contain at least one lowercase letter, one uppercase letter, one digit, and be 8 characters long</p>";
    } else {
        form.submit();
    }

    console.log("Form validated");
}

function validateLogin(login) {
    if (login.length < 3) {
        return false;
    } else if (login.match(/^[a-zA-Z0-9]{3,20}$/) === null) {
        return false;
    } else {
        return true;
    }
}

function validatePassword(password, passwordConfirm) {
    if (password.length < 8) {
        return false;
    } else if (password !== passwordConfirm) {
        return false;
    } else if (password.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/) === null) {
        return false;
    } else {
        return true;
    }
}
