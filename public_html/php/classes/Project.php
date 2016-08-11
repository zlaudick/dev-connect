<?php
namespace Edu\Cnm\DevConnect;

require_once("autoload.php");

/**
 * Project class
 *
 *This class consists of everything required to create and manage a project once submitted by a non-profit organization and to be reviewed or considered by a web-developer.
 *
 * @author Marcelo Ibarra <mibarra5@cnm.edu>
 *
 **/
class Project implements \JsonSerializable {
	use ValidateDate;
	/**
	 * project id; the primary key
	 * @var int $projectId
	 *
	 **/
	private $projectId;
	/**
	 * projectProfileIdId; a foreign key
	 * @var int $projectProfileId
	 **/
	private $projectProfileId;
	/**
	 * projectContent, associated to project contnent
	 * @var int $projectContent
	 **/
	private $projectContent;
	/**
	 * projectDate, the associated timestamp for a project
	 * @var \DateTime $projectDate
	 **/
	private $projectDate;
	/**
	 * projectName, the assigned title given to project
	 * @var int $projectName
	 **/
	private $projectName;

	 /** constructor for project
	 *
	 * @param int|null $newProjectId of this project or null if this is a new project
	 * @param int $newProjectProfileId for the project that is being considered
	 * @param string $newProjectContent for associated project, containing actual project data
	 * @param \DateTime|string|null $projectDate date and time project was posted or null if set to current date and time
	 * @param string $newProjectName string that contains the text of the project
	 * @throws \InvalidArgumentException if the data types are not valid
	 * @throws \RangeException if the data values are out of bounds (e.g., strings are too long, integers are negative or out of range, etc)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newProjectId = null, int $newProjectProfileId, string $newProjectContent, $newProjectDate = null, string $newProjectName) {
		try {
			$this->setProjectId($newProjectId);
			$this->setProjectProfileId($newProjectProfileId);
			$this->setProjectContent($newProjectContent);
			$this->setProjectDate($newProjectDate);
			$this->setProjectName($newProjectName);
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
	//BEGIN ACCESSORS AND MUTATORS FOR PROJECT
	/**
	 * accessor method for project id
	 *
	 * @return int|null value of project id, will be null if this is a new project
	 **/
	public function getProjectId() {

		return ($this->projectId);
	}
	/**
	 * mutator method for project id
	 *
	 * @param int|null $projectId creates new value for project id
	 * @throws \RangeException if $newProjectId is not positive
	 * @throws \TypeError if $newProjectId is not an integer
	 **/
	public function setProjectId($newProjectId = null) {
		//when this is null, this is a new project with no mySQL id yet
		if($newProjectId === null) {
			$this->projectId = null;
			return;
		}
		//verify that the project id is positive
		if($newProjectId <= 0) {
			throw(new \RangeException("project Id is not positive"));
		}
		//convert and store the project id
		$this->projectId = $newProjectId;
	}
	/**
	 * accessor method for project profile id
	 *
	 * @return int value of project profile id, a foreign key
	 **/
	public function getProjectProfileId() {
		return ($this->projectProfileId);
	}
	/**
	 * mutator method of project profile id
	 * @param int $newProjectProfileId creates new value for project profile id
	 * @throws \RangeException if $newProjectProfileId is not positive
	 * @throws \TypeError is $newProjectProfileId is not an integer
	 **/
	public function setProjectProfileId($newProjectProfileId) {
		//verify that the project profile id is positive
		if($newProjectProfileId <= 0) {
			throw(new \RangeException("Project Profile Id is not positive"));
		}
		//convert and store the project profile id
		$this->projectProfileId = $newProjectProfileId;
	}
	/**
	 * accessor method for project content id
	 *
	 * @return int value of project content id, a foreign key
	 **/
	public function getProjectContent() {
		return ($this->projectContent);
	}
	/**
	 * mutator method for project content
	 * @param int $newProjectContent creates a new value for project content
	 * @throws \RangeException if $newProjectContent is not positive
	 * @throws \TypeError if $newProjectContent is not an integer
	 **/
	public function setProjectContent(string $newProjectContent) {
		//verify that the project content id is a positive integer
		$newProjectContent = trim($newProjectContent);
		$newProjectContent = filter_var($newProjectContent, FILTER_SANITIZE_STRING);
		if(empty($newProjectContent) <= 0) {
			throw(new \RangeException("Project Content id is not positive"));
		}
		//convert and store the project content id
		$this->projectContent = $newProjectContent;
	}
	/**
	 * accessor method for project date
	 *
	 * @return \DateTime value for the project
	 **/
	public function getProjectDate() {
		return ($this->projectDate);
	}
	/**
	 * mutator method for project date
	 *
	 * @param \DateTime|string|null $newProjectDate the date of the project as a DateTime object, or null to load the current time
	 * @throws \InvalidArgumentException if $newProjectDate is not a valid object
	 * @throws \RangeException if $newProjectDate is a date that does not exist
	 **/
	public function setProjectDate($newProjectDate = null) {
		//base case-- if the date is null, use the current date and time
		if($newProjectDate === null) {
			$this->projectDate = new \DateTime();
			return;
		}
		//store the project date
		try {
			$newProjectDate = $this->validateDate($newProjectDate);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), 0, $range));
		}
		$this->projectDate = $newProjectDate;
	}
	/**
	 * accessor method for project name
	 *
	 * @return string value of the project name
	 **/
	public function getProjectName() {
		return ($this->projectName);
	}
	/**
	 * mutator method for project name
	 *
	 * @param string $newProjectName sets new value for the project name
	 * @throws \RangeException if project name is not 1-15
	 * @throws \TypeError if the project name is not an string
	 **/
	public function setProjectName($newProjectName) {
		//check that the project name is 1-15
		//or is either 'or' or '||'. || has higher precedence
		if($newProjectName < 1 || $newProjectName > 15) {
			throw(new \RangeException("project name must be between 1 and 15"));
		}
		//Store the project name
		$this->projectName = $newProjectName;
	}

	//BEGIN PDOs FOR PROJECT
	/**
	 * inserts the project into the mySQL database
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		//enforce that the project id is null (aka don't insert a project that already exists)
		if($this->projectId !== null) {
			throw(new \PDOException("Project is not new"));
		}
		//create a query template
		$query = "INSERT INTO project(projectProfileId, projectContent, projectDate, projectName) VALUES (:projectProfileId, :projectContent, :projectDate, :projectName)";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$formattedDate = $this->projectDate->format("Y-m-d H:i:s");
		$parameters = ["projectProfileId" => $this->projectProfileId, "projectContent" => $this->projectContent, "projectDate" => $formattedDate, "projectName" => $this->projectName];
			$statement->execute($parameters);
		//update the null projectId with what mySQL just gave us
		$this->projectId = intval($pdo->lastInsertId());
	}
	/**
	 * delete this project from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		//enforce that that projectId is not null (aka doesn't exist, so cannot delete)
		if($this->projectId === null) {
			throw(new \PDOException("Cannot delete a project that doesn't exist"));
		}
		//create a query template
		$query = "DELETE FROM project WHERE projectId = :project";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$parameters = ["project" => $this->projectId];
		$statement->execute($parameters);
	}
	/**
	 * updates the project in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) {
		//enforce that the project id is not null (aka don't update a project that doesn't exist)
		if($this->projectId === null) {
			throw(new \PDOException("Project is not new"));
		}
		//create a query template
		$query = "UPDATE project SET projectId = :projectId, projectProfileId = :projectProfileId, projectContent = :projectContent,  projectDate = :projectDate, projectName = :projectName WHERE projectId = :projectId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$formattedDate = $this->projectDate->format("Y-m-d H:i:s");
		$parameters = ["projectId"=> $this->projectId,"projectProfileId" => $this->projectProfileId, "projectContent" => $this->projectContent, "projectDate" => $formattedDate, "projectName" => $this->projectName];$statement->execute($parameters);
	}
	/**
	 * get the project by projectId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $projectId the projectId to search for
	 * @return Project|null either the project, or null if not found
	 * @throws \PDOException when mySQL related errors are found
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProjectByProjectId(\PDO $pdo, $projectId) {
		//sanitize the projectId before searching
		if($projectId <= 0) {
			throw(new \PDOException ("Project id is not positive"));
		}
		//create query template
		$query = "SELECT projectId, projectProfileId, projectContent, projectDate, projectName FROM project WHERE projectId = :projectId";
		$statement = $pdo->prepare($query);
		//bind the project id to the place holder in the template
		$parameters = array("projectId" => $projectId);
		$statement->execute($parameters);
		//grab the project from mySQL
		try{
			$project = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$project = new project($row["projectId"], $row["projectProfileId"], $row["projectContent"], \DateTime::createFromFormat("Y-m-d H:i:s", $row["projectDate"]), $row["projectName"]);
			}
		} catch(\Exception $exception) {
			//if the row couldn't be converted rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($project);
	}
	/** get the project by projectProfileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $projectProfileId the projectProfileId to search for
	 * @return \SplFixedArray SplFixedArray of projects or null if not found
	 * @throws \PDOException when mySQL related errors are found
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProjectByProfileId(\PDO $pdo, $projectProfileId) {
		//sanitize the projectId before searching
		if($projectProfileId <= 0) {
			throw(new \PDOException ("project id is not positive"));
		}
		//create query template
		$query = "SELECT projectId, projectProfileId, projectContent, projectDate, projectName FROM project WHERE projectProfileId = :projectProfileId";
		$statement = $pdo->prepare($query);
		//bind the project id to the place holder in the template
		$parameters = array("projectProfileId" => $projectProfileId);
		$statement->execute($parameters);
		//build an array of projects
		$projects = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$project = new project($row["projectId"], $row["projectProfileId"], $row["projectContent"], \DateTime::createFromFormat("Y-m-d H:i:s", $row["projectDate"]));
				$projects[$projects->key()] = $project;
				$projects->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($projects);
	}
	/** get the project by project content id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $projectContentId the projectContetId to search for
	 * @return \SplFixedArray SplFixedArray of projects or null if not found
	 * @throws \PDOException when mySQL related errors are found
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProjectByProjectContent(\PDO $pdo, string $projectContent) {
		//sanitize the contetId before searching
		if($projectContent <= 0) {
			throw(new \PDOException ("content id is not positive"));
		}
		//create query template
		$query = "SELECT projectId, projectProfileId, projectContent, projectDate, projectName FROM project
 WHERE projectContent = :projectContentId";
		$statement = $pdo->prepare($query);
		//bind the content id to the place holder in the template
		$parameters = array("projectContent" => $projectContent);
		$statement->execute($parameters);
		//build an array of projects
		$projects = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$project = new project($row["projectId"], $row["projectProfileId"], $row["projectContent"], \DateTime::createFromFormat("Y-m-d H:i:s", $row["projectDate"]), $row["projectName"]);
				$projects[$projects->key()] = $project;
				$projects->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($projects);
	}
	/** get the project by project date
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $projectDate the projectDate to search for
	 * @return \SplFixedArray SplFixedArray of projects or null if not found
	 * @throws \PDOException when mySQL related errors are found
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProjectByDate(\PDO $pdo, $projectDate) {
		//sanitize the projectName before searching
		if($projectDate <= 0) {
			throw(new \PDOException ("project id is not positive"));
		}
		//create query template
		$query = "SELECT projectId, projectProfileId, projectContent, projectDate, projectName FROM project WHERE projectDate = :projectDate";
		$statement = $pdo->prepare($query);
		//bind the project id to the place holder in the template
		$parameters = array("projectDate" => $projectDate);
		$statement->execute($parameters);
		//build an array of projects
			$projects = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$project = new Project($row["ProjectId"], $row["projectProfileId"], $row["projectContent"], \DateTime::createFromFormat("Y-m-d H:i:s", $row["projectDate"],$row["projectDate"]));
					$projects[$projects->key()] = $project;
					$projects->next();
				} catch(\Exception $exception) {
					//if the row couldn't be converted rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($projects);
	}
	/**
	 * get project by project name
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $projectName to search for
	 * @return \SplFixedArray SplFixedArray of projects that are found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProjectByProjetName(\PDO $pdo, $projectName) {
		//check that the project length is 1-15
		if($projectName < 1 || $projectName > 15) {
			throw(new \RangeException("Project Name must be between 1 and 15"));
		}
		//create query template
		$query = "SELECT projectId, projectProfileId, projectContent, projectDate, projectName FROM project WHERE projectName LIKE :projectName";
		$statement = $pdo->prepare($query);
		//bind the project name to the place holder in the template
		$parameters = array("projectName" => $projectName);
		$statement->execute($parameters);
		//build an array of projects
		$projects = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$project = new Project($row["projectId"], $row["projectProfieIdId"], $row["projectContent"], \DateTime::createFromFormat("Y-m-d H:i:s", $row["projectDate"]), $row["projectDate"]);
				$projects[$projects->key()] = $project;
				$projects->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($projects);
	}

//jsonSerialize
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["projectDate"] = intval($this->projectDate->format("U")) * 1000;
		return ($fields);
	}
}

