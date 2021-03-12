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

for (let i = 0; i < document.querySelectorAll(".toggleCreate"); i++) {
    document.querySelectorAll(".toggleCreate").addEventListener("click", function() {
        document.getElementById("createAppointment").classList.toggle("invisible")
    })
}

for (let j = 0; j < document.querySelectorAll(".toggleEdit"); j++) {
    document.querySelectorAll(".toggleEdit").addEventListener("click", function() {
        document.getElementById("createAppointment").classList.toggle("invisible")
    })
}

for (let k = 0; k < document.querySelectorAll(".toggleTag"); k++) {
    document.querySelectorAll(".toggleTag").addEventListener("click", function(event) {
        document.getElementById("createAppointment").classList.toggle("invisible")
        event.preventDefault();
    })
}

document.querySelector("#reloadButton").addEventListener("click", function() {
    location.reload()
})