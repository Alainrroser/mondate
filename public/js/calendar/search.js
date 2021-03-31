let searchResultLists = document.querySelectorAll(".search-result-list")
let searchFields = document.querySelectorAll(".search")

for(let searchField of searchFields) {
    searchField.addEventListener("click", function() {
        for(let searchResultList of searchResultLists) {
            searchResultList.classList.remove("invisible")
            showResults(searchResultList)
        }
    })
    searchField.addEventListener("input", function() {
        clearResults()
        let request = new XMLHttpRequest()
        request.onreadystatechange = function() {
            if(this.readyState === 4 && this.status === 200) {
                let objects = JSON.parse(this.responseText)
                for(let object of objects) {
                    if(searchField.value) {
                        if(object.name.toLowerCase().includes(searchField.value.toLowerCase()) ||
                           object.description.toLowerCase().includes(searchField.value.toLowerCase())) {
                            for(let searchResultList of searchResultLists) {
                                addSearchResultToSearchResultList(object, searchResultList)
                            }
                        }
                    }
                }
            }
        }
        request.open("POST", "/appointment/getAppointmentsFromUser", true)
        request.send()
    })
}

document.addEventListener("click", function(event) {
    if(!event.target.classList.contains("search")) {
        if(!event.target.classList.contains("search-result-list")) {
            if(!event.target.classList.contains("search-result")) {
                for(let searchResultList of searchResultLists) {
                    searchResultList.classList.add("invisible")
                }
            }
        }
    }
})

function addSearchResultToSearchResultList(object, searchResultList) {
    let searchResult = document.createElement("button")
    searchResult.classList.add("align-items-center", "d-flex", "flex-row", "pl-1", "list-group-item", "list-group-item-action", "search-result")
    searchResult.textContent = object.name + ": " + object.start
    searchResultList.appendChild(searchResult)
    addSearchResultEventListener(searchResult, object)
}

function showResults(searchResultList) {
    clearResults()
    let request = new XMLHttpRequest()
    request.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            let objects = JSON.parse(this.responseText)
            for(let object of objects) {
                addSearchResultToSearchResultList(object, searchResultList)
            }
        }
    }
    request.open("POST", "/appointment/getAppointmentsFromUser", true)
    request.send()
}

function addSearchResultEventListener(searchResult, object) {
    searchResult.addEventListener("click", function() {
        let data = new FormData()
        data.append("weekStart", object.start)
        
        let request = new XMLHttpRequest()
        request.open("POST", "/calendar/jumpToDate", true)
        request.send(data)
        
        location.reload()
    })
}

function clearResults() {
    for(let searchResultList of searchResultLists) {
        searchResultList.innerHTML = ""
    }
}