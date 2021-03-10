"use strict";

const CELL_HEIGHT = 50

let tableBody = document.querySelector("tbody")
tableBody.scrollTop = 8 * CELL_HEIGHT;

document.onkeydown = function(event) {
    if(event.key === 'ArrowRight') {
        window.location = '/calendar/next'
    } else if(event.key === 'ArrowLeft') {
        window.location = '/calendar/last'
    }
};