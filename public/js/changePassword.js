document.querySelector('#submit-button').addEventListener("click", function() {
    if(document.querySelector("#oldPassword").value === document.querySelector("#password").value) {
        password.setCustomValidity("The password can't be the same as the old one")
    } else {
        password.setCustomValidity("")
    }
})