<body class="sfooter">
	<div class="sfooter-content">
		<!-- begin header and nav -->
		<?php
		/*load header.php*/
		require_once(dirname(__DIR__) . "/php/partials/header.php");
		?>
		<h1>This is the Bloody Projects Page</h1>


		<!-- welcome section -->
		<section id="welcome">
			<div class="container">
				<div class="jumbotron text-center">
					<h1>Dev-Connect</h1>
					<p>Profile Page</p>
				</div>
			</div>
		</section> <!--welcome-->


	</div><!--container-->
	<!--</section>thumbnails-->
	</div><!--/.sfooter-content-->


	<div class="container">
		<div class="row">
			<div class="col-md-4 col-sm-12">
				<h2 class="text-center">$profileName</h2>
			</div><!--col-md-6 col-sm-12-->
			<div class="col-md-8 col-sm-12">
				<p class="text-center">$jobtitle from $city, $state | $email@$domain.com </p>
			</div><!--col-md-6 col-sm-12-->
		</div>

		<div class="row">
			<div class="col-md-4 col-sm-12">
				<img src="../images/profile.PNG" alt="Profile"
					  class="img-responsive img-square img-thumbnail center-block img-bot-margin">
			</div><!--col-md-6 col-sm-12-->

			<div class="col-md-8 col-sm-12">

				<p>Hello!

					My name is Devon Beets and I am a web developer from Albuquerque, NM. I have a passion for beautiful
					design and I love to work on a wide variety of projects!

					I especially hold a deep place in my heart for people with physical disabilities, my mother has worked
					with them since I was young and I have aloways looked up to her and her ability to give back to her
					community with her work and would like to do the same! If you would like to request my service, I can
					reached at the following contact information below! :)

					-Devon B</p>

			</div><!--col-md-6 col-sm-12-->
		</div> <!--row-->
	</div><!--container-->

	<!--begin footer -->
	<?php
	/*load footer.php*/
	require_once(dirname(__DIR__) . "/php/partials/footer.php");
	?>

</body>