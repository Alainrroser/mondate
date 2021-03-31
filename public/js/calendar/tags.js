"use strict"

let selectedTag = null

for(let toggleTagButton of document.querySelectorAll(".toggle-tag")) {
    toggleTagButton.addEventListener("click", function(event) {
        document.getElementById("dialog-manage-tags").classList.toggle("invisible")
        event.preventDefault()
    })
}

for(let tag of document.querySelectorAll(".tag")) {
    addTagEventListener(tag)
}

function setSelectedTag(tag) {
    if(selectedTag) {
        selectedTag.classList.remove("active")
    }
    selectedTag = tag
    selectedTag.classList.add("active")
}

function addTagEventListener(tag) {
    tag.addEventListener("click", function() {
        setSelectedTag(tag)
    })
}

function rgbToHex(rgb) {
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/)
    
    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2)
    }
    
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3])
}

let manageTagsDialogBodies = document.querySelectorAll("#dialog-manage-tags .card-body");

for(let manageTagsDialogBody of manageTagsDialogBodies) {
    manageTagsDialogBody.querySelector(".btn-add-tag").addEventListener("click", function() {
        addTag(manageTagsDialogBody)
    })
    
    manageTagsDialogBody.querySelector(".btn-remove-tag").addEventListener("click", function() {
        if(selectedTag) {
            removeTag()
        }
    })
}

function addTag(manageTagsDialogBody) {
    let tagNameElement = manageTagsDialogBody.querySelector(".tag-name")
    let tagColorElement = manageTagsDialogBody.querySelector(".tag-color")
    
    let inputValid = validateTagInput(manageTagsDialogBody, tagNameElement, tagColorElement)
    
    if(inputValid) {
        addTagToListAndSend(tagNameElement.value, tagColorElement.value)
        
        tagNameElement.value = ""
        tagColorElement.value = "#000000"
    }
}

function validateTagInput(manageTagsDialogBody, tagNameElement, tagColorElement) {
    let tagList = manageTagsDialogBody.querySelector(".tag-list")
    tagNameElement.setCustomValidity("")
    
    if(!tagNameElement.value) {
        tagNameElement.setCustomValidity("The tag name cannot be empty")
        tagNameElement.reportValidity()
        return false
    }
    
    for(let tag of tagList.children) {
        let tagName = tag.querySelector("span:last-child").textContent
        let tagColor = rgbToHex(tag.querySelector("span:first-child").style.backgroundColor)
        
        if(tagName.toLowerCase() === tagNameElement.value.toLowerCase() || tagColor === tagColorElement.value) {
            tagNameElement.setCustomValidity("A tag with this name or color already exists")
            tagNameElement.reportValidity()
            return false
        }
    }
    
    return true
}

function addTagToListAndSend(tagName, tagColor) {
    let data = new FormData()
    data.append("name", tagName)
    data.append("color", tagColor)
    
    let request = new XMLHttpRequest()
    request.open("POST", "/tag/create", true)
    request.onload = function() {
        if(this.readyState === 4 && this.status === 200) {
            let object = JSON.parse(this.responseText)
            
            for(let tagList of document.querySelectorAll(".tag-list")) {
                let tagButton = document.createElement("button")
                tagButton.classList.add("tag-" + object.id)
                tagButton.classList.add("align-items-center", "d-flex", "flex-row", "pl-1", "list-group-item", "list-group-item-action")
                tagButton.innerHTML =
                    "<span style=\"width:1rem;height:1rem;background-color:" + object.color + "\" class=\"mr-2\"></span>" +
                    "<span class=\"align-middle\">" + object.name + "</span>"
                tagList.appendChild(tagButton)
                addTagEventListener(tagButton)
            }
            
            for(let appointmentTag of document.querySelectorAll(".appointment-tags")) {
                appointmentTag.innerHTML +=
                    "<div class=\"tag-" + object.id + " appointment-tag align-items-center d-flex flex-row pl-1\">" +
                    "<input type=\"checkbox\" name=\"tags[" + object.id + "]\" class=\"align-middle mr-1\">" +
                    "<span style=\"width:1rem;height:1rem;background-color:" + object.color + "\" class=\"mr-2\"></span>" +
                    "<span class=\"align-middle\">" + object.name + "</span>" +
                    "</div>"
            }
            
            // The checkboxes for the tags are now all disabled because we've edited the DOM
            // This is why we reload the data from the server
            requestAndFillInAppointmentData()
        }
    }
    request.send(data)
}

function removeTag() {
    let data = new FormData()
    let selectedTagId = 0
    
    for(let tagClass of selectedTag.classList) {
        if(tagClass.startsWith("tag-")) {
            selectedTagId = tagClass.split("-")[1]
        }
    }
    
    data.append("id", selectedTagId.toString())
    
    let request = new XMLHttpRequest()
    request.open("POST", "/tag/delete", true)
    request.send(data)
    request.onload = function() {
        if(this.readyState === 4 && this.status === 200) {
            for(let tag of document.querySelectorAll(".appointment-tag")) {
                if(tag.classList.contains("tag-" + selectedTagId)) {
                    tag.remove()
                }
            }
            
            for(let tag of document.querySelectorAll(".tag-list button")) {
                if(tag.classList.contains("tag-" + selectedTagId)) {
                    tag.remove()
                }
            }
            
            selectedTag = null
        }
    }
}