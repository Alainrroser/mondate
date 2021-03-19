let btnCloseErrorDialog = document.querySelector("#btn-close-error-dialog")
if(btnCloseErrorDialog) {
    btnCloseErrorDialog.addEventListener("click", function() {
        document.querySelector("#dialog-error").classList.toggle("invisible")
    })
}