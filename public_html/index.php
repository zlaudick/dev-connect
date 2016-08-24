<?php
/*grab current directory*/
$CURRENT_DIR = __DIR__;

/*load head-utils.php*/
require_once("php/partials/head-utils.php");
?>

</head>
<body class="sfooter">
	<div class="sfooter-content">
		<!-- begin header and nav -->
		<?php
		/*load header.php*/
		require_once("php/partials/header.php");
		?>


		<!-- welcome section -->
		<section id="welcome">
			<div class="container">
				<div class="jumbotron text-center">
					<h1>Dev-Connect</h1>
					<p>Sharing your dream for a better future with the world</p>
				</div>
			</div>
		</section> <!--welcome-->




		</div><!--container-->
		<!--</section>thumbnails-->
	</div><!--/.sfooter-content-->

	<!--begin footer -->
	<?php
	/*load footer.php*/
	require_once("php/partials/footer.php");
	?>

</body>
</html>