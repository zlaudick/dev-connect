app.constant("PROJECT_ENDPOINT", "php/apis/project/");
app.service("ProjectService", function($http, PROJECT_ENDPOINT) {
	function getUrl() {
		return(PROJECT_ENDPOINT);
	}

	function getUrlForId(projectId){
		return(getUrl() + projectId);
	}

	this.all = function() {
		return($http.get(getUrl()));
	};

	this.fetch = function(projectId) {
		return($http.get(getUrlForId(projectId)));
	};

	this.fetchByProjectByProjectContentOrTagName = function(searchInput) {
		return($http.get(getUrl() + "?searchInput=" + searchInput));
	};

	this.create = function(project) {
		return($http.post(getUrl(), project));
	};

	this.update = function(projectId, project) {
		return($http.put(getUrlForId(projectId), project));
	};

	this.destroy = function(projectId) {
		return($http.delete(getUrlForId(projectId)));
	};
});