<?php

require_once dirname(__DIR__, 2) . "/classes/autoload.php";
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");


/**
 * API for the Message class
 *
 * @author
 **/

use Edu\Cnm\DevConnect\{Profile};
// grab the mySQL connection
$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/devconnect.ini");

$getProfileInfo = new Profile;

$getProfileInfo = Profile::getProfileByProfileId($pdo, $profileId);

$receiverName  = $getProfileInfo->$profileName;

$receiverEmail = $getProfileInfo->$profileEmail;


//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
try {
	//grab the mailgun configuration!!! There currently is no directory called 'encrypted-config'
	$config = readConfig("/etc/apache2/encrypted-config/devconnect.ini");
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	// handle GET request - just kick them out
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		header("Location: ..", true, 301);
	} else if($method === "POST") {
		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		// sanitize inputs
		$name = filter_var($requestObject->name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$email = filter_var($requestObject->email, FILTER_SANITIZE_EMAIL);
		$subject = filter_var($requestObject->subject, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$message = filter_var($requestObject->message, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		// throw out blank fields
		if(empty($name) === true) {
			throw(new InvalidArgumentException("name is required", 400));
		}
		if(empty($email) === true) {
			throw(new InvalidArgumentException("email is required", 400));
		}
		if(empty($subject) === true) {
			throw(new InvalidArgumentException("subject is required", 400));
		}
		if(empty($message) === true) {
			throw(new InvalidArgumentException("message is required", 400));
		}
		// start the mailgun client
		$client = new \Http\Adapter\Guzzle6\Client();
		$mailgun = new \Mailgun\Mailgun($config["mailgunKey"], $client);

		// send the message
		$result = $mailGunner->sendMessage($mailgun->domain, [
				"from" => "$senderName <$senderMail>",
				"to" => "$receiverName <$receiverMail>",
				"subject" => $subject,
				"text" => $message
			]
		);
		// inform the user of the result
		if($result->http_response_code !== 200) {
			throw(new RuntimeException("unable to send email", $result->http_response_code));
		}
		$reply->message = "Thank you for reaching out. I'll be in contact shortly!";
	}  else {
		throw(new InvalidArgumentException("Invalid HTTP method request", 405));
	}
	// update reply with exception information
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
// encode and return reply to front end caller
header("Content-type: application/json");
echo json_encode($reply);