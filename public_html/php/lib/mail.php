<?php

/**
 * function for the Mail class
 *
 * @author Gerald Sandoval <gsandoval16@cnm>
 **/

require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");


function mail ( $domain, $senderName, $senderMail, $receiverName, $receiverMail, $subject, $message) {


	//prepare an empty reply
	$reply = new stdClass();
	$reply->status = 200;

	// start the mailgun client
	$client = new \Http\Adapter\Guzzle6\Client();
	$mailgun = new \Mailgun\Mailgun($config["mailgunKey"], $client);

	// send the message
	$result = $mailgun->sendMessage("$domain", [
			"from" => "$senderName <$senderMail>",
			"to" => "$receiverName <$receiverMail>",
			"subject" => $subject,
			"text" => $message
		]
	);
/*
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
*/

	# Iterate through the results and echo the message IDs.
	$logItems = $result->http_response_body->items;
	foreach($logItems as $logItem){
		$mailMessageId = $logItem->message_id;
	}


   return $mailMessageId;
   //https://github.com/mailgun/mailgun-php

}



?>