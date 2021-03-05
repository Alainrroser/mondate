tableBody = document.querySelector("tbody")
tableBody.scrollTop = 8 * 50;

let scopeIdentifier = document.querySelector("#scope-identifier")
let buttonNext = document.querySelector("#btn-next")
let buttonLast = document.querySelector("#btn-last")

let startDate = new Date()

function dateToString(date) {
    let dd = (date.getDate()).toString()
    let mm = (date.getMonth() + 1).toString()
    let yyyy = (date.getFullYear()).toString()

    return dd + '.' + mm + '.' + yyyy
}

function setStartDate(date) {
    startDate = date

    let endDate = new Date(date)
    endDate.setDate(date.getDate() + 6)

    scopeIdentifier.innerHTML = dateToString(date) + ' - ' + dateToString(endDate)
}

currentDay = new Date(Date.now())
let newDate = currentDay.getDate() - (currentDay.getDay() - 1)
console.log(currentDay.getDate())
currentDay.setDate(newDate)
setStartDate(currentDay)

buttonNext.addEventListener("click", evt => {
    let nextDate = new Date(startDate)
    nextDate.setDate(startDate.getDate() + 7)
    setStartDate(nextDate)
});

buttonLast.addEventListener("click", evt => {
    let nextDate = new Date(startDate)
    nextDate.setDate(startDate.getDate() - 7)
    setStartDate(nextDate)
});