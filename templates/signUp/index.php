<div class="center-align">
    <div class="card w-25 pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Sign Up
        </h1>
        <img src="/images/logo.png"
             class="card-img-top w-25 mx-auto"
             alt="The Mondate Logo">
        <div class="card-body">
            <form action="/user/doSignUp"
                  method="post">
                <div class="
                  form-group
                  form">
                    <label for="e-mail">
                        E-Mail
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control"
                           placeholder="Enter an e-mail"
                           required>
                </div>
                <div class="form-group form">
                    <label for="password">
                        Password
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control"
                           placeholder="Enter a password"
                           required>
                </div>
                <div class="form-group form">
                    <label for="password">
                        Confirm Password
                    </label>
                    <input type="password"
                           id="confirmPassword"
                           name="confirmPassword"
                           class="form-control"
                           placeholder="Confirm your password"
                           required>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button class="btn btn-primary w-50 mb-2"
                            type="submit"
                            id="submit-button">
                        Sign Up
                    </button>
                    <a href="/signIn/"
                       class="btn btn-secondary w-50">
                        Sign In
                    </a>
                </div>
        </form>
        </div>
    </div>
</div>

<script type="text/javascript"
        src="/js/signUp.js"></script>