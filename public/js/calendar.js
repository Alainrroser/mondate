"use strict"

const CELL_HEIGHT = 50

let tableBody = document.querySelector("tbody")
tableBody.scrollTop = 8 * CELL_HEIGHT;

document.onkeydown = function (event) {
    if (event.key === 'ArrowRight') {
        window.location = '/calendar/next'
    } else if (event.key === 'ArrowLeft') {
        window.location = '/calendar/last'
    }
}

let appointmentButtons = document.querySelectorAll(".appointment")

let appointmentSelected = false;
let selectedAppointmentID = 0;

for (let appointmentButton of appointmentButtons) {
    appointmentButton.addEventListener("click", function () {
        let id = appointmentButton.id.split("-")[2]
        
        if (appointmentSelected && selectedAppointmentID === id) {
            appointmentSelected = false
        } else {
            if (appointmentSelected) {
                for (let otherAppointmentButton of appointmentButtons) {
                    if (appointmentButton.id.localeCompare("#appointment-id-" + selectedAppointmentID)) {
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
        for (let relatedButton of relatedButtons) {
            relatedButton.classList.toggle("appointment-selected")
        }
    });
}

let toggleCreateButtons = document.querySelectorAll(".toggleCreate")
let toggleEditButtons = document.querySelectorAll(".toggleEdit")
let toggleTagButtons = document.querySelectorAll(".toggleTag")
let toggleShareButtons = document.querySelectorAll(".toggleShare")

for (let toggleCreateButton of toggleCreateButtons) {
    toggleCreateButton.addEventListener("click", function () {
        document.getElementById("createAppointment").classList.toggle("invisible")
    })
}

for (let toggleEditButton of toggleEditButtons) {
    toggleEditButton.addEventListener("click", function () {
        document.getElementById("editAppointment").classList.toggle("invisible")
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

document.querySelector("#reloadButton").addEventListener("click", function () {
    location.reload()
})

document.querySelector("#btn-edit-appointment").addEventListener("click", function () {
    $.get("/appointment/get?id=" + selectedAppointmentID, function (data) {
        let object = JSON.parse(data)
        document.querySelector(".input-appointment-name").setAttribute("value", object.name)
        document.querySelector(".input-appointment-date").setAttribute("value", object.date)
        document.querySelector(".input-appointment-start").setAttribute("value", object.start)
        document.querySelector(".input-appointment-end").setAttribute("value", object.end)
        document.querySelector(".input-appointment-description").value = object.description
    })
})

document.querySelector("#btn-add-tag").addEventListener("click", function () {
    let data = new FormData()
    data.append("name", document.querySelector(".tag-name").value)
    data.append("color", document.querySelector(".tag-color").value)
    
    let request = new XMLHttpRequest()
    request.open("POST", "/tag/create", true)
    request.onload = function() {
        if (this.readyState === 4 && this.status === 200) {
            let object = JSON.parse(this.responseText)
            console.log(this.responseText)
            document.querySelector(".tag-color-list").innerHTML +=
                "<div class=\"align-items-center d-flex flex-row pl-1\">" +
                "<span style=\"width:1rem;height:1rem;background-color:" + object.color + "\" class=\"mr-2\"></span>" +
                "<span class=\"align-middle\">" + object.name + "</span>" +
                "</div>"
            document.querySelector(".tag-list").innerHTML +=
                "<div class=\"align-items-center d-flex flex-row pl-1\">" +
                "<input type=\"checkbox\" name=\"tags[$id]\" class=\"align-middle mr-1\">" +
                "<span style=\"width:1rem;height:1rem;background-color:" + object.color + "\" class=\"mr-2\"></span>" +
                "<span class=\"align-middle\">" + object.name + "</span>" +
                "</div>"
        }
    }
    request.send(data)
})