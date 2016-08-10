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

	/**
	 * accessor method for message id
	 *
	 * @return int|null value of message id
	 **/
	public function getMessageId() {
		return($this->messageId);
	}

	/**
	 * mutator method for message id
	 *
	 * @param int|null $newMessageId new value of message id
	 * @throws \RangeException if $newMessageId is not positive
	 * @throws \TypeError if $newMessageId is not an integer
	 **/
	public function setMessageId($newMessageId = null) {
		//base case: if the message id is null, then this is a new message without a MySQL assigned id yet
		if($newMessageId === null) {
			$this->messageId = null;
			return;
		}

		//verify the message id is positive
		if($newMessageId <= 0) {
			throw(new \RangeException("message id is not positive"));
		}

		//convert and store the message id
		$this->messageId = $newMessageId;
	}

	/**
	 * accessor method for the message receive profile id
	 *
	 * @return int value of the message receive profile id
	 **/
	public function getMessageReceiveProfileId() {
		return($this->messageReceiveProfileId);
	}

	/**
	 * mutator method for the message receive profile id
	 *
	 * @param int $newMessageReceiveProfileId new value of message receive profile id
	 * @throws \RangeException if $newMessageReceiveProfileId is not positive
	 * @throws \TypeError if $newMessageReceiveProfileId is not an integer
	 **/
	public function setMessageReceiveProfileId(int $newMessageReceiveProfileId) {
		//verify the message receive profile id is positive
		if($newMessageReceiveProfileId <= 0) {
			throw(new \RangeException("message receive profile id is not positive"));
		}

		//convert and store the message receive profile id
		$this->messageReceiveProfileId = $newMessageReceiveProfileId;
	}

	/**
	 * accessor method for the message sent profile id
	 *
	 * @return int value of the message sent profile id
	 **/
	public function getMessageSentProfileId() {
		return($this->messageSentProfileId);
	}

	/**
	 * mutator method for the message sent profile id
	 *
	 * @param int $newMessageSentProfileId new value of message sent profile id
	 * @throws \RangeException if $newMessageSentProfileId is not positive
	 * @throws \TypeError if $newMessageSentProfileId is not an integer
	 **/
	public function setMessageSentProfileId(int $newMessageSentProfileId) {
		//verify the message sent profile id is positive
		if($newMessageSentProfileId <= 0) {
			throw(new \RangeException("message sent profile id is not positive"));
		}

		//convert and store the message sent profile id
		$this->messageSentProfileId = $newMessageSentProfileId;
	}

	/**
	 * accessor method for the message content
	 *
	 * @return string value of the message content
	 **/
	public function getMessageContent() {
		return($this->messageContent);
	}

	/**
	 * mutator method for the message content
	 *
	 * @param string $newMessageContent new value of the message content
	 * @throws \InvalidArgumentException if $newMessageContent is not a string or insecure
	 * @throws \RangeException if $newMessageContent is > 2000 characters
	 * @throws \TypeError if $newMessageContent is not a string
	 **/
	public function setMessageContent(string $newMessageContent) {
		//verify the message content is secure
		$newMessageContent = trim($newMessageContent);
		$newMessageContent = filter_var($newMessageContent, FILTER_SANITIZE_STRING);
		if(empty($newMessageContent) === true) {
			throw(new \InvalidArgumentException("message content is empty or insecure"));
		}

		//verify the message content will fit in the database
		if(strlen($newMessageContent) > 2000) {
			throw(new \RangeException("message content is too large"));
		}

		//store the message content
		$this->messageContent = $newMessageContent;
	}

	/**
	 * accessor method for the Message date and time
	 *
	 * @return \DateTime value of the Message date and time
	 **/
	public function getMessageDateTime() {
		return($this->messageDateTime);
	}

	/**
	 * mutator method for the Message date and time
	 *
	 * @param \DateTime|string|null $newMessageDateTime message date and time as a DateTime object, or null to load the current time
	 * @throws \InvalidArgumentException if $newMessageDateTime is not a valid object or string
	 * @throws \RangeException if $newMessageDateTime is a date that does not exist
	 **/
	public function setMessageDateTime($newMessageDateTime = null) {
		//base case: if the date and time are null, use the current date and time
		if($newMessageDateTime === null) {
			$this->messageDateTime = new \DateTime();
			return;
		}

		//store the message date and time
		try {
			$newMessageDateTime = self::validateDateTime($newMessageDateTime);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), 0, $range));
		}
		$this->messageDateTime =$newMessageDateTime;
	}

	/**
	 * accessor method for the Message mailgun id
	 *
	 * @return string value of the Message mailgun id
	 **/
	public function getMessageMailgunId() {
		return($this->messageMailgunId);
	}

	/**
	 * mutator method for the Message mailgun id
	 *
	 * @param string $newMessageMailgunId new value of the Message mailgun id
	 * @throws \InvalidArgumentException if $newMessageMailgunId is not a string or insecure
	 * @throws \RangeException if $newMessageMailgunId is > 128 characters
	 * @throws \TypeError if $newMessageMailgunId is not a string
	 **/
	public function setMessageMailgunId(string $newMessageMailgunId) {
		//verify the Message mailgun id is secure
		$newMessageMailgunId = trim($newMessageMailgunId);
		$newMessageMailgunId = filter_var($newMessageMailgunId, FILTER_SANITIZE_STRING);
		if(empty($newMessageMailgunId) === true) {
			throw(new \InvalidArgumentException("message mailgun id is empty or insecure"));
		}

		//verify the message mailgun id will fit in the database
		if(strlen($newMessageMailgunId) > 128) {
			throw(new \RangeException("message mailgun id is too large"));
		}

		//store the message mailgun id
		$this->messageMailgunId = $newMessageMailgunId;
	}

	/**
	 * accessor method for Message subject
	 *
	 * @return string value of the Message subject
	 **/
	public function getMessageSubject() {
		return($this->messageSubject);
	}

	/**
	 * mutator method for Message subject
	 *
	 * @param string $newMessageSubject new value of the Message subject
	 * @throws \InvalidArgumentException if $newMessageSubject is not a string or insecure
	 * @throws \RangeException if $newMessageSubject is > 140 characters
	 * @throws \TypeError if $newMessageSubject is not a string
	 **/
	public function setMessageSubject(string $newMessageSubject) {
		//verify the message subject is secure
		$newMessageSubject = trim($newMessageSubject);
		$newMessageSubject = filter_var($newMessageSubject, FILTER_SANITIZE_STRING);
		if(empty($newMessageSubject) === true) {
			throw(new \InvalidArgumentException("message subject is empty or insecure"));
		}

		//verify the message subject will fit in the database
		if(strlen($newMessageSubject) > 140) {
			throw(new \RangeException("message subject is too large"));
		}

		//store the message subject
		$this->messageSubject = $newMessageSubject;
	}

	/**
	 * inserts this Message into MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when MySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		//enforce that the messageId is null (don't insert a message that already exists)
		if($this->messageId !== null) {
			throw(new \PDOException("not a new message"));
		}

		//create query template
		$query = "INSERT INTO message(messageReceiveProfileId, messageSentProfileId, messageContent, messageDateTime, messageMailgunId, messageSubject) VALUES (:messageReceiveProfileId, :messageSentProfileId, :messageContent, :messageDateTime, :messageMailgunId, :messageSubject)";
		$statement = $pdo->prepare($query);

		//bind the member variables to the placeholders in the template
		$formattedDate = $this->messageDateTime->format("Y-m-d H:i:s");
		$parameters = ["messageReceiveProfileId" => $this->messageReceiveProfileId, "messageSentProfileId" => $this->messageSentProfileId, "messageContent" => $this->messageContent, "messageDateTime" => $this->$formattedDate, "messageMailgunId" => $this->messageMailgunId, "messageSubject" => $this->messageSubject];
		$statement->execute($parameters);

		//update the null messageId with what MySQL just gave us
		$this->messageId = intval($pdo->lastInsertId());
	}

	/**
	 * deletes this Message from MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when MySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		//enforce that the messageId is not null (don't delete a message that hasn't been inserted)
		if($this->messageId === null) {
			throw(new \PDOException("unable to delete a message that does not exist"));
		}

		//create query template
		$query = "DELETE FROM message WHERE messageId = :messageId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the placeholder in the template
		$parameters = ["messageId" =>$this->messageId];
		$statement->execute($parameters);
	}

	/**
	 * updates this Message in MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when MySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) {
		//enforce that the messageId is not null (don't update a message that that hasn't been inserted)
		if($this->messageId === null) {
			throw(new \PDOException("unable to update a message that does not exist"));
		}

		//create query template
		$query = "UPDATE message SET messageReceiveProfileId = :messageReceiveProfileId, messageSentProfileId = :messageSentProfileId, messageContent = :messageContent, messageDateTime = :messageDateTime, messageMailgunId = :messageMailgunId, messageSubject = :messageSubject WHERE messageId = :messageId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the placeholders in the template
		$formattedDate = $this->messageDateTime->format("Y-m-d H:i:s");
		$parameters = ["messageReceiveProfileId" => $this->messageReceiveProfileId, "messageSentProfileId" => $this->messageSentProfileId, "messageContent" => $this->messageContent, "messageDateTime" => $this->$formattedDate, "messageMailgunId" => $this->messageMailgunId, "messageSubject" => $this->messageSubject];
		$statement->execute($parameters);
	}

	/**
	 * gets the Message by Message receive profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $messageReceiveProfileId to search for
	 * @return \SplFixedArray SplFixedArray of Messages found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getMessageByMessageReceiveProfileId(\PDO $pdo, int $messageReceiveProfileId) {
		//sanitize the messageReceiveProfileId before searching
		if($messageReceiveProfileId <= 0) {
			throw(new \PDOException("message receive profile id is not positive"));
		}

		//create query template
		$query = "SELECT messageId, messageReceiveProfileId, messageSentProfileId, messageContent, messageDateTime, messageMailgunId, messageSubject FROM message WHERE messageReceiveProfileId = :messageReceiveProfileId";
		$statement = $pdo->prepare($query);

		//bind the message receive profile id to the placeholder in the template
		$parameters = ["messageReceiveProfileId" => $messageReceiveProfileId];
		$statement->execute($parameters);

		//build an array of messages
		$messages = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row =$statement->fetch()) !== false) {
			try {
				$message = new Message($row["messageId"], $row["messageReceiveProfileId"], $row["messageSentProfileId"], $row["messageContent"], $row["messageDateTime"], $row["messageMailgunId"], $row["messageSubject"]);
				$messages[$messages->key()] = $message;
				$messages->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($messages);
	}

	/**
	 * gets the Message by Message sent profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $messageSentProfileId to search for
	 * @return \SplFixedArray SplFixedArray of Messages found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getMessageByMessageSentProfileId(\PDO $pdo, int $messageSentProfileId) {
		//sanitize the messageSentProfileId before searching
		if($messageSentProfileId <= 0) {
			throw(new \PDOException("message sent profile id is not positive"));
		}

		//create query template
		$query = "SELECT messageId, messageReceiveProfileId, messageSentProfileId, messageContent, messageDateTime, messageMailgunId, messageSubject FROM message WHERE messageSentProfileId = :messageSentProfileId";
		$statement = $pdo->prepare($query);

		//bind the message receive profile id to the placeholder in the template
		$parameters = ["messageSentProfileId" => $messageSentProfileId];
		$statement->execute($parameters);

		//build an array of messages
		$messages = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row =$statement->fetch()) !== false) {
			try {
				$message = new Message($row["messageId"], $row["messageReceiveProfileId"], $row["messageSentProfileId"], $row["messageContent"], $row["messageDateTime"], $row["messageMailgunId"], $row["messageSubject"]);
				$messages[$messages->key()] = $message;
				$messages->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($messages);
	}

	/**
	 * gets the Message by Message subject
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $messageSubject message subject to search for
	 * @return \SplFixedArray SplFixedArray of messages found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getMessageByMessageSubject (\PDO $pdo, string $messageSubject) {
		//sanitize the description before searching
		$messageSubject = trim($messageSubject);
		$messageSubject = filter_var($messageSubject, FILTER_SANITIZE_STRING);
		if(empty($messageSubject) === true) {
			throw(new \PDOException("message subject is invalid"));
		}

		//query the template
		$query = "SELECT messageId, messageReceiveProfileId, messageSentProfileId, messageContent, messageDateTime, messageMailgunId, messageSubject FROM message WHERE messageSubject LIKE :messageSubject";
		$statement = $pdo->prepare($query);

		//bind the message subject to the placeholder in the template
		$messageSubject = "%$messageSubject%";
		$parameters = ["messageSubject" => $messageSubject];
		$statement->execute($parameters);

		//build an array of messages
		$messages = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row =$statement->fetch()) !== false) {
			try {
				$message = new Message($row["messageId"], $row["messageReceiveProfileId"], $row["messageSentProfileId"], $row["messageContent"], $row["messageDateTime"], $row["messageMailgunId"], $row["messageSubject"]);
				$messages[$messages->key()] = $message;
				$messages->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($messages);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["messageDateTime"] = $this->messageDateTime->getTimestamp() * 1000;
		return($fields);
	}
}