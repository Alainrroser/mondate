let password = document.getElementById("password");
let confirm_password = document.getElementById("confirm_password");

const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-.]).{8,20}$/g

function confirmPassword() {
    if (!password.value.match(passwordPattern)) {
        password.setCustomValidity("Has to be 8-20 chars long & has to contain at least: 1 upper- & 1 lowercase letter, 1 number & 1 symbol");
        return false;
    }
    
    if (password.value.localeCompare(confirm_password.value)) {
        confirm_password.setCustomValidity("The passwords have to match");
        return false;
    } else {
        confirm_password.setCustomValidity("");
        return true;
    }
}

