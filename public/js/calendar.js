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

function toggleCreateAppointment() {
    createAppointment.classList.toggle("invisible")
}

let editAppointment = document.getElementById("editAppointment")

function toggleEditAppointment() {
    editAppointment.classList.toggle("invisible")
}

let tags = document.getElementById("tags");


function toggleTags(event) {
    tags.classList.toggle("invisible")
    event.preventDefault();
}