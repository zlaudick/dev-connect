app.constant("REVIEW_ENDPOINT", "php/apis/review");
app.service("ReviewService", function($http, REVIEW_ENDPOINT){
	function getUrl(reviewWriteProfileId, reviewReceiveProfileId) {
		return(REVIEW_ENDPOINT);
	}

	function getUrlForId(reviewWriteProfileId, reviewReceiveProfileId) {
		return(getUrl() + reviewWriteProfileId + reviewReceiveProfileId);
	}

	this.all = function() {
		return($http.get(getUrl()));
	}

	this.fetch = function() {
		return($http.get(getUrlForId(reviewWriteProfileId, reviewReceiveProfileId)));
	};

	this.create = function() {
		return($http.post(getUrl(), review));
	};

	this.update = function() {
		return($http.put(getUrlForId(reviewWriteProfileId, reviewReceiveProfileId), review));
	};

	this.destroy = function() {
		return($http.delete(getUrlForId(reviewWriteProfileId, reviewReceiveProfileId)));
	};
});