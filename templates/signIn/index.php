<div class = "center-align">
	<div class = "card">
		<img src = "/images/logo.png"
		     class = "card-img-top w-50 mx-auto"
		     alt = "The Mondate Logo">
		<div class = "card-body">
			<form action="/user/doSignIn" method="post">
				<div class = "form-group form">
					<label for = "email">
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
				<button class="btn btn-primary" type="submit">
					Sign In
				</button>
				<a href = "/signUp/"
				   class = "btn btn-primary">
					Sign Up
				</a>
			</form>
		</div>
	</div>
</div>