<?php
namespace Edu\Cnm\DevConnect;

require_once("autoload.php");

/**
 * ProfileImage class for DevConnect
 *
 * @author Zac Laudick <zlaudick@cnm.edu>
 **/
class ProfileImage implements \JsonSerializable {
	/**
	 * foreign key, this is part of the composite key
	 * @var int $profileImageProfileId
	 **/
	private $profileImageProfileId;

	/**
	 * foreign key, this is part of the composite key
	 * @var int $profileImageImageId
	 **/
	private $profileImageImageId;

	/**
	 * constructor for this profile image
	 *
	 * @param int $newProfileImageProfileId foreign key part of composite primary key
	 * @param int $newProfileImageImageId foreign key part of composite primary key
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newProfileImageProfileId, int $newProfileImageImageId) {
		try {
			$this->setProfileImageProfileId($newProfileImageProfileId);
			$this->setProfileImageImageId($newProfileImageImageId);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			// rethrows the exception to the caller
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
	 * accessor method for profileImageProfileId
	 * @return int|null value of profileImageProfileId
	 **/
	public function getProfileImageProfileId() {
		return($this->profileImageProfileId);
	}

	/**
	 * mutator method for profileImageProfileId
	 *
	 * @param int|null $newProfileImageProfileId new value of profileImageProfileId
	 * @throws \RangeException if $newProfileImageProfileId is not positive
	 * @throws \TypeError if $newProfileImageProfileId is not an integer
	 **/
	public function setProfileImageProfileId(int $newProfileImageProfileId) {
		// verify the profileImageProfileId is positive
		if($newProfileImageProfileId <= 0) {
			throw(new \RangeException("profileImageProfileId is not positive"));
		}

		// convert and store the profileImageProfileId
		$this->profileImageProfileId = $newProfileImageProfileId;
	}

	/**
	 * accessor method for profileImageImageId
	 * @return int|null value of profileImageImageId
	 **/
	public function getProfileImageImageId() {
		return($this->profileImageImageId);
	}

	/**
	 * mutator method for profileImageImageId
	 *
	 * @param int|null $newProfileImageImageId new value of profileImageImageId
	 * @throws \RangeException if $newProfileImageImageId is not positive
	 * @throws \TypeError if $newProfileImageImageId is not an integer
	 **/
	public function setProfileImageImageId(int $newProfileImageImageId) {
		// verify the profileImageImageId is positive
		if($newProfileImageImageId <= 0) {
			throw(new \RangeException("profileImageImageId is not positive"));
		}

		// convert and store the profileImageImageId
		$this->profileImageImageId = $newProfileImageImageId;
	}

	/**
	 * inserts profileImage composite primary key info into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		// enforce the profileImageProfileId or profileImageImageId is not null
		if($this->profileImageProfileId === null || $this->profileImageImageId === null) {
			throw(new \PDOException("not a valid composite key"));
		}

		// create query template
		$query = "INSERT INTO profileImage(profileImageProfileId, profileImageImageId) VALUES(:profileImageProfileId, :profileImageImageId)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["profileImageProfileId" => $this->profileImageProfileId, "profileImageImageId" => $this->profileImageImageId];
		$statement->execute($parameters);
	}

	/**
	 * deletes this profileImage composite primary key from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// enforce the profileImageProfileId and profileImageImageId is not null
		if($this->profileImageProfileId === null && $this->profileImageImageId === null) {
			throw(new \PDOException("unable to delete a profileImage that does not exist"));
		}

		// create query template
		$query = "DELETE FROM profileImage WHERE (profileImageProfileId = :profileImageProfileId AND profileImageImageId = :profileImageImageId)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["profileImageProfileId" => $this->profileImageProfileId, "profileImageImageId" => $this->profileImageImageId];
		$statement->execute($parameters);
	}

	/**
	 * get the profileImage by profileImageProfileIdAndImageId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $profileImageProfileId profileImageProfileId to search for
	 * @param int $profileImageImageId profileImageImageId to search for
	 * @return profileImage profileImage found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProfileImageByProfileImageProfileIdAndImageId(\PDO $pdo, int $profileImageProfileId, int $profileImageImageId) {
		// sanitize the profileImageProfileId before searching
		if($profileImageProfileId <= 0) {
			throw(new \PDOException("profileImageProfileId is not positive"));
		}

		// sanitize the profileImageImageId before searching
		if($profileImageImageId <= 0) {
			throw(new \PDOException("profileImageImageId is not positive"));
		}

		// create query template
		$query = "SELECT profileImageProfileId, profileImageImageId FROM profileImage WHERE profileImageProfileId = :profileImageProfileId AND profileImageImageId = :profileImageImageId";
		$statement = $pdo->prepare($query);

		// bind the composite key to the place holder in the template
		$parameters = array("profileImageProfileId" => $profileImageProfileId, "profileImageImageId" => $profileImageImageId);
		$statement->execute($parameters);

		// grab the category from mySQL
		try {
			$profileImage = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profileImage = new ProfileImage($row["profileImageProfileId"], $row["profileImageImageId"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($profileImage);
	}

	/**
	 * gets profileImage by profileImageProfileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $profileImageProfileId profileImageProfileId to search for
	 * @return \SplFixedArray SplFixedArray of profileImageProfileId's found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProfileImageByProfileImageProfileId(\PDO $pdo, int $profileImageProfileId) {
		// sanitize the profileImageProfileId before searching
		if($profileImageProfileId <= 0) {
			throw(new \PDOException("profileImageProfileId is not positive"));
		}
		// create query template
		$query = "SELECT profileImageProfileId, profileImageImageId FROM profileImage WHERE profileImageProfileId = :profileImageProfileId";
		$statement = $pdo->prepare($query);

		// bind the profileImageProfileId to the place holder in the template
		$parameters = array("profileImageProfileId" => $profileImageProfileId);
		$statement->execute($parameters);

		// build an array of profileImages
		$profileImages = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profileImage = new ProfileImage($row["profileImageProfileId"], $row["profileImageImageId"]);
				$profileImages[$profileImages->key()] = $profileImage;
				$profileImages->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($profileImages);
	}

	/**
	 * gets all profileImage primary keys
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of profileImages found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllProfileImages(\PDO $pdo) {
		// create query template
		$query = "SELECT profileImageProfileId, profileImageImageId FROM profileImage";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of profileImages
		$profileImages = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profileImage = new ProfileImage($row["profileImageProfileId"], $row["profileImageImageId"]);
				$profileImages[$profileImages->key()] = $profileImage;
				$profileImages->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($profileImages);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return($fields);
	}
}