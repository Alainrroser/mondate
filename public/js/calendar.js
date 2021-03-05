tableBody = document.querySelector("tbody")
tableBody.scrollTop = 8 * 50;

document.onkeydown = function(event) {
    if(event.key == 'ArrowRight') {
        window.location = '/calendar/next';
    } else if(event.key == 'ArrowLeft') {
        window.location = '/calendar/last';
    }
};