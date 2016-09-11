app.controller("AngularFormController", ["$scope", function($scope) {
	/**
	 * state variable to store the alerts generated from the submit event
	 * @type {Array}
	 **/
	$scope.alerts = [];

	/**
	 * state variable to keep track of the data entered into the form fields
	 * @type {Object}
	 **/
	$scope.formData = {"name": [], "subject": [], "message": []};


	/**
	 * method to reset form data when the submit and cancel buttons are pressed
	 **/
	$scope.reset = function() {
		$scope.formData = {"name": [], "subject": [], "message": []};
		$scope.contact-form.$setUntouched();
		$scope.contact-form.$setPristine();
	};

	/**
	 * method to process the action from the submit button
	 *
	 * @param formData object containing submitted form data
	 * @param validated true if passed validation, false if not
	 **/
	$scope.submit = function(formData, validated) {
		if(validated === true) {
			$scope.alerts[0] = {
				type: "success",
				msg: "Well done! You have found the submit button on this form and clicked it."
			};
		} else {
			$scope.alerts[0] = {
				type: "danger",
				msg: "Oh snap! You clicked the submit button while the form has invalid data. Check yourself before you wreck yourself!"
			};
		}
		$scope.reset();
	};
}]);