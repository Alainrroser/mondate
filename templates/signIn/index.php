<div class="d-flex flex-column center-align">
    <?php require '../templates/error/dialogError.php'; ?>
    <div class="account-screen card pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Sign In
        </h1>
        <img src="/images/logo.png" class="card-img-top w-25 mx-auto" alt="The Mondate Logo">
        <div class="card-body">
            <form action="/user/doSignIn" method="post">
                <div class="form-group form">
                    <label for="email">
                        E-Mail
                    </label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your e-mail" required>
                </div>
                <div class="form-group form">
                    <label for="password">
                        Password
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password"
                           required>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button class="btn btn-primary w-50 mb-2" type="submit">
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

<script type="text/javascript" src="/js/error.js"></script>