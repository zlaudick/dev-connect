app.constant("PROJECT_TAG_ENDPOINT", "php/apis/project-tag/");
app.service("ProjectTagService", function($http, PROJECT_TAG_ENDPOINT) {
	function getUrl() {
		return(PROJECT_TAG_ENDPOINT);
	}

	function getUrlForId(projectTagProjectId, projectTagTagId){
		return(getUrl() + projectTagProjectId + projectTagTagId);
	}

	this.all = function() {
		return($http.get(getUrl()));
	};

	this.fetch = function(projectTagProjectId, projectTagTagId) {
		return($http.get(getUrlForId(projectTagProjectId, projectTagTagId)));
	};

	this.create = function(projectTag) {
		return($http.post(getUrl(), projectTag));
	};

	this.destroy = function(projectTagProjectId, projectTagTagId) {
		return($http.delete(getUrlForId(projectTagProjectId, projectTagTagId)));
	};
});