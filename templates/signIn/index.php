<div class="d-flex flex-column center-align">
    <?php require '../templates/error/dialogError.php'; ?>
    <div class="account-screen card pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Sign In
        </h1>
        <img src="/images/logo.png" class="card-img-top mx-auto" alt="The Mondate Logo">
        <div class="card-body">
            <form action="/user/doSignIn" method="post">
                <div class="form-group form">
                    <label for="input-email">
                        E-Mail
                    </label>
                    <input id="input-email" type="email" name="email" class="form-control"
                           placeholder="Enter your e-mail" maxlength="450" required>
                </div>
                <div class="form-group form">
                    <label for="input-password">
                        Password
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password"
                           id="input-password" maxlength="50" required>
                </div>
                <div class="pb-3">
                    <input id="input-remember-me" type="checkbox">
                    <label for="input-remember-me">Remember Me</label>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button id="btn-submit" class="btn btn-primary w-50 mb-2" type="submit">
                        Sign In
                    </button>
                    <a href="/signUp/" class="btn btn-secondary w-50">
                        Sign Up
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/signIn.js"></script>
<script src="/js/error.js"></script>