"use strict"

let inputEmail = document.querySelector("#input-email")

let localStorageEmail = window.localStorage.getItem("email")
if(localStorageEmail) {
    inputEmail.value = localStorageEmail
}

document.querySelector("form").addEventListener("submit", function() {
    let checkbox = document.querySelector("#input-remember-me")
    if(checkbox.checked) {
        window.localStorage.setItem("email", inputEmail.value)
    } else {
        window.localStorage.removeItem("email")
    }
})