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
}