<div class="center-align">
    <div class="card w-25 pt-5">
        <img src="/images/logo.png"
             class="card-img-top w-25 mx-auto"
             alt="The Mondate Logo">
        <div class="card-body">
            <form action="/user/doSignUp" method="post">
                <div class="form-group form">
                    <label for="e-mail">
                        E-Mail
                    </label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Enter your e-mail">
                </div>
                <div class="form-group form">
                    <label for="password">
                        Password
                    </label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Enter your password">
                </div>
                <div class="form-group form">
                    <label for="password">
                        Confirm password
                    </label>
                    <input type="password"
                           name="confirm_password"
                           class="form-control"
                           placeholder="Enter your password">
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button class="btn btn-primary w-50 mb-2" type="submit">
                        Sign Up
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
