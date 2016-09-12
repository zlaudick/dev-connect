app.controller('ProjectsController', ["$scope", "ProjectService", "TagService", function($scope, ProjectService, TagService) {
	// state variables
	$scope.project = null;
	$scope.tag = null;

	/**
	 * accepts or rejects the promise from the project service
	 **/
	$scope.getProjectService = function() {
		// call the project service to fetch the projects
		ProjectService.fetch()
			.then(function(result) {
				// the promise is accepted
				if(result.data.status = 200) {
					$scope.project = result.data.data;
				} else{
					// we got data with a non 200 status, display an error
					$scope.project = ["Service did not return data"]
				}
			});
	};

	/**
	 * accepts or rejects the promise from the tag service
	 **/
	$scope.getTagService = function() {
		// call the project service to fetch the tags
		TagService.fetch()
			.then(function(result) {
				// the promise is accepted
				if(result.data.status = 200) {
					$scope.tag = result.data.data;
				} else {
					// we got data with a non 200 status, display an error
					$scope.tag = ["Service did not return data"]
				}
			});
	};
}]);
