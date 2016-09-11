<div class="container">
	<div class="row">
		<!--
		<div class="col-xs-12 col-md-6 col-md-offset-0">
		-->
		<div class="col-xs-12 col-md-6  col-md-offset-2">
			<!--
			<form id="contact-form" action="../php/mailer.php" method="post" novalidate>
			-->

			<!--Begin Contact Form-->
			<form class="form-horizontal well" id="contact-form"  novalidate>
				<h2>Contact $profileName:</h2>
				<!--
				<div class="form-group">
					<label for="email">Email <span class="text-danger">*</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</div>
						<input type="email" class="form-control" id="email" name="email" placeholder="Email">
					</div>
				</div>
				-->
				<div class="form-group">
					<label for="subject">Subject</label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-pencil" aria-hidden="true"></i>
						</div>
						<input type="text" class="form-control" id="subject" name="subject" placeholder="Subject"
								 ng-model="formData.subject" ng-minlength="2" ng-maxlength="128" ng-required="true">
					</div>


					<div class="alert alert-danger" role="alert" ng-messages="contact-form.subject.$error"
						  ng-if="contact-form.subject.$touched" ng-hide="contact-form.subject.$valid">
						<p ng-message="min">Your message is too small.</p>
						<p ng-message="max">Your message is too large.</p>
						<p ng-message="required">Please enter a message.</p>
					</div>



				</div> <!-- form-group -->


				<div class="form-group">
					<label for="message">Message <span class="text-danger">*</span></label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-comment" aria-hidden="true"></i>
						</div>
						<textarea class="form-control" rows="5" id="message" name="message"
									 placeholder="Message (2000 characters max)"
									 ng-model="formData.message" ng-minlength="2" ng-maxlength="2000" ng-required="true"></textarea>
					</div>

					<div class="alert alert-danger" role="alert" ng-messages="contact-form.message.$error"
						  ng-if="contact-form.message.$touched" ng-hide="contact-form.message.$valid">
						<p ng-message="min">Your message is too small.</p>
						<p ng-message="max">Your message is too large.</p>
						<p ng-message="required">Please enter a message.</p>
					</div>


				</div> <!-- form group -->

				<!-- reCAPTCHA
				<div class="g-recaptcha" data-sitekey="--YOUR RECAPTCHA SITE KEY--"></div>
				-->

				<button class="btn btn-success" type="submit"><i class="fa fa-paper-plane"></i> Send</button>
				<button class="btn btn-warning" type="reset"><i class="fa fa-ban"></i> Reset</button>

				<!--empty area for form error/success output-->
				<div class="row">
					<div class="col-xs-6 col-md-6 col-md-offset-5">
						<div id="output-area"></div>
					</div>
				</div>

			</form>



		</div> <!-- row -->
	</div>