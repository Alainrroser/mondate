let searchResultLists = document.querySelectorAll(".search-result-list")
let searchFields = document.querySelectorAll(".search")
let selectedSearchResult = null

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
        for(let searchResultList of searchResultLists) {
            searchResultList.classList.add("invisible")
        }
    }
})

function addSearchResultToSearchResultList(object, searchResultList) {
    let searchResult = document.createElement("button")
    searchResult.classList.add("align-items-center", "d-flex", "flex-row", "pl-1", "list-group-item", "list-group-item-action")
    searchResult.textContent = object.name + ": " + object.start + "-" + object.end + " " + object.description
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

function setSelectedSearchResult(searchResult) {
    if(selectedSearchResult) {
        selectedSearchResult.classList.remove("active")
    }
    selectedSearchResult = searchResult
    selectedSearchResult.classList.add("active")
}

function addSearchResultEventListener(searchResult, object) {
    searchResult.addEventListener("click", function() {
        setSelectedSearchResult(searchResult)
    })
    searchResult.addEventListener("dblclick", function() {
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