app.controller("SignupController", ["$scope", "$window", "SignupService", function($scope, $window, SignupService) {
	$scope.formData = {};
	$scope.alerts = [];

	$scope.goToGithub = function() {
		$window.location.href = "php/apis/github-login/";
	};

	$scope.signup = function(signupData, validated) {
		if(validated === true) {
			SignupService.signup(signupData)
				.then(function(result) {
					if(result.data.status === 200) {
						$scope.formData = result.data.data;
						$scope.alerts[0] = {type: "success", msg: result.data.message};
					} else {
						$scope.alerts[0] = {type: "danger", msg: result.data.message};
					}
				});
		}
	};
}]);
