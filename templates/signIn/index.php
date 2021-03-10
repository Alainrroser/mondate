<div class="center-align">
    <div class="card w-25 pt-5 shadow-sm">
        <img src="/images/logo.png"
             class="card-img-top w-25 mx-auto"
             alt="The Mondate Logo">
        <div class="card-body">
            <form action="/user/doSignIn"
                  method="post">
                <div class="form-group form">
                    <label for="email">
                        E-Mail
                    </label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Enter your e-mail"
                           required
                    >
                </div>
                <div class="form-group form">
                    <label for="password">
                        Password
                    </label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Enter your password"
                           pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,20}$"
                           required>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button class="btn btn-primary w-50 mb-2"
                            type="submit">
                        Sign In
                    </button>
                    <a href="/signUp/"
                       class="btn btn-secondary w-50">
                        Sign Up
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>