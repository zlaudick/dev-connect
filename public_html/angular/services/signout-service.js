app.service("SignoutService", function($http) {
	this.SIGNOUT_ENDPOINT = "php/api/signout/";

	this.signout = function() {
		return($http.get(this.SIGNOUT_ENDPOINT));
	}
});