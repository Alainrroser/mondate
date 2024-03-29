<?php
require '../templates/error/dialogError.php';
?>

<div class="d-flex flex-column center-align">
    <div class="account-screen card pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Change Password
        </h1>
        <img src="/images/logo.png" class="card-img-top mx-auto" alt="The Mondate Logo">
        <div class="card-body">
            <form action="/user/doChangePassword" method="post">
                <div class="form-group form">
                    <label for="input-old-password">
                        Old Password
                    </label>
                    <input type="password" id="input-old-password" name="oldPassword" class="form-control"
                           placeholder="Enter your password" required>
                </div>
                <div class="form-group form">
                    <label for="input-password">
                        New Password
                    </label>
                    <input type="password" id="input-password" name="password" class="form-control"
                           placeholder="Enter a new password" required>
                </div>
                <div class="form-group form">
                    <label for="input-confirm-password">
                        Confirm Password
                    </label>
                    <input type="password" id="input-confirm-password" name="confirmPassword" class="form-control"
                           placeholder="Confirm your password" required>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button class="btn btn-primary w-50 mb-2" type="submit" id="submit-button">
                        Change Password
                    </button>
                    <a href="/calendar/" class="btn btn-secondary w-50">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/changePassword.js"></script>
<script src="/js/confirmPassword.js"></script>
<script src="/js/error.js"></script>
