<?php
	require_once(dirname(__DIR__) . "/classes/autoload.php");
	require_once(dirname(__DIR__) . "/lib/xsrf.php");

	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	setXsrfCookie();
?>
<!DOCTYPE html>
<html lang="en" ng-app="DevConnect">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- set base for relative links - to enable pretty URLs -->
		<base href="/">

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Lato|Roboto" rel="stylesheet">

		<!-- Font Awesome -->
		<link type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css" rel="stylesheet" />

		<!-- Custom CSS Goes HERE -->
		<link rel="stylesheet" href="css/sticky-footer.css" type="text/css">
		<link rel="stylesheet" href="css/style.css" type="text/css">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- jQuery - required for Bootstrap Components -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

		<!--Angular JS Libraries-->
		<?php $ANGULAR_VERSION = "1.5.8";?>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/<?php echo $ANGULAR_VERSION;?>/angular.min.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/<?php echo $ANGULAR_VERSION;?>/angular-messages.min.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/<?php echo $ANGULAR_VERSION;?>/angular-route.min.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/<?php echo $ANGULAR_VERSION;?>/angular-animate.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/1.3.3/ui-bootstrap-tpls.min.js"></script>

		<!-- Load Our Angular Files -->
		<script src="angular/dev-connect.js"></script>
		<script src="angular/route-config.js"></script>
		<script src="angular/directives/bootstrap-breakpoint.js"></script>
		<script src="angular/services/image-service.js"></script>
		<script src="angular/services/message-service.js"></script>
		<script src="angular/services/profile-service.js"></script>
		<script src="angular/services/project-service.js"></script>
		<script src="angular/services/project-tag-service.js"></script>
		<script src="angular/services/review-service.js"></script>
		<script src="angular/services/signin-service.js"></script>
		<script src="angular/services/signout-service.js"></script>
		<script src="angular/services/signup-service.js"></script>
		<script src="angular/services/tag-service.js"></script>
		<script src="angular/controllers/activation-controller.js"></script>
		<script src="angular/controllers/home-controller.js"></script>
		<script src="angular/controllers/nav-controller.js"></script>
		<script src="angular/controllers/profile-controller.js"></script>
		<script src="angular/controllers/projects-controller.js"></script>
		<script src="angular/controllers/signup-controller.js"></script>
		<script src="angular/controllers/signin-controller.js"></script>
		<script src="angular/controllers/message-controller.js"></script>

		<title>DevConnect</title>
	</head>