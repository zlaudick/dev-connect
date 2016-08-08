<?php
namespace Edu\Cnm\DevConnect\Test;

use Edu\Cnm\DevConnect\Test\{Message};

// grab the project test parameters
require_once("DevConnectTest.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/public_html/php/classes/autoload.php");

/**
 * Full PHPUnit test for the Message class
 *
 * This is a complete PHPUnit test for the Message class. It is complete because *ALL* MySQL and PDO enabled methods
 * are tested for both valid and invalid inputs.
 *
 * @see Message
 * @author Devon Beets <dbeets@cnm.edu>
 **/

class MessageTest extends Message {
	/**
	 * content of the Message
	 * @var string $VALID_MESSAGECONTENT
	 **/
	protected $VALID_MESSAGECONTENT = "PHPUnit test great success!";

}

