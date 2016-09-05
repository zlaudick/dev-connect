app.constant("PROJECT_ENDPOINT", "php/apis/project");
app.service("ProjectService", function($http, PROJECT_ENDPOINT) {
	function getUrl(projectId) {
		return(PROJECT_ENDPOINT);
	}

	function getUrlForId(projectId){
		return(getUrl() + projectId);
	}

	this.all = function() {
		return($http.get(getUrl()));
	};

	this.fetch = function() {
		return($http.get(getUrlForId(projectId)));
	};

	this.create = function() {
		return($http.post(getUrl(), project));
	};

	this.update = function() {
		return($http.put(getUrlForId(projectId), project));
	};

	this.destroy = function() {
		return($http.delete(getUrlForId(projectId)));
	};
});