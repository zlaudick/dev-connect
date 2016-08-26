<?php
namespace Edu\Cnm\DevConnect;

require_once(dirname(__DIR__, 2) . "/classes/autoload.php");
require_once(dirname(__DIR__, 2) . "/lib/xsrf.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once(dirname(__DIR__, 4) . "/vendor/autoload.php");

/**  ___________________________________
 *
 * Light PHP wrapper for the OAuth 2.0
 * ___________________________________
 *
 *
 * AUTHOR & CONTACT
 * ================
 *
 * Charron Pierrick
 * - pierrick@webstart.fr
 *
 * Berejeb Anis
 * - anis.berejeb@gmail.com
 *
 *
 * DOCUMENTATION & DOWNLOAD
 * ========================
 *
 * Latest version is available on github at :
 * - https://github.com/adoy/PHP-OAuth2
 *
 * Documentation can be found on :
 * - https://github.com/adoy/PHP-OAuth2
 *
 *
 * LICENSE
 * =======
 *
 * This Code is released under the GNU LGPL
 *
 * Please do not change the header of the file(s).
 *
 * This library is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * See the GNU Lesser General Public License for more details.  **/


// verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// prepare an empty reply
$reply = new \stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the MySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/devconnect.ini");

	$config = readConfig("/etc/apache2/capstone-mysql/devconnect.ini");
	$oauth = json_decode($config["oauth"]);

// now $oauth->github->clientId and $oauth->github->clientKey exist


	$REDIRECT_URI = 'https://bootcamp-coders.cnm.edu/~zlaudick/dev-connect/public_html/php/apis/github-login/';
	$AUTHORIZATION_ENDPOINT = 'https://github.com/login/oauth/authorize';
	$TOKEN_ENDPOINT = 'https://github.com/login/oauth/access_token';

	$client = new \OAuth2\Client($oauth->github->clientId, $oauth->github->clientKey);
	if(!isset($_GET['code'])) {
		$auth_url = $client->getAuthenticationUrl($AUTHORIZATION_ENDPOINT, $REDIRECT_URI, ['scope' => 'user:email']);
		header('Location: ' . $auth_url);
		die('Redirect');
	} else {
		// Initialize profile variables here
		$profileName = "";
		$profileEmail = "";
		$profileGithubAccessToken = "";

		$params = array('code' => $_GET['code'], 'redirect_uri' => $REDIRECT_URI);
		$response = $client->getAccessToken($TOKEN_ENDPOINT, 'authorization_code', $params);
		parse_str($response['result'], $info);
		$client->setAccessToken($info['access_token']);
		$profileGithubAccessToken = $response;
//		var_dump($client);
		$response = $client->fetch('https://api.github.com/user', [], 'GET', ['User-Agent' => 'Talcott Auto Deleter']);
//		var_dump($response);
		$profileName = $response["result"]["login"];
		$response = $client->fetch('https://api.github.com/user/emails', [], 'GET', ['User-Agent' => 'Talcott Auto Deleter']);
//		var_dump($response);
		foreach($response['result'] as $result) {
			if($result['primary'] === true) {
				$profileEmail = $result['email'];
			}
		}
		// get profile by email to see if it exists, if it does not then create a new one
		$profile = Profile::getProfileByProfileEmail($pdo, $profileEmail);
		if(empty($profile) === true) {
			// create a new profile
			$profile = new Profile(null, "D", null, null, null, null, null, $profileEmail, $profileGithubAccessToken, null, null, $profileName, null);
			$profile->insert($pdo);
		}

		header("Content-type: application/json");
		if($reply->data === null) {
			unset($reply->data);
		}

		// encode and return reply to front end caller
		echo json_encode($reply);
	}
} catch(\Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
} catch(\TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
