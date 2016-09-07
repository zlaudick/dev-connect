<?php
use Edu\Cnm\DevConnect\Profile;

require_once dirname(__DIR__, 2) . "/classes/autoload.php";
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


/**
 * api for the Tag class
 *
 * @author Zac Laudick <zlaudick@cnm.edu>
 **/

// verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/devconnect.ini");

	// determine which http method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$profileApproved = filter_input(INPUT_GET, "profileApproved", FILTER_VALIDATE_BOOLEAN);

	//handle POST request
	if($method === "POST") {
		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// verify id is given
		if(empty($id) === true) {
			throw(new InvalidArgumentException("id cannot be empty or negative", 405));
		}

		// verify profile is in session and is admin
		if($_SESSION["profile"] === false) {
			throw(new InvalidArgumentException("profile is not in session", 403));
		}

		// request object profileApproved
		if(empty($requestObject->profileApproved) === true) {
			throw(new InvalidArgumentException("no approval status given", 405));
		}

		// set the new approval status
		$profile = Profile::getProfileByProfileId($pdo, $id);
		if($profile === null) {
			throw(new RuntimeException("Profile does not exist.", 404));
		}
		$profile->setProfileApproved($requestObject->profileApproved);


		// send email about approval status
		if($profileApproved === true) {
			$subject = "DevConnect Profile Approved!";
			$message = "You're profile has been approved! You can now create projects!";
		} elseif($profileApproved === false) {
			$subject = "DevConnect Profile Rejected";
			$message = "Y U NO REGISTERED NON PROFIT?!?!";
		}

		$response = mailGunner("DevConnect", "gsandoval49@cnm.edu", $requestObject->profileName,
			$requestObject->profileEmail, $subject, $message);
	}

}// update reply with exception information
catch
(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
}catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return reply to front end caller
echo json_encode($reply);