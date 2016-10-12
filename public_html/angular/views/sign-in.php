<div class="container">
	<div class="row">
		<!--
		<div class="col-xs-12 col-md-6 col-md-offset-0">
		-->
		<div class="col-xs-12 col-md-6 col-md-offset-3">
			<div class="well">
				<!--
				<form id="contact-form" action="../php/mailer.php" method="post" novalidate>
				-->


				<form name="signinForm" id="signinForm" class="form-horizontal well  sign-form" ng-controller="SigninController"
						ng-submit="signin(formData, signinForm.$valid)" novalidate>

					<h2 align="center">Sign In</h2>


					<div class="form-group"
						  ng-class="{ 'has-error': signinForm.email.$touched && signinForm.email.$invalid }">
						<label for="username">Email <span class="text-danger"></span></label>
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-user fa-fw" aria-hidden="true"></i>
							</div>
							<input type="text" class="form-control" id="email" name="email" placeholder="Email"
									 ng-model="formData.profileEmail" ng-required="true"/>
						</div>
						<div class="alert alert-danger" role="alert" ng-messages="signinForm.email.$error"
							  ng-if="signinForm.email.$touched" ng-hide="signinForm.email.$valid">
							<p ng-message="required">Please enter your Email.</p>
						</div>
					</div>

					<div class="form-group">
						<label for="password">Password<span class="text-danger"></span></label>
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-key fa-fw" aria-hidden="true"></i>
							</div>
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" ng-model="formData.password">
						</div>
					</div>


					<div class="form-group">
						<input type="checkbox" checked="checked"> Remember me


					</div>

					<div class="form-group" class="row">

						<button class="btn btn-success" type="submit"><i class="fa fa-paper-plane fa-fw"></i> Log In</button>
						<p>Forgot your <a href="#">password?</a></p>
						<!--
						<span class="psw" align="right" >Forgot your <a href="#">password?</a></span>
						<p>Forgot your <a href="#">password?</a></p>
						<span class="psw">Forgot your <a href="#">password?</a></span>
						-->

					</div>
					<pre>{{ formData | json }}</pre>


				</form>
				<h2 align="center">- Sign in with Github -</h2>
				<button class="btn btn-success" ng-click="goToGithub();" id="github-button"><i
						class="fa fa-github fa-fw fa-2x"></i> GitHub
				</button>


			</div>
		</div>


	</div> <!-- row -->
</div>




