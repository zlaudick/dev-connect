app.constant("PROFILE_ENDPOINT", "php/apis/profile/");
app.service("ProfileService", function($http, PROFILE_ENDPOINT) {
	function getUrl() {
		return(PROFILE_ENDPOINT);
	}

	function getUrlForId(profileId) {
		return(getUrl() + profileId);
	}

	this.fetch = function(profileId) {
		return($http.get(getUrlForId(profileId)));
	};

	this.update = function(profileId, profile) {
		return($http.put(getUrlForId(profileId), profile));
	};

	this.destroy = function(profileId) {
		return($http.delete(getUrlForId(profileId)));
	};
});
