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
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$tagName = filter_input(INPUT_GET, "tagName", FILTER_SANITIZE_STRING);

	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id<0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	// handle GET request - if id is present, that Tag is returned
	if($method === "GET") {
		// set XSRF cookie
		setXsrfCookie();

		// get a specific tag or all tags and update reply
		if(empty($id) === false) {
			$tag = DevConnect\Tag::getTagByTagId($pdo, $id);
			if($tag !== null) {
				$reply->data = $tag;
			}
		}elseif(empty($tagName) === false) {
			$tags = DevConnect\Tag::getTagByTagContent($pdo, $tagName);
			if($tags !== null) {
				$reply->data = $tags;
			}
		}else {
			$tags = DevConnect\Tag::getAllTags($pdo);
			if($tags !== null) {
				$reply->data = $tags;
			}
		}
	}else if ($method === "PUT" || $method === "POST") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// make sure the Tag Name is available
		if(empty($requestObject->tagName) === true) {
			throw(new InvalidArgumentException("No tag name", 405));
		}

		// perform the actual put or post
		if($method === "PUT") {

			// retrieve the tag to update
			$tag = DevConnect\Tag::getTagByTagId($pdo, $id);
			if($tag === null) {
				throw(new RuntimeException("Tag does not exist", 404));
			}

			// update all attributes
			$tag->setTagName($requestObject->tagName);
			$tag->update($pdo);

			// update reply
			$reply->message = "Tag updated OK";
		}else if($method === "POST") {

			// create new tag and insert into database
			$tag = new DevConnect\Tag(null, $requestObject->tagName);
			$tag->insert($pdo);

			// update reply
			$reply->message = "Tag created OK";
		}
	}else if($method === "DELETE") {
		verifyXsrf();

		// retrieve the tag to be deleted
		$tag = DevConnect\Tag::getTagByTagId($pdo, $id);
		if($tag === null) {
			throw(new RuntimeException("Tag does not exist", 404));
		}

		// delete the tag
		$tag->delete($pdo);

		//update reply
		$reply->message = "Tag deleted OK";
	} else {
		throw(new InvalidArgumentException("Invalid HTTP method request"));
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