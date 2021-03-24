"use strict"

let appointmentButtons = document.querySelectorAll(".appointment")

let appointmentSelected = false
let selectedAppointmentID = 0

function updateEditDeleteButtonStates() {
    for(let btn of document.querySelectorAll(".btn-edit-appointment, .btn-delete-appointment")) {
        btn.disabled = !appointmentSelected
    }
}

document.addEventListener("click", function() {
    updateEditDeleteButtonStates()
})

for(let appointmentButton of appointmentButtons) {
    appointmentButton.addEventListener("click", function() {
        let id
        for(let cssClass of appointmentButton.classList) {
            if(cssClass.startsWith("appointment-id-")) {
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
            for(let input of document.querySelectorAll(".delete-appointment-id")) {
                input.setAttribute("value", selectedAppointmentID)
            }
            for(let input of document.querySelectorAll(".edit-appointment-id")) {
                input.setAttribute("value", selectedAppointmentID)
            }
        }
        let relatedButtons = document.querySelectorAll(".appointment-id-" + id)
        for(let relatedButton of relatedButtons) {
            relatedButton.classList.toggle("appointment-selected")
        }
    })
}

for(let toggleCreateButton of document.querySelectorAll(".toggleCreate")) {
    toggleCreateButton.addEventListener("click", function() {
        document.getElementById("dialog-create-appointment").classList.toggle("invisible")

        let date = new Date()
        let hour = date.getHours()
        let minute = date.getMinutes()
        minute += (minute < 10 ? "0" : "")
        hour += (hour < 10 ? "0" : "")
        document.querySelector(".input-appointment-start").value = hour + ":" + minute
        date.setHours(date.getHours() + 1)
        let endHour = date.getHours()
        document.querySelector(".input-appointment-end").value = endHour + ":" + minute
    })
}

for(let toggleTagButton of document.querySelectorAll(".toggleTag")) {
    toggleTagButton.addEventListener("click", function(event) {
        document.getElementById("dialog-manage-tags").classList.toggle("invisible")
        event.preventDefault()
    })
}

for(let toggleShareButton of document.querySelectorAll(".toggleShare")) {
    toggleShareButton.addEventListener("click", function(event) {
        document.getElementById("dialog-share").classList.toggle("invisible")
        event.preventDefault()
    })
}

let refreshButtons = document.querySelectorAll(".refresh")
for(let refreshButton of refreshButtons) {
    refreshButton.addEventListener("click", function() {
        location.reload()
    })
}

function showEditAppointmentDialog() {
    let request = new XMLHttpRequest()
    request.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            let object = JSON.parse(this.responseText)
            document.querySelectorAll(".input-appointment-name").forEach(function(input) {
                input.setAttribute("value", object.name)
            })
            document.querySelectorAll(".input-appointment-date").forEach(function(input) {
                input.setAttribute("value", object.date)
            })
            document.querySelectorAll(".input-appointment-start").forEach(function(input) {
                input.setAttribute("value", object.start)
            })
            document.querySelectorAll(".input-appointment-end").forEach(function(input) {
                input.setAttribute("value", object.end)
            })
            document.querySelectorAll(".input-appointment-description").forEach(function(input) {
                input.setAttribute("value", object.description)
            })
            let appointmentTagsDivs = document.querySelectorAll(".appointment-tags")
            
            for(let appointmentTags of appointmentTagsDivs) {
                for(let tagDiv of appointmentTags.getElementsByTagName("div")) {
                    let tagCheckbox = tagDiv.getElementsByTagName("input")[0]
                    tagCheckbox.checked = false
                    
                    for(let tag of object.tags) {
                        if(tagDiv.classList.contains("tag-" + tag)) {
                            tagCheckbox.checked = true
                        }
                    }
                }
            }

            if(object.emails) {
                for(let email of object.emails) {
                    addEmailToList(email)
                }
            }
        }
    }
    request.open("GET", "/appointment/get?id=" + selectedAppointmentID, true)
    request.send()
    
    document.getElementById("dialog-edit-appointment").classList.remove("invisible")
}

for(let btn of document.querySelectorAll(".btn-edit-appointment")) {
    btn.addEventListener("click", function() {
        showEditAppointmentDialog()
    })
}

let tags = document.querySelectorAll(".tag")
let selectedTag = null

for(let tag of tags) {
    addTagEventListener(tag)
}

function setSelectedTag(tag) {
    if(selectedTag) {
        selectedTag.classList.remove("active")
    }
    selectedTag = tag
    selectedTag.classList.add("active")
}

function addTagEventListener(tag) {
    tag.addEventListener("click", function() {
        setSelectedTag(tag)
    })
}

function rgbToHex(rgb) {
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/)
    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2)
    }
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3])
}

document.querySelector("#btn-add-tag").addEventListener("click", function() {
    let exists = false
    for(let tag of tags) {
        if(tag.querySelector("span:last-child").textContent === document.querySelector("#tag-name").value ||
           rgbToHex(tag.querySelector("span:first-child").style.backgroundColor) === document.querySelector("#tag-color").value) {
            exists = true
        }
    }
    if(exists) {
        document.querySelector("#tag-name").setCustomValidity("A tag with this name or color already exists")
        document.querySelector("#tag-name").reportValidity()
    } else {
        document.querySelector("#tag-name").setCustomValidity("")
        let data = new FormData
        data.append("name", document.querySelector(".tag-name").value)
        data.append("color", document.querySelector(".tag-color").value)
    
        let request = new XMLHttpRequest()
        request.open("POST", "/tag/create", true)
        request.onload = function() {
            if(this.readyState === 4 && this.status === 200) {
                let object = JSON.parse(this.responseText)
            
                let tagButton = document.createElement("button")
                tagButton.id = "tag-" + object.id
                tagButton.classList.add("align-items-center", "d-flex", "flex-row", "pl-1", "list-group-item", "list-group-item-action")
                tagButton.innerHTML =
                    "<span style=\"width:1rem;height:1rem;background-color:" + object.color + "\" class=\"mr-2\"></span>" +
                    "<span class=\"align-middle\">" + object.name + "</span>"
                document.querySelector(".tag-list").appendChild(tagButton)
                addTagEventListener(tagButton)
            
                for(let appointmentTag of document.querySelectorAll(".appointment-tags")) {
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
    }
    tags = document.querySelectorAll(".tag")
})

document.querySelector("#btn-remove-tag").addEventListener("click", function() {
    let data = new FormData()
    let selectedTagId = selectedTag.id.split("-")[1]
    data.append("id", selectedTagId)
    
    let request = new XMLHttpRequest()
    request.open("POST", "/tag/delete", true)
    request.send(data)
    
    let appointmentTags = document.querySelectorAll(".appointment-tag")
    for(let tag of appointmentTags) {
        if(tag.classList.contains("tag-" + selectedTagId)) {
            tag.remove()
        }
    }
    
    document.querySelector(".tag-list").removeChild(selectedTag)
    selectedTag = tags[0]
    selectedTag.classList.add("active")
    tags = document.querySelectorAll(".tag")
})

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

document.querySelector("#add-email").addEventListener("click", function() {
    let exists = false
    for(let email of emails) {
        if(email.textContent === document.querySelector("#email").value) {
            exists = true
        }
    }
    if(exists) {
        document.querySelector("#email").setCustomValidity("This email already exists")
        document.querySelector("#email").reportValidity()
    } else {
        let email = document.querySelector("#email").value
        addEmailToList(email)
        document.querySelector("#email").value = ""
        emails = document.querySelectorAll(".share-entry")
    }
})

document.querySelector("#remove-email").addEventListener("click", function() {
    document.querySelector(".email-list").removeChild(selectedEmail)
    let inputs = document.getElementsByTagName("input")
    for(let input of inputs) {
        if(input.classList.contains("shared-appointment-email")) {
            input.remove()
        }
    }
    emails = document.querySelectorAll(".share-entry")
})

function deleteAccount() {
    document.querySelector("#dialog-delete-account").classList.toggle("invisible")
}