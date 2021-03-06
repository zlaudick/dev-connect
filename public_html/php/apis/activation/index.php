<?php
require_once dirname(dirname(__DIR__)) . "/classes/autoload.php";
require_once dirname(dirname(__DIR__)) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once(dirname(__DIR__, 4) . "/vendor/autoload.php");
use Edu\Cnm\DevConnect\Profile;

/**
 * controller/api for activation token
 *
 * @author Marcelo Ibarra
 */
//verify the xsrf challenge
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare a empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//Grab MySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/devconnect.ini");

	//determine which http method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//get the Sign Up based on the given field
	$profileActivationToken = filter_input(INPUT_GET, "profileActivationToken", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//handle REST calls, while allowing administrators to access database modifying methods
	if($method === "GET") {
		//set Xsrf cookie
		setXsrfCookie("/");

		if(empty($profileActivationToken)) {
			throw(new \RangeException ("No ActivationToken Code"));
		}

		$profile = Profile::getProfileByProfileActivationToken($pdo, $profileActivationToken);

		if(empty($profile)) {
			throw(new \InvalidArgumentException ("no profile for activation token"));
		}

		$profile->setProfileActivationToken(null);
		$profile->update($pdo);

		//update reply
		$reply->message = "Well done you clicked";
	} else {
		throw(new \Exception("Invalid HTTP method"));
	}
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
} catch(\TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
	$reply->trace = $typeError->getTraceAsString();
}

header("Content-type: application/json");

if($reply->data === null) {
	unset($reply->data);
}

echo json_encode($reply);