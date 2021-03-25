"use strict"

let selectedTag = null

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

let manageTagDialogBodies = document.querySelectorAll("#dialog-manage-tags .card-body");

for(let manageTagDialogBody of manageTagDialogBodies) {
    for(let btnAddTag of manageTagDialogBody.querySelectorAll(".btn-add-tag")) {
        btnAddTag.addEventListener("click", function() {
            if(selectedTag) {
                addTag(manageTagDialogBody)
            }
        })
    }

    for(let btnRemoveTag of manageTagDialogBody.querySelectorAll(".btn-remove-tag")) {
        btnRemoveTag.addEventListener("click", function() {
            if(selectedTag) {
                removeTag()
            }
        })
    }
}

function addTag(manageTagDialogBody) {
    let tagNameElement = manageTagDialogBody.querySelector(".tag-name")
    let tagColorElement = manageTagDialogBody.querySelector(".tag-color")

    let tagList = manageTagDialogBody.querySelector(".tag-list")
    let inputValid = true

    tagNameElement.setCustomValidity("")

    if(tagNameElement.value) {
        console.log(tagList.childNodes)

        for(let tag of tagList.children) {
            let tagName = tag.querySelector("span:last-child").textContent
            let tagColor = rgbToHex(tag.querySelector("span:first-child").style.backgroundColor)

            if(tagName === tagNameElement.value || tagColor === tagColorElement.value) {
                tagNameElement.setCustomValidity("A tag with this name or color already exists")
                tagNameElement.reportValidity()
                inputValid = false
            }
        }
    } else {
        tagNameElement.setCustomValidity("The tag name cannot be null")
        tagNameElement.reportValidity()
        inputValid = false
    }

    if(inputValid) {
        sendAddTag(tagNameElement.value, tagColorElement.value)

        tagNameElement.value = ""
        tagColorElement.value = "#000000"
    }
}

function sendAddTag(tagName, tagColor) {
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
                    "<div class=\"tag-" + object.id + "align-items-center d-flex flex-row pl-1\">" +
                    "<input type=\"checkbox\" name=\"tags[" + object.id + "]\" class=\"align-middle mr-1\">" +
                    "<span style=\"width:1rem;height:1rem;background-color:" + object.color + "\" class=\"mr-2\"></span>" +
                    "<span class=\"align-middle\">" + object.name + "</span>" +
                    "</div>"
            }
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

    data.append("id", selectedTagId)

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

            for(let tag of document.querySelectorAll("#dialog-manage-tags button")) {
                if(tag.classList.contains("tag-" + selectedTagId)) {
                    tag.remove()
                }
            }

            let tags = document.querySelectorAll(".tag")
            selectedTag = tags[0]
            selectedTag.classList.add("active")
        }
    }
}