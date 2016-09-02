<?php
/*grab current directory*/
$CURRENT_DIR = __DIR__;

/*load head-utils.php*/
require_once("php/partials/head-utils.php"); ?>

<body class="sfooter">
	<div class="sfooter-content">

		<!-- Begin header and Nav -->
		<?php require_once("php/partials/header.php"); ?>


		<!-- welcome section -->
		<section id="welcome">
			<div class="container">

				<!-- angular view directive -->
				<!-- <div ng-view></div> -->


				<div class="jumbotron text-center">
					<h1>Dev-Connect</h1>
					<p>Sharing your dream for a better future with the world</p>
				</div>
			</div>
		</section>


		<!-- End sfooter-content-->
	</div>


	<!--begin footer -->
	<?php require_once("php/partials/footer.php"); ?>

</body>
</html>