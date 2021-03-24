function setCookie(name, value) {
    let date = new Date()
    date.setTime(date.getTime() + 30 * 60 * 1000)
    let expires = "expires=" + date.toUTCString()
    document.cookie = name + "=" + value + ";" + expires + ";path=/"
}

function getCookie(name) {
    let nameWithEquals = name + "="
    let decodedCookie = decodeURIComponent(document.cookie)
    let cookies = decodedCookie.split(";")

    for(let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim()
        if(cookie.indexOf(nameWithEquals) === 0) {
            return cookie.substring(nameWithEquals.length, cookie.length)
        }
    }

    return ""
}

let tableBody = document.querySelector("tbody")
tableBody.addEventListener("scroll", function() {
    setCookie("scroll", tableBody.scrollTop)
})

let scrollCookie = getCookie("scroll")
if(scrollCookie === "") {
    const CELL_HEIGHT = 50

    tableBody.scrollTop = 8 * CELL_HEIGHT
} else {
    tableBody.scrollTop = parseInt(scrollCookie)
}