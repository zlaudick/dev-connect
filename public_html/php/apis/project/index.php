<?php

require_once dirname(__DIR__, 2) . "../classes/autoload.php"; /** DIR not going up the directory correctly **/
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DevConnect\Project;

/**
 * API for the Message class
 *
 * @author Devon Beets <dbeetzz@gmail.com> based on code by Derek Mauldin
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

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$profileId = filter_input(INPUT_GET, "profileId", FILTER_VALIDATE_INT);
	$projectContent = filter_input(INPUT_GET, "projectContent", FILTER_SANITIZE_STRING);
	$projectName = filter_input(INPUT_GET, "projectName", FILTER_SANITIZE_STRING);

	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw (new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	// handle GET request, if id is present that project is returned
	if($method === "GET") {
		// set XSRF cookie
		setXsrfCookie();

		// get a specific project and update reply
		if(empty($id) === false) {
			$project = Project::getProjectByProjectId($pdo, $id);
			if($project !== null) {
				$reply->data = $project;
			}
			// project by profile id
		} elseif(empty($profileId) === false) {
			$project = Project::getProjectByProjectProfileId($pdo, $profileId);
			if($project !== null) {
				$reply->data = $project;
			}
		} elseif(empty($projectName) === false) {
			$project = Project::getProjectByProjectName($pdo, $projectName);
			if($project !== null) {
				$reply->data = $project;
			}
		}
	} elseif($method === "PUT" || $method === "POST") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// make sure that the project content is available
		if(empty($requestObject) === true) {
			throw(new \InvalidArgumentException("No content for project", 405));
		}

		// make sure the profile id is available
		if(empty($requestObject->profileId) === true) {
			throw(new \InvalidArgumentException("No profile id", 405));
		}

		// perform the actual put or post
		if($method === "PUT") {

			// retrieve the project to update
			$project = Project::getProjectByProjectId($pdo, $id);
			if($project === null) {
				throw(new \RuntimeException("Project does not exist", 404));
			}

			// update all attributes
			$project->setProjectContent($requestObject->projectContent);
			$project->setProjectDate($requestObject->projectDate);
			$project->setProjectName($requestObject->projectName);
			$project->update($pdo);

			// update reply
			$reply->message = "Project updated OK";
		} elseif($method === "POST") {

			// create the new project and insert it into the database
			$project = new Project(null, $requestObject->projectContent, $requestObject->projectDate, $requestObject->projectName, $requestObject->profileContent);
			$project->insert($pdo);

			// update reply
			$reply->project = "Project created OK";
		}
	} elseif($method === "DELETE") {

		verifyXsrf();

		// retrieve the project to be deleted
		$project = Project::getProjectByProjectId($pdo, $id);
		if($project === null) {
			throw(new \RuntimeException("Project does not exist", 404));
		}

		// delete the project
		$project->delete($pdo);

		// update reply
		$reply->project = "Project deleted OK";
	} else {
		throw (new \InvalidArgumentException("Invalid HTTP method request"));
	}

	// update the reply with exception information
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

header("Content type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return the reply to the front end caller
echo json_encode($reply);