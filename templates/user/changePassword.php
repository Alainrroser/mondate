<div class="center-align">
    <div class="card w-25 pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Change Password
        </h1>
        <img src="/images/logo.png"
             class="card-img-top w-25 mx-auto"
             alt="The Mondate Logo">
        <div class="card-body">
            <form action="/user/doChangePassword"
                  method="post">
                <div class="form-group form">
                    <label for="password">
                        Old Password
                    </label>
                    <input type="password"
                           id="oldPassword"
                           name="oldPassword"
                           class="form-control"
                           placeholder="Enter your password"
                           required>
                </div>
                <div class="form-group form">
                    <label for="password">
                        New Password
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control"
                           placeholder="Enter a new password"
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
                        Change Password
                    </button>
                    <a href="/calendar/"
                       class="btn btn-secondary w-50">
                        Back
                    </a>
                </div>
        </form>
        </div>
    </div>
</div>

<script type="text/javascript"
        src="/js/signUp.js"></script>
