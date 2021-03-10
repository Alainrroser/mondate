<div class="center-align">
    <div class="card w-25 pt-5 shadow-sm">
        <img src="/images/logo.png"
             class="card-img-top w-25 mx-auto"
             alt="The Mondate Logo">
        <div class="card-body">
            <form action="/user/doChangePassword"
                  method="post">
                <div class="form-group form">
                    <label for="password">
                        Password
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control"
                           placeholder="Enter your password"
                           required>
                </div>
                <div class="form-group form">
                    <label for="password">
                        Confirm Password
                    </label>
                    <input type="password"
                           id="confirm_password"
                           name="confirm_password"
                           class="form-control"
                           placeholder="Enter your password"
                           required>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button class="btn btn-primary w-50 mb-2"
                            type="submit">
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
