document.querySelector('#submit-button').addEventListener("click", function() {
    let oldPassword = document.querySelector("#input-old-password").value
    let newPassword = document.querySelector("#input-password").value
    
    password.addEventListener("input", function() {
        password.setCustomValidity("")
    })
    
    if(oldPassword === newPassword) {
        password.setCustomValidity("The password can't be the same as the old one")
    }
})