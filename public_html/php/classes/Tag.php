<?php
namespace Edu\Cnm\DevConnect;
require_once("autoload.php");
/**
 * Tag (strong) entity
 *
 * Accessor and mutator methods for the following private data:
 * tagId(primary key)
 * tagName
 * Public Methods:
 * delete
 * insert
 * update
 * get tags by content
 * get tag by tag id
 * get all tags
 *
 * @author Gerald Sandoval <gsandoval16@cnm.edu>
 * @version
 **/
class Tag implements \JsonSerializable {
	/**
	 * id for this tag entity; this is the primary key
	 * @var int $tagId
	 **/
	private $tagId;
	/**
	 * textual content which is the name of the tag
	 * @var string $tagName
	 **/
	private $tagName;
	/**
	 * @var int|null
	 */
	private $newTagId;

	/**
	 * constructor for this tag
	 *
	 * @param int|null $newTagId id of this Tag or null if a new Tag
	 * @param string $newTagName string containing actual Tag data
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newTagId = null, string $newTagName) {
		try {
			$this->setTagId($newTagId);
			$this->setTagName($newTagName);
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
		$this->newTagId = $newTagId;
	}
	/**
	 * mutator method for the tag id primary key
	 *
	 * @param int $newTagId new value of the tag id
	 * @throws \RangeException if $newTagId is not positive
	 * @throws \TypeError if $newTagId is not an integer
	 **/
	public function setTagId(int $newTagId) {
		// verify the tag id is positive
		if($newTagId <= 0) {
			throw(new \RangeException("tag id is not positive"));
		}

		// convert and store the profile id
		$this->tagId = $newTagId;
	}
	/**
	 * accessor method for tag id
	 *
	 * @return int|null value of tag id
	 **/
	public function getTagId() {
		return($this->tagId);
	}
	/**
	 * mutator method for the tag name
	 *
	 * @param string $newTagName new value of tag name
	 * @throws \InvalidArgumentException if $newTagName is not a string or insecure
	 * @throws \RangeException if $newTagName is > 64 bytes (max size for this database field)
	 * @throws \TypeError if $newTagName is not a string
	 **/
	public function setTagName(string $newTagName) {
		// verify the article content is secure
		$newTagName = trim($newTagName);
		$newTagName = filter_var($newTagName, FILTER_SANITIZE_STRING);
		if(empty($newTagName) === true) {
			throw(new \InvalidArgumentException("tag name is empty or insecure"));
		}

		// verify the tag name content will fit in the database
		if(strlen($newTagName) > 64) {
			throw(new \RangeException("tag name too large"));
		}

		// store the article content
		$this->tagName = $newTagName;
	}
	/**
	 * accessor method for tag name
	 *
	 * @return string value of tag name
	 **/
	public function getTagName() {
		return($this->tagName);
	}
	/**
	 * insert this Tag into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		// enforce the tagId is null (i.e., don't insert a tag that already exists)
		if($this->tagId !== null) {
			throw(new \PDOException("not a new tag"));
		}
		// create query template
		$query = "INSERT INTO tag(tagId, tagName) VALUES(:tagId, :tagName)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["tagId" => $this->tagId, "tagName" => $this->tagName];
		$statement->execute($parameters);
		// update the null tagId with what mySQL just gave us
		$this->tagId = intval($pdo->lastInsertId());
	}
	/**
	 * delete this Tag from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// enforce the tagId is not null (i.e., don't delete a tag that hasn't been inserted)
		if($this->tagId === null) {
			throw(new \PDOException("unable to delete a tag that does not exist"));
		}
		// create query template
		$query = "DELETE FROM tag WHERE tagId = :tagId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holder in the template
		$parameters = ["tagId" => $this->tagId];
		$statement->execute($parameters);
	}
	/**
	 * updates this Tag in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) {
		// enforce the tagId is not null (i.e., don't update a tag that hasn't been inserted)
		if($this->tagId === null) {
			throw(new \PDOException("unable to update a tag that does not exist"));
		}
		// create query template
		$query = "UPDATE tag SET tagId = :tagId, tagName = :tagName WHERE tagId = :tagId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["tagId" => $this->tagId, "tagName" => $this->tagName];
		$statement->execute($parameters);
	}
	/**
	 * gets Tags by content
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $tagContent tag content to search for
	 * @return \SplFixedArray SplFixedArray of Tags found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getTagByTagContent(\PDO $pdo, string $tagContent) {
		// sanitize the description before searching
		$tagContent = trim($tagContent);
		$tagContent = filter_var($tagContent, FILTER_SANITIZE_STRING);
		if(empty($tagContent) === true) {
			throw(new \PDOException("tag content is invalid"));
		}
		// create query template
		$query = "SELECT tagId, tagName
                  FROM tag WHERE tagName LIKE :tagContent";
		$statement = $pdo->prepare($query);
		// bind the tag content to the place holder in the template
		$tagContent = "%$tagContent%";
		$parameters = ["tagContent" => $tagContent];
		$statement->execute($parameters);

		// build an array of tags
		$tags = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$tag = new Review($row["tagId"], $row["tagName"]);
				$tags[$tags->key()] = $tag;
				$tags->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($tags);
	}
	/**
	 * get Tag by tagId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $tagId tag id to search for
	 * @return Tag|null Tag found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getTagByTagId(\PDO $pdo, int $tagId) {
		// sanitize the tagId before searching
		if($tagId <= 0) {
			throw(new \PDOException("tag id is not positive"));
		}
		// create query template
		$query = "SELECT tagId, tagName
                         FROM tag WHERE tagId = :tagId";
		$statement = $pdo->prepare($query);
		// bind the tag id to the place holder in the template
		$parameters = ["tagId" => $tagId];
		$statement->execute($parameters);

		// grab the tag from mySQL
		try {
			$tag = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$tag = new Tag($row["tagId"], $row["tagName"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($tag);
	}
	/**
	 * get all Tags
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Tags found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllTags(\PDO $pdo) {
		// create query template
		$query = "SELECT tagId, tagName FROM tag";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of tags
		$tags = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$tag = new Review($row["tagId"], $row["tagName"]);
				$tags[$tags->key()] = $tag;
				$tags->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($tags);
	}
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 * stub for now
	 **/
	public function jsonSerialize() {
		//$fields = get_object_vars($this);
		//$fields["likeDate"] = intval($this->likeDate->format("U")) * 1000;
		//return($fields);
		return(0);
	}
}
