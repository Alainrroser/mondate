"use strict"

let password = document.querySelector("#input-password")
let confirmPassword = document.querySelector("#input-confirm-password")

const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-.]).{8,50}$/

password.addEventListener("input", function() {
    password.setCustomValidity("")
})

confirmPassword.addEventListener("input", function() {
    confirmPassword.setCustomValidity("")
})

document.querySelector('#submit-button').addEventListener("click", function() {
    if(!password.value.match(passwordPattern)) {
        password.setCustomValidity("Requirements: 8-20 chars, 1 uppercase, 1 lowercase, 1 number, 1 symbol")
    }
    
    if(password.value.localeCompare(confirmPassword.value)) {
        confirmPassword.setCustomValidity("The passwords have to match")
    }
})