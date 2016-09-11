<div class="container">
	<div class="row">
		<!--
		<div class="col-xs-12 col-md-6 col-md-offset-0">
		-->
		<div class="col-xs-12 col-md-6  col-md-offset-0">
			<!--
			<form id="contact-form" action="../php/mailer.php" method="post" novalidate>
			-->


			<form name="signupForm" class="form-horizontal well" ng-controller="SignupController" ng-submit="submit(formData, signupForm.$valid);" id="contact-form" action="../php/mailer.php" method="post" novalidate>

				<h2>Create an account</h2>


				<p>Are you a Developer or do you represent an organization?</p>

				<input type="radio" name="gender" value="male" ng-model="formData.gender" checked> Developer<br>
				<input type="radio" name="gender" value="female" ng-model="formData.gender"> Non-Profit Organization<br>

				<div class="form-group" ng-class="{ 'has-error': signupForm.name.$touched && signupForm.name.$invalid }">
					<label for="name">Name <span class="text-danger">*</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-user" aria-hidden="true"></i>
						</div>
						<input type="text" class="form-control" id="name" name="name" placeholder="Name" ng-model="formData.name" ng-required="true">
					</div>
					<div class="alert alert-danger" role="alert" ng-messages="signupForm.name.$error" ng-if="signupForm.name.$touched" ng-hide="signupForm.name.$valid">
						<p ng-message="required">Please enter your name.</p>
					</div>
				</div>

				<div class="form-group">
					<label for="email">Email <span class="text-danger">*</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</div>
						<input type="email" class="form-control" id="email" name="email" placeholder="Email">
					</div>
				</div>

				<div class="form-group">
					<label for="username">User Name <span class="text-danger">*</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-user" aria-hidden="true"></i>
						</div>
						<input type="text" class="form-control" id="username" name="username" placeholder="Username">
					</div>
				</div>

				<div class="form-group">
					<label for="password">Password<span class="text-danger"> *</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-key" aria-hidden="true"></i>
						</div>
						<input type="text" class="form-control" id="password" name="password" placeholder="Password">
					</div>
				</div>

				<button class="btn btn-success" type="submit"><i class="fa fa-paper-plane"></i> Submit</button>
				<button class="btn btn-warning" type="reset"><i class="fa fa-ban"></i> Reset</button>
				<div id="output-area"></div>
			</form>


		</div>

		<div class="col-xs-12 col-md-5 ">
			<form class="form-horizontal well" id="contact-form" action="../php/mailer.php" method="post" novalidate>

				<h2>
					Or, if you're a developer: <p></p><a href="https://github.com/login">
						<img border="0" alt="GitHub" src="images/GitHub.png" width="313" height="92">
					</a>
				</h2>

			</form>

		</div>


	</div> <!-- row -->
</div>




