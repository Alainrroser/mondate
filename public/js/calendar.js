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

let toggleCreateButtons = document.querySelectorAll(".toggleCreate")
let toggleEditButtons = document.querySelectorAll(".toggleEdit")
let toggleTagButtons = document.querySelectorAll(".toggleTag")

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
        event.preventDefault();
    })
}

document.querySelector("#reloadButton").addEventListener("click", function() {
    location.reload()
})