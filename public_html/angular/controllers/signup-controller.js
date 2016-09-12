app.controller("SignupController", ["$scope", "$window", "SignupService", function($scope, $window, SignupService) {
	$scope.signupData = null;
	$scope.alerts = [];

	$scope.goToGithub = function() {
		$window.location.href = "php/apis/github-login/";
	};

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
