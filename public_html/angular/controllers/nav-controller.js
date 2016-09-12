app.controller("NavController", ["$http", "$scope", "$window", "SignoutService", function($http, $scope, $window, SignoutService) {
	$scope.breakpoint = null;
	$scope.navCollapsed = null;

	// collapse the navbar if the screen is changed to a extra small screen
	$scope.$watch("breakpoint", function() {
		$scope.navCollapsed = ($scope.breakpoint === "xs");
	});

	$scope.signout = function() {
		// call the Signout service to fetch the signout procedure
		SignoutService.signout()
			.then(function(result) {
				// the promise is accepted
				if(result.data.status === 200) {
					$window.location.reload();
				}
			});
	};
}]);
