let searchButtons = document.querySelectorAll(".toggle-search")
let searchResultLists = document.querySelectorAll(".search-result-list")
let searchFields = document.querySelectorAll(".search")
let searchDialog = document.querySelector("#dialog-search")

let selectedSearchResult = null

function addSearchResultToSearchResultList(object) {
    for(let searchResultList of searchResultLists) {
        let searchResult = document.createElement("button")
        searchResult.classList.add("align-items-center", "d-flex", "flex-row", "pl-1", "list-group-item", "list-group-item-action")
        searchResult.textContent = object.name + ": " + object.start + "-" + object.end + " " + object.description
        searchResultList.appendChild(searchResult)
        addSearchResultEventListener(searchResult)
    }
}

for(let searchButton of searchButtons) {
    searchButton.addEventListener("click", function() {
        searchDialog.classList.toggle("invisible")
        let request = new XMLHttpRequest()
        request.onreadystatechange = function() {
            if(this.readyState === 4 && this.status === 200) {
                let objects = JSON.parse(this.responseText)
                for(let object of objects) {
                    addSearchResultToSearchResultList(object)
                }
            }
        }
        request.open("POST", "/appointment/getAppointmentsFromUser", true)
        request.send()
    })
}

function setSelectedSearchResult(searchResult) {
    if(selectedSearchResult) {
        selectedSearchResult.classList.remove("active")
    }
    selectedSearchResult = searchResult
    selectedSearchResult.classList.add("active")
}

function addSearchResultEventListener(searchResult) {
    searchResult.addEventListener("click", function() {
        setSelectedSearchResult(searchResult)
    })
}

searchDialog.addEventListener("input", function() {
    for(let searchResultList of searchResultLists) {
        searchResultList.innerHTML = ""
    }
    let request = new XMLHttpRequest()
    request.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            let objects = JSON.parse(this.responseText)
            for(let object of objects) {
                for(let searchField of searchFields) {
                    if(searchField.value) {
                        if(object.name.toLowerCase().includes(searchField.value.toLowerCase())) {
                            addSearchResultToSearchResultList(object)
                        }
                        if(object.description.toLowerCase().includes(searchField.value.toLowerCase())) {
                            addSearchResultToSearchResultList(object)
                        }
                    }
                }
            }
        }
    }
    request.open("POST", "/appointment/getAppointmentsFromUser", true)
    request.send()
})