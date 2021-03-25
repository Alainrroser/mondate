"use strict"

let emails = document.querySelectorAll(".share-entry")
let selectedEmail = null

for(let email of emails) {
    addEmailEventListener(email)
}

function setSelectedEmail(email) {
    if(selectedEmail) {
        selectedEmail.classList.remove("active")
    }
    selectedEmail = email
    selectedEmail.classList.add("active")
}

function addEmailEventListener(email) {
    email.addEventListener("click", function() {
        setSelectedEmail(email)
    })
}

function addEmailToList(email) {
    let emailButton = document.createElement("button")
    emailButton.classList.add("align-items-center", "d-flex", "flex-row", "pl-1", "list-group-item", "list-group-item-action", "share-entry")
    emailButton.type = "button"
    emailButton.textContent = email

    let input = document.createElement("input")
    input.setAttribute("type", "hidden")
    input.setAttribute("name", "emails[]")
    input.classList.add("shared-appointment-email")
    input.setAttribute("value", email)
    document.querySelector("#dialog-create-appointment form").appendChild(input.cloneNode(true))
    document.querySelector("#dialog-edit-appointment form").appendChild(input.cloneNode(true))
    document.querySelector(".email-list").appendChild(emailButton)
    addEmailEventListener(emailButton)
}

// document.querySelector("#add-email").addEventListener("click", function() {
//     let exists = false
//     for(let email of emails) {
//         if(email.textContent === document.querySelector("#email").value) {
//             exists = true
//         }
//     }
//     if(exists) {
//         document.querySelector("#email").setCustomValidity("This email already exists")
//         document.querySelector("#email").reportValidity()
//     } else {
//         let email = document.querySelector("#email").value
//         addEmailToList(email)
//         document.querySelector("#email").value = ""
//         emails = document.querySelectorAll(".share-entry")
//     }
// })
//
// document.querySelector("#remove-email").addEventListener("click", function() {
//     document.querySelector(".email-list").removeChild(selectedEmail)
//     let inputs = document.getElementsByTagName("input")
//     for(let input of inputs) {
//         if(input.classList.contains("shared-appointment-email")) {
//             input.remove()
//         }
//     }
//     emails = document.querySelectorAll(".share-entry")
// })