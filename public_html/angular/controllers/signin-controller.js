app.controller("SigninController", ["$scope", "$window", "SigninService", function($scope, $window, SigninService) {
	// state variables
	$scope.signinData = {};
	$scope.alerts = [];

	$scope.goToGithub = function() {
		$window.location.href = "php/apis/github-login/";
	};

	// methods
	// $scope.signin = function() {
	// 	return("You signed in!")
	// };



	/**
	 * Accepts or rejects the promise from the Signin service
	 **/
	$scope.signin = function(signinData, validated) {
		// call the Signin service to fetch the signin procedure
		if(validated === true) {
			SigninService.signin(signinData)
				.then(function(result) {
					// the promise is accepted
					if(result.data.status === 200) {
						$scope.signin = result.data.status;
						$scope.alerts[0] = {type: "success", msg: result.data.message};
						$window.location.href = "/";
					} else {
						// we got data with a non 200 message, display an error
						$scope.alerts[0] = {type: "danger", msg: result.data.message};
					}
				});
		}
	};
}]);

