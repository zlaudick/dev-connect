<div class="container">
	<div class="row">
		<!--
		<div class="col-xs-12 col-md-6 col-md-offset-0">
		-->
		<div class="col-xs-12 col-md-6  col-md-offset-3">
			<!--
			<form id="contact-form" action="../php/mailer.php" method="post" novalidate>
			-->
			<div class="well">


			<form name="signupForm" class="form-horizontal well sign-form" ng-controller="SignupController" ng-submit="submit(formData, signupForm.$valid);" id="contact-form" action="../../php/mail.php" method="post" novalidate>

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

				<div class="form-group" ng-class="{ 'has-error': signupForm.email.$touched && signupForm.email.$invalid }">
					<label for="email">Email <span class="text-danger">*</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</div>
						<input type="email" class="form-control" id="email" name="email" placeholder="Email" ng-model="formData.email" ng-required="true">
					</div>
					<div class="alert alert-danger" role="alert" ng-messages="signupForm.email.$error" ng-if="signupForm.email.$touched" ng-hide="signupForm.email.$valid">
						<p ng-message="required">Please enter your email.</p>
					</div>
				</div>

				<div class="form-group" ng-class="{ 'has-error': signupForm.password.$touched && signupForm.password.$invalid }">
					<label for="password">Password<span class="text-danger"> *</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-key" aria-hidden="true"></i>
						</div>
						<input type="password" class="form-control" id="password" name="password" placeholder="Password" ng-model="formData.password" ng-required="true">
					</div>
					<div class="alert alert-danger" role="alert" ng-messages="signupForm.password.$error" ng-if="signupForm.password.$touched" ng-hide="signupForm.password.$valid">
						<p ng-message="required">Please enter a password.</p>
					</div>
				</div>

				<div class="form-group" ng-class="{ 'has-error': signupForm.confirmPassword.$touched && signupForm.confirmPassword.$invalid }">
					<label for="confirmPassword">Confirm Password<span class="text-danger"> *</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-key" aria-hidden="true"></i>
						</div>
						<input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" ng-model="formData.confirmPassword" ng-required="true">
					</div>
					<div class="alert alert-danger" role="alert" ng-messages="signupForm.confirmPassword.$error" ng-if="signupForm.confirmPassword.$touched" ng-hide="signupForm.confirmPassword.$valid">
						<p ng-message="required">Please confirm your password.</p>
					</div>
				</div>

				<button class="btn btn-success" type="submit"><i class="fa fa-paper-plane"></i> Submit</button>
				<button class="btn btn-warning" type="reset"><i class="fa fa-ban"></i> Reset</button>
				<div id="output-area"></div>

			</form>
				<h2>Sign-up with GitHub!</h2>
				<button class="btn btn-success" ng-click="goToGithub();" id="github-button"><i class="fa fa-github fa-fw fa-2x"></i> GitHub</button>


		</div>


	</div> <!-- row -->
</div>




