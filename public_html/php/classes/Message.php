<?php
namespace Edu\Cnm\DevConnect;

require_once("autoload.php");

/**
 * Message class for sending messages between two profiles
 *
 * This Message can store data about messages sent between two profiles, including timestamp, content and subject.
 *
 * @author Devon Beets <dbeetzz@gmail.com>
 * @version 1.0.0
 **/
class Message implements \JsonSerializable {
	use ValidateDate;
	/**
	 * id for this Message, this is the primary key
	 * @var int $messageId
	 **/
	private $messageId;
	/**
	 * id for the profile that received this Message, this is a foreign key
	 * @var int $messageReceiveProfileId
	 **/
	private $messageReceiveProfileId;
	/**
	 * id for the profile that sent this Message, this is a foreign key
	 * @var int $messageSentProfileId
	 **/
	private $messageSentProfileId;
	/**
	 * textual content of the Message
	 * @var string $messageContent
	 **/
	private $messageContent;
	/**
	 * date and time this Message was sent, in a PHP DateTime object
	 * @var \DateTime $messageDateTime
	 **/
	private $messageDateTime;
	/**
	 * SMTP transactional string of the mailgun API that is created for each Message
	 * @var string $messageMailgunId
	 **/
	private $messageMailgunId;
	/**
	 * subject of the Message
	 * @var string $messageSubject
	 **/
	private $messageSubject;

	/**
	 * constructor for this Message
	 *
	 * @param int|null $newMessageId id of this Message or null if a new Message
	 * @param int $newMessageReceiveProfileId id of the profile that received this Message
	 * @param int $newMessageSentProfileId id of the profile that sent this Message
	 * @param string $newMessageContent string containing the actual Message data
	 * @param \DateTime|string|null $newMessageDateTime date and time that the Message was sent, or null if set to the current date and time
	 * @param string $newMessageMailgunId string containing the SMTP of the Mailgun API
	 * @param string $newMessageSubject string containing the subject of the Message
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (i.e., strings too long or negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newMessageId = null, int $newMessageReceiveProfileId, int $newMessageSentProfileId, string $newMessageContent, $newMessageDateTime = null, string $newMessageMailgunId, string $newMessageSubject) {
		try {
			$this->setMessageId($newMessageId);
			$this->setMessageReceiveProfileId($newMessageReceiveProfileId);
			$this->setMessageSentProfileId($newMessageSentProfileId);
			$this->setMessageContent($newMessageContent);
			$this->setMessageDateTime($newMessageDateTime);
			$this->setMessageMailgunId($newMessageMailgunId);
			$this->setMessageSubject($newMessageSubject);
		} catch(\InvalidArgumentException $invalidArgument) {
			//rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			//rethrow the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			//rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			//rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}



}