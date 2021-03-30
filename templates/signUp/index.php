<div class="d-flex flex-column center-align">
    <?php require '../templates/error/dialogError.php'; ?>
    <div class="account-screen card pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Sign Up
        </h1>
        <img src="/images/logo.png" class="card-img-top mx-auto" alt="The Mondate Logo">
        <div class="card-body">
            <form action="/user/doSignUp" method="post">
                <div class="form-group form">
                    <label for="input-email">
                        E-Mail
                    </label>
                    <input type="email" id="input-email" name="email" class="form-control"
                           placeholder="Enter an e-mail" maxlength="450" required>
                </div>
                <div class="form-group form">
                    <label for="input-password">
                        Password
                    </label>
                    <input type="password" id="input-password" name="password" class="form-control"
                           placeholder="Enter a password" maxlength="50" required>
                </div>
                <div class="form-group form">
                    <label for="input-confirm-password">
                        Confirm Password
                    </label>
                    <input type="password" id="input-confirm-password" name="confirmPassword" class="form-control"
                           placeholder="Confirm your password" maxlength="50" required>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button class="btn btn-primary w-50 mb-2" type="submit" id="submit-button">
                        Sign Up
                    </button>
                    <a href="/signIn/" class="btn btn-secondary w-50">
                        Sign In
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/confirmPassword.js"></script>
<script src="/js/error.js"></script>