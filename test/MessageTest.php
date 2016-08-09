<?php
namespace Edu\Cnm\DevConnect\Test;

use Edu\Cnm\DevConnect\{Message, Profile};

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
 * @author Devon Beets <dbeetzz@gmail.com>
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
	 * @var \DateTime $VALID_MESSAGEDATE
	 **/
	protected $VALID_MESSAGEDATE = null;
	/**
	 * mailgun id of the Message
	 * @var string $VALID_MAILGUNID
	 **/
	protected $VALID_MAILGUNID = "1337";
	/**
	 * mailgun id of the updated Message
	 * @var string $VALID_MAILGUNID2
	 **/
	protected $VALID_MAILGUNID2 = "5W4G";
	/**
	 * content of the Message subject
	 * @var string $VALID_MESSAGESUBJECT
	 **/
	protected $VALID_MESSAGESUBJECT = "PHPUnit message subject test great success";
	/**
	 * Profile that created the Message, this is for foreign key relations
	 * @var Profile sender
	 **/
	protected $sender = null;

	/**
	 * Profile that received the Message, this is for foreign key relations
	 * @var Profile receiver
	 **/
	protected $receiver = null;

	/**
	 * create dependent objects first before running each test
	 **/
	public final function setUp() {
		//run the default setUp() method first
		parent::setUp();

		//create and insert a Profile to own the test Message, must figure out what to put for these...
		//do we need two profiles created, the send and receive profiles??
		$this->sender = new Profile(null, "Q", "1234567", true, 1, null, "Hi, I'm Markimoo!", "foo@bar.com", "4018725372539424208555279506880426447359803448671421461653568500", null, "Los Angeles", "Mark Fischbach", null);
		$this->sender->insert($this->getPDO());

		$this->receiver = new Profile(null, "T", "9876543", true, 2, null, "Hi, I'm Irelia!", "bar@foo.com", "4018725372539424208555279506880426447359803448671421461653568500", null, "Ionia", "Irelia Ionia", null);
		$this->receiver->insert($this->getPDO());

		//calculate the date using the time the unit test was set up
		$this->VALID_MESSAGEDATE = new \DateTime();
	}

	/**
	 * test inserting a valid Message and verifying that actual MySQL data matches
	 **/
	public function testInsertValidMessageContent() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("message");

		//create a new message and insert into MySQL
		$message = new Message(null, $this->receiver->getProfileId(), $this->sender->getProfileId(), $this->VALID_MESSAGECONTENT, $this->VALID_MESSAGEDATE, $this->VALID_MAILGUNID, $this->VALID_MESSAGESUBJECT);
		$message->insert($this->getPDO());

		//grab the data from MySQL and enforce that the fields match our expectations
		$pdoMessage = Message::getMessageByMessageId($this->getPDO(), $message->getMessageId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("message"));
		$this->assertEquals($pdoMessage->getProfileId(), $this->receiver->getProfileId());
		$this->assertEquals($pdoMessage->getProfileId(), $this->sender->getProfileId());
		$this->assertEquals($pdoMessage->getMessageContent(), $this->VALID_MESSAGECONTENT);
		$this->assertEquals($pdoMessage->getMessageDate(), $this->VALID_MESSAGEDATE);
		$this->assertEquals($pdoMessage->getMessageDate(), $this->VALID_MAILGUNID);
		$this->assertEquals($pdoMessage->getMessageSubject(), $this->VALID_MESSAGESUBJECT);
	}

	/**
	 * test inserting a Message that already exists
	 * @expectedException \PDOException
	 **/


}

