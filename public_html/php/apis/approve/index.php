<?php
require_once dirname(dirname(__DIR__)) . "/classes/autoload.php";
require_once dirname(dirname(__DIR__)) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\DevConnect\Approve;
/**
 * API for approve class
 *
 * @author Marcelo Ibarra mibarra5@cnm.edu
 **/

//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/devconnect.ini");
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	$reply->method = $method;
	if($method === "POST") {
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		if(empty($requestObject->projectName) === true) {
			throw(new \InvalidArgumentException ("Must fill in project name", 405));
		} else {
			$projectName = filter_var($requestObject->projectName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		}
		if(empty($requestObject->profileName) === true) {
			throw(new \InvalidArgumentException ("Must fill in valid profile name", 405));
		} else {
			$profileName = filter_var($requestObject->profileName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		}
		if(empty($requestObject->password) === true) {
			throw(new \InvalidArgumentException ("Must input valid password", 405));
		} else {
			$password = filter_var($requestObject->password, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		}
		if(empty($requestObject->userEmail) === true) {
			throw(new \InvalidArgumentException ("Must enter a valid email address", 405));
		} else {
			$userEmail = filter_var($requestObject->userEmail, FILTER_SANITIZE_EMAIL);
		}
		$salt = bin2hex(random_bytes(32));
		$hash = hash_pbkdf2("sha512", $password, $salt, 262144);
		$userAccessLevel = 0;
		$userActivationToken = bin2hex(random_bytes(16));
		$user = new \User(null, null, $projectName, $profileName, $password, $userEmail);
		$user->insert($pdo);
		$messageSubject = "Dec-Connect Welcomes You! -- Account Activation";
		//building the activation link that can travel to another server and still work. This is the link that will be clicked to confirm the account.
		// FIXME: make sure URL is /public_html/php/apis/activation/$activation
		$basePath = dirname($_SERVER["SCRIPT_NAME"], 2);
		$urlglue = $basePath . "/activation/" . $userActivationToken;
		$confirmLink = "https://" . $_SERVER["SERVER_NAME"] . $urlglue;
		$message = <<< EOF
<h2>Welcome to Dev-Connect application.</h2>
<p>In order to find a project posted by a Non-Profit organization please visit the following URL to set a new password and complete the registration process: </p>
<p><a href="$confirmLink">$confirmLink</a></p>
EOF;
		$response = sendEmail($userEmail, $projectName, $profileName, $password );
		if($response === "Email sent.") {
			$reply->message = "Sign up was successful, please check your email for activation message.";
		} else {
			throw(new InvalidArgumentException("Error sending email."));
		}
	} else{
		throw (new InvalidArgumentException("invalid http request"));
	}

}catch(\Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(\TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);


