<?php
namespace Edu\Cnm\DevConnect;

require_once("autoload.php");
/**
 * Class ProjectTag
 *
 * This class contains everything for a user to be able to link project tags with the project that they browse
 *
 * @author Marcelo Ibarra <mibarra%@cnm.edu>
 **/
	class ProjectTag implements \JsonSerializable {
		/**
		 * this is the Id for this project that this project tag refers to, this is the foreign key
		 * @var int $projectTagProjectId
		 **/
		private $projectTagProjectId;
		/**
		 * this is the Id for this tag that was applied to this specific project tag, this is a foreign key
		 * @var int $projectTagTagId
		 **/
		private $projectTagTagId;

	/**
	 * Constructor for class ProjectTag
	 * @param int $newProjectTagProjectId new value of project tag project Id
	 * @param int $newProjectTagTagId new value of the tag id assigned to this project
	 * @throws \InvalidArgumentException if the data types are not valid
	 * @throws \RangeException if data values are not positive
	 * @throws \TypeError if the data entered is the incorrect type
	 * @throws \Exception if any other type of errors occur
	 **/
	public function __construct(int $newProjectTagProjectId, int $newProjectTagTagId) {
		try {
			$this->setProjectTagProjectId($newProjectTagProjectId);
			$this->setProjectTagTagId($newProjectTagTagId);
		} catch(\InvalidArgumentException $InvalidArgument) {
			//rethrow the exception to the caller
			throw(new \InvalidArgumentException($InvalidArgument->getMessage(), 0, $InvalidArgument));
		} catch(\RangeException $range) {
			//rethrow the exception to the caller
			throw (new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			//rethrow exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\ Exception $exception) {
			//rethrow the exception to the caller
			throw (new \Exception($exception->getMessage(), 0, $exception));
		}
	}

/**
 * accessors and mutators for class projectTagProjectId
 */
/**
 * accessor method for projectTagProjectId
 * @return int value of projectTagProjectId, foreign key
 **/
public function getprojectTagProjectId() {
	return ($this->projectTagProjectId);
}
/**
 * mutator method for projectTagProjectId
 * @param int $newProjectTagProjectId
 * @throws \RangeException if $newprojectTagProjectId is not positive
 * @throws \TypeError if $newprojectTagProjectId is not an integer
 **/
public function setprojectTagProjectId(int $newProjectTagProjectId) {
	//verify the project tag project id content is positive
	if($newProjectTagProjectId <= 0) {
		throw (new \RangeException("project tag project id is not positive"));
	}
	// convert and store the new project tag project id
	$this->projectTagProjectId = $newProjectTagProjectId;
}
/**
 * accessor method for projectTagTagId
 * @return int value of projectTag tag Id, foreign key
 **/
public function getProjectTagTagId() {
	return ($this->getProjectTagTagId);
}
/**
 * mutator method for projectTag tag Id
 * @param int $newProjectTagTagId new value of the tag id assigned to this project
 * @throws \RangeException if $newProjectTagTagId is not positive
 * @throws \TypeError if $newProjectTagTagId is not an integer
 **/
public function setProjectTagTagId(int $newProjectTagTagId) {
	//verify the project tag tag Id content is positive
	if($newProjectTagTagId < 0) {
		throw (new \RangeException("project tag tag Id is not positive"));
	}
	//convert and store the project tag tag Id
	$this->ProjectTagTagId = $newProjectTagTagId;
}
/**
 * inserts this projectTag into mySQL
 * @param \PDO $pdo PDO connection object
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError if $pdo is not a PDO connection object
 **/
	public function insert(\PDO $pdo) {
		//check that the project tag exists before inserting into SQL
		if($this->projectTagProjectId === null || $this->projectTagProjectId === null) {
			throw (new \PDOException("project or tag not valid"));
		}
		//create query template
		$query = "INSERT INTO projectTag(projectTagProjectId, projectTagTagId) VALUES (:projectTagProjectId, :projectTagTagId)";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$parameters = ["projectTagProjectId" => $this->projectTagProjectId, "projectTagTagId" => $this->projectTagProjectId];
		$statement->execute($parameters);
	}
	/**
	 * deletes this project tag from mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occure
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// check that the object exists before deleting it
		if($this->projectTagProjectId === null || $this->projectTagProjectId === null) {
			throw (new \PDOException ("project or tag not valid"));
		}
		//create a query template
		$query = "DELETE FROM projectTag WHERE projectTagProjectId = :projectTagProjectId AND projectTagProjectId = :projectTagTagId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holder in the template
		$parameters = ["projectTagProjectId" => $this->projectTagProjectId, "projectTagTagId" => $this->projectTagTagId];
		$statement->execute($parameters);
	}
	/**
	 * gets the projectTag by project Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $projectTagProjectId the projectId to search for
	 * @return \SplFixedArray of ProjectTags found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProjectTagByProjectId(\PDO $pdo, int $projectTagProjectId) {
		//sanitize the  id
		if($projectTagProjectId < 0) {
			throw (new \PDOException("project id is not positive"));
		}
		//create query template
		$query = "SELECT projectTagProjectId, projectTagTagId FROM projectTag WHERE projectTagProjectId = :projectTagProjectId";
		$statement = $pdo->prepare($query);
		//bind the project id to the place holder in the template
		$parameters = ["projectTagProjectId" => $projectTagProjectId];
		$statement->execute($parameters);
		//build an array of projectTags
		$projectTags = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$projectTag = new ProjectTag($row["projectTagProjectId"], $row["projectTagProjectId"]);
				$projectTags[$projectTags->key()] = $projectTag;
				$projectTags->next();
			} catch(\Exception $exception) {
				//if the row cant be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($projectTags);
	}
	/**
	 * gets the project tag by tag Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $projectTagTagId tag Id to search for
	 * @return \SplFixedArray of ProjectTags found or null if nothing is found
	 * @throws \PDOException when mySQL related errors are found
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProjectTagByTagId(\PDO $pdo, int $projectTagTagId) {
		// sanitize the tag id
		if($projectTagTagId < 0) {
			throw(new \PDOException ("Tag Id is not positive"));
		}
		//create query template
		$query = "SELECT projectTagProjectId, projectTagTagId FROM projectTag WHERE projectTagTagId = :projectTagTagId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the placeholders in the template
		$parameters = ["projectTagTagId" => $projectTagTagId];
		$statement->execute($parameters);
		//build an array of project tags
		$projectTags = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$projectTag = new ProjectTag($row["projectTagProjectId"], $row["projectTagProjectId"]);
				$projectTags[$projectTags->key()] = $projectTag;
				$projectTags->next();
			} catch(\Exception $exception) {
				//if the row could not be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($projectTags);
	}
	//get project tag by project id and tag id
	/**
	 * gets the project tag by both project and tag id
	 * @param \PDO $pdo PDO connection object
	 * @param int $projectTagProjectID project id to search for
	 * @param int $projectTagTagId tag id to search for
	 * @return ProjectTag|null projectTag if found, null if not
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError when variables are not of the correct data type
	 **/
	public static function getProjectTagByProjectIdAndTagId(\PDO $pdo, int $projectTagProjectID, int $projectTagTagId) {
	//sanitize the project id and the tag id before searching
	if($projectTagProjectID < 0) {
		throw (new \PDOException("project id is not positive"));
	}
	if($projectTagTagId < 0) {
		throw (new \PDOException("tag id is not positive"));
	}
	//create a query template
	$query = "SELECT projectTagProjectID, projectTagTagId FROM projectTag WHERE projectTagProjectID = :projectTagProjectID AND projectTagTagId = :projectTagTagId;";
	$statement = $pdo->prepare($query);
	//bind the variables to the placeholders in the template
	$parameters = ["projectTagProjectID" => $projectTagProjectID, "projectTagTagId" => $projectTagTagId];
	$statement->execute($parameters);
	//grab the project tag from mySQL
	try {
		$projectTag = null;
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$row = $statement->fetch();
		if($row !== false) {
			$projectTag = new ProjectTag($row["projectTagProjectID"], $row["projectTagTagId"]);
		}
	} catch(\Exception $exception) {
		throw(new \PDOException($exception->getMessage(), 0, $exception));
	}
	return ($projectTag);
}
	//jsonSerialize
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
	$fields = get_object_vars($this);
	return ($fields);
}
}
