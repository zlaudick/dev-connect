app.constant("SIGNIN_ENDPOINT", "php/apis/signin/");
app.service("SigninService", function($http, SIGNIN_ENDPOINT) {
	function getUrl() {
		return(SIGNIN_ENDPOINT);
	}

	this.signin = function(signinData) {
		console.log("inside signin service");
		return($http.post(getUrl(), signinData));
	};
});