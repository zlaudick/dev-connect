app.controller("SigninController", ["$scope", "SigninService", function($scope, SigninService) {
	// state variables
	$scope.signinData = null;
	$scope.alerts = [];

	// methods
	$scope.signin = function() {
		return("You signed in!")
	};

	/**
	 * Accepts or rejects the promise from the Signin service
	 **/
	$scope.getSigninFromService = function() {
		// call the Signin service to fetch the signin procedure
		SigninService.fetch()
			.then(function(result) {
				// the promise is accepted
				if(result.data.status === 200) {
					$scope.signin = result.data.status;
				} else {
					// we got data with a non 200 message, display an error
					$scope.alerts[0] = {type: "danger", msg: result.data.message};
				}
			});
	};
}]);

