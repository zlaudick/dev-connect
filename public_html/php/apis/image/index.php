<?php

require_once dirname(__DIR__, 2) . "../classes/autoload.php"; /**this don't work still**/
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

/**
 * API for the Image class
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
	// grab the MySQL connection
	$pdo = connectToEncryptedMySQL("etc/apache2/capstone-mysql/dev-connect.ini");

	// determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$imagePath = filter_input(INPUT_GET, "imagePath", FILTER_SANITIZE_STRING);
	$imageType = filter_input(INPUT_GET, "imageType", FILTER_SANITIZE_STRING);

	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw (new InvalidArgumentException("id cannot be empty or negative", 405));
	}


}

