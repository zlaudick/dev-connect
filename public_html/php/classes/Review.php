<?php
namespace Edu\Cnm\DevConnect;
require_once("autoload.php");


/**
 * Review (weak) entity
 *
 * Accessor and mutator methods for the following private data:
 * reviewReceiveProfileId(primary composite key)
 * reviewWriteProfileId(primary composite key)
 * reviewContent
 * reviewDateTime
 * reviewRating
 * Public Methods:
 * delete
 * insert
 * update
 * get reviews by content
 * get reviews by profile write
 * get reviews by profile receive
 * get all reviews
 *
 * @author Gerald Sandoval <gsandoval16@cnm.edu>
 * @version
 **/
class Review implements \JsonSerializable {
	use ValidateDate;
	/**
	 * id of the Profile that is receiving a review; this is a component of a composite primary key (and a foreign key)
	 * @var int $reviewReceiveProfileId
	 **/
	private $reviewReceiveProfileId;
	/**
	 * id of the Profile that is writing a review; this is a component of a composite primary key (and a foreign key)
	 * @var int $reviewWriteProfileId
	 **/
	private $reviewWriteProfileId;
	/**
	 * actual text that makes up the content of the review
	 * @var string $reviewContent
	 **/
	private $reviewContent;
	/**
	 * date and time the review was submitted
	 * @var \DateTime $reviewDateTime
	 **/
	private $reviewDateTime;
	/**
	 * the rating of the review
	 * @var int $reviewRating
	 **/
	private $reviewRating;
	/**
	 * constructor for this Like
	 *
	 * @param int $newReviewReceiveProfileId id of the profile receiving the review
	 * @param int $newReviewWriteProfileId id of the profile writing the review
	 * @param string $newReviewContent actual text of the content of the review
	 * @param \DateTime|null $newReviewDateTime date the review was submitted (or null for current time)
	 * @param int $newReviewRating
	 * @throws \Exception if some other exception occurs
	 * @throws \TypeError if data types violate type hints
	 */
	public function __construct(int $newReviewReceiveProfileId = null, int $newReviewWriteProfileId = null,
										 string $newReviewContent, $newReviewDateTime = null,
										 int $newReviewRating = null) {
		// Exceptions
		try {
			$this->setReviewReceiveProfileId($newReviewReceiveProfileId);
			$this->setReviewWriteProfileId($newReviewWriteProfileId);
			$this->setReviewContent($newReviewContent);
			$this->setReviewDateTime($newReviewDateTime);
			$this->setReviewRating($newReviewRating);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			// rethrow the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			// rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			// rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * accessor method for reviewReceiveProfileId id of profile receiving review
	 *
	 * @return int value of profile id
	 **/
	public function getReviewReceiveProfileId() {
		return ($this->reviewReceiveProfileId);
	}
	/**
	 * mutator method for reviewReceiveProfileId id of profile receiving review
	 *
	 * @param int $newReviewReceiveProfileId new value of profile id receiving review
	 * @throws \RangeException if $newReviewReceiveProfileId is not positive
	 * @throws \TypeError if $newReviewReceiveProfileId is not an integer
	 **/
	public function setReviewReceiveProfileId(int $newReviewReceiveProfileId) {
		// verify the profile id is positive
		if($newReviewReceiveProfileId <= 0) {
			throw(new \RangeException("profile id is not positive"));
		}
		// convert and store the profile id
		$this->reviewReceiveProfileId = $newReviewReceiveProfileId;
	}
	/**
	 * accessor method for reviewWriteProfileId id of profile writing review
	 *
	 * @return int value of profile id
	 **/
	public function getReviewWriteProfileId() {
		return ($this->reviewWriteProfileId);
	}
	/**
	 * mutator method for reviewWriteProfileId id of profile writing review
	 *
	 * @param int $newReviewWriteProfileId new value of profile id writing review
	 * @throws \RangeException if $newReviewWriteProfileId is not positive
	 * @throws \TypeError if $newReviewWriteProfileId is not an integer
	 **/
	public function setReviewWriteProfileId(int $newReviewWriteProfileId) {
		// verify the profile id is positive
		if($newReviewWriteProfileId <= 0) {
			throw(new \RangeException("profile id is not positive"));
		}
		// convert and store the profile id
		$this->reviewWriteProfileId = $newReviewWriteProfileId;
	}
	/**
	 * accessor method for review content
	 *
	 * @return string value of review content
	 **/
	public function getReviewContent() {
		return($this->reviewContent);
	}
	/**
	 * mutator method for review content
	 *
	 * @param string $newReviewContent new value of review content
	 * @throws \InvalidArgumentException if $newReviewContent is not a string or insecure
	 * @throws \RangeException if $newReviewContent is > 140 characters
	 * @throws \TypeError if $newReviewContent is not a string
	 **/
	public function setReviewContent(string $newReviewContent) {
		// verify the review content is secure
		$newReviewContent = trim($newReviewContent);
		$newReviewContent = filter_var($newReviewContent, FILTER_SANITIZE_STRING);
		if(empty($newReviewContent) === true) {
			throw(new \InvalidArgumentException("review content is empty or insecure"));
		}
		// verify the review content will fit in the database
		if(strlen($newReviewContent) > 1000) {
			throw(new \RangeException("review content too large"));
		}
		// store the review content
		$this->reviewContent = $newReviewContent;
	}
	/**
	 * accessor method for review date
	 *
	 * @return \DateTime value of review date
	 **/
	public function getReviewDateTime() {
		return ($this->reviewDateTime);
	}
	/**
	 * mutator method for review date
	 *
	 * @param \DateTime|string|null $newReviewDateTime review date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newReviewDateTime is not a valid object or string
	 * @throws \RangeException if $newReviewDateTime is a date that does not exist
	 **/
	public function setReviewDateTime($newReviewDateTime) {
		// base case: if the date is null, use the current date and time
		if($newReviewDateTime === null) {
			$this->reviewDateTime = new \DateTime();
			return;
		}
		// store the review date
		try {
			$newReviewDateTime = self::validateDateTime($newReviewDateTime);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), 0, $range));
		}
		$this->reviewDateTime = $newReviewDateTime;
	}
	/**
	 * accessor method for reviewRating
	 *
	 * @return int value of reviewRating
	 **/
	public function getReviewRating() {
		return ($this->reviewRating);
	}
	/**
	 * mutator method for reviewRating id review rating
	 *
	 * @param int $newReviewRating new value of review rating
	 * @throws \RangeException if $newReviewRating is not positive
	 * @throws \TypeError if $newReviewRating is not an integer
	 **/
	public function setReviewRating(int $newReviewRating) {
		// verify the reviewRating is positive
		if($newReviewRating <= 0) {
			throw(new \RangeException("review rating is not positive"));
		}
		// convert and store the review rating
		$this->reviewRating = $newReviewRating;
	}
	/**
	 * inserts this Review into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		// ensure the object exists before inserting
		if($this->reviewReceiveProfileId === null ||
			$this->reviewWriteProfileId === null) {
			throw(new \PDOException("not a valid like"));
		}

		// create query template
		$query = "INSERT INTO review(reviewReceiveProfileId, 
                                    reviewWriteProfileId,
                                    reviewContent, 
                                    reviewDateTime, 
                                    reviewRating) 
                   VALUES(:reviewReceiveProfileId, :reviewWriteProfileId, 
                           :reviewContent, :reviewDateTime, :reviewRating)";

		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$formattedDate = $this->reviewDateTime->format("Y-m-d H:i:s");

		$parameters = ["reviewReceiveProfileId" => $this->reviewReceiveProfileId,
			"reviewWriteProfileId" => $this->reviewWriteProfileId,
			"reviewContent" => $this->reviewContent,
			"reviewDateTime" => $formattedDate,
			"reviewRating" => $this->reviewRating];

		echo "before execute ";
		$statement->execute($parameters);
		echo "after execute ";

	}
	/**
	 * deletes this Review from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// ensure the object exists before deleting
		if($this->reviewReceiveProfileId === null ||
			$this->reviewWriteProfileId === null) {
			throw(new \PDOException("not a valid like"));
		}
		// create query template
		$query = "DELETE FROM review WHERE reviewReceiveProfileId = :reviewReceiveProfileId AND
                                           reviewWriteProfileId = :reviewWriteProfileId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["reviewReceiveProfileId" => $this->reviewReceiveProfileId,
			"reviewWriteProfileId" => $this->reviewWriteProfileId];
		$statement->execute($parameters);
	}
	/**
	 * updates this Review in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) {
		// enforce the composite Id is not null (i.e., don't update a review that hasn't been inserted)
		if($this->reviewReceiveProfileId === null ||
			$this->reviewWriteProfileId === null) {
			throw(new \PDOException("unable to update a review that does not exist"));
		}
		// create query template
		$query = "UPDATE review SET reviewContent = :reviewContent, reviewRating = :reviewRating, 
                    reviewDateTime = :reviewDateTime 
                  WHERE reviewReceiveProfileId = :reviewReceiveProfileId AND 
                    reviewWriteProfileId = :reviewWriteProfileId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$formattedDate = $this->reviewDateTime->format("Y-m-d H:i:s");
		$parameters = ["reviewContent" => $this->reviewContent,  "reviewDateTime" => $formattedDate, "reviewRating" => $this->reviewRating];
		$statement->execute($parameters);
	}
	/**
	 * gets the Review by content
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $reviewContent review content to search for
	 * @return \SplFixedArray SplFixedArray of Reviews found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getReviewByReviewContent(\PDO $pdo, string $reviewContent) {
		// sanitize the description before searching
		$reviewContent = trim($reviewContent);
		$reviewContent = filter_var($reviewContent, FILTER_SANITIZE_STRING);
		if(empty($reviewContent) === true) {
			throw(new \PDOException("review content is invalid"));
		}
		// create query template
		$query = "SELECT reviewReceiveProfileId, reviewWriteProfileId, 
                  reviewContent, reviewDateTime, reviewRating 
                  FROM review WHERE reviewContent LIKE :reviewContent";
		$statement = $pdo->prepare($query);
		// bind the review content to the place holder in the template
		$reviewContent = "%$reviewContent%";
		$parameters = ["reviewContent" => $reviewContent];
		$statement->execute($parameters);

		// build an array of reviews
		$reviews = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$review = new Review($row["reviewReceiveProfileId"],
					$row["reviewWriteProfileId"],
					$row["reviewContent"], $row["reviewDateTime"],
					$row["reviewRating"]);
				$reviews[$reviews->key()] = $review;
				$reviews->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($reviews);
	}
	/**
	 * get Reviews by reviewReceiveProfileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $reviewReceiveProfileId review receive id to search for
	 * @return \SplFixedArray SplFixedArray of Reviews found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getReviewByReviewReceiveProfileId(\PDO $pdo, int $reviewReceiveProfileId) {
		// sanitize the reviewReceiveProfileId before searching
		if($reviewReceiveProfileId <= 0) {
			throw(new \PDOException("review receive profile id is not positive"));
		}
		// create query template
		$query = "SELECT reviewReceiveProfileId, reviewWriteProfileId, reviewContent, 
                         reviewDateTime, reviewRating 
                         FROM review WHERE reviewReceiveProfileId = :reviewReceiveProfileId";
		$statement = $pdo->prepare($query);
		// bind the review receive profile id to the place holder in the template
		$parameters = ["reviewReceiveProfileId" => $reviewReceiveProfileId];
		$statement->execute($parameters);

		// build an array of reviews
		$reviews = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$review = new Review($row["reviewReceiveProfileId"],
					$row["reviewWriteProfileId"],
					$row["reviewContent"], $row["reviewDateTime"],
					$row["reviewRating"]);
				$reviews[$reviews->key()] = $review;
				$reviews->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($reviews);
	}
	/**
	 * get Reviews by reviewWriteProfileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $reviewWriteProfileId review write id to search for
	 * @return \SplFixedArray SplFixedArray of Reviews found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getReviewByReviewWriteProfileId(\PDO $pdo, int $reviewWriteProfileId) {
		// sanitize the reviewWriteProfileId before searching
		if($reviewWriteProfileId <= 0) {
			throw(new \PDOException("review write profile id is not positive"));
		}
		// create query template
		$query = "SELECT reviewReceiveProfileId, reviewWriteProfileId, reviewContent, 
                         reviewDateTime, reviewRating 
                         FROM review WHERE reviewWriteProfileId = :reviewWriteProfileId";
		$statement = $pdo->prepare($query);
		// bind the review write profile id to the place holder in the template
		$parameters = ["reviewWriteProfileId" => $reviewWriteProfileId];
		$statement->execute($parameters);

		// build an array of reviews
		$reviews = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$review = new Review($row["reviewReceiveProfileId"],
					$row["reviewWriteProfileId"],
					$row["reviewContent"], $row["reviewDateTime"],
					$row["reviewRating"]);
				$reviews[$reviews->key()] = $review;
				$reviews->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($reviews);
	}

	/**
	 * get all Reviews
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Reviews found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllReviews(\PDO $pdo) {
		// create query template
		$query = "SELECT reviewReceiveProfileId, reviewWriteProfileId, 
                         reviewContent, reviewDateTime, reviewRating 
                         FROM review";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of reviews
		$reviews = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$review = new Review($row["reviewReceiveProfileId"],
					$row["reviewWriteProfileId"],
					$row["reviewContent"], $row["reviewDateTime"],
					$row["reviewRating"]);
				$reviews[$reviews->key()] = $review;
				$reviews->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($reviews);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["reviewDateTime"] = intval($this->reviewDateTime->format("U")) * 1000;
		return($fields);
	}
}