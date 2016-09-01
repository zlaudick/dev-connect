<?php

/**
 * function for the Mail class
 *
 * @author Gerald Sandoval <gsandoval16@cnm>
 **/

require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");




function mailGunner ($senderName, $senderMail, $receiverName, $receiverMail, $subject, $message) {


	$config = readConfig("/etc/apache2/capstone-mysql/devconnect.ini");
	$mailgun = json_decode($config["mailgun"]);

// now $mailgun->domain and $mailgun->apiKey exist


	// start the mailgun client
	$client = new \Http\Adapter\Guzzle6\Client();
	$mailGunner = new \Mailgun\Mailgun($mailgun->apiKey, $client);

	// send the message
	$result = $mailGunner->sendMessage($mailgun->domain, [
			"from" => "$senderName <$senderMail>",
			"to" => "$receiverName <$receiverMail>",
			"subject" => $subject,
			"text" => $message
		]
	);

	if($result->http_response_code !== 200) {
		throw(new RuntimeException("unable to send email", $result->http_response_code));
	}
	//split the result before the at symbol
	$atIndex = strpos($result->http_response_body->id, "@");
	if($atIndex === false) {
		throw (new RangeException("unable to send email", 503));
	}
	$mailgunMessageId = substr($result->http_response_body->id, 1, $atIndex - 1);

   return $mailgunMessageId;
   //https://github.com/mailgun/mailgun-php
}
