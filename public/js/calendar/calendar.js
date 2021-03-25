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
        setDateTimeToNow()
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

function setDateTimeToNow() {
    let date = new Date()
    let hour = date.getHours()
    let minute = date.getMinutes()
    minute += (minute < 10 ? "0" : "")
    hour += (hour < 10 ? "0" : "")
    document.querySelector(".input-appointment-start").value = hour + ":" + minute
    date.setHours(date.getHours() + 1)
    let endHour = date.getHours()
    document.querySelector(".input-appointment-end").value = endHour + ":" + minute
}

function deleteAccount() {
    document.querySelector("#dialog-delete-account").classList.toggle("invisible")
}