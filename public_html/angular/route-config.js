// configure our routes
app.config(function($routeProvider, $locationProvider) {
	$routeProvider

	// route for the home page
		.when('/', {
			controller: 'HomeController',
			templateUrl: 'angular/views/home.php'
		})

		// route for the projects page
		.when('/profile', {
			controller: 'ProfileController',
			templateUrl: 'angular/views/profile.php'
		})

		.when('/activation/:profileActivation', {
			controller: 'ActivationController',
			templateUrl: 'angular/views/activation.php'
		})

		// route for the sign up page
		.when('/sign-up', {
			controller: 'SignupController',
			templateUrl: 'angular/views/sign-up.php'
		})

		// route for the projects page
		.when('/projects', {
			controller: 'ProjectsController',
			templateUrl: 'angular/views/projects.php'
		})

		// route for the create a project page
		.when('/create-project', {
			controller: 'CreateProjectController',
			templateUrl: 'angular/views/create-project.php'
		})

		// route for the message page
		.when('/message', {
			controller: 'MessageController',
			templateUrl: 'angular/views/message.php'
		})

		// route for the message page
		.when('/sign-in', {
			controller: 'SigninController',
			templateUrl: 'angular/views/sign-in.php'
		})

		// otherwise redirect to home
		.otherwise({
			redirectTo: '/'
		});

	//use the HTML5 History API
	$locationProvider.html5Mode(true);
});