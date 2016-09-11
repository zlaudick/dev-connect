<?php
use Edu\Cnm\DevConnect;

require_once dirname(__DIR__, 2) . "/classes/autoload.php";
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


/**
 * api for the Review class
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
	$reviewReceiveProfileId = filter_input(INPUT_GET, "reviewReceiveProfileId", FILTER_VALIDATE_INT);
	$reviewWriteProfileId = filter_input(INPUT_GET, "reviewWriteProfileId", FILTER_VALIDATE_INT);
	$reviewContent = filter_input(INPUT_GET, "reviewContent", FILTER_SANITIZE_STRING);
	$reviewRating = filter_input(INPUT_GET, "reviewRating", FILTER_VALIDATE_INT);

	// make sure the id's are valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($reviewReceiveProfileId) === true || $reviewReceiveProfileId < 0) && empty($reviewWriteProfileId) === true || $reviewWriteProfileId < 0) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	// handle GET request - if id is present, that feedback is returned
	if($method === "GET") {
		// set xsrf cookie
		setXsrfCookie();

		// get a specific review and update reply
		if((empty($reviewReceiveProfileId) === false) && (empty($reviewWriteProfileId) === false)) {
			$review = DevConnect\Review::getReviewByReceiveProfileIdAndWriteProfileId($pdo, $reviewReceiveProfileId, $reviewWriteProfileId);
			if($review !== null) {
				$reply->data = $review;
			}
			// get review by reviewReceiveProfileId and update reply
		} elseif(empty($reviewReceiveProfileId) === false) {
			$reviews = DevConnect\Review::getReviewByReviewReceiveProfileId($pdo, $reviewReceiveProfileId);
			if($reviews !== null) {
				$reply->data = $reviews;
			}
			// get review by reviewWriteProfileId and update reply
		} elseif(empty($reviewWriteProfileId) === false) {
			$reviews = DevConnect\Review::getReviewByReviewWriteProfileId($pdo, $reviewWriteProfileId);
			if($reviews !== null) {
				$reply->data = $reviews;
			}
			// get review by content and update reply
		} elseif(empty($reviewContent) === false) {
			$reviews = DevConnect\Review::getReviewByReviewContent($pdo, $reviewContent);
			if($reviews !== null) {
				$reply->data = $reviews;
			}
		}
	} elseif($method === "POST") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// make sure review content or review rating is available
		if(empty($requestObject->reviewContent) === true && empty($requestObject->reviewRating) === true) {
			throw(new InvalidArgumentException("No content or rating in review", 405));
		}

		// make sure review date is accurate
		if(empty($requestObject->reviewDateTime) === true) {
			$requestObject->reviewDateTime = new \DateTime();
		}

		// make sure reviewReceiveProfileId and reviewWriteProfileId are available
		if(empty($requestObject->reviewReceiveProfileId) === true && empty($requestObject->reviewWriteProfileId) === true) {
			throw(new InvalidArgumentException("no receive or write id", 405));
		} elseif($_SESSION["profile"]->getProfileAccountType() === "O" && $_SESSION["profile"]->getProfileId() === $reviewWriteProfileId || $_SESSION["profile"]->getProfileAccountType() === "A") {
			// create new review and insert into the database
			$review = new DevConnect\Review($requestObject->reviewReceiveProfileId, $requestObject->reviewWriteProfileId, $requestObject->reviewContent, null, $requestObject->reviewRating);
			$review->insert($pdo);
			// update reply
			$reply->message = "Review created OK";
		} else {
			throw(new InvalidArgumentException("You do not have permission to write a review", 403));
		}

	} elseif($method === "DELETE") {
		verifyXsrf();

		// retrieve the review to be deleted
		$review = DevConnect\Review::getReviewByReceiveProfileIdAndWriteProfileId($pdo, $reviewReceiveProfileId, $reviewWriteProfileId);
		if($review === null) {
			throw(new RuntimeException("review does not exist", 404));
		} elseif($_SESSION["profile"]->getProfileAccountType() === "O" && $_SESSION["profile"]->getProfileId() === $reviewWriteProfileId || $_SESSION["profile"]->getProfileAccountType() === "A") {
			// delete review
			$review->delete($pdo);
			// update reply
			$reply->message = "Review deleted OK";
		} else {
			throw(new InvalidArgumentException("You do not have permission to delete this review", 403));
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