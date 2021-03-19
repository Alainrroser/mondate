let btnCloseErrorDialog = document.querySelector("#btn-close-error-dialog")
if(btnCloseErrorDialog) {
    btnCloseErrorDialog.addEventListener("click", function() {
        location.replace("/signIn")
    })
}