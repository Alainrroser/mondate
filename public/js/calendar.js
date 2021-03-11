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

function reload() {
    location.reload()
}

let createAppointment = document.getElementById("createAppointment")

function showCreateAppointment() {
    createAppointment.classList.remove("invisible")
}

function hideCreateAppointment() {
    createAppointment.classList.add("invisible")
}

let editAppointment = document.getElementById("editAppointment")

function showEditAppointment() {
    editAppointment.classList.remove("invisible")
}


function hideEditAppointment() {
    editAppointment.classList.add("invisible")
}