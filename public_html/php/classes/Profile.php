<?php
namespace Edu\Cnm\DevConnect;

require_once("autoload.php");

/**
 * Profile class for DevConnect
 *
 * @author Zac Laudick <zlaudick@cnm.edu>
 **/
class Profile implements \JsonSerializable {
	use ValidateDate;
	/**
	 * id for this Profile; this is the primary key
	 * @var int $profileId
	 **/
	private $profileId;
	/**
	 * account type of this Profile
	 * @var string $profileAccountType
	 **/
	private $profileAccountType;
	/**
	 * activation token for this Profile
	 * @var string $profileActivationToken
	 **/
	private $profileActivationToken;
	/**
	 * approval status of this Profile
	 * @var bool $profileApproved
	 **/
	private $profileApproved;
	/**
	 * profile id that approved this Profile
	 * @var int $profileApprovedById
	 **/
	private $profileApprovedById;
	/**
	 * datetime stamp of the Profile approval
	 * @var \DateTime $profileApprovedDateTime
	 **/
	private $profileApprovedDateTime;
	/**
	 * content of the Profile
	 * @var string $profileContent
	 **/
	private $profileContent;
	/**
	 * email of the Profile
	 * @var string $profileEmail
	 **/
	private $profileEmail;
	/**
	 * github access token for the Profile
	 * @var string $profileGithubAccessToken
	 **/
	private $profileGithubAccessToken;
	/**
	 * password hash for this Profile
	 * @var string $profileHash
	 **/
	private $profileHash;
	/**
	 * location of the Profile
	 * @var string $profileLocation
	 **/
	private $profileLocation;
	/**
	 * name of this Profile
	 * @var string $profileName
	 **/
	private $profileName;
	/**
	 * password salt for this Profile
	 * @var string $profileSalt
	 **/
	private $profileSalt;

	/**
	 * constructor for this Profile
	 *
	 * @param int|null $newProfileId id of this Profile or null if new Profile
	 * @param string $newProfileAccountType account type of this Profile
	 * @param string $newProfileActivationToken activation token for this Profile
	 * @param bool $newProfileApproved approval status of this Profile
	 * @param int $newProfileApprovedById profileId that approved this Profile
	 * @param \DateTime|string|null $newProfileApprovedDateTime time stamp when this Profile was approved or null if set to current date and time
	 * @param string $newProfileContent content of this Profile
	 * @param string $newProfileEmail email associated with this Profile
	 * @param string $newProfileGithubAccessToken github access token for this Profile
	 * @param string $newProfileHash password hash for this Profile
	 * @param string $newProfileLocation location of this Profile
	 * @param string $newProfileName name of this Profile
	 * @param string $newProfileSalt password salt for this Profile
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newProfileId = null, string $newProfileAccountType, string $newProfileActivationToken = null, bool $newProfileApproved = null, int $newProfileApprovedById = null, $newProfileApprovedDateTime = null, string
	$newProfileContent, string $newProfileEmail, string $newProfileGithubAccessToken = null, string $newProfileHash = null, string $newProfileLocation, string $newProfileName, string $newProfileSalt = null) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileAccountType($newProfileAccountType);
			$this->setProfileActivationToken($newProfileActivationToken);
			$this->setProfileApproved($newProfileApproved);
			$this->setProfileApprovedById($newProfileApprovedById);
			$this->setProfileApprovedDateTime($newProfileApprovedDateTime);
			$this->setProfileContent($newProfileContent);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileGithubAccessToken($newProfileGithubAccessToken);
			$this->setProfileHash($newProfileHash);
			$this->setProfileLocation($newProfileLocation);
			$this->setProfileName($newProfileName);
			$this->setProfileSalt($newProfileSalt);
		} catch (\InvalidArgumentException $invalidArgument){
			// rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch (\RangeException $range) {
			// rethrow the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch (\TypeError $typeError) {
			// rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch (\Exception $exception) {
			// rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for profile id
	 * @return int|null value of profile id
	 **/
	public function getProfileId() {
		return($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param int|null $newProfileId new value of profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not an integer
	 **/
	public function setProfileId(int $newProfileId = null) {
		// base case: if the profile id is null, this is a new profile id without a mySQL assigned id (yet)
		if ($newProfileId === null) {
			$this->profileId = null;
			return;
		}

		// verify the profile id is positive
		if ($newProfileId <=0) {
			throw(new \RangeException("profile id is not positive"));
		}

		// convert and store the profile id
		$this->profileId = $newProfileId;
	}

	/**
	 * accessor method for profile account type
	 * @return string value of profile account type
	 **/
	public function getProfileAccountType() {
		return($this->profileAccountType);
	}

	/**
	 * mutator method for profile account type
	 *
	 * @param string $newProfileAccountType new account type of the profile
	 * @throws \InvalidArgumentException if $newProfileAccountType is not a string or insecure
	 * @throws \RangeException if $newProfileAccountType is !== 1 character
	 * @throws \TypeError if $newProfileAccountType is not a string
	 **/
	public function setProfileAccountType(string $newProfileAccountType) {
		$newProfileAccountType = strtoupper($newProfileAccountType);
 		$validAccountTypes = ["D", "O", "A"];
		if(in_array($newProfileAccountType, $validAccountTypes) === false) {
			throw(new \InvalidArgumentException("Not a valid account type."));
		}

		// store the account type
		$this->profileAccountType = $newProfileAccountType;
	}

	/**
	 * accessor method for profile activation token
	 * @return string value of profile activation token
	 **/
	public function getProfileActivationToken() {
		return($this->profileActivationToken);
	}

	/**
	 * mutator method for profile activation token
	 *
	 * @param string $newProfileActivationToken new value of profile activation token
	 * @throws \InvalidArgumentException if $newProfileActivationToken is not a string or insecure
	 * @throws \RangeException if $newProfileActivationToken is !== 32 characters
	 * @throws \TypeError if $newProfileActivationToken is not a string
	 **/
	public function setProfileActivationToken(string $newProfileActivationToken = null) {
		if ($newProfileActivationToken === null) {
			$this->profileActivationToken = null;
			return;
		}
		// verify the activation token is secure
		$newProfileActivationToken = trim($newProfileActivationToken);
		$newProfileActivationToken = filter_var($newProfileActivationToken, FILTER_SANITIZE_STRING);
		if(empty($newProfileActivationToken) === true) {
			throw(new \InvalidArgumentException("activation token is empty or insecure"));
		}

		// verify the activation token will fit in the database
		if(strlen($newProfileActivationToken) !== 32) {
			throw(new \RangeException("activation token is not 32 characters"));
		}

		// store the activation token
		$this->profileActivationToken = $newProfileActivationToken;
	}

	/**
	 * accessor method for profile approved
	 * @returns bool value of profile approved
	 **/
	public function getProfileApproved() {
		return($this->profileApproved);
	}

	/**
	 * mutator method for profile approved
	 *
	 * @param bool $newProfileApproved new value of profile approved
	 * @throws \TypeError if $newProfileApproved is not a boolean
	 **/
	public function setProfileApproved(bool $newProfileApproved = null) {
		if ($newProfileApproved === null) {
			$this->profileApproved = null;
			return;
		}
		// verify the profile approved value is boolean
		if(is_bool($newProfileApproved) === false) {
			throw(new \TypeError("this is not a boolean value"));
		}

		// convert and store the profile approved value
		$this->profileApproved = $newProfileApproved;
	}

	/**
	 * accessor method for profile approved by id
	 * @returns int value of profile approved by id
	 **/
	public function getProfileApprovedById() {
		return($this->profileApprovedById);
	}

	/**
	 * mutator method for profile approved by id
	 *
	 * @param int $newProfileApprovedById new value of profile approved by id
	 * @throws \RangeException if $newProfileApprovedById is not positive
	 * @throws \TypeError if $newProfileApprovedById is not an integer
	 **/
	public function setProfileApprovedById(int $newProfileApprovedById = null) {
		if ($newProfileApprovedById === null) {
			$this->profileApprovedById = null;
			return;
		}
		// verify the profile approved by id is positive
		if($newProfileApprovedById <= 0) {
			throw(new \RangeException("profile approved by id is not positive"));
		}

		// convert and store the profile approved by id
		$this->profileApprovedById = $newProfileApprovedById;
	}

	/**
	 * accessor method for profile approved date time
	 * @return \DateTime value of profile approved date time
	 **/
	public function getProfileApprovedDateTime() {
		return($this->profileApprovedDateTime);
	}

	/**
	 * mutator method for profile approved date time
	 *
	 * @param \DateTime|string|null $newProfileApprovedDateTime new value of profile approved date time
	 * @throws \InvalidArgumentException if $newProfileApprovedDateTime is not a valid object or string
	 * @throws \RangeException if $newProfileApprovedDateTime is a date that does not exist
	 **/
	public function setProfileApprovedDateTime($newProfileApprovedDateTime = null) {
		// base case: if the date is null, use the current date and time
		if($newProfileApprovedDateTime === null) {
			$this->profileApprovedDateTime = null;
			return;
		}

		// store the profile approved date time
		try {
			$newProfileApprovedDateTime = self::validateDateTime($newProfileApprovedDateTime);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), 0, $range));
		}
		$this->profileApprovedDateTime = $newProfileApprovedDateTime;
	}

	/**
	 * accessor method for profile content
	 * @return string value of profile content
	 **/
	public function getProfileContent() {
		return($this->profileContent);
	}

	/**
	 * mutator method for profile content
	 *
	 * @param string $newProfileContent new value of profile content
	 * @throws \InvalidArgumentException if $newProfileContent is not a string or insecure
	 * @throws \RangeException if $newProfileContent is > 2000 characters
	 * @throws \TypeError if $newProfileContent is not a string
	 **/
	public function setProfileContent(string $newProfileContent) {
		// verify the content is secure
		$newProfileContent = trim($newProfileContent);
		$newProfileContent = filter_var($newProfileContent, FILTER_SANITIZE_STRING);
		if(empty($newProfileContent) === true) {
			throw(new \InvalidArgumentException("content is empty or insecure"));
		}

		// verify the content will fit in the database
		if(strlen($newProfileContent) > 2000) {
			throw(new \RangeException("content is too large"));
		}

		// store the content
		$this->profileContent = $newProfileContent;
	}

	/**
	 * accessor method for profile email
	 * @return string value of profile email
	 **/
	public function getProfileEmail() {
		return($this->profileEmail);
	}

	/**
	 * mutator method for profile email
	 *
	 * @param string $newProfileEmail new value of profile email
	 * @throws \InvalidArgumentException if $newProfileEmail is not a string or insecure
	 * @throws \RangeException if $newProfileEmail is > 128 characters
	 * @throws \TypeError if $newProfileEmail is not a string
	 **/
	public function setProfileEmail(string $newProfileEmail) {
		// verify the email is secure
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_SANITIZE_STRING);
		if(empty($newProfileEmail) === true) {
			throw(new \InvalidArgumentException("email is empty or insecure"));
		}

		// verify the email will fit in the database
		if(strlen($newProfileEmail) > 128) {
			throw(new \RangeException("email is too large"));
		}

		// convert and store the email
		$this->profileEmail = $newProfileEmail;
	}

	/**
	 * accessor method for profile github access token
	 * @return string value of profile github access token
	 **/
	public function getProfileGithubAccessToken() {
		return($this->profileGithubAccessToken);
	}

	/**
	 * mutator method for profile github access token
	 *
	 * @param string $newProfileGithubAccessToken new value of profile github access token
	 * @throws \InvalidArgumentException if $newProfileGithubAccessToken is not a string or insecure
	 * @throws \RangeException if $newProfileGithubAccessToken is not 64 characters
	 * @throws \TypeError if $newProfileGithubAccessToken is not a string
	 **/
	public function setProfileGithubAccessToken(string $newProfileGithubAccessToken = null) {
		if ($newProfileGithubAccessToken === null) {
			$this->profileGithubAccessToken = null;
			return;
		}
		// verify the github access token is secure
		$newProfileGithubAccessToken = trim($newProfileGithubAccessToken);
		$newProfileGithubAccessToken = filter_var($newProfileGithubAccessToken, FILTER_SANITIZE_STRING);
		if(empty($newProfileGithubAccessToken) === true) {
			throw(new \InvalidArgumentException("github access token is emtpy or insecure"));
		}

		// verify the github access token will fit in the database
		if(strlen($newProfileGithubAccessToken) > 64) {
			throw(new \RangeException("github access token is too large"));
		}

		// convert and store the github access token
		$this->profileGithubAccessToken = $newProfileGithubAccessToken;
	}

	/**
	 * accessor method for profile hash
	 * @return string value of profile hash
	 **/
	public function getProfileHash() {
		return($this->profileHash);
	}

	/**
	 * mutator method for profile hash
	 *
	 * @param string $newProfileHash new value of profile hash
	 * @throws \InvalidArgumentException if $newProfileHash is not a string or insecure
	 * @throws \RangeException if $newProfileHash is !== 128 characters
	 * @throws \TypeError if $newProfileHash is not a string
	 **/
	public function setProfileHash(string $newProfileHash = null) {
		if ($newProfileHash === null) {
			$this->profileHash = null;
			return;
		}
		// verify the hash is secure
		if(empty($newProfileHash)) {
			throw(new \InvalidArgumentException("hash is empty or insecure"));
		}

		// verify the hash is a hexadecimal
		if(!ctype_xdigit($newProfileHash)) {
			throw(new \InvalidArgumentException("hash is empty or insecure"));
		}

		// verify the hash will fit in the database
		if(strlen($newProfileHash) !== 128) {
			throw(new \RangeException("hash is not of valid length"));
		}

		// store the hash
		$this->profileHash = $newProfileHash;
	}

	/**
	 * accessor method for profile location
	 * @return string value of profile location
	 **/
	public function getProfileLocation() {
		return($this->profileLocation);
	}

	/**
	 * mutator method for profile location
	 *
	 * @param string $newProfileLocation new value of profile location
	 * @throws \InvalidArgumentException if $newProfileLocation is not a string or insecure
	 * @throws \RangeException if $newProfileLocation is > 64 characters
	 * @throws \TypeError if $newProfileLocation is not a string
	 **/
	public function setProfileLocation(string $newProfileLocation) {
		// verify the profile location is secure
		$newProfileLocation = trim($newProfileLocation);
		$newProfileLocation = filter_var($newProfileLocation, FILTER_SANITIZE_STRING);
		if(empty($newProfileLocation) === true) {
			throw(new \InvalidArgumentException("profile location is empty or insecure"));
		}

		// verify the profile location will fit in the database
		if(strlen($newProfileLocation) > 64) {
			throw(new \RangeException("profile location is too large"));
		}

		// convert and store the profile location
		$this->profileLocation = $newProfileLocation;
	}

	/**
	 * accessor method for profile name
	 * @return string value of profile name
	 **/
	public function getProfileName() {
		return($this->profileName);
	}

	/**
	 * mutator method for profile name
	 *
	 * @param string $newProfileName new value of profile name
	 * @throws \InvalidArgumentException if $newProfileName is not a string or insecure
	 * @throws \RangeException if $newProfileName is > 32 characters
	 * @throws \TypeError if $newProfileName is not a string
	 **/
	public function setProfileName(string $newProfileName) {
		// verify the profile name is secure
		$newProfileName = trim($newProfileName);
		$newProfileName = filter_var($newProfileName, FILTER_SANITIZE_STRING);
		if(empty($newProfileName) === true) {
			throw(new \InvalidArgumentException("profile name is empty or insecure"));
		}

		// verify the profile name will fit in the database
		if(strlen($newProfileName) > 32) {
			throw(new \RangeException("profile name is too large"));
		}

		// convert and store the profile name
		$this->profileName = $newProfileName;
	}

	/**
	 * accessor method for profile salt
	 * @return string value of profile salt
	 **/
	public function getProfileSalt() {
		return($this->profileSalt);
	}

	/**
	 * mutator method for profile salt
	 *
	 * @param string $newProfileSalt new value of profile salt
	 * @throws \InvalidArgumentException if $newProfileName is not a string or insecure
	 * @throws \RangeException if $newProfileName is !== 64 characters
	 * @throws \TypeError if $newProfileName is not a string
	 **/
	public function setProfileSalt(string $newProfileSalt = null) {
		if ($newProfileSalt === null) {
			$this->profileSalt = null;
			return;
		}
		// verify the profile salt is secure
		if(empty($newProfileSalt) === true) {
			throw(new \InvalidArgumentException("profile salt is empty or insecure"));
		}

		// verify the salt is a hexadecimal
		if(!ctype_xdigit($newProfileSalt)) {
			throw(new \InvalidArgumentException("profile salt is empty or insecure"));
		}

		// verify the salt will fit in the database
		if(strlen($newProfileSalt) !== 64) {
			throw(new \RangeException("profile salt is not of valid length"));
		}

		// store the profile salt
		$this->profileSalt = $newProfileSalt;
	}

	/**
	 * inserts this Profile in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		// enforce the user is null
		if($this->profileId !== null) {
			throw(new \PDOException("profile already exists"));
		}

		// create query template
		$query = "INSERT INTO profile(profileAccountType, profileActivationToken, profileApproved, profileApprovedById, profileApprovedDateTime, profileContent, profileEmail, profileGithubAccessToken, profileHash, profileLocation, profileName, profileSalt) VALUES(:profileAccountType, :profileActivationToken, :profileApproved, :profileApprovedById, :profileApprovedDateTime, :profileContent, :profileEmail, :profileGithubAccessToken, :profileHash, :profileLocation, :profileName, :profileSalt)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		if (empty($this->profileApprovedDateTime) === true) {
			$formattedDate = null;
		} else {
			$formattedDate = $this->profileApprovedDateTime->format("Y-m-d H:i:s");
		}
		$formattedProfileApproved = 1;
		if ($this->profileApproved === false) {
			$formattedProfileApproved = 0;
		}
		$parameters = [
			"profileAccountType" => $this->profileAccountType,
			"profileActivationToken" => $this->profileActivationToken,
			"profileApproved" => $formattedProfileApproved,
			"profileApprovedById" => $this->profileApprovedById,
			"profileApprovedDateTime" => $formattedDate,
			"profileContent" => $this->profileContent,
			"profileEmail" => $this->profileEmail,
			"profileGithubAccessToken" => $this->profileGithubAccessToken,
			"profileHash" => $this->profileHash,
			"profileLocation" => $this->profileLocation,
			"profileName" => $this->profileName,
			"profileSalt" => $this->profileSalt
		];
		$statement->execute($parameters);

		// update the null profile id with what mySQL just gave us
		$this->profileId = intval($pdo->lastInsertId());
	}

	/**
	 * deletes this Profile from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// enforce the profileId is not null
		if($this->profileId === null) {
			throw(new \PDOException("unable to delete a profile that does not exist"));
		}
		// create a query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holder in the template
		$parameters = ["profileId" => $this->profileId];
		$statement->execute($parameters);
	}

	/**
	 * updates this Profile in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) {
		// enforce the profileId is not null
		if($this->profileId === null) {
			throw(new \PDOException("unable to update a profile that does not exist"));
		}
		// create a query template
		$query = "UPDATE profile SET profileAccountType = :profileAccountType, profileActivationToken = :profileActivationToken, profileApproved = :profileApproved, profileApprovedById = :profileApprovedById, profileApprovedDateTime = :profileApprovedDateTime, profileContent = :profileContent, profileEmail = :profileEmail, profileGithubAccessToken = :profileGithubAccessToken, profileHash = :profileHash, profileLocation = :profileLocation, profileName = :profileName, profileSalt = :profileSalt WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// Format values
		if (empty($this->profileApprovedDateTime) === true) {
			$formattedDate = null;
		} else {
			$formattedDate = $this->profileApprovedDateTime->format("Y-m-d H:i:s");
		}
		$formattedProfileApproved = 1;
		if ($this->profileApproved === false) {
			$formattedProfileApproved = 0;
		}
		// bind the member variables to the place holders in this template
		$parameters = ["profileAccountType" => $this->profileAccountType, "profileActivationToken" => $this->profileActivationToken, "profileApproved" => $formattedProfileApproved, "profileApprovedById" => $this->profileApprovedById, "profileApprovedDateTime" => $formattedDate, "profileContent" => $this->profileContent, "profileEmail" => $this->profileEmail, "profileGithubAccessToken" => $this->profileGithubAccessToken, "profileHash" => $this->profileHash, "profileLocation" => $this->profileLocation, "profileName" => $this->profileName, "profileSalt" => $this->profileSalt, "profileId" => $this->getProfileId()];
		$statement->execute($parameters);
	}

	/**
	 * gets the profile by profileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $profileId profileId to search for
	 * @return Profile|null Profile found or null if not found
	 * @throws \PDOException when mySQL relate errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProfileByProfileId(\PDO $pdo, $profileId) {
		// sanitize the profileId before searching
		if($profileId <= 0) {
			throw(new \PDOException("profile id is not positive"));
		}
		// create query template
		$query = "SELECT profileId, profileAccountType, profileActivationToken, profileApproved, profileApprovedById, profileApprovedDateTime, profileContent, profileEmail, profileGithubAccessToken, profileHash, profileLocation, profileName, profileSalt FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);
		// bind the profile id to the place holder template
		$parameters = array("profileId" => $profileId);
		$statement->execute($parameters);
		// grab the profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileAccountType"], $row["profileActivationToken"], $row["profileApproved"], $row["profileApprovedById"], $row["profileApprovedDateTime"], $row["profileContent"], $row["profileEmail"], $row["profileGithubAccessToken"], $row["profileHash"], $row["profileLocation"], $row["profileName"], $row["profileSalt"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted throw it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($profile);
	}

	/**
	 * gets the Profile by profileActivationToken
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $profileActivationToken profile activation token to search for
	 * @return Profile|null Profile found or null if not found
	 * @throws \TypeError when variable are not the correct data type
	 **/
	public static function getProfileByProfileActivationToken(\PDO $pdo, $profileActivationToken) {
		// sanitize the token before searching
		$profileActivationToken = trim($profileActivationToken);
		$profileActivationToken = filter_var($profileActivationToken, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($profileActivationToken) === true) {
			throw(new \PDOException("profile activation token is invalid"));
		}
		//create query template
		$query = "SELECT profileId, profileAccountType, profileActivationToken, profileApproved, profileApprovedById, profileApprovedDateTime, profileContent, profileEmail, profileGithubAccessToken, profileHash, profileLocation, profileName, profileSalt FROM profile WHERE profileActivationToken = :profileActivationToken";
		$statement = $pdo->prepare($query);
		// bind the profileActivationToken to the place holder in the template
		$parameters = array("profileActivationToken" => $profileActivationToken);
		$statement->execute($parameters);
		// build an array of profiles
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileAccountType"], $row["profileActivationToken"], $row["profileApproved"], $row["profileApprovedById"], $row["profileApprovedDateTime"], $row["profileContent"], $row["profileEmail"], $row["profileGithubAccessToken"], $row["profileHash"], $row["profileLocation"], $row["profileName"], $row["profileSalt"]);
			}
		} catch
		(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($profile);
	}

	/**
	 * gets the Profile by profileEmail
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $profileEmail profile email to search for
	 * @return Profile|null Profile found or null if not found
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProfileByProfileEmail(\PDO $pdo, $profileEmail) {
		// sanitize the email before searching
		$profileEmail = trim($profileEmail);
		$profileEmail = filter_var($profileEmail, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($profileEmail) === true) {
			throw(new \PDOException("profile email is invalid"));
		}
		// create query template
		$query = "SELECT profileId, profileAccountType, profileActivationToken, profileApproved, profileApprovedById, profileApprovedDateTime, profileContent, profileEmail, profileGithubAccessToken, profileHash, profileLocation, profileName, profileSalt FROM profile WHERE profileEmail = :profileEmail";
		$statement = $pdo->prepare($query);
		// bind the profile email to the place holder in the template
		$parameters = array("profileEmail" => $profileEmail);
		$statement->execute($parameters);
		// build an array of profiles
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileAccountType"], $row["profileActivationToken"], $row["profileApproved"], $row["profileApprovedById"], $row["profileApprovedDateTime"], $row["profileContent"], $row["profileEmail"], $row["profileGithubAccessToken"], $row["profileHash"], $row["profileLocation"], $row["profileName"], $row["profileSalt"]);
			}
		} catch
		(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($profile);
	}

	/**
	 * gets all Profiles
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Profiles found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllProfiles(\PDO $pdo) {
		// create query template
		$query = "SELECT profileId, profileAccountType, profileActivationToken, profileApproved, profileApprovedById, profileApprovedDateTime, profileContent, profileEmail, profileGithubAccessToken, profileHash, profileLocation, profileName, profileSalt FROM profile";
		$statement = $pdo->prepare($query);
		$statement->execute();
		// build an array of users
		$profiles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profile = new Profile($row["profileId"], $row["profileAccountType"], $row["profileActivationToken"], $row["profileApproved"], $row["profileApprovedById"], $row["profileApprovedDateTime"], $row["profileContent"], $row["profileEmail"], $row["profileGithubAccessToken"], $row["profileHash"], $row["profileLocation"], $row["profileName"], $row["profileSalt"]);
				$profiles[$profiles->key()] = $profile;
				$profiles->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($profiles);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		unset($fields["profileHash"]);
		unset($fields["profileSalt"]);
		unset($fields["profileGithubAccessToken"]);
		if (empty($this->profileApprovedDateTime) === false) {
			$fields["profileApprovedDateTime"] = $this->profileApprovedDateTime->getTimestamp() * 1000;
		}
		return ($fields);
	}
}