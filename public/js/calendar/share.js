"use strict"

let selectedEmail = null

for(let email of document.querySelectorAll(".share-entry")) {
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

let shareDialogBodies = document.querySelectorAll("#dialog-share .card-body");

for(let shareDialogBody of shareDialogBodies) {
    shareDialogBody.querySelector(".btn-add-email").addEventListener("click", function() {
        addEmail(shareDialogBody)
    })

    shareDialogBody.querySelector(".btn-remove-email").addEventListener("click", function() {
        if(selectedEmail) {
            removeEmail()
        }
    })
}

function addEmail(shareDialogBody) {
    let emailElement = shareDialogBody.querySelector(".input-email")

    let inputValid = validateEmailInput(shareDialogBody, emailElement)

    if(inputValid) {
        addEmailToList(emailElement.value)
        shareDialogBody.querySelector(".input-email").value = ""
    }
}

function validateEmailInput(shareDialogBody, emailElement) {
    let emailList = shareDialogBody.querySelector(".email-list")
    emailElement.setCustomValidity("")

    if(!emailElement.value) {
        emailElement.setCustomValidity("The email cannot be empty")
        emailElement.reportValidity()
        return false
    }

    for(let email of emailList.children) {
        let emailName = email.textContent

        if(emailName === emailElement.value) {
            emailElement.setCustomValidity("A tag with this name or color already exists")
            emailElement.reportValidity()
            return false
        }
    }

    return true
}

function addEmailToList(email) {
    for(let shareDialogBody of shareDialogBodies) {
        let emailButton = document.createElement("button")
        emailButton.classList.add("align-items-center", "d-flex", "flex-row", "pl-1", "list-group-item", "list-group-item-action", "share-entry")
        emailButton.type = "button"
        emailButton.textContent = email

        shareDialogBody.querySelector(".email-list").appendChild(emailButton)
        addEmailEventListener(emailButton)
    }

    let forms = document.querySelectorAll("#dialog-create-appointment form, #dialog-edit-appointment form")
    for(let form of forms) {
        let input = document.createElement("input")
        input.setAttribute("type", "hidden")
        input.setAttribute("name", "emails[]")
        input.classList.add("shared-appointment-email")
        input.setAttribute("value", email)
        form.appendChild(input)
    }
}

function removeEmail() {
    let email = selectedEmail.textContent;

    for(let shareEntry of document.querySelectorAll(".share-entry")) {
        if(shareEntry.textContent === email) {
            shareEntry.remove()
        }
    }

    for(let input of document.querySelectorAll(".shared-appointment-email")) {
        if(input.value === email) {
            input.remove()
        }
    }

    selectedEmail = null
}