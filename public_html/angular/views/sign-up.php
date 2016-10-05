<div class="container">
	<div class="row">
		<div class="col-xs-12 col-md-6  col-md-offset-3">

			<div class="well">

			<form name="signupForm" class="form-horizontal well sign-form" ng-controller="SignupController" ng-submit="signup(formData, signupForm.$valid);" id="contact-form" novalidate>

				<h2>Create an account</h2>
				<p>Are you a Developer or do you represent an organization?</p>
				<input type="radio" name="accountType" value="D" ng-model="formData.profileAccountType" checked> Developer<br>
				<input type="radio" name="accountType" value="O" ng-model="formData.profileAccountType"> Non-Profit Organization<br>

				<div class="form-group" ng-class="{ 'has-error': signupForm.name.$touched && signupForm.name.$invalid }">
					<label for="profileName">Name <span class="text-danger">*</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-user fa-fw" aria-hidden="true"></i>
						</div>
						<input type="text" class="form-control" id="profileName" name="profileName" placeholder="Name" ng-model="formData.profileName" ng-required="true">
					</div>
					<div class="alert alert-danger" role="alert" ng-messages="signupForm.profileName.$error" ng-if="signupForm.profileName.$touched" ng-hide="signupForm.profileName.$valid">
						<p ng-message="required">Please enter your name.</p>
					</div>
				</div>

				<div class="form-group" ng-class="{ 'has-error': signupForm.email.$touched && signupForm.email.$invalid }">
					<label for="profileEmail">Email <span class="text-danger">*</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-envelope fa-fw" aria-hidden="true"></i>
						</div>
						<input type="email" class="form-control" id="profileEmail" name="profileEmail" placeholder="Email" ng-model="formData.profileEmail" ng-required="true">
					</div>
					<div class="alert alert-danger" role="alert" ng-messages="signupForm.profileEmail.$error" ng-if="signupForm.profileEmail.$touched" ng-hide="signupForm.profileEmail.$valid">
						<p ng-message="required">Please enter your email.</p>
					</div>
				</div>

				<div class="form-group" ng-class="{ 'has-error': signupForm.password.$touched && signupForm.password.$invalid }">
					<label for="password">Password<span class="text-danger"> *</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-key fa-fw" aria-hidden="true"></i>
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
							<i class="fa fa-key fa-fw" aria-hidden="true"></i>
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
				<pre>{{ formData | json }}</pre>

			</form>
				<h2>Sign-up with GitHub!</h2>
				<button class="btn btn-success" ng-click="goToGithub();" id="github-button"><i class="fa fa-github fa-fw fa-2x"></i> GitHub</button>


		</div>


	</div> <!-- row -->
</div>




