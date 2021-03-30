"use strict"

let appointmentButtons = document.querySelectorAll(".appointment")

let appointmentSelected = false
let selectedAppointmentID = 0

function updateEditDeleteButtonStates() {
    for(let btn of document.querySelectorAll(".btn-edit-appointment, .btn-delete-appointment")) {
        btn.disabled = !appointmentSelected
    }
}

updateEditDeleteButtonStates()

for(let appointmentButton of appointmentButtons) {
    appointmentButton.addEventListener("click", function(event) {
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

        updateEditDeleteButtonStates()

        event.preventDefault()
        event.stopPropagation()
    })
}

for(let toggleCreateButton of document.querySelectorAll(".toggle-create")) {
    toggleCreateButton.addEventListener("click", function() {
        setDateTimeToNow()
        document.getElementById("dialog-create-appointment").classList.toggle("invisible")
    })
}

for(let deleteButton of document.querySelectorAll(".btn-delete-appointment")) {
    appointmentSelected = null;
    updateEditDeleteButtonStates()
}

let refreshButtons = document.querySelectorAll(".refresh")
for(let refreshButton of refreshButtons) {
    refreshButton.addEventListener("click", function() {
        location.reload()
    })
}

document.addEventListener("keydown", function(event) {
    if(appointmentSelected) {
        if(event.key === "Delete") {
            document.querySelector(".btn-delete-appointment").click()
        }
    }
})

function requestAndFillInAppointmentData() {
    let request = new XMLHttpRequest()
    request.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            let object = JSON.parse(this.responseText)

            document.querySelectorAll(".input-appointment-name").forEach(function(input) {
                input.setAttribute("value", object.name)
            })
            document.querySelectorAll(".input-appointment-start").forEach(function(input) {
                input.setAttribute("value", object.start)
            })
            document.querySelectorAll(".input-appointment-end").forEach(function(input) {
                input.setAttribute("value", object.end)
            })
            document.querySelectorAll(".input-appointment-description").forEach(function(input) {
                input.value = object.description;
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
}

function showEditAppointmentDialog() {
    requestAndFillInAppointmentData()
    document.getElementById("dialog-edit-appointment").classList.remove("invisible")
}

for(let btn of document.querySelectorAll(".btn-edit-appointment")) {
    btn.addEventListener("click", function() {
        showEditAppointmentDialog()
    })
}

function convertDateToDateTimeLocalValue(date) {
    let result = ''
    result += date.getFullYear().toString().padStart(4, "0") + "-"
    result += (date.getMonth() + 1).toString().padStart(2, "0") + "-"
    result += date.getDate().toString().padStart(2, "0") + "T"
    result += date.getHours().toString().padStart(2, "0") + ":"
    result += date.getMinutes().toString().padStart(2, "0")
    return result
}

function setDateTimeToNow() {
    let date = new Date()
    document.querySelectorAll(".input-appointment-start").forEach(function(element) {
        element.value = convertDateToDateTimeLocalValue(date)
    })

    date.setHours(date.getHours() + 1)
    document.querySelectorAll(".input-appointment-end").forEach(function(element) {
        element.value = convertDateToDateTimeLocalValue(date)
    })
}

let deleteAccountLinks = document.querySelectorAll(".delete-account")
for(let deleteAccountLink of deleteAccountLinks) {
    deleteAccountLink.addEventListener("click", function() {
        document.querySelector("#dialog-delete-account").classList.toggle("invisible")
    })
}

document.querySelector("#delete-account").addEventListener("click", function() {
    window.localStorage.removeItem("email")
    window.localStorage.removeItem("rememberMe")
})

for(let inputWeekStart of document.querySelectorAll(".input-week-start")) {
    inputWeekStart.addEventListener("input", function() {
        let data = new FormData()
        data.append("weekStart", this.value)

        let request = new XMLHttpRequest()
        request.open("POST", "/calendar/jumpToDate", true)
        request.send(data)

        location.reload()
    })
}

for(let appointmentCell of document.querySelectorAll(".appointment-cell")) {
    appointmentCell.addEventListener("click", function() {
        let dateTimeInSeconds = appointmentCell.id.split("-")[2]
        let date = new Date(parseInt(dateTimeInSeconds) * 1000)

        document.querySelectorAll(".input-appointment-start").forEach(function(element) {
            element.value = convertDateToDateTimeLocalValue(date)
        })

        date.setHours(date.getHours() + 1)
        document.querySelectorAll(".input-appointment-end").forEach(function(element) {
            element.value = convertDateToDateTimeLocalValue(date)
        })

        document.getElementById("dialog-create-appointment").classList.toggle("invisible")
    })
}