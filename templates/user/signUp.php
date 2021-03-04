<div class = "center-align">
	<div class = "card">
		<img src = "/images/logo.png"
		     class = "card-img-top"
		     alt = "The Mondate Logo">
		<div class = "card-body">
			<form action="/user/doCreate" method="post">
				<div class = "form-group form">
					<label for = "e-mail">
						E-Mail
					</label>
					<input type = "email"
					       name = "email"
					       class = "form-control"
					       placeholder = "Enter your e-mail">
				</div>
				<div class = "form-group form">
					<label for = "password">
						Password
					</label>
					<input type = "password"
					       name = "password"
					       class = "form-control"
					       placeholder = "Enter your password">
				</div>
				<div class = "form-group form">
					<label for = "password">
						Confirm password
					</label>
					<input type = "password"
					       name = "confirm_password"
					       class = "form-control"
					       placeholder = "Enter your password">
				</div>
                <button class="btn btn-primary" type="submit">
                    Sign Up
                </button>
			</form>
		</div>
	</div>
</div>
