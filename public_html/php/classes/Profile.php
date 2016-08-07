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
}