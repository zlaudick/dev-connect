<?php
namespace Edu\Cnm\DevConnect;

require_once("autoload.php");

/**
 * Profile class for DevConnect
 *
 * @author Zac Laudick <zlaudick@cnm.edu>
 **/
class Profile {
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
	 * @var boolean $profileApproved
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
	 * @param boolean $newProfileApproved approval status of this Profile
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
	public function __construct(int $newProfileId = null, string $newProfileAccountType, string $newProfileActivationToken, boolean $newProfileApproved, int $newProfileApprovedById, $newProfileApprovedDateTime = null, string $newProfileContent, string $newProfileEmail, string $newProfileGithubAccessToken, string $newProfileHash, string $newProfileLocation, string $newProfileName, string $newProfileSalt) {
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
		// verify the account type is secure
		$newProfileAccountType = trim($newProfileAccountType);
		$newProfileAccountType = filter_var($newProfileAccountType, FILTER_SANITIZE_STRING);
		if(empty($newProfileAccountType) === true) {
			throw(new \InvalidArgumentException("account type is emtpy or insecure"));
		}

		// verify the account type will fit in the database
		if(strlen($newProfileAccountType) !== 1) {
			throw(new \RangeException("account type is too large"));
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
	public function setProfileActivationToken(string $newProfileActivationToken) {
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
	 * @returns boolean value of profile approved
	 **/
	public function getProfileApproved() {
		return($this->profileApproved);
	}

	/**
	 * mutator method for profile approved
	 *
	 * @param boolean $newProfileApproved new value of profile approved
	 * @throws \TypeError if $newProfileApproved is not a boolean
	 **/
	public function setProfileApproved(boolean $newProfileApproved) {
		// verify the profile approved value is boolean
		if(is_bool($newProfileApproved) === false) {
			throw(new \TypeError("this is not a boolean value"));
		}

		// convert and store the profile approved value
		$this->profileApproved = $newProfileApproved;
	}
}