"use strict"

let inputEmail = document.querySelector("#input-email")
let inputRememberMe = document.querySelector("#input-remember-me")

let localStorageEmail = window.localStorage.getItem("email")
if(localStorageEmail) {
    inputEmail.value = localStorageEmail
}

let localStorageRememberMe = window.localStorage.getItem("rememberMe")
if(localStorageRememberMe) {
    inputRememberMe.checked = true
}

document.querySelector("form").addEventListener("submit", function() {
    let checkbox = document.querySelector("#input-remember-me")
    if(checkbox.checked) {
        window.localStorage.setItem("email", inputEmail.value)
        window.localStorage.setItem("rememberMe", inputRememberMe.checked)
    } else {
        window.localStorage.removeItem("email")
        window.localStorage.removeItem("rememberMe")
    }
})