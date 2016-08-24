<?php

require_once dirname(__DIR__, 2) . "/classes/autoload.php";
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\DevConnect\Profile;


/**
 * api for signing in to DevConnect
 *
 * @author Devon Beets <dbeetzz@gmail.com>
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
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/dev-connect.ini");

	// determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//perform the actual post
	if($method === "POST") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//check that necessary fields have been filled in and sanitize
		if(empty($requestObject->profileEmail) === true) {
			throw(new \InvalidArgumentException("Please fill in an email", 405));
		} else {
			$profileEmail = filter_input($requestObject->profileEmail, FILTER_SANITIZE_EMAIL, FILTER_FLAG_NO_ENCODE_QUOTES);
		}

		if(empty($requestObject->password) === true) {
			throw(new \InvalidArgumentException("Please fill in a password", 405));
		} else {
			$password = filter_input($requestObject->password, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		}

		//create the user
		$profile = Profile::getProfileByProfileEmail($pdo, $profileEmail);

		if(empty($profile) === true) {
			throw(new \InvalidArgumentException("Email or password is incorrect"));
		}

		//hash for the password
		$hash =  hash_pbkdf2("sha512", $password, $user->getUserSalt(), 262144);

		//verify the hash is correct
		if($hash !== $profile->getProfileHash()) {
			throw (new \InvalidArgumentException("Email or password is incorrect"));
		}
	}







} catch(\Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(\TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);