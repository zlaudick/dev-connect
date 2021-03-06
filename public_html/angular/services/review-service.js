app.constant("REVIEW_ENDPOINT", "php/apis/review");
app.service("ReviewService", function($http, REVIEW_ENDPOINT){
	function getUrl() {
		return(REVIEW_ENDPOINT);
	}

	function getUrlForId(reviewWriteProfileId, reviewReceiveProfileId) {
		return(getUrl() + reviewWriteProfileId + reviewReceiveProfileId);
	}

	this.all = function() {
		return($http.get(getUrl()));
	}

	this.fetch = function(reviewWriteProfileId, reviewReceiveProfileId) {
		return($http.get(getUrlForId(reviewWriteProfileId, reviewReceiveProfileId)));
	};

	this.create = function(review) {
		return($http.post(getUrl(), review));
	};

	this.update = function(reviewWriteProfileId, reviewReceiveProfileId, review) {
		return($http.put(getUrlForId(reviewWriteProfileId, reviewReceiveProfileId), review));
	};

	this.destroy = function(reviewWriteProfileId, reviewReceiveProfileId) {
		return($http.delete(getUrlForId(reviewWriteProfileId, reviewReceiveProfileId)));
	};
});