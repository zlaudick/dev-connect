<?php

require_once dirname(__DIR__, 2) . "/classes/autoload.php";
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DevConnect\Profile;

/**
 * API for the sign up process
 *
 * @author Devon Beets <dbeetzz@gmail.com>
 **/

//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare the default message
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//grab the MySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/dev-connect.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$profileName = filter_input(INPUT_POST, "profileName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileEmail = filter_input(INPUT_POST, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$confirmPassword = filter_input(INPUT_POST, "confirmPassword", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//perform the actual post
	if($method === "POST"){

		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//check if the user selected developer or non-profit organization for account type and offer developers to sign up with Github?

		//check that the user fields that are required have been sent and filled out correctly
		if(empty($requestObject->profileName) === true) {
			throw(new \InvalidArgumentException("Please fill in a name."));
		} elseif(empty($requestObject->profileEmail) === true) {
			throw(new \InvalidArgumentException("Please fill in an email."));
		} elseif(empty($requestObject->password) === true) {
			throw(new \InvalidArgumentException("Please fill in a password."));
		} elseif(empty($requestObject->confirmPassword) === true) {
			throw(new \InvalidArgumentException("Please confirm the password"));
		} elseif($requestObject->password !== $requestObject->confirmPassword) {
			throw(new \InvalidArgumentException("Password does not match"));
		}
	}

	//create a new profile for the user
	$profile = new Profile(null, $profileName, $profileEmail);
	$profile->insert($pdo);

	//create a new user, password salt and hash, and activation token
	$activation = bin2hex(random_bytes(16));
	$salt = bin2hex(random_bytes(32));


}