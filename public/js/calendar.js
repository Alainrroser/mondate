"use strict"

let appointmentButtons = document.querySelectorAll(".appointment")

let appointmentSelected = false;
let selectedAppointmentID = 0;

function updateEditDeleteButtonStates() {
    document.querySelector("#btn-edit-appointment").disabled = !appointmentSelected
    document.querySelector("#btn-delete-appointment").disabled = !appointmentSelected
}

document.addEventListener("click", function() {
    updateEditDeleteButtonStates()
})

for(let appointmentButton of appointmentButtons) {
    appointmentButton.addEventListener("click", function () {
        let id
        for(let cssClass of appointmentButton.classList) {
            if (cssClass.startsWith("appointment-id-")) {
                id = cssClass.split("-")[2]
            }
        }
        
        if(appointmentSelected && selectedAppointmentID === id) {
            showEditAppointmentDialog()
        } else {
            if(appointmentSelected) {
                for(let otherAppointmentButton of appointmentButtons) {
                    if(otherAppointmentButton.classList.contains("appointment-id-" + selectedAppointmentID)) {
                        otherAppointmentButton.classList.toggle("appointment-selected")
                    }
                }
            } else {
                appointmentSelected = true
            }
            
            selectedAppointmentID = id
            document.querySelector("#delete-appointment-id").setAttribute("value", selectedAppointmentID)
            document.querySelector("#edit-appointment-id").setAttribute("value", selectedAppointmentID)
        }
        let relatedButtons = document.querySelectorAll(".appointment-id-" + id)
        for(let relatedButton of relatedButtons) {
            relatedButton.classList.toggle("appointment-selected")
        }
    })
}

let toggleCreateButtons = document.querySelectorAll(".toggleCreate")
let toggleTagButtons = document.querySelectorAll(".toggleTag")
let toggleShareButtons = document.querySelectorAll(".toggleShare")

for (let toggleCreateButton of toggleCreateButtons) {
    toggleCreateButton.addEventListener("click", function () {
        document.getElementById("createAppointment").classList.toggle("invisible")
    })
}

for (let toggleTagButton of toggleTagButtons) {
    toggleTagButton.addEventListener("click", function (event) {
        document.getElementById("tags").classList.toggle("invisible")
        event.preventDefault()
    })
}

for (let toggleShareButton of toggleShareButtons) {
    toggleShareButton.addEventListener("click", function (event) {
        document.getElementById("share").classList.toggle("invisible")
        event.preventDefault()
    })
}

let refreshButtons = document.querySelectorAll(".refresh")
for (let refreshButton of refreshButtons) {
    refreshButton.addEventListener("click", function () {
        location.reload()
    })
}

function showEditAppointmentDialog() {
    let request = new XMLHttpRequest()
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let object = JSON.parse(this.responseText)
            document.querySelector(".input-appointment-name").setAttribute("value", object.name)
            document.querySelector(".input-appointment-date").setAttribute("value", object.date)
            document.querySelector(".input-appointment-start").setAttribute("value", object.start)
            document.querySelector(".input-appointment-end").setAttribute("value", object.end)
            document.querySelector(".input-appointment-description").value = object.description

            let appointmentTagsDivs = document.querySelectorAll(".appointment-tags")

            for (let appointmentTags of appointmentTagsDivs) {
                for (let tagDiv of appointmentTags.getElementsByTagName("div")) {
                    let tagCheckbox = tagDiv.getElementsByTagName("input")[0]
                    tagCheckbox.checked = false

                    for (let tag of object.tags) {
                        if (tagDiv.classList.contains("tag-" + tag)) {
                            tagCheckbox.checked = true
                        }
                    }
                }
            }
            for (let email of object.emails) {
                addEmailToList(email)
            }
        }
    };
    request.open("GET", "/appointment/get?id=" + selectedAppointmentID, true)
    request.send()

    document.getElementById("editAppointment").classList.remove("invisible")
}

document.querySelector("#btn-edit-appointment").addEventListener("click", function () {
    showEditAppointmentDialog()
})

let tags = document.querySelectorAll(".tag")
let selectedTag = null

for (let tag of tags) {
    addTagEventListener(tag)
}

function setSelectedTag(tag) {
    if (selectedTag) {
        selectedTag.classList.remove("active")
    }
    selectedTag = tag
    selectedTag.classList.add("active")
}

function addTagEventListener(tag) {
    tag.addEventListener("click", function () {
        setSelectedTag(tag)
    })
}

document.querySelector("#btn-add-tag").addEventListener("click", function () {
    let data = new FormData()
    data.append("name", document.querySelector(".tag-name").value)
    data.append("color", document.querySelector(".tag-color").value)
    
    let request = new XMLHttpRequest()
    request.open("POST", "/tag/create", true)
    request.onload = function () {
        if (this.readyState === 4 && this.status === 200) {
            let object = JSON.parse(this.responseText)
            
            let tagButton = document.createElement("button")
            tagButton.id = "tag-" + object.id
            tagButton.classList.add("align-items-center", "d-flex", "flex-row", "pl-1", "list-group-item", "list-group-item-action")
            tagButton.innerHTML =
                "<span style=\"width:1rem;height:1rem;background-color:" + object.color + "\" class=\"mr-2\"></span>" +
                "<span class=\"align-middle\">" + object.name + "</span>";
            document.querySelector(".tag-list").appendChild(tagButton)
            addTagEventListener(tagButton)
            
            for (let appointmentTag of document.querySelectorAll(".appointment-tags")) {
                appointmentTag.innerHTML +=
                    "<div class=\"tag-" + object.id + "align-items-center d-flex flex-row pl-1\">" +
                    "<input type=\"checkbox\" name=\"tags[" + object.id + "]\" class=\"align-middle mr-1\">" +
                    "<span style=\"width:1rem;height:1rem;background-color:" + object.color + "\" class=\"mr-2\"></span>" +
                    "<span class=\"align-middle\">" + object.name + "</span>" +
                    "</div>"
            }
            document.querySelector("#tag-name").value = ""
            document.querySelector("#tag-color").value = ""
        }
    }
    request.send(data)
})

document.querySelector("#btn-edit-tag").addEventListener("click", function () {
    let selectedTagId = selectedTag.id.split("-")[1]
    let request = new XMLHttpRequest()
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let object = JSON.parse(this.responseText)
            document.querySelector(".tag-name").value = object.name
            document.querySelector(".tag-color").value = "#" + object.color
        }
    };
    request.open("GET", "/tag/get?id=" + selectedTagId, false)
    request.send()
})

document.querySelector("#btn-save-tag").addEventListener("click", function () {
    let selectedTagId = selectedTag.id.split("-")[1]
    let data = new FormData
    data.append("id", selectedTagId)
    data.append("name", document.querySelector(".tag-name").value)
    data.append("color", document.querySelector(".tag-color").value)
    
    let sendData = new XMLHttpRequest()
    sendData.open("POST", "/tag/edit", false)
    sendData.send(data)
    
    let request = new XMLHttpRequest()
    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let object = JSON.parse(this.responseText)
            selectedTag.innerHTML =
                "<span style=\"width:1rem;height:1rem;background-color: #" + object.color + "\" class=\"mr-2\"></span>" +
                "<span class=\"align-middle\">" + object.name + "</span>";
        }
    }
    request.open("GET", "/tag/get?id=" + selectedTagId, false)
    request.send()
})

document.querySelector("#btn-remove-tag").addEventListener("click", function () {
    let data = new FormData()
    let selectedTagId = selectedTag.id.split("-")[1]
    data.append("id", selectedTagId)
    
    let request = new XMLHttpRequest()
    request.open("POST", "/tag/delete", true)
    request.send(data)
    
    let appointmentTags = document.querySelectorAll(".appointment-tag")
    for (let tag of appointmentTags) {
        if (tag.classList.contains("tag-" + selectedTagId)) {
            tag.remove()
        }
    }
    
    document.querySelector(".tag-list").removeChild(selectedTag)
    selectedTag = tags[0]
    selectedTag.classList.add("active")
})

let emails = document.querySelectorAll(".share-entry")
let selectedEmail = null

for (let email of emails) {
    addEmailEventListener(email)
}

function setSelectedEmail(email) {
    if (selectedEmail) {
        selectedEmail.classList.remove("active")
    }
    selectedEmail = email
    selectedEmail.classList.add("active")
}

function addEmailEventListener(email) {
    email.addEventListener("click", function () {
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
    document.querySelector("#createAppointment form").appendChild(input.cloneNode(true))
    document.querySelector("#editAppointment form").appendChild(input.cloneNode(true))
    document.querySelector(".email-list").appendChild(emailButton)
    addEmailEventListener(emailButton)
}

document.querySelector("#add-email").addEventListener("click", function () {
    let email = document.querySelector("#email").value
    addEmailToList(email)
    document.querySelector("#email").value = ""
})

document.querySelector("#remove-email").addEventListener("click", function () {
    document.querySelector(".email-list").removeChild(selectedEmail)
    let inputs = document.getElementsByTagName("input")
    for (let input of inputs) {
        if (input.classList.contains("shared-appointment-email")) {
            input.remove()
        }
    }
})

document.querySelector("#edit-email").addEventListener("click", function () {
    document.querySelector("#email").value = selectedEmail.textContent
})

document.querySelector("#save-changes").addEventListener("click", function () {
    selectedEmail.textContent = document.querySelector("#email").value
})

let btnCloseErrorDialog = document.querySelector("#btn-close-error-dialog")
if(btnCloseErrorDialog) {
    btnCloseErrorDialog.addEventListener("click", function () {
        document.querySelector("#dialog-error").classList.toggle("invisible")
    })
}

function setCookie(name, value) {
    let date = new Date()
    date.setTime(date.getTime() + 30 * 60 * 1000)
    let expires = "expires=" + date.toUTCString()
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

function getCookie(name) {
    let nameWithEquals = name + "="
    let decodedCookie = decodeURIComponent(document.cookie)
    let cookies = decodedCookie.split(";")

    for(let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim()
        if(cookie.indexOf(nameWithEquals) === 0) {
            return cookie.substring(nameWithEquals.length, cookie.length)
        }
    }

    return ""
}

let tableBody = document.querySelector("tbody")
tableBody.addEventListener("scroll", function() {
    setCookie("scroll", tableBody.scrollTop)
})

let scrollCookie = getCookie("scroll")
if(scrollCookie === "") {
    const CELL_HEIGHT = 50

    tableBody.scrollTop = 8 * CELL_HEIGHT
} else {
    tableBody.scrollTop = scrollCookie
}