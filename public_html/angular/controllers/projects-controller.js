app.controller('ProjectsController', ["$scope", "ProjectService", "ProjectTagService", "TagService", function($scope, ProjectService, ProjectTagService, TagService) {
	// state variables
	$scope.projects = [];
	$scope.projectTag = [];
	$scope.tag = [];

	$scope.alerts = [];

	/**
	 * accepts or rejects the promise from the project service
	 **/
	$scope.getAllProjects = function() {
		// call the project service to fetch the projects
		ProjectService.all()
			.then(function(result) {
				// the promise is accepted
				if(result.data.status = 200) {
					$scope.projects = result.data.data;
				} else{
					// we got data with a non 200 status, display an error
					$scope.alerts[0] = {type: "danger", msg: result.data.message};
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
					$scope.alerts[0] = {type: "danger", msg: result.data.message};
				}
			});
	};

	// load the projects on load by retrying when the data is null
	if($scope.projects.length === 0) {
		$scope.getAllProjects();
	}
}]);
