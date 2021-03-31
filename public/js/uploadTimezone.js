let data = new FormData()
data.append("timezone", Intl.DateTimeFormat().resolvedOptions().timeZone)

let request = new XMLHttpRequest()
request.onload = function() {
    if(this.readyState === 4 && this.status === 200) {
        window.location.replace("/calendar")
    }
}
request.open("POST", "/calendar/uploadTimezone", false)
request.send(data)