"use strict";

window.addEventListener("load", function() {
    let tableBody = document.querySelector("tbody")
    tableBody.addEventListener("scroll", function() {
        window.localStorage.setItem("scroll", tableBody.scrollTop.toString())
    })

    let localStorageScroll = window.localStorage.getItem("scroll")
    if(!localStorageScroll) {
        const CELL_HEIGHT = 50
        tableBody.scrollTop = 8 * CELL_HEIGHT
    } else {
        tableBody.scrollTop = parseInt(localStorageScroll)
    }
})