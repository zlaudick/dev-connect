app.controller("SignoutController", ["$scope", "$window", "SignoutService", function($scope, $window, SignoutService) {
	/**
	 * Accepts or rejects the promise from the Signout service
	 **/
	$scope.getSignoutFromService = function() {
		// call the Signout service to fetch the signout procedure
		SignoutService.fetch()
			.then(function(result) {
				// the promise is accepted
				if(result.data.status === 200) {
					$window.location.reload();
				}
			});
	};
}]);