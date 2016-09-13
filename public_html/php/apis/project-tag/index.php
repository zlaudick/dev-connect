<?php
use Edu\Cnm\DevConnect;

require_once dirname(__DIR__, 2) . "/classes/autoload.php";
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


/**
 * api for the ProjectTag class
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
	$projectTagProjectId = filter_input(INPUT_GET, "projectTagProjectId", FILTER_VALIDATE_INT);
	$projectTagTagId = filter_input(INPUT_GET, "projectTagTagId", FILTER_VALIDATE_INT);

	// make sure the id's are valid for methods that require it
	if(($method === "DELETE") && (empty($projectTagProjectId) === true || $projectTagProjectId < 0) && empty($projectTagTagId) === true || $projectTagTagId < 0) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}


	// handle GET request
	if($method === "GET") {
		// set xsrf cookie
		setXsrfCookie();

		// get a specific projectTag and update reply
		if((empty($projectTagProjectId) === false) && (empty($projectTagTagId) === false)) {
			$projectTag = DevConnect\ProjectTag::getProjectTagByProjectIdAndTagId($pdo, $projectTagProjectId, $projectTagTagId);
			if($projectTag !== null) {
				$reply->data = $projectTag;
			}
			// get projectTag by projectTagProjectId and update reply
		} elseif(empty($projectTagProjectId) === false) {
			$projectTag = DevConnect\ProjectTag::getProjectTagByProjectId($pdo, $projectTagProjectId);
			if($projectTag !== null) {
				$reply->data = $projectTag;
			}
			// get projectTag by projectTagTagId and update reply
		} elseif(empty($projectTagTagId) === false) {
			$projectTag = DevConnect\ProjectTag::getProjectTagByTagId($pdo, $projectTagTagId);
			if($projectTag !== null) {
				$reply->data = $projectTag;
			}
		}
	} elseif($method === "POST") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// make sure projectTagProjectId and projectTagTagId are available
		if(empty($requestObject->projectTagProjectId) === true && empty($requestObject->projectTagTagId) === true) {
			throw(new InvalidArgumentException("no tag id or project id", 405));
		} else {
			// create new projectTag and insert into database
			$projectTag = new DevConnect\ProjectTag($requestObject->projectTagProjectId, $requestObject->projectTagTagId);
			$projectTag->insert($pdo);
			// update reply
			$reply->message = "projectTag created OK";
		}
	} elseif($method === "DELETE") {
		verifyXsrf();

		// retrieve the projectTag to be deleted
		$projectTag = DevConnect\ProjectTag::getProjectTagByProjectIdAndTagId($pdo, $projectTagProjectId, $projectTagTagId);
		if($projectTag === null) {
			throw(new RuntimeException("projectTag does not exist", 404));
		} else {
			// delete projectTag
			$projectTag->delete($pdo);
			// update reply
			$reply->message = "projectTag deleted OK";
		}
	} else {
		throw(new InvalidArgumentException("Invalid HTTP method request"));
	}
} // update reply with exception information
catch
(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return reply to front end caller
echo json_encode($reply);