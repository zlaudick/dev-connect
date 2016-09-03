app.controller("activationController", ["$routeParams", "$scope", function($routeParams, $scope) {
	/**
	 * TEST METHOD! TALCOTT THIS IN THE FINAL PRODUCT!!!!
	 */
	$scope.getProfileActivation = function() {
		return($routeParams.profileActivation);
	};
}]);