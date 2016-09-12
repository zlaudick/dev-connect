<div class="container">
	<div class="row">
		<!--
		<div class="col-xs-12 col-md-6 col-md-offset-0">
		-->
		<div class="col-xs-12 col-md-5 col-md-offset-3">
			<!--
			<form id="contact-form" action="../php/mailer.php" method="post" novalidate>
			-->


			<form  name="signinForm" id="signinForm" class="form-horizontal well" ng-controller="SigninController" ng-submit="submit(signinData, signinForm.$valid)"method="post" novalidate>

				<h2 align="center">Sign In</h2>





				<div class="form-group" ng-class="{ 'has-error': signinForm.email.$touched && signinForm.email.$invalid }">
					<label for="username">Email <span class="text-danger"></span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-user fa-fw" aria-hidden="true"></i>
						</div>
						<input type="text" class="form-control" id="email" name="email" placeholder="Email" ng-model="signinData.email" ng-required="true" />
					</div>
					<div class="alert alert-danger" role="alert" ng-messages="signinForm.email.$error" ng-if="signinForm.email.$touched" ng-hide="signinForm.email.$valid">
						<p ng-message="required">Please enter your Email.</p>
					</div>
				</div>

				<div class="form-group">
					<label for="password">Password<span class="text-danger"></span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-key fa-fw" aria-hidden="true"></i>
						</div>
						<input type="text" class="form-control" id="password" name="password" placeholder="Password">
					</div>
				</div>


				<div class="form-group">
					<input type="checkbox" checked="checked"> Remember me


				</div>

				<div class="form-group" class="row">

					<button class="btn btn-success" type="submit"><i class="fa fa-paper-plane"></i> Log In</button>
					<p>Forgot your <a href="#">password?</a></p>
					<!--
					<span class="psw" align="right" >Forgot your <a href="#">password?</a></span>
					<p>Forgot your <a href="#">password?</a></p>
					<span class="psw">Forgot your <a href="#">password?</a></span>
					-->

				</div>






				<h2 align="center">-  or  -<p> </p><a align="center" href="https://github.com/login">
						<img border="0" alt="GitHub" src="images/GitHub.png" width="313" height="92">
					</a>
				</h2>
			</form>


		</div>




	</div> <!-- row -->
</div>




