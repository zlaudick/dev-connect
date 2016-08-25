<?php
use Edu\Cnm\DevConnect;

require_once (dirname(__DIR__, 2) . "/classes/autoload.php");
require_once (dirname(__DIR__, 2) . "/lib/xsrf.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once (dirname(__DIR__, 4) . "/vendor/autoload.php");

/**  ___________________________________

                    Light PHP wrapper for the OAuth 2.0
___________________________________


AUTHOR & CONTACT
================

Charron Pierrick
- pierrick@webstart.fr

Berejeb Anis
- anis.berejeb@gmail.com


DOCUMENTATION & DOWNLOAD
========================

Latest version is available on github at :
    - https://github.com/adoy/PHP-OAuth2

Documentation can be found on :
    - https://github.com/adoy/PHP-OAuth2


LICENSE
=======

This Code is released under the GNU LGPL

Please do not change the header of the file(s).

This library is free software; you can redistribute it and/or modify it
under the terms of the GNU Lesser General Public License as published
by the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This library is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE.

See the GNU Lesser General Public License for more details.  **/

try {

	$config = readConfig("/etc/apache2/capstone-mysql/devconnect.ini");

	$oauth = json_decode($config["oauth"]);

// now $oauth->github->clientId and $oauth->github->clientKey exist


	const REDIRECT_URI = 'https://bootcamp-coders.cnm.edu/~zlaudick/dev-connect/public_html/php/apis/github-login/';
	const AUTHORIZATION_ENDPOINT = 'https://github.com/login/oauth/authorize';
	const TOKEN_ENDPOINT = 'https://github.com/login/oauth/access_token';

	$client = new OAuth2\Client($oauth->github->clientId, $oauth->github->clientKey);
	if(!isset($_GET['code'])) {
		$auth_url = $client->getAuthenticationUrl(AUTHORIZATION_ENDPOINT, REDIRECT_URI, ['scope' => 'user:email']);
		header('Location: ' . $auth_url);
		die('Redirect');
	} else {
		$params = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
		$response = $client->getAccessToken(TOKEN_ENDPOINT, 'authorization_code', $params);
		parse_str($response['result'], $info);
		$client->setAccessToken($info['access_token']);
		var_dump($client);
		$response = $client->fetch('https://api.github.com/user/emails', [], 'GET', ['User-Agent' => 'Talcott Auto Deleter']);
		var_dump($response);
//	$response = $client->fetch('https://graph.facebook.com/me');
//	var_dump($response, $response['result']);
	}
} catch{

}