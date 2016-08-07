<?php
namespace Edu\Cnm\DevConnect;

require_once("autoload.php");

/**
 * Profile class for DevConnect
 *
 * @author Zac Laudick <zlaudick@cnm.edu>
 **/
class Profile {
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
}