// configure our routes
app.config(function($routeProvider, $locationProvider) {
	$routeProvider

	// route for the home page
		.when('/', {
			controller: 'homeController',
			templateUrl: 'angular/views/home.php'
		})

		// route for the projects page
		.when('/profile', {
			controller: 'profileController',
			templateUrl: 'angular/views/profile.php'
		})

		.when('/activation/:profileActivation', {
			controller: 'activationController',
			templateUrl: 'angular/views/activation.php'
		})

		// route for the sign up page
		.when('/sign-up', {
			controller: 'signupController',
			templateUrl: 'angular/views/sign-up.php'
		})

		// route for the projects page
		.when('/projects', {
			controller: 'projectsController',
			templateUrl: 'angular/views/projects.php'
		})

		// route for the message page
		.when('/message', {
			controller: 'messageController',
			templateUrl: 'angular/views/message.php'
		})

		// otherwise redirect to home
		.otherwise({
			redirectTo: '/'
		});

	//use the HTML5 History API
	$locationProvider.html5Mode(true);
});