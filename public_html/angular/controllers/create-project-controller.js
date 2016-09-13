app.controller("CreateProjectController", ["$scope", "ProjectService", "TagService", function($scope, ProjectService, TagService) {
	// state variables
	$scope.projectData = [];
	$scope.alerts = [];

	// methods
	$scope.createProject = function() {
		return("You created a project!");
	};

	$scope.createTag = function () {
		return("You added a tag to your project!");
	};

	/**
	 * Accept or reject the promise from the Project Service
	 **/
	$scope.createProjectFromService = function() {
		// call the Project Service to fetch the project creation procedure
		ProjectService.fetch()
			.then(function(result) {
				// the promise is accepted
				if(result.data.status === 200) {
					$scope.createProject = result.data.status;
				} else {
					// we got data with a non 200 message, display an error
					$scope.alerts[0] = {type: "danger", msg: result.data.message};
				}
			});
	};

	/**
	 * Accept or reject the promise from the Tag Service
	 **/
	$scope.createTagFromService = function() {
		// call the Tag Service to fetch the tag creation procedure
		TagService.fetch()
			.then(function(result) {
				// the promise is accepted
				if(result.data.status === 200) {
					$scope.createTag = result.data.status;
				} else {
					// we got data with a non 200 message, display an error
					$scope.alerts[0] = {type: "danger", msg: result.data.message};
				}
			});
	};
}]);