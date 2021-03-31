let btnCloseErrorDialog = document.querySelector("#btn-close-error-dialog")
if(btnCloseErrorDialog) {
    btnCloseErrorDialog.addEventListener("click", function() {
        if(window.location.href.indexOf("user/doChangePassword") > -1) {
            location.replace("/user/changePassword")
        } else {
            location.replace("/signIn")
        }
    })
}