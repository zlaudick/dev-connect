<?php
namespace Edu\Cnm\DevConnect\Test;

use Edu\Cnm\DevConnect\Test\DevConnectTest;

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

class MessageTest extends DevConnectTest {
	/**
	 * content of the Message
	 * @var string $VALID_MESSAGECONTENT
	 **/
	protected $VALID_MESSAGECONTENT = "PHPUnit message content test great success";
	/**
	 * content of the updated Message
	 * @var string $VALID_MESSAGECONTENT2
	 **/
	protected $VALID_MESSAGECONTENT2 = "PHPUnit message content test still great success";
	/**
	 * timestamp of the Message; starts as null and is assigned later
	 * @var DateTime $VALID_MESSAGEDATE
	 **/
	protected $VALID_MESSAGEDATE = null;
	/**
	 * content of the Message subject
	 * @var string $VALID_MESSAGESUBJECT
	 **/
	protected $VALID_MESSAGESUBJECT = "PHPUnit message subject test great success";
	/**
	 * Profile that created the Message, this is for foreign key relations
	 * @var Profile profile
	 **/
	protected $profile = null;

	/**
	 * create dependent objects first before running each test
	 **/
	public final function setUp() {
		//run the default setUp() method first
		parent::setUp();

		//create and insert a Profile to own the test Message, must figure out what to put for these...
		$this->profile = new Profile(null, null, null, null, null, null, null, "foo@bar.com", null, null, null, "Los Angeles", "Mark Fischbach", null);
		$this->profile->insert($this->getPDO());

		//calculate the date using the time the unit test was set up
		$this->VALID_MESSAGEDATE = new \DateTime();
	}

	/**
	 * test inserting valid Message content and verifying that actual MySQL data matches
	 **/
	public function testInsertValidMessageContent() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("message");

		//create a new message and insert into MySQL
		$message = new $message($this->profile->getProfileId(), $this->VALID_MESSAGECONTENT, $this->VALID_MESSAGEDATE, $this->VALID_MESSAGESUBJECT);
	}

}

