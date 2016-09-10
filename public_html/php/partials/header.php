

<header ng-controller="navController">

	<!-- bootstrap breakpoint directive to control collapse behavior -->
	<bootstrap-breakpoint></bootstrap-breakpoint>
	<nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
		<div class="container">

			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<a class="navbar-brand" href="index">
					<img alt="dev connect logo" src="images/DevConnectTransparent.png">
				</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" uib-collapse="navCollapsed">

				<ul class="nav navbar-nav navbar-right center">
					<li><a href="sign-up">Sign Up</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="sign-in">Sign In</a></li>
							<li><a href="profile">My Profile</a></li>
							<li><a href="message">Messages</a></li>
						</ul>
					</li>
					<li><a href="projects">Projects</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div>
	</nav>
</header>