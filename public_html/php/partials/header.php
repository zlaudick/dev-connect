<header ng-controller="NavController">

	<!-- bootstrap breakpoint directive to control collapse behavior -->
	<bootstrap-breakpoint></bootstrap-breakpoint>
	<nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
		<div class="container">

			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
						  data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index">
					<img alt="dev connect logo" src="images/DevConnectTransparent.png">
				</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right center">
					<?php if(empty($_SESSION["profile"]) === true) { ?>
						<li><a href="sign-up">Sign Up</a></li>
					<?php } ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
							aria-expanded="false">Profile<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<?php if(empty($_SESSION["profile"]) === true) { ?>
								<li><a href="sign-in">Sign In</a></li>
							<?php } else { ?>
								<li><a ng-click="signout();">Sign Out</a></li>
							<?php } ?>
							<li><a href="profile">My Profile</a></li>
							<li><a href="message">Messages</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
							aria-expanded="false">Projects<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<?php if(empty($_SESSION["profile"]) === true) { ?>
							<li><a href="create-project">Create a Project</a></li>
							<?php } ?>
							<li><a ng-click="signout();">View Projects</a></li>
						</ul>
			</div>
		</div>
	</nav>
</header>