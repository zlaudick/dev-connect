app.controller("SignupController", [$scope, "SignupService", function($scope, SignupService) {
	$scope.signupData = null;
	$scope.alerts = [];

	$scope.signup = function() {
		SignupService.signup()
			.then(function(result) {
				if(result.data.status === 200) {
					$scope.signup = result.data.data;
				} else {
					$scope.alerts[0] = {type: "danger", msg: result.data.message};
				}
			});
	};
}]);
