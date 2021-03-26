let timezoneOffset = new Date().getTimezoneOffset() * 60;

let data = new FormData()
data.append("timezoneOffset", timezoneOffset.toString())

let request = new XMLHttpRequest()
request.onload = function() {
    if(this.readyState === 4 && this.status === 200) {
        location.replace("/calendar/")
    }
}
request.open("POST", "/calendar/uploadTimezoneOffset", false)
request.send(data)