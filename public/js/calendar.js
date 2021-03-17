"use strict"

const CELL_HEIGHT = 50

let tableBody = document.querySelector("tbody")
tableBody.scrollTop = 8 * CELL_HEIGHT;

document.onkeydown = function (event) {
    if(event.key === 'ArrowRight') {
        window.location = '/calendar/next'
    } else if(event.key === 'ArrowLeft') {
        window.location = '/calendar/last'
    }
}

let appointmentButtons = document.querySelectorAll(".appointment")

let appointmentSelected = false;
let selectedAppointmentID = 0;

for(let appointmentButton of appointmentButtons) {
    appointmentButton.addEventListener("click", function() {
        let id = appointmentButton.id.split("-")[2]

        if(appointmentSelected && selectedAppointmentID === id) {
            appointmentSelected = false
        } else {
            if(appointmentSelected) {
                for(let otherAppointmentButton of appointmentButtons) {
                    if(appointmentButton.id.localeCompare("#appointment-id-" + selectedAppointmentID)) {
                        otherAppointmentButton.classList.remove("appointment-selected")
                    }
                }
            } else {
                appointmentSelected = true
            }

            selectedAppointmentID = id
            document.querySelector("#delete-appointment-id").setAttribute("value", selectedAppointmentID)
            document.querySelector("#edit-appointment-id").setAttribute("value", selectedAppointmentID)
        }

        let relatedButtons = document.querySelectorAll("#appointment-id-" + id)
        for(let relatedButton of relatedButtons) {
            relatedButton.classList.toggle("appointment-selected")
        }
    });
}

let toggleCreateButtons = document.querySelectorAll(".toggleCreate")
let toggleEditButtons = document.querySelectorAll(".toggleEdit")
let toggleTagButtons = document.querySelectorAll(".toggleTag")
let toggleShareButtons = document.querySelectorAll(".toggleShare")

for (let toggleCreateButton of toggleCreateButtons) {
    toggleCreateButton.addEventListener("click", function() {
        document.getElementById("createAppointment").classList.toggle("invisible")
    })
}

for (let toggleEditButton of toggleEditButtons) {
    toggleEditButton.addEventListener("click", function() {
        document.getElementById("editAppointment").classList.toggle("invisible")
    })
}

for (let toggleTagButton of toggleTagButtons) {
    toggleTagButton.addEventListener("click", function(event) {
        document.getElementById("tags").classList.toggle("invisible")
        event.preventDefault()
    })
}

for (let toggleShareButton of toggleShareButtons) {
    toggleShareButton.addEventListener("click", function(event) {
        document.getElementById("share").classList.toggle("invisible")
        event.preventDefault()
    })
}

document.querySelector("#reloadButton").addEventListener("click", function() {
    location.reload()
})