<?php
require_once dirname(dirname(__DIR__)) . "/classes/autoload.php";
require_once dirname(dirname(__DIR__)) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\DevConnect\Profile;
/**
 * API for profile class
 *
 * @author Marcelo Ibarra mibarra5@cnm.edu
 *
 * @see https://github.com/mzibert/BrewCrew/blob/master/public_html/php/apis/user/index.php for api I referenced
 */
// Verify xsrf challenge
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
// Prepare a empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// Grab the MySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/devconnect.ini");
	// Determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	// Sanitize inputs
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	// Make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be negative or empty", 405));
	}

	// Sanitize and trim other fields
	$profileLocation = filter_input(INPUT_GET, "profileLocation", FILTER_VALIDATE_INT);
	$profileName = filter_input(INPUT_GET, "profileName", FILTER_VALIDATE_INT);
	$profileActivationToken = filter_input(INPUT_GET, "profileActivationToken", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileContent = filter_input(INPUT_GET, "profileContent", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	// Handle rest calls, while only allowing admins to access database-modifying methods
	if($method === "GET") {
		// Set XSRF cookie
		setXsrfCookie("/");
		// Get the profile based on the given
		if(empty($profileName) === false) {
			$profile = Profile::getProfileByProfileEmail($pdo, $profileName);
			if($profile !== null) {
				$reply->data = $profile;
			}

		} else {
			if(empty($_SESSION["profile"]) === false) {
				$reply->data = $_SESSION["profile"];
			} else {
				$reply->data = new stdClass();
			}
		}

	} else if($method === "PUT") {
		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		// Make sure all fields are present in order to prevent database issues
		$requestObject->profileLocation = (filter_var($requestObject->profileLocation, FILTER_SANITIZE_EMAIL));
		$requestObject->profileContent = (filter_var($requestObject->profileContent, FILTER_SANITIZE_STRING));
		$requestObject->profileName = (filter_var($requestObject->profileName, FILTER_SANITIZE_STRING));

		if(empty($requestObject->profileLocation) === true) {
			throw(new InvalidArgumentException ("Location cannot be empty", 405));
		}
		if(empty($requestObject->profileContent) === true) {
			throw(new InvalidArgumentException ("Profile content cannot be empty", 405));
		}
		if(empty($requestObject->profileName) === true) {
			throw(new InvalidArgumentException ("Profile name cannot be empty", 405));
		}
		if(empty($requestObject->profileLocation) === true)
		// Perform the actual put
		$profile = Profile::getProfileByProfileEmail($pdo, $id);
		if($profile === null) {
			throw(new RuntimeException("Profile does not exist.", 404));
		}

		// If there's a password, hash it and set it
		if(isset($requestObject->profilePassword) === true && isset($requestObject->confirmPassword) === true) {
			if($requestObject->profilePassword !== $requestObject->confirmPassword) {
				throw (new \RangeException("Passwords do not match."));
			} else {
				$hash = hash_pbkdf2("sha512", $requestObject->profilePassword, $profile->getProfileSalt(), 262144);
				$profile->setProfileHash($hash);
			}
		}
		// Put the new profile content into the profile and update
		$profile->setProfileLocation($requestObject->profileLocation);
		$profile->setProfileContent($requestObject->profileContent);
		$profile->setprofileName($requestObject->profileName);
		$profile->update($pdo);
		$reply->message = "Profile updated successfully.";
	} else if($method === "DELETE") {
		$profile = Profile::getProfileByProfileEmail($pdo, $id);
		if($profile === null) {
			throw(new RuntimeException("Profile does not exist.", 404));
		}
		$profile->delete($pdo);
		$deletedObject = new stdClass();
		$reply->message = "Profile deleted successfully.";
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request"));
	}


	header("Content-type: application/json");
	$reply = json_encode($reply);
	unset($reply->salt);
	unset($reply->hash);
	echo $reply;
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	header("Content-type: application/json");
	echo(json_encode($reply));
} catch(\TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
	header("Content-type: application/json");
	echo(json_encode($reply));
}
